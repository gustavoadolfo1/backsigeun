<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;

class Carreras extends Model
{
    protected $table = 'ura.carreras';
    protected $primaryKey = 'iCarreraId';

    public function silabo()
    {
        return $this->hasMany('App\Model\Docente\Silabo', 'iCarreraId', 'iCarreraId');
    }

    public function curriculasCursos()
    {
        return $this->hasMany('App\Model\Docente\CurriculasCursos', 'iCarreraId', 'iCarreraId');
    }

     public function curriculascursosDetalles()
    {
        return $this->hasMany('App\Model\Docente\CurriculasCursosDetalles', 'iCarreraId', 'iCarreraId');
    }


    public function cargasHorarias()
    {
        return $this->hasMany('App\Model\Docente\CargasHorarias', 'iCarreraId', 'iCarreraId');
    }
}
