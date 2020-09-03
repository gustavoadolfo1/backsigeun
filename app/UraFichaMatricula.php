<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraFichaMatricula extends Model
{
    protected $table = 'ura.ficha_matriculas';
    protected $primaryKey = 'iMatricId';

    protected $hidden = [
        'cMatricUsuarioSis', 'dtMatricFechaSis', 'cMatricEquipoSis', 'cMatricIpSis', 'cMatricOpenUsr', 'cMatricMacNicSis',
    ];

    public function uraFichaMatriculaDetalles()
    {
        return $this->hasMany('App\UraFichaMatriculaDetalle', 'iMatricId');
    }
}
