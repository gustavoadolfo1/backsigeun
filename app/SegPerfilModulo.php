<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegPerfilModulo extends Model
{
    protected $table = 'seg.perfiles_modulos';
    protected $primaryKey = 'iPerfilModuloId';

    public function segModulo()
    {
        return $this->belongsTo('App\SegModulo', 'iModuloId');
    }
    public function perfil()
    {
        return $this->belongsTo('App\SegPerfil', 'iPerfilId');
    }
    public function getNombrePerfilAttribute(){

    }
}
