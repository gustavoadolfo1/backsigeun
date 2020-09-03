<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegCredencialPerfilFilialCarrera extends Model
{
    protected $table = 'seg.credenciales_perfiles_filiales_carreras';
    protected $primaryKey = 'iCredPerfFilCarId';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cFilUsuarioSis', 'dtFilFechaSis', 'cFilEquipoSis', 'cFilIpSis', 'cFilOpenUsr', 'cFilMacNicSis',
    ];

    public function grlFilial()
    {
        return $this->belongsTo('App\GrlFilial', 'iFilId');
    }

    public function uraCarrera()
    {
        return $this->belongsTo('App\UraCarrera', 'iCarreraId');
    }
}
