<?php

namespace App\Repositories\cctic;




use App\Model\cctic\Curricula;
use App\model\cctic\PlanTrabajo;
use Illuminate\Support\Facades\DB;

class PlanTrabajoRepository {

    public function obtenerPlanesXcarrea($id)
    {
        return \DB::select('exec [acad].[Sp_SEL_planXCarreraId]  ?', [$id]);
    }

    public function obtenerPlanTrabajo()
    {
        return DB::table('acad.plan_trabajo_modulos as plantm')
            ->join('acad.plan_trabajo as pt', 'pt.iPlanTrabajoId', '=', 'plantm.iPlanTrabajoId')
            ->where('plantm.iProgramasAcadId', '=', '3')
            ->get();
    }

    public function obtenerPlanTrabajoDisponibles($filial, $programAcad)
    {
        return DB::table('acad.plan_trabajo_modulos as plantm')
            ->join('acad.plan_trabajo as pt', 'pt.iPlanTrabajoId', '=', 'plantm.iPlanTrabajoId')
            ->where('plantm.iFilId', '=', $filial)
            ->where('plantm.iProgramasAcadId', '=', $programAcad)
            ->distinct('plantm.iPlanTrabajoId')
            ->orderByRaw('cPlanTrabajoDsc desc')
//            ->select('c.iCurricId', 'c.cCurricAnio')
            ->get();
    }


    public function obtenerPlanesTrabajo($filial, $programAcad)
    {
        return DB::table('acad.plan_trabajo_modulos as plantm')
            ->join('acad.plan_trabajo as pt', 'pt.iPlanTrabajoId', '=', 'plantm.iPlanTrabajoId')
            ->where('plantm.iFilId', '=', $filial)
            ->where('plantm.iProgramasAcadId', '=', $programAcad)
            ->whereNull('plantm.iModuloId')
            ->distinct('plantm.iPlanTrabajoId')
            ->orderByRaw('plantm.iPlanTrabajoId desc')
//            ->select('c.iCurricId', 'c.cCurricAnio')
            ->get();
    }
}
