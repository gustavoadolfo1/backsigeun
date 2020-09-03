<?php

namespace App\Notifications;

use App\Mail\NotificacionActividad;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Actividades extends Notification implements ShouldQueue
{
    use Queueable;
    public $tries = 5;
    public $curso, $actividad;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($curso, $actividad)
    {
        // dd('aaa');
        $this->curso = $curso;
        $this->actividad = $actividad;
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
     * @return Mailable
     */
    public function toMail($notifiable)
    {
        //dd($notifiable->email);
        return (new NotificacionActividad($notifiable, $this->actividad, $this->curso))->to($notifiable->email);
/*
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');*/

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
