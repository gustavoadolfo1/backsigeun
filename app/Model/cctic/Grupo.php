<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{

    protected $table = 'acad.grupos';
    protected $primaryKey = 'iGruposId';
//    protected $guarded = [];

    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'iPublicacionId');
    }

    public function curriculaModulo()
    {
        return $this->belongsTo(CurriculaModulo::class, 'iCurriculaModuloId');
    }
}
