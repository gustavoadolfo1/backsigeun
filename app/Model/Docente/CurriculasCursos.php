<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class CurriculasCursos extends Model
{
    protected $table = 'ura.curriculas_cursos';
    protected $primaryKey = 'iCurricCursoId';

    public function carreras()
    {
        return $this->belongsTo('App\Model\Docente\Carreras', 'iCarreraId', 'iCarreraId');
    }

    public function curriculas()
    {
        return $this->belongsTo('App\Model\Docente\Curriculas', 'iCurricId', 'iCurricId');
    }

    public function silabo()
    {
        return $this->hasMany('App\Model\Docente\Silabo', 'iCurricCursoId', 'iCurricCursoId');
    }

    public function curriculascursosDetalles()
    {
        return $this->hasMany('App\Model\Docente\CurriculasCursosDetalles', 'iCurricCursoId', 'iCurricCursoId');
    }
}
