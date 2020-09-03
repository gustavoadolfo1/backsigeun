<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;
use App\Model\Docente as Docente;

class Categoria extends Model
{
    protected $table = 'grl.categoria';
    protected $primaryKey = 'iCategoriaId';
    //iDocenteId

    public function docente()
    {
        return $this->hasMany('App\Model\Docente\Docente', 'iCategoriaId', 'iCategoriaId');
    }
}
