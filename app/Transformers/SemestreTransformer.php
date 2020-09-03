<?php

namespace App\Transformers;

use App\Model\Ura\Semestre as Semestre;
use League\Fractal\TransformerAbstract;

class SemestreTransformer extends TransformerAbstract
{
    public function transform(Semestre $semestre)
    {
        return[
            'iSemId'            => $semestre->iSemId,
            'iSemDsc'           => $semestre->iSemDsc,
            'cSemUsuarioSis'    => $semestre->cSemUsuarioSis,
            'dtSemFechaSis'     => $semestre->dtSemFechaSis,
            'cSemEquipoSis'     => $semestre->cSemEquipoSis,
            'cSemIpSis'         => $semestre->cSemIpSis,
            'cSemOpenUsr'       => $semestre->cSemOpenUsr,
            'cSemMacNicSis'     => $semestre->cSemMacNicSis,
        ];
    }
}
