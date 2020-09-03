<?php

namespace App\Http\Controllers\Api\Grl;

use App\GrlFilial as Filial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $filial = Filial::query()->get();

        return response()->json($filial);
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
     * @param  \App\GrlFilial  $grlFilial
     * @return \Illuminate\Http\Response
     */
    public function show(GrlFilial $grlFilial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GrlFilial  $grlFilial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GrlFilial $grlFilial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GrlFilial  $grlFilial
     * @return \Illuminate\Http\Response
     */
    public function destroy(GrlFilial $grlFilial)
    {
        //
    }
}
