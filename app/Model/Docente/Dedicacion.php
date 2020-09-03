<?php

namespace App\Model\Docente;

use Illuminate\Database\Eloquent\Model;
use App\Model\Docente as Docente;

class Dedicacion extends Model
{
    protected $table = 'grl.dedicacion';
    protected $primaryKey = 'iDedicId';


    public function docente()
    {
        return $this->hasMany('App\Model\Docente\Docente','iDedicId','iDedicId');
    }
}
