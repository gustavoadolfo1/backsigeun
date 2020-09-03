<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }




    public function reprogramar(Request $request)
    {
        $params=  [
            $request->iGrupoId,
            json_encode($request->clases),
            auth()->user()->cCredUsuario,
            'E',
            $request->server->get('REMOTE_ADDR'),
            'M'
        ];

        try {
            $resp = DB::select('exec [acad].[Sp_CCTIC_UPD_Asistencias_Reprogramacion]
                @iGrupoId = ?,
                @json = ?,
                
                @cUsuarioSis = ?,
	            @cEquipoSis= ?,	
	            @cIpSis = ?,			
	            @cMacNicSis = ?' ,
                $params
            );

            $response = ['validated' => true, 'message' => 'Clase(s) repgramada(s) correctamente', 'data' => []];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'Error al reprogramar', 'error' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function update(Request $request)
    {
        $data = [
            $request->iAsistenciaDetalleId,
            $request->estadoAsistencia,
            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR'),
        ];
        try {
            $resp = DB::select('exec [acad].[Sp_CCTIC_UPD_Asistencias_ActualizaAsistencia] ?, ?, ?, ?, ?',
            $data);


            $response = ['validated' => true, 'message' => 'Actualizado correctamente', 'data' => $resp];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'no se pudo actualizar la asistencia', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function actuailzarAsistenciaDocente(Request $request)
    {

        $params = [
            $request->id,
            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR'),
        ];
        try {
            $resp = DB::statement('exec [acad].[Sp_CCTIC_UPD_Asistencias_ActualizaAsistencia_Fecha] ?, ?, ?, ?', $params);

            $response = ['validated' => true, 'message' => 'Actualizado correctamente', 'data' => []];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'no se pudo actualizar la asistencia', 'error' => $e->getMessage(), 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);

    }
}
