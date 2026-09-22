<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash');
    }

    /**
     * Parse unstructured Telegram chat into structured ticket JSON.
     *
     * @param string $userMessage
     * @return array
     */
    public function parseUnstructuredMessage(string $userMessage): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key is not configured.');
            return $this->fallbackParse($userMessage);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $systemInstruction = "Anda adalah Asisten AI IT Helpdesk. Tugas Anda adalah menganalisis keluhan/kendala IT pengguna dari percakapan bebas dan mengekstraknya menjadi JSON terstruktur.
Pilih kategori yang paling sesuai dari: Perangkat keras (Hardware), Perangkat Lunak (Software), Jaringan & Internet, Akun & Otentikasi, Lain-lain.
Pilih prioritas dari: low, medium, high, urgent. (urgent jika mengganggu operasional luas/manager/direksi).
Buat judul yang ringkas dan padat maksimal 8 kata. Buat deskripsi yang lengkap dan rapi.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                        'system_instruction' => [
                            'parts' => [['text' => $systemInstruction]]
                        ],
                        'contents' => [
                            [
                                'parts' => [['text' => "Ekstrak kendala IT dari laporan pengguna berikut:\n\n\"{$userMessage}\""]]
                            ]
                        ],
                        'generationConfig' => [
                            'response_mime_type' => 'application/json',
                            'response_schema' => [
                                'type' => 'OBJECT',
                                'properties' => [
                                    'title' => [
                                        'type' => 'STRING',
                                        'description' => 'Judul ringkas dan padat dari kendala'
                                    ],
                                    'description' => [
                                        'type' => 'STRING',
                                        'description' => 'Detail dan kronologi masalah pengguna'
                                    ],
                                    'category' => [
                                        'type' => 'STRING',
                                        'enum' => [
                                            'Perangkat Keras (Hardware)',
                                            'Perangkat Lunak (Software)',
                                            'Jaringan & Internet',
                                            'Akun & Otentikasi',
                                            'Lain-lain',
                                        ],
                                        'description' => 'Kategori resmi kendala IT: Perangkat Keras (Hardware), Perangkat Lunak (Software), Jaringan & Internet, Akun & Otentikasi, atau Lain-lain'
                                    ],
                                    'priority' => [
                                        'type' => 'STRING',
                                        'description' => 'Skala prioritas: low, medium, high, atau urgent'
                                    ],
                                ],
                                'required' => ['title', 'description', 'category', 'priority']
                            ]
                        ]
                    ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates.0.content.parts.0.text');
                if ($candidates) {
                    $parsed = json_decode($candidates, true);
                    if (is_array($parsed) && isset($parsed['title'])) {
                        return $parsed;
                    }
                }
            } else {
                Log::error('Gemini API Error Response: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
        }

        return $this->fallbackParse($userMessage);
    }

    /**
     * Fallback parsing jika API Key belum dipasang / offline / rate limited saat testing.
     */
    protected function fallbackParse(string $userMessage): array
    {
        $msgLower = mb_strtolower($userMessage);
        $category = 'Perangkat Keras (Hardware)';

        if (str_contains($msgLower, 'wifi') || str_contains($msgLower, 'jaringan') || str_contains($msgLower, 'internet') || str_contains($msgLower, 'rto') || str_contains($msgLower, 'koneksi')) {
            $category = 'Jaringan & Internet';
        } elseif (str_contains($msgLower, 'akun') || str_contains($msgLower, 'password') || str_contains($msgLower, 'sandi') || str_contains($msgLower, 'login') || str_contains($msgLower, 'terblokir') || str_contains($msgLower, 'hris')) {
            $category = 'Akun & Otentikasi';
        } elseif (str_contains($msgLower, 'excel') || str_contains($msgLower, 'aplikasi') || str_contains($msgLower, 'software') || str_contains($msgLower, 'windows') || str_contains($msgLower, 'photoshop')) {
            $category = 'Perangkat Lunak (Software)';
        }

        return [
            'title' => mb_substr($userMessage, 0, 50) . '...',
            'description' => $userMessage,
            'category' => $category,
            'priority' => (str_contains($msgLower, 'urgent') || str_contains($msgLower, 'direksi') || str_contains($msgLower, 'manager')) ? 'urgent' : 'medium',
        ];
    }
}
