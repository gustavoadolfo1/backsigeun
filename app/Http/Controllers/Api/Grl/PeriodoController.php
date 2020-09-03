<?php

namespace App\Http\Controllers\Api\Grl;

use App\Model\Grl\Periodo as Periodo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periodo = Periodo::query()->get();

        return response()->json($periodo);
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
     * @param  \App\GrlPeriodo  $grlPeriodo
     * @return \Illuminate\Http\Response
     */
    public function show(GrlPeriodo $grlPeriodo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GrlPeriodo  $grlPeriodo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GrlPeriodo $grlPeriodo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GrlPeriodo  $grlPeriodo
     * @return \Illuminate\Http\Response
     */
    public function destroy(GrlPeriodo $grlPeriodo)
    {
        //
    }
}
