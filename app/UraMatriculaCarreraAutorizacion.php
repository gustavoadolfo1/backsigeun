<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraMatriculaCarreraAutorizacion extends Model
{
    protected $table = 'ura.matriculas_carreras_autorizaciones';
    protected $primaryKey = 'iMatricCarrAutoId';

    protected $hidden = [
        'cMatricCarrAutoUsuarioSis', 'dtMatricCarrAutoFechaSis', 'cMatricCarrAutoEquipoSis', 'cMatricCarrAutoIpSis', 'cMatricCarrAutoOpenUsr', 'cMatricCarrAutoMacNicSis',
    ];

    public function carrera()
    {
        return $this->belongsTo( 'App\UraCarrera', 'iCarreraId');
    }
}
