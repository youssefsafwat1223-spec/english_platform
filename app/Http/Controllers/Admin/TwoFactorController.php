<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RequireAdminTwoFactor;
use App\Services\TwoFactorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    private const SETUP_SESSION_KEY = 'admin_two_factor_setup';

    public function __construct(private readonly TwoFactorAuthService $twoFactorAuthService)
    {
    }

    public function showChallenge(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            abort(403);
        }

        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.dashboard');
        }

        if (RequireAdminTwoFactor::isVerified($request, $user)) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.two-factor.challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:32'],
        ]);

        $user = $request->user();
        abort_unless($user && $user->is_admin && $user->hasTwoFactorEnabled(), 403);

        if ($this->twoFactorAuthService->verifyCode($user->two_factor_secret, $request->code)) {
            RequireAdminTwoFactor::markVerified($request->session(), $user);

            return redirect()->intended(route('admin.dashboard'));
        }

        if ($this->consumeRecoveryCode($user, $request->code)) {
            RequireAdminTwoFactor::markVerified($request->session(), $user);

            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('status', __('Recovery code accepted. It has now been invalidated.'));
        }

        return back()->withErrors([
            'code' => __('The authentication code is invalid.'),
        ]);
    }

    public function showSecurity(Request $request)
    {
        $user = $request->user();
        $setup = $request->session()->get(self::SETUP_SESSION_KEY);
        $qrCodeSvg = null;

        if (is_array($setup) && !empty($setup['secret'])) {
            $qrCodeSvg = QrCode::format('svg')
                ->size(180)
                ->margin(1)
                ->generate($this->twoFactorAuthService->provisioningUri($user, $setup['secret']));
        }

        return view('admin.settings.security', [
            'pendingSetup' => $setup,
            'qrCodeSvg' => $qrCodeSvg,
            'freshRecoveryCodes' => $request->session()->get('admin_two_factor_recovery_codes', []),
        ]);
    }

    public function beginSetup(Request $request)
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return back()->with('error', __('Two-factor authentication is already enabled.'));
        }

        $request->session()->put(self::SETUP_SESSION_KEY, [
            'secret' => $this->twoFactorAuthService->generateSecret(),
            'recovery_codes' => $this->twoFactorAuthService->generateRecoveryCodes(),
        ]);

        return redirect()
            ->route('admin.settings.security')
            ->with('status', __('Scan the QR code and confirm with a code from your authenticator app.'));
    }

    public function confirmSetup(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:32'],
        ]);

        $setup = $request->session()->get(self::SETUP_SESSION_KEY);

        if (!is_array($setup) || empty($setup['secret']) || empty($setup['recovery_codes'])) {
            return redirect()
                ->route('admin.settings.security')
                ->with('error', __('Start the setup process again before confirming.'));
        }

        if (!$this->twoFactorAuthService->verifyCode($setup['secret'], $request->code)) {
            return back()->withErrors([
                'code' => __('The authentication code is invalid.'),
            ]);
        }

        $user = $request->user();
        $user->forceFill([
            'two_factor_secret' => $setup['secret'],
            'two_factor_recovery_codes' => $setup['recovery_codes'],
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget(self::SETUP_SESSION_KEY);
        $request->session()->flash('admin_two_factor_recovery_codes', $setup['recovery_codes']);

        RequireAdminTwoFactor::markVerified($request->session(), $user);

        return redirect()
            ->route('admin.settings.security')
            ->with('success', __('Two-factor authentication is now enabled.'));
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->user();

        if (!$user->hasTwoFactorEnabled()) {
            return back()->with('error', __('Enable two-factor authentication first.'));
        }

        $recoveryCodes = $this->twoFactorAuthService->generateRecoveryCodes();

        $user->forceFill([
            'two_factor_recovery_codes' => $recoveryCodes,
        ])->save();

        return back()
            ->with('success', __('Recovery codes regenerated successfully.'))
            ->with('admin_two_factor_recovery_codes', $recoveryCodes);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget(self::SETUP_SESSION_KEY);
        $request->session()->forget('admin_two_factor_recovery_codes');
        RequireAdminTwoFactor::forgetVerification($request->session());

        return back()->with('success', __('Two-factor authentication has been disabled.'));
    }

    protected function consumeRecoveryCode($user, string $candidate): bool
    {
        $codes = Collection::make($user->two_factor_recovery_codes ?? []);
        $normalizedCandidate = $this->twoFactorAuthService->normalizeRecoveryCode($candidate);
        $matched = false;

        $remaining = $codes->reject(function (string $code) use ($normalizedCandidate, &$matched) {
            $isMatch = $this->twoFactorAuthService->normalizeRecoveryCode($code) === $normalizedCandidate;

            if ($isMatch) {
                $matched = true;
            }

            return $isMatch;
        })->values()->all();

        if (!$matched) {
            return false;
        }

        $user->forceFill([
            'two_factor_recovery_codes' => $remaining,
        ])->save();

        return true;
    }
}
