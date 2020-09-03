<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;
use App\Model\Docente as Docente;

class Condicion extends Model
{
    protected $table = 'grl.condicion';
    protected $primaryKey = 'iCondicionId';


    public function docente()
    {
        return $this->hasMany('App\Model\Docente\Docente','iCondicionId','iCondicionId');
    }
}
