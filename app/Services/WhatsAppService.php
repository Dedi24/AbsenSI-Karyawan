<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }

    public function sendNotification($phoneNumber, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->post($this->apiUrl, [
                'phone' => $phoneNumber,
                'message' => $message
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendGroupNotification($groupId, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->post($this->apiUrl . '/group', [
                'group_id' => $groupId,
                'message' => $message
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('WhatsApp group notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
