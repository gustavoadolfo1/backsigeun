<?php

namespace App\Transformers;

use App\GrlFilial;
use League\Fractal\TransformerAbstract;

class GrlFilialTransformer extends TransformerAbstract
{
    public function transform(GrlFilial $grlfilial)
    {
        return[
            'iFilId'            => $grlfilial->iFilId,
            'iEntId'            => $grlfilial->iEntId,
            'cFilDescripcion'   => $grlfilial->cFilDescripcion,
            'cFilSigla'         => $grlfilial->cFilSigla,
            'cUbigeoId'         => $grlfilial->cUbigeoId,
            'cFilUsuarioSis'    => $grlfilial->cFilUsuarioSis,
            'dtFilFechaSis'     => $grlfilial->dtFilFechaSis,
            'cFilEquipoSis'     => $grlfilial->cFilEquipoSis,
            'cFilIpSis'         => $grlfilial->cFilIpSis,
            'cFilOpenUsr'       => $grlfilial->cFilOpenUsr,
            'cFilMacNicSis'     => $grlfilial->cFilMacNicSis,
        ];
    }
}
