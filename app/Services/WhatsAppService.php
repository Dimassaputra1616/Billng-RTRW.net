<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim pesan WhatsApp melalui Gateway Node.js
     *
     * @param string $number Nomor tujuan (628xxx)
     * @param string $message Isi pesan
     * @return bool
     */
    public static function sendMessage($number, $message)
    {
        try {
            $response = Http::post('http://localhost:3000/send-message', [
                'number' => $number,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Gateway Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WA Service Exception: ' . $e->getMessage());
            return false;
        }
    /**
     * Kirim media WhatsApp melalui Gateway Node.js
     */
    public static function sendMedia($number, $message, $filename, $base64File)
    {
        try {
            $response = Http::post('http://localhost:3000/send-media', [
                'number' => $number,
                'message' => $message,
                'filename' => $filename,
                'file' => $base64File,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('WA Gateway Media Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WA Service Media Exception: ' . $e->getMessage());
            return false;
        }
    }
}
