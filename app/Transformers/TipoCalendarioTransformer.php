<?php

namespace App\Transformers;

use App\Model\Ura\TipoCalendario as TipCal;
use League\Fractal\TransformerAbstract;

class TipoCalendarioTransformer extends TransformerAbstract
{
    public function transform(TipCal $tipcal)
    {
        return[
            'iTiposCalenId'             => $tipcal->iTiposCalenId,
            'cTiposCalendDsc'           => $tipcal->cTiposCalendDsc,
        ];
    }
}
