<?php

namespace App\Http\Controllers\Api\Ura;

use App\Model\Ura\ActividadCalendario as ActividadCal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActividadCalendarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actividad = ActividadCal::all();
        return response()->json($actividad);
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
     * @param  \App\Model\Ura\ActividadCalendario  $actividadCalendario
     * @return \Illuminate\Http\Response
     */
    public function show(ActividadCalendario $actividadCalendario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Ura\ActividadCalendario  $actividadCalendario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActividadCalendario $actividadCalendario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Ura\ActividadCalendario  $actividadCalendario
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActividadCalendario $actividadCalendario)
    {
        //
    }
}
