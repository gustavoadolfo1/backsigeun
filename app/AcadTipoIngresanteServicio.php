<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcadTipoIngresanteServicio extends Model
{
    protected $table = 'acad.tipos_ingresantes_servicios';
    protected $primaryKey = 'iTiposIngServId';

    protected $fillable = [
        'cTiposIngServDsc',
    ];
}
