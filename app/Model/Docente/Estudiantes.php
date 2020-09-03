<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class Estudiantes extends Model
{
    protected $table = 'ura.estudiantes';
    protected $primaryKey = 'iEstudId';


    public function curriculas()
    {
        return $this->belongsTo('App\Model\Docente\Curriculas', 'iCurricId', 'iCurricId');
    }
}
