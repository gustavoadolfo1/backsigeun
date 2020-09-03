<?php

namespace App\Transformers;

use App\Model\Grl\Periodo as Periodo;
use League\Fractal\TransformerAbstract;

class PeriodoTransformer extends TransformerAbstract
{
    public function transform(Periodo $periodo)
    {
        return[
            'iPeriodoId'            => $periodo->iPeriodoId,
            'nPeriodoUIT'           => $periodo->nPeriodoUIT,
            'cPeriodoDescripcion'   => $periodo->cPeriodoDescripcion,
            'cPeriodoUsuarioSis'    => $periodo->cPeriodoUsuarioSis,
            'dtPeriodoFechaSis'     => $periodo->dtPeriodoFechaSis,
            'cPeriodoEquipoSis'     => $periodo->cPeriodoEquipoSis,
            'cPeriodoIpSis'         => $periodo->cPeriodoIpSis,
            'cPeriodoOpenUsr'       => $periodo->cPeriodoOpenUsr,
            'cPeriodoMacNicSis'     => $periodo->cPeriodoMacNicSis,
        ];
    }
}
