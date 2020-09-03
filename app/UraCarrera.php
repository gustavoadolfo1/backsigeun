<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UraCarrera extends Model
{
    protected $table = 'ura.carreras';
    protected $primaryKey = 'iCarreraId';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cCarreraUsuarioSis', 'dtCarreraFechaSis', 'cCarreraEquipoSis', 'cCarreraIpSis', 'cCarreraOpenUsr', 'cCarreraMacNicSis',
    ];
}
