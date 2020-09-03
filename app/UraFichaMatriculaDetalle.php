<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraFichaMatriculaDetalle extends Model
{
    protected $table = 'ura.ficha_matriculas_detalles';
    protected $primaryKey = 'iMatricDetId';

    protected $hidden = [
        'cMatricDetUsuarioSis', 'dtMatricDetFechaSis', 'cMatricDetEquipoSis', 'cMatricDetIpSis', 'cMatricDetOpenUsr', 'cMatricDetMacNicSis',
    ];

    public function uraCurriculaCurso()
    {
        return $this->belongsTo('App\UraCurriculaCurso', 'cCurricCursoCod', 'cCurricCursoCod');
    }
}
