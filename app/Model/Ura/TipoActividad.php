<?php

namespace App\Model\Ura;

use Illuminate\Database\Eloquent\Model;
use App\Model\Ura\ActividadCalendario as ActividadCal;

class TipoActividad extends Model
{
    protected $table = 'ura.tipo_actividad';
    protected $primaryKey = 'iTipoActId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cTipoActivDsc'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cTipoActUsuarioSis', 'dtTipoActFechaSis', 'cTipoActEquipoSis', 'cTipoActIpSis', 'cTipoActOpenUsr', 'cTipoActMacNicSis',
    ];

    /**
     * Get the articles for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividadcalendario()
    {
        return $this->hasMany(ActividadCal::class, 'iActivId');
    }
}
