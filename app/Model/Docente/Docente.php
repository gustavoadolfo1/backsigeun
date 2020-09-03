<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;
use App\Model\Docente\Categoria;

class Docente extends Model
{
    protected $table = 'ura.docentes';
    protected $primaryKey = 'iDocenteId';


    public function categoria()
    {
        return $this->belongsTo('App\Model\Docente\Categoria', 'iCategoriaId', 'iCategoriaId');
    }


    public function condicion()
    {
        return $this->belongsTo('App\Model\Docente\Condicion', 'iCondicionId', 'iCondicionId');
    }


    public function dedicacion()
    {
        return $this->belongsTo('App\Model\Docente\Dedicacion', 'iDedicId', 'iDedicId');
    }

    public function silabo()
    {
        return $this->hasMany('App\Model\Docente\Silabo', 'iDocenteId', 'iDocenteId');
    }

    public function cargasHorarias()
    {
        return $this->hasMany('App\Model\Docente\CargasHorarias', 'iDocenteId', 'iDocenteId');
    }
    public function notas()
    {
        return $this->hasMany('App\Model\Docente\Notas', 'iDocenteId', 'iDocenteId');
    }
}
