<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $user->load([
            'enrollments.course',
            'certificates',
            'achievements',
        ]);

        $stats = [
            'total_enrollments' => $user->enrollments()->count(),
            'completed_courses' => $user->enrollments()->completed()->count(),
            'certificates' => $user->certificates()->count(),
            'total_points' => $user->total_points,
            'rank' => $user->getRank(),
            'achievements' => $user->achievements()->count(),
        ];

        return view('student.profile.show', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = auth()->user();

        return view('student.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'telegram_username' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'secondary_email' => 'nullable|email|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'telegram_username', 'address', 'secondary_email']);

        // Handle avatar upload with compression
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $image = null;
            $extension = strtolower($file->getClientOriginalExtension());

            // Create image resource based on type
            switch ($extension) {
                case 'jpeg':
                case 'jpg':
                    $image = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'png':
                    $image = imagecreatefrompng($file->getRealPath());
                    break;
                case 'gif':
                    $image = imagecreatefromgif($file->getRealPath());
                    break;
            }

            if ($image) {
                // Get original dimensions
                $origWidth = imagesx($image);
                $origHeight = imagesy($image);

                // Max dimensions for avatar
                $maxSize = 300;

                // Calculate new dimensions keeping aspect ratio
                if ($origWidth > $maxSize || $origHeight > $maxSize) {
                    if ($origWidth >= $origHeight) {
                        $newWidth = $maxSize;
                        $newHeight = (int) round($origHeight * ($maxSize / $origWidth));
                    } else {
                        $newHeight = $maxSize;
                        $newWidth = (int) round($origWidth * ($maxSize / $origHeight));
                    }

                    // Resize
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                    imagedestroy($image);
                    $image = $resized;
                }

                // Save as compressed JPEG
                $filename = 'avatars/' . uniqid('avatar_') . '.jpg';
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                imagejpeg($image, $tempPath, 70); // 70% quality
                imagedestroy($image);

                // Store the compressed file
                Storage::disk('public')->put($filename, file_get_contents($tempPath));
                unlink($tempPath);

                $data['avatar'] = $filename;
            } else {
                // Fallback: store as-is if GD fails
                $data['avatar'] = $file->store('avatars', 'public');
            }
        }

        $user->update($data);

        return back()->with('success', __('تم تحديث الملف الشخصي بنجاح!'));
    }

    public function changePassword()
    {
        return view('student.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', __('تم تغيير كلمة المرور بنجاح!'));
    }

    public function achievements()
    {
        $user = auth()->user();

        $earned = $user->achievements()
            ->orderBy('user_achievements.earned_at', 'desc')
            ->get();

        $available = \App\Models\Achievement::active()
            ->whereNotIn('id', $earned->pluck('id'))
            ->get();

        return view('student.profile.achievements', compact('earned', 'available'));
    }

    public function pointsHistory()
    {
        $history = auth()->user()->pointsHistory()
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $stats = [
            'total_points' => auth()->user()->total_points,
            'this_week' => auth()->user()->pointsHistory()
                ->where('created_at', '>=', now()->subWeek())
                ->sum('points_earned'),
            'this_month' => auth()->user()->pointsHistory()
                ->where('created_at', '>=', now()->subMonth())
                ->sum('points_earned'),
        ];

        return view('student.profile.points-history', compact('history', 'stats'));
    }

    public function leaderboard()
    {
        $topUsers = \App\Models\User::students()
            ->orderBy('total_points', 'desc')
            ->take(100)
            ->get();

        $userRank = auth()->user()->getRank();

        return view('student.profile.leaderboard', compact('topUsers', 'userRank'));
    }
}