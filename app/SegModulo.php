<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegModulo extends Model
{
    protected $table = 'seg.modulos';
    protected $primaryKey = 'iModuloId';

    protected $hidden = [
        'cModuloUsuarioSis', 'dtModuloFechaSis', 'cModuloEquipoSis', 'cModuloIpSis', 'cModuloOpenUsr', 'cModuloMacNicSis',
    ];

    public function perfiles(){
        return $this->hasMany('App\SegPerfilModulo', 'iModuloId');
    }
}
