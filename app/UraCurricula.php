<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraCurricula extends Model
{
    protected $table = 'ura.curriculas';
    protected $primaryKey = 'iCurricId';

    protected $hidden = [
        'cCurricUsuarioSis', 'dtCurricFechaSis', 'cCurricEquipoSis', 'cCurricIpSis', 'cCurricOpenUsr', 'cCurricMacNicSis',
    ];
}
