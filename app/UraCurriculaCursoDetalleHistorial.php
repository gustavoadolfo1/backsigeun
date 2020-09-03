<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraCurriculaCursoDetalleHistorial extends Model
{
    protected $table = 'ura.curriculas_cursos_detalles_historial';
    protected $primaryKey = 'iCurricDetId';

    protected $hidden = [
        'cCurricDetUsuarioSis', 'dtCurricDetFechaSis', 'cCurricDetEquipoSis', 'cCurricDetIpSis', 'cCurricDetOpenUsr', 'cCurricDetMacNicSis',
    ];

    public function uraCurriculaCurso()
    {
        return $this->belongsTo('App\UraCurriculaCurso', 'iCurricCursoId');
    }
}
