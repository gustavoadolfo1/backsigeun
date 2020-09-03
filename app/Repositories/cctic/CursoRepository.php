<?php

namespace App\Repositories\cctic;
use App\Model\cctic\CurriculaModulo;
use App\Model\cctic\Curso;
use App\Model\cctic\Unidad;
use Illuminate\Support\Facades\DB;
use App\Model\cctic\Modulo;
class CursoRepository {

    public function getCursos($curricula, $filial, $programAcad, $activo = 1)
    {
        $cursos =  $this->Prerequisitos($curricula, $filial, $programAcad, $activo);


        foreach ($cursos as $key => $curso) {
            $curso->modulos = Modulo::where('iCursoId', '=', $curso->iCursoId)
                ->whereNotNull('bModuloEstado')
                ->where('bModuloEstado', '=', $activo)
                ->get();

            foreach ($curso->modulos as $modulo) {
                $modulo->unidades = Unidad::where('iModuloId', '=', $modulo->iModuloId)
                    ->where('bUnidadEstado', '=', $activo)
                    ->get();

                $modulo->publicoObjetivo = DB::table('acad.modulo_publico_objetivo as mpo')
                    ->join('acad.modulos as m', 'm.iModuloId', '=', 'mpo.iModuloId')
                    ->join('acad.publico_objetivo as po', 'po.iPublicoObjetivoId', '=', 'mpo.iPublicoObjetivoId')
                    ->select('po.iPublicoObjetivoId', 'po.cPublicoObjetivoDsc')
                    ->where('m.iModuloId', '=', $modulo->iModuloId)
                    ->get();
            }

        }

        return $cursos;
    }

    public function CursoById($id)
    {
        return Curso::where('iCursoId', '=', $id)->first();
    }

    public function CursoAllById($id)
    {
        $curso = Curso::where('iCursoId', '=', $id)
            ->first();

        $curso->modulos = Modulo::where('iCursoId', '=', $curso->iCursoId)
            ->whereNotNull('bModuloEstado')
            ->where('bModuloEstado', '=', 1)
            ->get();
        foreach ($curso->modulos as $modulo) {
            $modulo->unidades = Unidad::where('iModuloId', '=', $modulo->iModuloId)
                ->where('bUnidadEstado', '=', 1)
                ->get();
        }


        foreach ($curso->modulos as $key => $modulo) {
            $modulo->publico_objetivo = DB::table('acad.modulo_publico_objetivo as mpo')
                ->join('acad.modulos as m', 'm.iModuloId', '=', 'mpo.iModuloId')
                ->join('acad.publico_objetivo as po', 'po.iPublicoObjetivoId', '=', 'mpo.iPublicoObjetivoId')
                ->select('po.iPublicoObjetivoId', 'po.cPublicoObjetivoDsc', 'mpo.fMensualidad', 'mpo.fTotal')
                ->where('m.iModuloId', '=', $modulo->iModuloId)
                ->get();
        }


        if (count($curso->modulos) > 0) {
            $planTrabajo = DB::table('acad.plan_trabajo_modulos as plantm')
                ->join('acad.plan_trabajo as plant', 'plantm.iPlanTrabajoId', '=', 'plant.iPlanTrabajoId')
                ->where('plantm.iModuloId', '=', $curso->modulos[0]->iModuloId)
                ->select('plant.iPlanTrabajoId')->first();

            if ($planTrabajo) {
                $curso->iCurricId = $planTrabajo->iPlanTrabajoId;
            }
        }

        return $curso;
    }


    public function Prerequisitos($curricula, $filial, $programAcad, $activo = 1)
    {

        $cursos =  DB::table('acad.plan_trabajo_modulos as plantm')
            ->join('acad.modulos as m', 'plantm.iModuloId', '=', 'm.iModuloId')
            ->join('acad.cursos as c', 'm.iCursoId', '=', 'c.iCursoId')
            ->join('acad.tipo_cursos as t', 't.iTipoCursoId', '=', 'c.iTipoCursoId')
            ->where('iPlanTrabajoId', '=', $curricula)
            ->where('plantm.iFilId', '=', $filial)
            ->where('c.bCursoEstado', '=', $activo)
            ->where('plantm.iProgramasAcadId', '=', $programAcad)
            ->where('m.bModuloEstado', '=', $activo)
            ->select('c.iCursoId','cCursoNombre', 't.iTipoCursoId', 't.cTipoCursoDsc', 'c.bCursoEstado')
            ->distinct('c.iCursoId')
            ->get();
//        dd(DB::getQueryLog());

        return $cursos;
    }


    public function crearCurso()
    {
        Curso::create();
    }
}

