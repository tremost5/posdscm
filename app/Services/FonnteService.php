<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class FonnteService
{
    public function sendText(string $targetPhone, string $message): void
    {
        $token = (string) config('services.fonnte.token');
        $url = (string) config('services.fonnte.url');

        if ($token === '') {
            throw new RuntimeException('FONNTE_TOKEN belum diatur.');
        }

        $response = Http::timeout(15)
            ->withHeaders(['Authorization' => $token])
            ->asForm()
            ->post($url, [
                'target' => $targetPhone,
                'message' => $message,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gagal mengirim WhatsApp: '.$response->body());
        }
    }
}
