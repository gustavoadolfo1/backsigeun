<?php

namespace App\Http\Controllers\Api\Ura;

use App\Model\Ura\CalendarioAcadDet as CalDet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarioAcadDetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $CalAcadId = $request->get('calactid');

        $caldet = CalDet::hydrate(
            DB::select(
                'Exec  ura.Sp_DASA_SEL_calendariosAcadDetallesXiCalAcadId ?',
                ["{$CalAcadId}"]
            )
        );
        return $this->response->json($caldet);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $caldet = CalDet::hydrate(
            DB::select(
                'EXEC ura.Sp_DASA_INS_calendariosAcademicosDetalles
                 ?,?,?,?,?,?,?,?,?',
                $data
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Ura\CalendarioAcadDet  $calDetalle
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarioAcadDet $calDetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $caldet = CalDet::withoutGlobalScope(DraftScope::class)->findOrFail($id);

        return $this->response->item($caldet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Ura\CalendarioAcadDet  $calDetalle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CalendarioAcadDet $calDetalle)
    {
        $caldet = CalDet::hydrate(
            DB::update(
                'EXEC ura.Sp_DASA_UPD_calendariosAcademicosDetalles',
                ["{$id}"]
            )
        );

        return $this->response->withNoContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param  \App\Model\Ura\CalendarioAcadDet  $calDetalle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $caldet = CalDet::hydrate(
            DB::select(
                'EXEC ura.Sp_DASA_DEL_calendariosAcademicosDetalles ?',
                ["{$id}"]
            )
        );
        if ($caldet[0]->eliminados > 0) {
            $response = [
                'validated' => true,
                'mensaje'   => 'Se eliminó el Registro exitosamente.','eliminados' => $caldet[0]->eliminados
             ];
            $codeResponse = 200;
        } else {
            $response = [
                'validated' => true,
                'mensaje' => 'El Registro no se ha podido eliminar o no existe.',
                'eliminados' => $caldet[0]->eliminados ];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }
}
