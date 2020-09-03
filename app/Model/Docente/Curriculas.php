<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class Curriculas extends Model
{
    protected $table = 'ura.curriculas';
    protected $primaryKey = 'iCurricId';


    public function curriculasCursos()
    {
        return $this->hasMany('App\Model\Docente\CurriculasCursos', 'iCurricId', 'iCurricId');
    }

    public function curriculascursosDetalles()
    {
        return $this->hasMany('App\Model\Docente\CurriculasCursosDetalles', 'iCurricId', 'iCurricId');
    }

    public function estudiantes()
    {
        return $this->hasMany('App\Model\Docente\Estudiantes', 'iCurricId', 'iCurricId');
    }
}
