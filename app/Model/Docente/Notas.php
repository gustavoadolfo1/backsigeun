<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    protected $table = 'ura.notas';
    protected $primaryKey = 'iNotasId';

    public function notasdetalle()
    {
        return $this->hasMany('App\Model\Docente\NotasDetalle', 'iNotasId', 'iNotasId');
    }

    public function docente()
    {
        return $this->belongsTo('App\Model\Docente\Docente', 'iDocenteId', 'iDocenteId');
    }
}
