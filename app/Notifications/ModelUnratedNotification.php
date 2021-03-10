<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModelUnratedNotification extends Notification
{
    use Queueable;

    private $qualifierName;
    private $rateableName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( string $qualifierName, string $rateableName )
    {
        $this->qualifierName = $qualifierName;
        $this->rateableName = $rateableName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line( "El usuario {$this->qualifierName} ha removido su calificación de tu producto {$this->rateableName}" );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
