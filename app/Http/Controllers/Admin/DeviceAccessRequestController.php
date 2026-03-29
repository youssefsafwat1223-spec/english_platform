<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceReplacementRequest;
use App\Models\UserDevice;
use App\Services\DeviceAccessService;
use Illuminate\Http\Request;

class DeviceAccessRequestController extends Controller
{
    public function __construct(private readonly DeviceAccessService $deviceAccessService)
    {
    }

    public function index(Request $request)
    {
        $status = $request->string('status')->toString();

        $query = DeviceReplacementRequest::with(['user', 'reviewer', 'replacementForDevice'])
            ->latest();

        if (in_array($status, [
            DeviceReplacementRequest::STATUS_PENDING,
            DeviceReplacementRequest::STATUS_APPROVED,
            DeviceReplacementRequest::STATUS_REJECTED,
        ], true)) {
            $query->where('status', $status);
        }

        $deviceRequests = $query->paginate(25)->withQueryString();

        return view('admin.device-requests.index', compact('deviceRequests', 'status'));
    }

    public function show(DeviceReplacementRequest $deviceReplacementRequest)
    {
        $deviceReplacementRequest->load(['user', 'reviewer', 'replacementForDevice']);

        $activeDevices = $deviceReplacementRequest->user->devices()
            ->active()
            ->latest('last_seen_at')
            ->get();

        return view('admin.device-requests.show', compact('deviceReplacementRequest', 'activeDevices'));
    }

    public function approve(Request $request, DeviceReplacementRequest $deviceReplacementRequest)
    {
        $deviceReplacementRequest->load('user');

        $activeDevices = $deviceReplacementRequest->user->devices()->active()->get();
        $replacementDevice = null;

        if ($activeDevices->count() >= DeviceAccessService::MAX_ALLOWED_DEVICES) {
            $request->validate([
                'replacement_device_id' => ['required', 'integer'],
            ]);

            $replacementDevice = $activeDevices->firstWhere('id', (int) $request->integer('replacement_device_id'));

            if (!$replacementDevice) {
                return back()->with('error', 'اختر جهازًا حاليًا صحيحًا لاستبداله.');
            }
        }

        $this->deviceAccessService->approveReplacementRequest(
            $deviceReplacementRequest,
            $replacementDevice,
            $request->user()
        );

        return redirect()
            ->route('admin.device-requests.show', $deviceReplacementRequest)
            ->with('success', 'تمت الموافقة على طلب استبدال الجهاز.');
    }

    public function reject(Request $request, DeviceReplacementRequest $deviceReplacementRequest)
    {
        $this->deviceAccessService->rejectReplacementRequest($deviceReplacementRequest, $request->user());

        return redirect()
            ->route('admin.device-requests.show', $deviceReplacementRequest)
            ->with('success', 'تم رفض طلب استبدال الجهاز.');
    }
}
