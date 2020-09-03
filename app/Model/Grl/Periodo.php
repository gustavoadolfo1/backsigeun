<?php

namespace App\Model\Grl;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'grl.periodos';
    protected $primaryKey = 'iPeriodoId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nPeriodoUIT',
        'cPeriodoDescripcion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cPeriodoUsuarioSis', 'dtPeriodoFechaSis', 'cPeriodoEquipoSis', 'cPeriodoIpSis', 'cPeriodoOpenUsr', 'cPeriodoMacNicSis',
    ];

    /**
     * Get the articles for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calendarioacedemico()
    {
        return $this->hasMany(CalendarioAcad::class, 'iCalAcadId');
    }
}
