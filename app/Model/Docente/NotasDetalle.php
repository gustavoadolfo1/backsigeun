<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class NotasDetalle extends Model
{
    protected $table = 'ura.notas_detalle';
    protected $primaryKey = 'iNotasDetId';

    public function notas()
    {
        return $this->belongsTo('App\Model\Docente\Notas', 'iNotasId', 'iNotasId');
    }
}
