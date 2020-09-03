<?php

namespace App\Model\Ura;

use Illuminate\Database\Eloquent\Model;
use App\Model\Ura\ActividadCalendario as ActividadCal;
use App\Model\Ura\CalendarioAcademico as CalendarioAcad;
use App\Model\Ura\Semestre as Semestre;

class CalendarioAcadDet extends Model
{
    protected $table = 'ura.calendarios_academicos_detalles';
    protected $primaryKey = 'iCalAcadDetId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'iCalAcadId',
        'iActvivId',
        'iSemId',
        'dInicio',
        'dFin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cCalAcadDetUsuarioSis', 'dtCalAcadDetFechaSis', 'cCalAcadDetEquipoSis',
        'cCalAcadDetIpSis',
        'cCalAcadDetOpenUsr', 'cCalAcadDetMacNicSis',
    ];

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function calendarioacedemico()
    {
        return $this->belongsTo(CalendarioAcad::class, 'iCalAcadId');
    }

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actividadcalendario()
    {
        return $this->belongsTo(ActividadCal::class, 'iActivId');
    }

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'iSemId');
    }
}
