<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Curso;

class TipoCurso extends Model
{
    protected $table = 'acad.tipo_cursos';
    protected $primaryKey = 'iTipoCursoId';


    const CREATED_AT = 'dTipoCursoCreatedAt';
    const UPDATED_AT = 'dTipoCursoUpdatedAt';

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'iTipoCursoId');
    }
}
