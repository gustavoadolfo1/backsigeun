<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UraCurriculaCursoController extends Controller
{
    /**
     * Activa Cursos de un plan
     */
    public function activarCursosCurricula(Request $request)
    {
        foreach ($request->cursos as $curso) {
            \DB::select('exec ura.[Sp_DASA_UPD_activaCursosPlan] ?, ?, ?',array($curso['curricDetId'], $curso['curricDetActi'], $curso['curricDetActiSecc']));
        }
    }
}
