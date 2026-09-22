<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
    }

    /**
     * Send Markdown/HTML text message back to a Telegram Chat ID.
     *
     * @param string|int $chatId
     * @param string $text
     * @param string $parseMode
     * @return bool
     */
    public function sendMessage($chatId, string $text, string $parseMode = 'HTML'): bool
    {
        if (empty($this->botToken)) {
            Log::warning("Telegram Bot Token is not configured. Message to {$chatId} was not sent.");
            return false;
        }

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        try {
            $response = Http::post($url, [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => $parseMode,
            ]);

            if ($response->failed()) {
                Log::error("Telegram API SendMessage Failed: " . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Telegram API Exception: " . $e->getMessage());
            return false;
        }
    }
}
