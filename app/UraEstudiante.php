<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UraEstudiante extends Model
{
    use Notifiable;
    protected $table = 'ura.estudiantes';
    protected $primaryKey = 'iEstudId';

    protected $hidden = [
        'cEstudUsuarioSis', 'dtEstudFechaSis', 'cEstudEquipoSis', 'cEstudIpSis', 'cEstudOpenUsr', 'cEstudMacNicSis',
    ];

    protected $appends = [
        'email', 'name'
    ];

    public function getEmailAttribute() {
        return $this->cEstudCodUniv . '@unam.edu.pe';
        // return '2014204017@unam.edu.pe';
    }

    public function getNameAttribute() {
        return $this->persona->nombre_completo;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function persona(){
        return $this->belongsTo(GrlPersona::class, 'iPersId');
    }
}
