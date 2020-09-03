<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\UraControlCicloAcademico;

class UraDocenteController extends Controller
{
    /**
     * Busca docentes por nombre o apellido o documento
     */
    public function buscarDocentes($parametro)
    {
        $docentes = \DB::select('exec ura.[Sp_GRAL_SEL_docentes] ?',array($parametro));

        return response()->json( $docentes );
    }
}