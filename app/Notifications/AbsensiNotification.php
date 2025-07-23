<?php

namespace App\Notifications;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbsensiNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = '';

        switch ($this->data['type']) {
            case 'check_in':
                $message = "✅ {$this->data['employee_name']} telah absen masuk pada {$this->data['time']} di lokasi {$this->data['location']}";
                break;
            case 'check_out':
                $message = "🔚 {$this->data['employee_name']} telah absen pulang pada {$this->data['time']} di lokasi {$this->data['location']}";
                break;
            case 'late':
                $message = "⚠️ {$this->data['employee_name']} terlambat masuk pada {$this->data['time']} ({$this->data['late_minutes']} menit)";
                break;
            case 'alpha':
                $message = "❌ {$this->data['employee_name']} tidak hadir pada {$this->data['date']}";
                break;
        }

        // Kirim notifikasi WhatsApp
        $whatsappService = new WhatsAppService();
        $adminGroup = config('services.whatsapp.admin_group');
        if ($adminGroup) {
            $whatsappService->sendGroupNotification($adminGroup, $message);
        }


        return [
            'message' => $message,
            'type' => $this->data['type'],
            'data' => $this->data
        ];
    }
}
