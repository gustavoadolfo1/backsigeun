<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegCredencialPerfilSubmodulo extends Model
{
    protected $table = 'seg.credenciales_perfiles_submodulos';
    protected $primaryKey = 'iPerfilSubModId';

    protected $hidden = [
        'cPerfilSubModUsuarioSis', 'dtPerfilSubModFechaSis', 'cPerfilSubModEquipoSis', 'cPerfilSubModIpSis', 'cPerfilSubModOpenUsr', 'cPerfilSubModMacNicSis',
    ];

    public function segRangoSubmodulo()
    {
        return $this->belongsTo('App\SegRangoSubmodulo', 'iRangoSubModId');
    }

    public function segSubmodulo()
    {
        return $this->belongsTo('App\SegSubmodulo', 'iSubModId');
    }
}
