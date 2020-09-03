<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class CurriculasCursosDetalles extends Model
{
    protected $table = 'ura.curriculas_cursos_detalles';
    protected $primaryKey = 'iCurricDetId';

    public function curriculasCursos()
    {
        return $this->belongsTo('App\Model\Docente\CurriculasCursos', 'iCurricCursoId', 'iCurricCursoId');
    }
}
