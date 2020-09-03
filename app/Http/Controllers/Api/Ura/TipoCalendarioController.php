<?php

namespace App\Http\Controllers\Api\Ura;

use App\Model\Ura\TipoCalendario as TipoCal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoCalendarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipoCalendario = TipoCal::query()->get();
        return response()->json($tipoCalendario);
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
     * @param  \App\Model\Ura\TipoCalendario  $tipoCalendario
     * @return \Illuminate\Http\Response
     */
    public function show(TipoCalendario $tipoCalendario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Ura\TipoCalendario  $tipoCalendario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoCalendario $tipoCalendario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Ura\TipoCalendario  $tipoCalendario
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoCalendario $tipoCalendario)
    {
        //
    }
}
