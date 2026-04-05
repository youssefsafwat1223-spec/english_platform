<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LanguageToolService
{
    public function check(string $text, string $locale = 'en'): array
    {
        if (!(bool) config('services.writing_ai.enabled')) {
            return [];
        }

        $baseUrl = rtrim((string) config('services.writing_ai.languagetool_url'), '/');

        if ($baseUrl === '' || trim($text) === '') {
            return [];
        }

        try {
            $response = Http::timeout(10)
                ->asForm()
                ->post($baseUrl . '/v2/check', [
                    'text' => $text,
                    'language' => $this->resolveLanguageCode($locale),
                ]);

            if (!$response->successful()) {
                Log::warning('LanguageTool request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            return collect($response->json('matches', []))
                ->map(function (array $match): array {
                    return [
                        'message' => (string) ($match['message'] ?? ''),
                        'short_message' => (string) ($match['shortMessage'] ?? ''),
                        'offset' => (int) ($match['offset'] ?? 0),
                        'length' => (int) ($match['length'] ?? 0),
                        'category' => (string) ($match['rule']['category']['name'] ?? ''),
                        'rule_id' => (string) ($match['rule']['id'] ?? ''),
                        'replacements' => collect($match['replacements'] ?? [])
                            ->pluck('value')
                            ->filter()
                            ->take(3)
                            ->values()
                            ->all(),
                    ];
                })
                ->filter(fn (array $issue) => $issue['message'] !== '')
                ->values()
                ->all();
        } catch (\Throwable $e) {
            Log::warning('LanguageTool check failed', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    private function resolveLanguageCode(string $locale): string
    {
        return str_starts_with(strtolower($locale), 'ar') ? 'ar' : 'en-US';
    }
}
