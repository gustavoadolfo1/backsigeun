<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;

class Descuentos extends Model
{

    protected $table = 'acad.descuentos';
    protected $primaryKey = 'iDescuentoId';
    protected $guarded = [];
//    protected  $dateFormat ='Y-d-m H:i:s';

}
