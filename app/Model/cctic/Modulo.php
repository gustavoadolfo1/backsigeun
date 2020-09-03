<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Curso;
use App\Model\cctic\Unidad;
use App\Model\cctic\PublicoObjetivo;
use App\Model\cctic\Curricula;
use App\Model\cctic\CurriculaModulo;
class Modulo extends Model
{

    protected $table = 'acad.modulos';
    protected $primaryKey = 'iModuloId';

//    const CREATED_AT = 'dCursosCreatedAt';
//    const UPDATED_AT = 'dCursosUpdatedAt';

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'iCursoId');
    }

    public function unidades()
    {
        return $this->hasMany(Unidad::class, 'iModuloId');
    }

    public function publicoObjetivo()
    {
        return $this->belongsToMany(PublicoObjetivo::class, 'acad.modulo_publico_objetivo', 'iModuloId', 'iPublicoObjetivoId');
    }

    public function curriculas()
    {
        return $this->belongsToMany(Curricula::class, 'acad.curricula_modulo', 'iModuloId', 'iCurricId')->using(CurriculaModulo::class);
    }

    public function planesTrabajo()
    {
        return $this->belongsToMany(PlanTrabajo::class, 'acad.plan_trabajo_modulos', 'iModuloId', 'iPlanTrabajoId');
    }
}
