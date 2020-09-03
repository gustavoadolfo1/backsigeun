<?php

namespace App\model\cctic;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'acad.inscripcion';
    protected $primaryKey = 'iInscripcionId';
//    protected  $dateFormat ='Y-d-m H:i:s';

    const CREATED_AT = 'dCursosCreatedAt';

}
