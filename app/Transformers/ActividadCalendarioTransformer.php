<?php

namespace App\Transformers;

use App\Model\Ura\ActividadCalendario as ActCal;
use League\Fractal\TransformerAbstract;

class ActividadCalendarioTransformer extends TransformerAbstract
{
    public function transform(ActCal $actcal)
    {
        return[
            'iActivId'            => $actcal->iActivId,
            'iTipoActId'          => $actcal->iTipoActId,
            'cActivDsc'           => $actcal->cActivDsc,
            'cActivUsuarioSis'    => $actcal->cActivUsuarioSis,
            'dtActivFechaSis'     => $actcal->dtActivFechaSis,
            'cActivEquipoSis'     => $actcal->cActivEquipoSis,
            'cActivIpSis'         => $actcal->cActivIpSis,
            'cActivOpenUsr'       => $actcal->cActivOpenUsr,
            'cActivMacNicSis'     => $actcal->cActivMacNicSis,
        ];
    }
}
