<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactNotificationAdmin extends Notification
{
    use Queueable;
    public $formData;
    /**
     * Create a new notification instance.
     */
    public function __construct( $formData)
    {
        //
        $this->formData = $formData;
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                   
        
                     ->line('Name: ' . $this->formData['name'])
            ->line('Email: ' .$this->formData['email'])
            ->line('Message: ' . $this->formData['message']);
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
