<?php

namespace App\Model\cctic;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    public $timestamps  = false;
    protected $table = 'acad.docentes';
    protected $primaryKey = 'iDocenteId';
    protected $fillable = [
        'iPersId',
        'iProgramasAcadId',
        'iFilId',
        'iDedicId',
        'iGradoAcadId',
        'bDocenteActivo',
        'cDescGradoAcad',
        'cDocenteDoc',
        'cDocenteCel',
        'cDocenteCorreoElec',
        'cDocenteTel',
        'cDocenteDirec',
        'dDocenteFechNac',
        'bDocentePide',
        'cDocenUsuarioSis',
        'dtDocenFechaSis',
        'cDocenEquipoSis',
        'cDocenOpenUsr',
        'cDocenMacNicSis',
        'cDocenIpSis',
        'cDocenteRuc',
        'cDocentecvPath',
    ];

    public function personas (){
        return $this->hasOne('App\GrlPersona', 'iPersId', 'iPersId' );
    }
}
