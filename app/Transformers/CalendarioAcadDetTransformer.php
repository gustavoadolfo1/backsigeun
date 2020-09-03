<?php

namespace App\Transformers;

use App\Model\Ura\CalendarioAcadDet as Calacaddet;
use League\Fractal\TransformerAbstract;

class CalendarioAcadDetTransformer extends TransformerAbstract
{
    public function transform(Calacaddet $calactdet)
    {
        return[
            'iCalAcadDetId'         => $calactdet->iCalAcadDetId,
            'iCalAcadId'            => $calactdet->iCalAcadId,
            'iActivId'              => $calactdet->iActivId,
            'iSemId'                => $calactdet->iSemId,
            'dInicio'               => $calactdet->dInicio,
            'dFin'                  => $calactdet->dFin,
            'cCalAcadDetUsuarioSis' => $calactdet->cCalAcadDetUsuarioSis,
            'dtCalAcadDetFechaSis'  => $calactdet->dtCalAcadDetFechaSis,
            'cCalAcadDetEquipoSis'  => $calactdet->cCalAcadDetEquipoSis,
            'cCalAcadDetIpSis'      => $calactdet->cCalAcadDetIpSis,
            'cCalAcadDetOpenUsr'    => $calactdet->cCalAcadDetOpenUsr,
            'cCalAcadDetMacNicSis'  => $calactdet->cCalAcadDetMacNicSis,

        ];
    }
}
