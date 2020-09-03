<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'acad.unidades';
    protected $primaryKey = 'iUnidadId';


    const CREATED_AT = 'dUnidadesCreatedAt';
    const UPDATED_AT = 'dUnidadesUpdatedAt';

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
