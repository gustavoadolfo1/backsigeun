<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Hashids\Hashids;

class InscripcionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->hashids = new Hashids('SIGEUN Admision', 20);
    }

    public function imprimirPreInscripcion($idInscripcion, $isHashed = null)
    {
        if ($isHashed != null) {
            $ids = $this->hashids->decode($idInscripcion);
            $id = $ids[0];
        } else {
            $id = $idInscripcion;
        }

        $data = \DB::select("exec [adm].[SP_SEL_fichaPostulanteXiInscripId] ?", [ $id ]);

        // dd($data);

        $data[0]->deudas = json_decode($data[0]->json_estado_cta_cte);

        $pdf = PDF::loadView('admision.preInscripcion', [ 'data' => $data[0] ])->setPaper('A4');
        return $pdf->stream();
    }

    public function imprimirConstanciaInscripcion($idInscripcion, $isHashed = null)
    {
        if ($isHashed != null) {
            $ids = $this->hashids->decode($idInscripcion);
            $id = $ids[0];
        } else {
            $id = $idInscripcion;
        }

        $data = \DB::select("exec [adm].[SP_SEL_fichaPostulanteXiInscripId] ?", [ $id ]);

        // dd($data);

        $data[0]->deudas = json_decode($data[0]->json_estado_cta_cte);

        $pdf = PDF::loadView('admision.constanciaInscripcion', [ 'data' => $data[0] ])->setPaper('A6');
        return $pdf->stream();
    }

    public function editInscripcion(Request $request)
    {
        $fechaNac = date('Y-m-d', strtotime($request->dNacimiento));

        $carreraFilial = explode('-', $request->carrera);

        $parametros = [
            $request->iInscripId,
            $request->filial,
            $request->iGrupoControl,
            $request->iNacionId,
            $request->iTipoIdent,
            $request->cDocumento,
            $request->cPaterno,
            $request->cMaterno,
            $request->cNombre,
            $request->cSexo,
            $fechaNac,
            NULL,
            $request->cTelefono,
            NULL,
            $request->cEmail,
            $request->cDireccion,
            $carreraFilial[0],
            $request->colegio,
            $request->tipoColegio,
            $request->iPreEgreso,
            $request->preparacion,
            $request->cModalidadCod,
            $carreraFilial[1],

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'

        ];

        // return response()->json( $parametros );
        
        try {
            $queryResult = \DB::select('EXEC [adm].[Sp_INS_UPD_inscripcionesOnline] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function buscarInscripcion($dni, $grupoControl)
    {
        $queryResult = \DB::select('EXEC [adm].[SP_SEL_inscritosOnlineXcPersDocumentoXiCicloControl] ?, ?', [ $dni, $grupoControl ]);

        return response()->json( $queryResult );
    }

    public function eliminarInscripcion($idInscripcion)
    {
        
        $queryResult = \DB::select('EXEC [adm].[SP_DEL_inscritosXiInscripId] ?', [ $idInscripcion ]);

        $response = ['validated' => true, 'mensaje' => 'Inscripción eliminada correctamente', 'queryResult' => $queryResult[0]];
        $codeResponse = 200;

        if ($queryResult[0]->iResult == 0) {
            $response = ['validated' => true, 'mensaje' => $queryResult[0]->cMensaje];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
