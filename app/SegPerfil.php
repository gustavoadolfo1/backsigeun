<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegPerfil extends Model
{
    protected $table = 'seg.perfiles';
    protected $primaryKey = 'iPerfilId';

    protected $hidden = [
        'cPerfilUsuarioSis', 'dtPerfilFechaSis', 'cPerfilEquipoSis', 'cPerfilIpSis', 'cPerfilOpenUsr', 'cPerfilMacNicSis',
    ];

    public function segPerfilesModulos()
    {
        return $this->hasMany('App\SegPerfilModulo', 'iPerfilId');
    }
}
