<?php

namespace App\Model\Ura;

use Illuminate\Database\Eloquent\Model;
use App\Model\Ura\CalendarioAcadDet as CalendarioDet;

class Semestre extends Model
{
    protected $table = 'ura.semestres';
    protected $primaryKey = 'iSemId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cSemDsc'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cSemUsuarioSis',
        'dtSemFechaSis',
        'cSemEquipoSis',
        'cSemIpSis',
        'cSemOpenUsr',
        'cSemMacNicSis',
    ];

    /**
     * Get the articles for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function calendariodetalle()
    {
        return $this->hashOne(CalendarioDet::class, 'iCalAcadDetId');
    }
}
