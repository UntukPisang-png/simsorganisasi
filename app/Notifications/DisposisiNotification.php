<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Disposisi;;

class DisposisiNotification extends Notification
{
    use Queueable;

    /**
     * The disposisi instance.
     *
     * @var mixed
     */
    private $disposisi;

    /**
     * Create a new notification instance.
     */
    public function __construct($disposisi)
    {
        $this->disposisi = $disposisi; // Data disposisi
    }
    
    public function via($notifiable)
    {
        return ['mail']; // Menggunakan email sebagai contoh
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Disposisi Baru')
            ->line('Anda memiliki disposisi baru:')
            ->line('Sifat: ' . $this->disposisi->sifat)
            ->line('Tindakan: ' . $this->disposisi->tindakan)
            ->line('Catatan: ' . $this->disposisi->catatan)
            ->action('Lihat Detail', url('/disposisi/' . $this->disposisi->id))
            ->line('Harap ditindaklanjuti secepatnya.');
    }
    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
