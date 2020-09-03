<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOpenGroup extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('vapazaa@unam.edu.pe', 'U.N.A.M. - CCTIC')
            ->subject('😃 Notificacion, Grupo Abierto')
            ->view('cctic.emailGrupo')
            ->with([
                'data' => $this->data
            ]);
    }
}
