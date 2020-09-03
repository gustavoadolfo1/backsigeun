<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Modulo;
use App\Model\cctic\TipoCurso;

class Curso extends Model
{

    protected $table = 'acad.cursos';
    protected $primaryKey = 'iCursoId';
    protected $guarded = [];
//    protected  $dateFormat ='Y-d-m H:i:s';

    const CREATED_AT = 'dCursosCreatedAt';
    const UPDATED_AT = 'dCursosUpdatedAt';

    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'iCursoId');
    }

    public function tipoCurso()
    {
        return $this->belongsTo(TipoCurso::class, 'iTipoCursoId');
    }

    public function filiales()
    {
        return $this->belongsTo(Filial::class, 'iFilId');
    }

}
