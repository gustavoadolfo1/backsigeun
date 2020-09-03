<?php

namespace App\Repositories\cctic;
use Illuminate\Support\Facades\DB;
use App\model\cctic\TipoDuracion;

class GeneralRepository {
    public function modalidadesByProgaramAcad($programAcadId)
    {
        $modalidades = DB::table('acad.modalidades_estudios as me')
            ->join('acad.programas_academicos_modalidades_estudios as pame', 'pame.iModalEstudId', '=', 'me.iModalEstudId')
            ->where('pame.iProgramasAcadId', '=', $programAcadId)
            ->select('me.cModalEstudDsc', 'pame.iProgAcadModEstudId')
            ->get();

        return $modalidades;
    }

    public function publicacionDuracion()
    {
       return  TipoDuracion::get();
    }
}
