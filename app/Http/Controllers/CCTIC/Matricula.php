<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Matricula extends Controller
{
    public function obtenerCarrerasxProgramaAcad($progAcad)
    {

        try {
            $carreras = \DB::select('exec [acad].[Sp_SEL_carrerasXiProgramasAcadId]  ?',
                [$progAcad]);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo las carreras correctamente', 'data' => $carreras];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $carreras = [];
            $response = ['validated' => true, 'mensaje' => 'No se pudo obtener las carreras', 'data' => $carreras];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);


    }


    public function planByCarreraID($id)
    {

        try {
            $planes = \DB::select('exec [acad].[Sp_SEL_planXCarreraId]  ?',
                [$id]);
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo el plan cerrectamente', 'data' => $planes];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $planes = [];
            $response = ['validated' => false, 'mensaje' => 'No se pudo obtener el plan', 'data' => $planes];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);


    }

    public function MatricularPreinscritos(Request $request)
    {

        $ids = json_encode($request->preInscripId);
        $user = auth()->user()->cCredUsuario;
//        return response()->json(['data' => auth()->user()]);
        $parameters = [
            $request->iProgAcadCod,
            $ids,
            auth()->user()->cCredUsuario,
//            auth()->user()->cCredKey,
            'a',
            $request->server->get('REMOTE_ADDR'),
            'M'
        ];

        try {
            $query = \DB::select('[acad].[Sp_CCTIC_ESTUD_INS_generaPreDeuda] ?, ?, ?, ?, ?, ?', $parameters);
            $data = json_decode($query[0]->idAdeudoCaja_json);

            $responseData = [];
            foreach ($data as $resp => $key) {
                  if ($key->bProcesado) {
                      $preinscrito = \DB::select(' [acad].[SP_UPDATE_preinscripciones_set_Adeudo] ?, ?', [$key->id_pre_inscripcion, $key->iAdeuCabId]);
                     array_push( $responseData, $preinscrito[0]);
                  }
            }


            $response = ['validated' => true, 'mensaje' => 'Gurdado correctamente', 'data' => $responseData];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

}
