<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegSubmodulo extends Model
{
    protected $table = 'seg.submodulos';
    protected $primaryKey = 'iSubModId';

    protected $hidden = [
        'cSubModUsuarioSis', 'dtSubModFechaSis', 'cSubModEquipoSis', 'cSubModIpSis', 'cSubModOpenUsr', 'cSubModMacNicSis',
    ];

    public function segModulo()
    {
        return $this->belongsTo('App\SegModulo', 'iModuloId');
    }
}
