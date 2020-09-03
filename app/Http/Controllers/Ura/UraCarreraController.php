<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GrlFilial;

class UraCarreraController extends Controller
{
    /**
     * Obtiene las carreras por filial
     * 
     */
    public function obtenerCarrerasPorFilial()
    {
        $filiales = GrlFilial::select('iFilId', 'cFilDescripcion')->with('carreras')->get();

        return response()->json( $filiales );
    }
}
