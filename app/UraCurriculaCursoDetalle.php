<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraCurriculaCursoDetalle extends Model
{
    protected $table = 'ura.curriculas_cursos_detalles';
    protected $primaryKey = 'iCurricDetId';

    protected $hidden = [
        'cCurricDetUsuarioSis', 'dtCurricDetFechaSis', 'cCurricDetEquipoSis', 'cCurricDetIpSis', 'cCurricDetOpenUsr', 'cCurricDetMacNicSis',
    ];

    public function uraCurriculaCurso()
    {
        return $this->belongsTo('App\UraCurriculaCurso', 'iCurricCursoId');
    }
}
