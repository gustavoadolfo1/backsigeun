<?php

namespace App\Http\Controllers\Api\Ura;

use App\Model\Ura\TipoActividad as TipoAct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoActividad = TipoAct::all();

        return response()->json($tipoActividad);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Ura\TipoActividad  $tipoActividad
     * @return \Illuminate\Http\Response
     */
    public function show(TipoActividad $tipoActividad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Ura\TipoActividad  $tipoActividad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoActividad $tipoActividad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Ura\TipoActividad  $tipoActividad
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoActividad $tipoActividad)
    {
        //
    }
}
