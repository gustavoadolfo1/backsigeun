<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;

class PreHorario extends Model
{

    protected $table = 'acad.pre_horarios';
    protected $primaryKey = 'iPreHorariosId';
    protected $guarded = [];

    const CREATED_AT = 'dPreHorariosFechaSis';

    public function preinscripcion()
    {

    }
}
