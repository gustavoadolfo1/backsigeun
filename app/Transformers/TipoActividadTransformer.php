<?php

namespace App\Transformers;

use App\Model\Ura\TipoActividad as TipAct;
use League\Fractal\TransformerAbstract;

class TipoActividadTransformer extends TransformerAbstract
{
    public function transform(TipAct $tipact)
    {
        return[
            'iTipoActId'            => $tipact->iTipoActId,
            'cTipoActDsc'           => $tipact->cTipoActDsc,
            'cTipoActUsuarioSis'    => $tipact->cTipoActUsuarioSis,
            'dtTipoActFechaSis'     => $tipact->dtTipoActFechaSis,
            'cTipoActEquipoSis'     => $tipact->cTipoActEquipoSis,
            'cTipoActIpSis'         => $tipact->cTipoActIpSis,
            'cTipoActOpenUsr'       => $tipact->cTipoActOpenUsr,
            'cTipoActMacNicSis'     => $tipact->cTipoActMacNicSis,
        ];
    }
}
