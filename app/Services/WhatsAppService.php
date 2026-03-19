<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $endpoint = 'https://api.africastalking.com/version1/messaging/whatsapp';

    public function send(string $to, string $message): bool
    {
        // WhatsApp sends are handled via wa.me share links — no API call needed here.
        // Africa's Talking integration preserved below for when direct API sending is added.
        return true;

        // @phpstan-ignore-next-line
        $to      = $this->formatPhone($to);
        $apiKey  = config('services.africastalking.api_key', '');
        $username = config('services.africastalking.username', '');

        if (empty($apiKey) || empty($username)) {
            Log::warning('WhatsApp: AT credentials not configured — skipping send', ['to' => $to]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'apiKey' => $apiKey,
                'Accept' => 'application/json',
            ])->asForm()->post($this->endpoint, [
                'username' => $username,
                'to'       => $to,
                'message'  => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('WhatsApp send failed', [
                'to'     => $to,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;

        } catch (\Throwable $e) {
            Log::error('WhatsApp exception', ['to' => $to, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Normalise a Kenyan phone number to E.164 (+254XXXXXXXXX).
     */
    public function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '254')) {
            return '+' . $phone;
        }

        if (str_starts_with($phone, '0') && strlen($phone) === 10) {
            return '+254' . substr($phone, 1);
        }

        if (strlen($phone) === 9) {
            return '+254' . $phone;
        }

        return '+' . $phone;
    }
}
