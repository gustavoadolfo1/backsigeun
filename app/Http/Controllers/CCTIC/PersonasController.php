<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class PersonasController extends Controller
{
    public function buscarPersonaNumDocumento(Request $request)
    {
        $this->validate(
            $request,
            ['cDocumento_cDescripcion' => 'required']
        );

        $parameters = [
            $request->cDocumento_cDescripcion
        ];
        try {

            $persona = DB::select('exec [grl].[Sp_SEL_personasXcDocumento_cDescripcion] ?', $parameters);

            $response = ['validated' => true, 'data' => $persona, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function buscarPersonaPorIpersId(Request $request)
    {
        $this->validate(
            $request,
            ['iPersId' => 'required']
        );

        $parameters = [
            $request->iPersId
        ];
        try {

            $persona = DB::select('exec [grl].[Sp_SEL_personasXiPersId] ?', $parameters);

            $response = ['validated' => true, 'data' => $persona, 'message' => 'Datos obtenidos correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function registrarPersona(Request $request)
    {
        $parameters = [
            1,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            /*Persona Natural*/
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPerSexo,//cPersSexo,
            $request->dFechaNac, //dPersNacimiento,
            /*Persona Juridica*/
            null,
            null,
            null,
            null,
            /*Campos de autoria*/
            null,
            gethostname(),
            $request->getClientIp(),
            'getmac'
        ];

        return $parameters[2];
        try {

            $persona = DB::select('exec [grl].[Sp_INS_personas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $persona, 'message' => 'Datos registrados correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function actualizarPersona(Request $request)
    {

        $parameters = [
            $request->iPersId,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo,
            $request->dPersNacimiento,
            // @_cPersRazonSocialNombre VARCHAR(MAX),
            // @_cPersRazonSocialCorto VARCHAR(50),
            // @_cPersRazonSocialSigla VARCHAR(25),
            // @_cPersRepresentateLegal VARCHAR(150),
            $request->iCredId,
            $request->cEquipoSis,
            $request->cIpSis,
            $request->cMacNicSis,
        ];
        try {

            $persona = DB::select('exec [grl].[Sp_UPD_personas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $persona, 'message' => 'Datos actualizados correctamente',];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se pudo obtener los datos', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
}
