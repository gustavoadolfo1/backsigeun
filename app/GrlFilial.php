<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrlFilial extends Model
{
    protected $table = 'grl.filiales';
    protected $primaryKey = 'iFilId';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cFilUsuarioSis', 'dtFilFechaSis', 'cFilEquipoSis', 'cFilIpSis', 'cFilOpenUsr', 'cFilMacNicSis',
    ];

    public function carreras()
    {
        return $this->belongsToMany( 'App\UraCarrera', 'ura.carreras_filiales', 'iFilId', 'iCarreraId');
    }

    public function carrerasFiliales()
    {
        return $this->hasMany('App\UraCarreraFilial', 'iFilId');
    }

    public function matriculasAutorizaciones()
    {
        return $this->hasMany('App\UraMatriculaCarreraAutorizacion', 'iFilId');
    }
}
