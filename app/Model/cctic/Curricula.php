<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\Modulo;
use App\Model\cctic\CurriculaModulo;
class Curricula extends Model
{
    protected $table = 'ura.curriculas';
    protected $primaryKey = 'iCurricId';


//    protected $casts = [
//        'iCurricId' => 'string',
//    ];


    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'acad.curricula_modulo', 'iCurricId', 'iModuloId')->using(CurriculaModulo::class);
    }
}
