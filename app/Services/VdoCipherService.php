<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VdoCipherService
{
    protected string $apiSecret;
    protected string $baseUrl = 'https://dev.vdocipher.com/api';

    public function __construct()
    {
        $this->apiSecret = config('vdocipher.api_secret', '');
    }

    /**
     * Generate OTP and playback info for a VdoCipher video.
     *
     * @param string $videoId The VdoCipher video ID
     * @param array $annotations Optional watermark/annotation config
     * @return array|null Returns ['otp' => '...', 'playbackInfo' => '...'] or null on failure
     */
    public function getOTP(string $videoId, array $annotations = []): ?array
    {
        if (empty($this->apiSecret)) {
            Log::error('VdoCipher: API secret key is not configured.');
            return null;
        }

        try {
            $body = [];

            // Add dynamic watermark annotations if provided
            if (!empty($annotations)) {
                $body['annotate'] = json_encode($annotations);
            }

            $response = Http::withHeaders([
                'Authorization' => "Apisecret {$this->apiSecret}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("{$this->baseUrl}/videos/{$videoId}/otp", $body);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'otp' => $data['otp'] ?? null,
                    'playbackInfo' => $data['playbackInfo'] ?? null,
                ];
            }

            Log::error('VdoCipher: Failed to get OTP', [
                'videoId' => $videoId,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('VdoCipher: Exception while getting OTP', [
                'videoId' => $videoId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generate OTP with user watermark annotation.
     *
     * @param string $videoId
     * @param string $watermarkText Text to display as watermark (e.g., user name + phone)
     * @return array|null
     */
    public function getOTPWithWatermark(string $videoId, string $watermarkText): ?array
    {
        $annotations = [
            [
                'type' => 'rtext',
                'text' => $watermarkText,
                'alpha' => '0.40',
                'color' => '0xFFFFFF',
                'size' => '15',
                'interval' => '5000',
            ],
        ];

        return $this->getOTP($videoId, $annotations);
    }
}
