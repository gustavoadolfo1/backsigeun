<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\CurriculaModulo;
use App\Model\cctic\Publicacion;
class CurriculaModuloCosto extends Model
{

    protected $table = 'acad.curricula_modulo_costos';
    protected $primaryKey = 'iCurriculaModuloCostosId';


    public function curricula()
    {
        return $this->belongsTo(CurriculaModulo::class, 'iCurriculaModuloId');
    }

    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'iCurriculaModuloCostosId');
    }

}
