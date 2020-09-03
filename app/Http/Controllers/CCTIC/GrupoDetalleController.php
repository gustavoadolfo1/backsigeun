<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use PDF;


class GrupoDetalleController extends Controller
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cambiarFecha(Request $request)
    {
        $data = [
            $request->iGrupoDetalleId,
            $request->dFecha,
            $request->tipo_fecha,
        ];



        try {

            if ($request->tipo_fecha == 'fe') {
                $resp = DB::select('exec [acad].[Sp_CCTIC_UPD_GruposDetalle_Actualiza_Fecha_InicioFin]
                    @iGrupoDetalleId = ?,
                    @dFecha  = ?,
                    @tipo_fecha = ? ',
                    $data
                ) ;
            }

            if ($request->tipo_fecha == 'ff' || $request->tipo_fecha == 'fi') {
                $data = [
                    $request->iGrupoDetalleId,
                    $request->fechaInicio,
                    'fi'
                ];

                $resp = DB::select('exec [acad].[Sp_CCTIC_UPD_GruposDetalle_Actualiza_Fecha_InicioFin]
                    @iGrupoDetalleId = ?,
                    @dFecha  = ?,
                    @tipo_fecha = ? ',
                    $data
                ) ;

                $data = [
                    $request->iGrupoDetalleId,
                    $request->fechaFin,
                    'ff'
                ];

                $resp = DB::select('exec [acad].[Sp_CCTIC_UPD_GruposDetalle_Actualiza_Fecha_InicioFin]
                    @iGrupoDetalleId = ?,
                    @dFecha  = ?,
                    @tipo_fecha = ? ',
                    $data
                ) ;
            }


            $response = ['validated' => true, 'message' => 'Datos actualizados correctamente', 'data' => []];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'Error al actualizar la fecha', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function grupoDetalle(Request $request)
    {

        try {
            $grupoDetalles = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Datos_GrupoDetalle] ?', [$request->iGruposId]);
            $response = ['validated' => true, 'message' => 'Datos obtenidos correctamente', 'data' => $grupoDetalles];
            $responseCode= 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'Error al obtener los datos del', 'error' => $e->getMessage(), 'data' => []];
            $responseCode= 500;
        }

        return response()->json($response, $responseCode);


    }

    public function byGrupoDetaleID($grupoDetalleID)
    {
        try {
            $grupoDetalle = DB::select('exec [acad].[Sp_CCTIC_SEL_Asistencias_FechaClases] @iGrupoDetalleId = ?', [$grupoDetalleID]);

            if (count($grupoDetalle) == 0) {
                return response()->json(['validated' => true, 'message' => 'No se encontraron registros', 'data' => []], 200);
            }

            $grupoDetalle = $grupoDetalle[0];

            $grupoDetalle->detalles = json_decode($grupoDetalle->detalles);

            $response = ['validated' => true, 'message' => 'Datos obtenidos correctamente', 'data' => $grupoDetalle];
            $responseCode = 200;


        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se obtuvieron los datos correctamente', 'data' => [], 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return  response()->json($response, $responseCode);
    }

    public function cerrarUnidad(Request $request)
    {
        $params = [
            $request->id,
            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR'),
        ];
        try {

            DB::select('exec  [acad].[Sp_CCTIC_UPD_Notas_CierreUnidad] ?, ?, ?, ?', $params);

            $response = ['validated' => true, 'message' => 'Unidad cerrada correctamente', 'data' => []];
            $responseCode = 200;


        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo cerrar la unidad', 'data' => [], 'error' => $e->getMessage(), 'errorMessage' => substr($e->errorInfo[2] ?? '', 54)];
            $responseCode = 500;
        }

        return  response()->json($response, $responseCode);
    }

    public function cronogramaPDF()
    {
//        return view('cctic.cronograma');
        $pdf = PDF::loadView('cctic.cronograma', [])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
}
