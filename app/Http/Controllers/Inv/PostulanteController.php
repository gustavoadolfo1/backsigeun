<?php

namespace App\Http\Controllers\Inv;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use PDF;

use Illuminate\Support\Facades\Storage;

class PostulanteController extends Controller
{
    public function registrarNuevoPostulante(Request $request)
    {


        $data = \DB::select("EXEC grl.Sp_SEL_personas_proveedorXcNroRUC ?", [ $request->ruc ]);

        //return response()->json($data);

        if (count($data) > 0) {
            $sp = 'exec grl.Sp_UPD_personas_proveedor ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';

            if ($data[0]->iCredId != null) {
                return response()->json( ['validated' => true, 'mensaje' => 'Este proveedor ya se encuentra registrado.'], 500 );
            }

            $parametros = [
                $data[0]->iPersId,
                $request->razonSocial,
                $request->tipoDocRepLegal,
                $request->repLegalDoc ?? NULL,
                $request->repLegal ?? NULL,
                $request->repLegalInscrito ?? NULL,

                $request->direccion,
                $request->phone,
                $request->email,

                NULL,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
        }
        else {
            $sp = 'exec grl.Sp_INS_personas_proveedor ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';

            $parametros = [
                $request->ruc,
                $request->razonSocial,
                $request->tipoDocRepLegal,
                $request->repLegalDoc ?? NULL,
                $request->repLegal ?? NULL,
                $request->repLegalInscrito ?? NULL,

                $request->direccion,
                $request->phone,
                $request->email,

                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
        }

        try {
            $queryResult = \DB::select( $sp, $parametros );

            if ($request->sunatActivo == 1) {
                \DB::select('grl.Sp_INS_UPD_personas_sunatXiPersId ?, ?, ?', [ $queryResult[0]->iPersId, $request->sunatActivo, $request->sunatHabido] );
            }


            $response = ['validated' => true, 'mensaje' => 'Se envió el formulario con éxito.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

        }
        return response()->json( $response, $codeResponse );
    }
}
