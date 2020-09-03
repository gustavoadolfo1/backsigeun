<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraCurriculaCurso extends Model
{
    protected $table = 'ura.curriculas_cursos';
    protected $primaryKey = 'iCurricCursoId';
    //protected $keyType = 'string';

    protected $hidden = [
        'cCurricUsuarioSis', 'dtCurricFechaSis', 'cCurricEquipoSis', 'cCurricIpSis', 'cCurricOpenUsr', 'cCurricMacNicSis',
    ];

    public function uraCurriculaCursoDetalle()
    {
        return $this->hasOne('App\UraCurriculaCursoDetalle', 'iCurricCursoId');
    }
}
