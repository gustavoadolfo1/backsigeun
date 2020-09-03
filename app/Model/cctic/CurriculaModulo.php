<?php

namespace App\Model\cctic;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;
use App\Model\cctic\CurriculaModuloCosto;
use App\Model\cctic\Modulo;
class   CurriculaModulo extends Pivot
{
    protected $table = 'acad.curricula_modulo';
    protected $primaryKey = 'iCurriculaModuloId';

//    public $incrementing = true;

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'iModuloId');
    }

    public function costos()
    {
        return $this->hasMany(CurriculaModuloCosto::class, 'iCurriculaModuloId');
    }


}
