<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;


class CertificadosController extends Controller
{
    public function obtenerCertificadosGrupos(Request $request)
    {
        $this->validate(
            $request,
            ['iPersId' => 'required']
        );

        $parameters = [
            $request->iPersId
        ];
        try {
            $datos["grupos"] = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_ListadoGrupos] ?', $parameters);

            $datos["suficiencia"] = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_ListadoSuficiencia] ?', $parameters);
            // $datos[0]->unidades = json_decode($datos[0]->Notas_Certificado, true);

            $response = ['validated' => true, 'data' => $datos, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function validarDatosCertificadoGrupo(Request $request)
    {
        $this->validate(
            $request,
            [
                'iPersId' => 'required',
                'iGrupoId' => 'required',
                'iFilId' => 'required'
            ],
        );

        $parameters = [
            $request->iPersId,
            $request->iGrupoId,
            $request->iFilId
        ];
        try {
            $datos = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_Datos] ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $datos, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function validarDatosCertificadoSuficiencia(Request $request)
    {
        $this->validate(
            $request,
            [
                'iExamenId' => 'required',
                'iFilId' => 'required'
            ]
        );

        $parameters = [
            $request->iExamenId,
            $request->iFilId
        ];
        try {
            $datos = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_Datos_Suficiencia] ?, ?', $parameters);
            // $datos[0]->unidades = json_decode($datos[0]->Notas_Certificado, true);

            $response = ['validated' => true, 'data' => $datos, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function obtenerDatosCertificadoGrupo($iPersId, $iGrupoId, $iFilId)
    {

        header("Access-Control-Allow-Origin: *");

        $parameters = [
            $iPersId,
            $iGrupoId,
            $iFilId
        ];

        $data = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_Datos] ?, ?, ?', $parameters);

        $data[0]->unidades = $data;
        $data[0]->backgroundImage = public_path('cctic-image/certificado.jpg');

        $pdf = PDF::loadView('cctic.certificado', ['data' => $data[0]])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
    public function obtenerDatosCertificadoSuficiencia($iExamenId, $iFilId)
    {
        header("Access-Control-Allow-Origin: *");

        $parameters = [
            $iExamenId,
            $iFilId
        ];

        $data = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_Datos_Suficiencia] ?, ?', $parameters);

        $data[0]->unidades = json_decode($data[0]->Notas_Certificado);
        // var_dump($data);

        $data[0]->backgroundImage = public_path('cctic-image/certificado.jpg');

        $pdf = PDF::loadView('cctic.certif-suficiencia', ['data' => $data[0]])->setPaper('A4', 'landscape');

        return $pdf->stream();
    }
    public function imprimirActaNotasExamenSuficiencia($iExamenId, $iFilId)
    {
        header("Access-Control-Allow-Origin: *");

        $parameters = [
            $iExamenId,
            $iFilId
            // 2, 1
        ];
        // $iFilId = 1;

        $data = DB::select('exec [acad].[Sp_CCTIC_SEL_Notas_Certificado_Datos_Suficiencia] ?, ?', $parameters);

        $data[0]->notas = json_decode($data[0]->Notas_Acta);
        switch ($iFilId) {
            case 1:
                $data[0]->sede = "MN";
                break;
            case 2:
                $data[0]->sede = "ILO";
                break;
            case 3:
                $data[0]->sede = "ICHUÑA";
                break;

            default:
                $data[0]->sede = "MN";
                break;
        }
        $data[0]->estado = ((float) $data[0]->Promedio_Acta >= 14) ? 'APROBADO' : 'DESAPROBADO';
        // var_dump($data);

        // $data[0]->backgroundImage = public_path('cctic-image/certificado.jpg');

        $pdf = PDF::loadView('cctic.actaExamenSuficienciaNotas', ['data' => $data[0]])->setPaper('A4');

        return $pdf->stream();
    }
    public function validarDatosParaActaExamenUbicacion(Request $request)
    {
        $this->validate(
            $request,
            [
                'iExamenId' => 'required',
                'iFilId' => 'required'
            ]
        );

        $parameters = [
            $request->iExamenId,
            $request->iFilId
        ];

        try {
            $datos = DB::select('exec [acad].[Sp_CCTIC_SEL_Examen_Ubicacion_Acta] ?, ?', $parameters);
            // $datos[0]->unidades = json_decode($datos[0]->Notas_Certificado, true);

            $response = ['validated' => true, 'data' => $datos, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function imprimirActaNotasExamenUbicacion($iExamenId, $iFilId)
    {

        header("Access-Control-Allow-Origin: *");

        $parameters = [
            $iExamenId,
            $iFilId
        ];

        $data = DB::select('exec [acad].[Sp_CCTIC_SEL_Examen_Ubicacion_Acta] ?, ?', $parameters);
        $data[0]->notas = json_decode($data[0]->Notas_Acta);
        switch ($iFilId) {
            case 1:
                $data[0]->sede = "MN";
                break;
            case 2:
                $data[0]->sede = "ILO";
                break;
            case 3:
                $data[0]->sede = "ICHUÑA";
                break;

            default:
                $data[0]->sede = "MN";
                break;
        };
        // $data[0]->unidades = $data;
        // $data[0]->backgroundImage = public_path('cctic-image/certificado.jpg');

        $pdf = PDF::loadView('cctic.actaExamenUbicacionNotas', ['data' => $data[0]])->setPaper('A4');

        return $pdf->stream();
    }
}
