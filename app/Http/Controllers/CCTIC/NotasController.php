<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class NotasController extends Controller
{




    public function actualizarNotaEstudiante(Request $request)
    {
        $data = [
            $request->iNotaId,
	        $request->nota,

            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR')
        ];
        try {

            DB::statement('exec [acad].[Sp_CCTIC_UPD_Notas_ActualizaNota] ?, ?, ?, ?, ?', $data);

            $response = ['validated' => true, 'message' => 'Actualizado correctamente', 'data' => []];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo actualizar la nota', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function actaNotasByUnidad(Request $request)
    {
        $params = [
            $request->iGruposId,
            $request->iGrupoDetalleId,
            $request->iFilId
        ];


        try {
            $data = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_ActaNotas_Final_Unidad] ?, ?, ?', $params);
        } catch (\Exception $e) {
            $data = [];
            return response()->json($e->getMessage());
        }

        $pdf = \PDF::loadView('cctic.actaNotas', compact(['data']))->setPaper('A4');
//        return $pdf->download("actaNotas.pdf");
        return $pdf->stream();
    }



}
