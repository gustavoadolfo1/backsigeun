<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CursoController extends Controller
{
    public function obtenerAreasCurriculares()
    {
        $queryResult = \DB::select('exec [aula].[Sp_SEL_areasCurriculares]');

        return response()->json( $queryResult );
    }
}
