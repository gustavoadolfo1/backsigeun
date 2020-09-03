<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Modalidad;
use App\Model\cctic\CurriculaModuloCosto;
class Publicacion extends Model
{
    protected $table = 'acad.publicaciones';
    protected $primaryKey = 'iPublicacionId';

    const CREATED_AT = 'dPublicacionCreatedAt';
    const UPDATED_AT = 'dPublicacionUpdatedAt';
    protected  $dateFormat ='Y-d-m H:i:s';

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'iModalEstudId');
    }

    public function costo()
    {
        return $this->belongsTo(CurriculaModuloCosto::class, 'iCurriculaModuloCostosId');
    }

    public function preinscripciones()
    {
        return $this->hasMany(PreInscripcion::class, 'iPublicacionId');
    }
}
