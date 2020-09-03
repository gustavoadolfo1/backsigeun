<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Ura\CalendarioAcademico as CalAcad;
use App\Transformers\CalendarioAcadDetTransformer as CalAcadDetTrans;

class CalendarioAcademicoTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'calacaddet'
    ];

    public function transform(CalAcad $calacad)
    {
        return[
            'iCalAcadId'            => $calacad->iCalAcadId,
            'iTiposCalenId'         => $calacad->iTiposCalenId,
            'iFilId'                => $calacad->iFilId,
            'cCalAcadResol'         => $calacad->cCalAcadResol,
            'iPeriodoId'            => $calacad->iPeriodoId,
            'cCalAcadTitulo'        => $calacad->cCalAcadTitulo,
            'cCalAcadSubTitulo'     => $calacad->cCalAcadSubTitulo,
            'cCalAcadUsuarioSis'    => $calacad->cCalAcadUsuarioSis,
            'dtCalAcadFechaSis'     => $calacad->dtCalAcadFechaSis,
            'cCalAcadEquipoSis'     => $calacad->cCalAcadEquipoSis,
            'cCalAcadIpSis'         => $calacad->cCalAcadIpSis,
            'cCalAcadOpenUsr'       => $calacad->cCalAcadOpenUsr,
            'cCalAcadMacNicSis'     => $calacad->cCalAcadMacNicSis,
            'cFilDescripcion'       => $calacad->cFilDescripcion,
            'cTiposCalendDsc'       => $calacad->cTiposCalendDsc,
            'cFilSigla'             => $calacad->cFilSigla,
        ];
    }
    /**
     * Include Calacaddet
     *
     * @param CalAcad $calacad
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCalacaddet(CalAcad $calacad)
    {
        if ($calacaddet = $calacad->calacaddet) {
            return $this->item($calacaddet, new CalAcadDetTrans);
        }
    }
}
