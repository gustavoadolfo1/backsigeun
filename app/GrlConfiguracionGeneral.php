<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrlConfiguracionGeneral extends Model
{
    protected $table = 'grl.configuraciones_generales';
    protected $primaryKey = 'iConfigGrlesId';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
