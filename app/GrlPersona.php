<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrlPersona extends Model
{
    protected $table = 'grl.personas';
    protected $primaryKey = 'iPersId';
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cPersUsuarioSis', 'dtPersFechaSis', 'cPersEquipoSis', 'cPersIpSis', 'cPersOpenUsr', 'cPersMacNicSis',
    ];

    protected $appends = [
        'nombre_completo', 'nombre_simple'
    ];

    public function getNombreCompletoAttribute(){
        return trim($this->cPersPaterno) . ' ' . trim($this->cPersMaterno) . ', ' . trim($this->cPersNombre);
    }

    public function getNombreSimpleAttribute(){
        return trim($this->cPersNombre) . ' ' . trim($this->cPersPaterno);
    }
}
