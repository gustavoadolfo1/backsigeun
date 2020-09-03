<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegCredencialPerfil extends Model
{
    protected $table = 'seg.credenciales_perfiles';
    protected $primaryKey = 'iCredPerfilId';

    protected $hidden = [
        'cCredPerfilUsuarioSis', 'dtCredPerfilFechaSis', 'cCredPerfilEquipoSis', 'cCredPerfilIpSis', 'cCredPerfilOpenUsr', 'cCredPerfilMacNicSis',
    ];

    public function segPerfil()
    {
        return $this->belongsTo('App\SegPerfil', 'iPerfilId');
    }

    public function segCredencialesPerfilesSubmodulos()
    {
        return $this->hasMany(SegCredencialPerfilSubmodulo::class, 'iCredPerfilId');
    }

    public function segCredencialesPerfilesFilialesCarreras()
    {
        return $this->hasMany('App\SegCredencialPerfilFilialCarrera', 'iCredPerfilId');
    }
}
