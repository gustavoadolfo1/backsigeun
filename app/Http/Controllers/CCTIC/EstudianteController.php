<?php

namespace App\Http\Controllers\CCTIC;

use App\Http\Controllers\Tram\TramiteOnController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class EstudianteController extends Controller
{
    public function byAsistencia(Request $request)
    {
        $data = [
            $request->iGrupoDetalleId,
            $request->iAsistenciaId,
            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR'),
        ];


        try {
            $error = DB::select('exec [acad].[Sp_CCTIC_INS_Asistencias_GeneraListado] ?, ?, ?, ?, ?',$data);


            $listaEstudiantes = DB::select('exec [acad].[Sp_CCTIC_SEL_Asistencias_MuestraListado] ?',
                [$request->iAsistenciaId]
            );

            if (count($listaEstudiantes) == 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'No se encontraron estudiantes'], 200);
            }

            $response = ['validated' => true, 'data' => $listaEstudiantes, 'message' => 'Estudiantes obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => 'No se pudo obtener los estudianets', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function byNotas(Request $request, $id)
    {

        $params = [
            $id,
            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR'),
        ];

        try {
            $resp = DB::statement('exec [acad].[Sp_CCTIC_INS_Notas_GeneraListado] ?, ?, ?, ?', $params);

            $listaEstudiantes = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_MuestraListado] ?', [$id]);

            $response = ['validated' => true, 'message' => 'Actualizado correctamente', 'data' => $listaEstudiantes];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'no se pudo actualizar las notas', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);

    }


    public function byDNI(Request $request)
    {

        $params = [
            $request->dni,
            $request->programaAcad
        ];
        try {
            $estudiante = DB::select('exec [acad].[Sp_CCTIC_SEL_Panel_Muestra_InfoEstudiante] ?, ?', $params);

            if (count($estudiante) == 0) {
                $response = ['validated' => true, 'message' => 'Estudiante no encontrado', 'data' => $estudiante];
                $responseCode = 200;
                return response()->json($response, $responseCode);
            }

            $estudiante = $estudiante[0];

            $response = ['validated' => true, 'message' => 'Estudiante obtenido correctamente', 'data' => $estudiante];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener el estudiante', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);

    }

}
