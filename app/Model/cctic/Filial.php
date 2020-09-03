<?php

namespace App\Model\cctic;
use App\Model\cctic\Curso;
use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    protected $table = 'grl.filiales';
    protected $primaryKey = 'iFilId';


//    public function Cursos()
//    {
//        return $this->belongsToMany(Curso::class, 'acad.cursos_filial', 'iFilId', 'iCursoId');
//    }

}
