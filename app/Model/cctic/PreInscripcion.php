<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;

class PreInscripcion extends Model
{
    protected $table = 'acad.preinscripciones';
    protected $primaryKey = 'iPreinscripcionId';
    protected $guarded = [];
//    protected $dateFormat = 'Ymd ';

    const CREATED_AT = 'dPreinscripcionRegistro';
    const UPDATED_AT = 'dPreinscricpionUpdatedAt';



    public function Publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'iPublicacionId');
    }

}
