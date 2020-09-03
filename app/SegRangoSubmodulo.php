<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegRangoSubmodulo extends Model
{
    protected $table = 'seg.rango_submodulos';
    protected $primaryKey = 'iRangoSubModId';

    protected $hidden = [
        'cRangoSubUsuarioSis', 'dtRangoSubFechaSis', 'cRangoSubEquipoSis', 'cRangoSubIpSis', 'cRangoSubOpenUsr', 'cRangoSubMacNicSis',
    ];
}
