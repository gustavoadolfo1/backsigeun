<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraControlCicloAcademico extends Model
{
    protected $table = 'ura.controles';
    protected $primaryKey = 'iControlCicloAcad';

    protected $hidden = [
        'cControlUsuarioSis', 'dtControlFechaSis', 'cControlEquipoSis', 'cControlIpSis', 'cControlOpenUsr', 'cControlMacNicSis',
    ];
}
