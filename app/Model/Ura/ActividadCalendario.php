<?php

namespace App\Model\Ura;

use Illuminate\Database\Eloquent\Model;
use App\Model\TipoActividad as TipoAct;
use App\Model\Ura\CalendarioAcadDet as CalendarioDet;

class ActividadCalendario extends Model
{
    protected $table = 'ura.actividades_calendarios';
    protected $primaryKey = 'iActivId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'iTipoActId',
        'cActivDsc'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cActivUsuarioSis',
        'dtActivFechaSis',
        'cActivEquipoSis',
        'cActivIpSis',
        'cActivOpenUsr',
        'cActivMacNicSis',
    ];

    /**
     * Get the activiadad for the calenario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendariodetalle()
    {
        return $this->hasOne(CalendarioDet::class, 'iCalAcadDetId');
    }

    /**
     * tipo de activiad for the actividad calendario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoactividad()
    {
        return $this->belongsTo(TipoAct::class, 'iTipoActId');
    }
}
