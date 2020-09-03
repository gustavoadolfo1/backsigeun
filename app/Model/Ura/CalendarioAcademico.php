<?php

namespace App\Model\Ura;

use Illuminate\Database\Eloquent\Model;
use App\Model\Grl\Periodo as Periodo;
use App\GrlFilial as Filial;
use App\Model\Ura\CalendarioAcadDet as CalendarioDet;
use App\Model\Ura\TipoCalendario as TipoCal;

class CalendarioAcademico extends Model
{
    protected $table = 'ura.calendarios_academicos';
    protected $primaryKey = 'iCalAcadId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'iTipoCalenId',
        'iFilId',
        'iPeriodoId',
        'cCalAcadResol',
        'cCalAcadTitulo',
        'cCalAcadSubTitulo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cCalAcadUsuarioSis',
        'dtCalAcadFechaSis',
        'cCalAcadEquipoSis',
        'cCalAcadIpSis',
        'cCalAcadOpenUsr',
        'cCalAcadMacNicSis',
    ];

    /**
     * Get the articles for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calendariodetalle()
    {
        return $this->hasMany(CalendarioDet::class, 'iCalAcadId');
    }

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'iPeriodoId');
    }

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipocalendario()
    {
        return $this->belongsTo(TipoCal::class, 'iTiposCalenId');
    }

    /**
     * Get the category for the blog article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filial()
    {
        return $this->belongsTo(Filial::class, 'iFilId');
    }

    public function actividadcalendario()
    {
        return $this->hasManyThrough(ActividadCalendario::class, CalendarioDet::class, 'iCalAcadId', 'iActivId', 'iCalAcadId', 'iActivId');
    }

    public function semestre()
    {
        return $this->hasManyThrough(Semestre::class, CalendarioDet::class, 'iCalAcadId', 'iSemId', 'iCalAcadId', 'iSemId');
    }
}
