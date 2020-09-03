<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrlReniec extends Model
{
    protected $table = 'grl.reniec';
    protected $primaryKey = 'iPersId';
    public $timestamps = false;

    protected $fillable = [
        'cReniecDni',
        'cReniecApel_pate',
        'cReniecApel_mate',
        'cReniecNombres',
        'cReniecUbigeo',
        'cReniecDireccion',
        'cReniecEsta_civi',
        'cReniecRestricciones',
        'imgReniecFotografia',

        'cReniecUser_dni',
        'cReniecUser_fecha',
        'cReniecUser_hora',
        'cReniecUser_ip',
        'cReniecEstado',
    ];

    protected $hidden = [
        'cReniecUser_dni', 'cReniecUser_fecha', 'cReniecUser_hora', 'cReniecUser_ip', 'cReniecEstado',
    ];
}

/*
 *
 * iPersId
cReniecFuente
cReniecDni
cReniecApel_pate
cReniecApel_mate
cReniecNombres
cReniecUbigeo
cReniecDireccion
cReniecEsta_civi
cReniecRestricciones
cReniecFotografia
'cReniecUser_dni',
'cReniecUser_fecha',
'cReniecUser_hora',
'cReniecUser_ip',
'cReniecEstado',

 */
