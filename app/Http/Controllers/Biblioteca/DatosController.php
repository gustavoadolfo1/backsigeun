<?php

namespace App\Http\Controllers\Biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;
use App\Http\Controllers\Ura\GeneralController;

class DatosController extends Controller
{
    public function userdata(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'POST':

                DB::beginTransaction();
                try {
                    $responseJson = DB::select('EXEC bib.Sp_SEL_credenciales_rolXcBusqueda ?', [
                        $data['cCredUsuario'],
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }
                break;
        }

        return response()->json($responseJson);
    }

    public function datasolicitante(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                DB::beginTransaction();

                try {
                    $solicitantes = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_Solicitantes ?', [
                        $id,
                    ]);
                    if (isset($solicitantes)) {
                        $responseJson = [];
                    }
                    foreach ($solicitantes as $key => $value) {

                        $responseJson[] = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_DatosSolicitante ?', [$value->iPersIdSolicita]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;
        }
        return response()->json($responseJson);
    }

    public function info(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'dni'      => 'required'
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_General_Muestra_DatosUsuario ?', [
                        $data['dni']
                    ]);



                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;
        }
        return response()->json($responseJson);
    }
}
