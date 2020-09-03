<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Modulo;

class PublicoObjetivo extends Model
{

    protected $table = 'acad.publico_objetivo';
    protected $primaryKey = 'iPublicoObjetivoId';

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'acad.modulo_publico_objetivo', 'iPublicoObjetivo', 'iModuloId');
    }
}
