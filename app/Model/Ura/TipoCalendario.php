<?php

namespace App\Model\Ura;

use Illuminate\Database\Eloquent\Model;
use App\Model\Ura\CalendarioAcademico as CalendarioAcad;

class TipoCalendario extends Model
{
    protected $table = 'ura.tipos_calendarios';
    protected $primaryKey = 'iTiposCalenId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cTiposCalendDsc',
    ];

    /**
     * Get the articles for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calendarioacedemico()
    {
        return $this->hashMany(CalendarioAcad::class, 'iCalAcadId');
    }
}
