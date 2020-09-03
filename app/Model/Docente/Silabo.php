<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class Silabo extends Model
{
    protected $table = 'ura.silabo';
    protected $primaryKey = 'iSilaboId';

    public function docente()
    {
        return $this->belongsTo('App\Model\Docente\Docente', 'iDocenteId', 'iDocenteId');
    }

    public function carreras()
    {
        return $this->belongsTo('App\Model\Docente\Carreras', 'iCarreraId', 'iCarreraId');
    }

    public function curriculasCursos()
    {
        return $this->belongsTo('App\Model\Docente\CuriculasCursos', 'iCurricCursoId', 'iCurricCursoId');
    }
}
