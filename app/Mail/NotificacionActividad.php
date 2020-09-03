<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionActividad extends Mailable
{
    use Queueable, SerializesModels;

    public $estudiante;
    private $asunto;
    private $nombreEstudiante;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($estudiante, $actividad, $curso)
    {
        //dd('B');
        $this->estudiante = $estudiante;
        $this->actividad = $actividad;
        $this->curso = $curso;

        $this->asunto = $actividad->cTipoActividad . ' - ' . $actividad->cActividadesTitulo;
        $this->nombreEstudiante = trim($estudiante->name);

        //dd($this);
        //dd($this->estudiante->actividad->cTipoActividad);
        /*
        dd($this->estudiante->actividad->cActividadesTitulo);
        dd(
            [
                'nombreEstudiante' => trim($this->estudiante->name),
                'actividad' => $this->estudiante->actividad,
                'curso' => $this->estudiante->curso,
            ]);
        dd($this->estudiante);
        */
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->estudiante);
        return $this->view('aula.notificacion_actividades')
            ->from('soporte.sigeun@unam.edu.pe', 'Aula Virtual - SIGEUN')
            ->subject('[AulaVirtual] ' . $this->asunto)
            //->bcc('antony@hynotech.com', 'Pruebas')
            ->with(
                [
                    'nombreEstudiante' => $this->nombreEstudiante,
                    'actividad' => $this->actividad,
                    'curso' => $this->curso,
                ]
            )
            ;

    }
}
