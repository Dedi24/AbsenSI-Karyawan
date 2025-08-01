<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbsensiNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        // Kirim ke database dan telegram
        return ['database', 'telegram']; // Tambahkan channel lain jika perlu
    }

    public function toDatabase($notifiable)
    {
        return $this->data;
    }

    public function toTelegram($notifiable)
    {
        // Pastikan .env sudah diisi dengan TELEGRAM_BOT_TOKEN dan TELEGRAM_CHAT_ID
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$botToken || !$chatId) {
            Log::error("Telegram credentials are missing for notification.");
            return;
        }

        $data = $this->data;
        $message = '';

        switch ($data['type']) {
            case 'check_in':
                $message = "✅ *Absensi Masuk*\n" .
                           "👤 Nama: *{$data['employee_name']}*\n" .
                           "🕘 Waktu: `{$data['time']}`\n" .
                           "📅 Tanggal: `{$data['date']}`";
                break;
            case 'check_out':
                $message = "🔚 *Absensi Pulang*\n" .
                           "👤 Nama: *{$data['employee_name']}*\n" .
                           "🕘 Waktu: `{$data['time']}`\n" .
                           "📅 Tanggal: `{$data['date']}`";
                break;
            case 'late':
                $message = "⚠️ *Peringatan Keterlambatan*\n" .
                           "👤 Nama: *{$data['employee_name']}*\n" .
                           "🕘 Waktu Masuk: `{$data['time']}`\n" .
                           "⏱ Terlambat: *{$data['late_minutes']} menit*\n" .
                           "📅 Tanggal: `{$data['date']}`";
                break;
            case 'device_reset':
                $message = "🔄 *Reset Device Fingerprint*\n" .
                           "👤 Karyawan: *{$data['employee_name']}*\n" .
                           "📅 Tanggal: `{$data['date']}`";
                break;
            default:
                $message = "📢 *Notifikasi Absensi*\n" .
                           "👤 Nama: *{$data['employee_name']}*\n" .
                           "📄 Data: `" . json_encode($data) . "`";
                break;
        }

        $payload = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ];

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", $payload);

            if ($response->successful()) {
                Log::info("Telegram notification sent successfully to chat ID: {$chatId}");
            } else {
                Log::error("Failed to send Telegram notification", [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Exception sending Telegram notification: " . $e->getMessage());
        }
    }
}