<?php

namespace App\Repositories\cctic;


class CarrerasRepository {

    public function obtenerCarreraByID($carreaId)
    {
        return \DB::select('exec [acad].[SP_SEL_carreraXid] ?', [$carreaId]);
    }

    // cctic 3
    public function obtenerCarerras($proAcadId = 3)
    {
        return \DB::select('[acad].[Sp_SEL_carrerasXiProgramasAcadId] ?', [$proAcadId]);
    }
}

?>
