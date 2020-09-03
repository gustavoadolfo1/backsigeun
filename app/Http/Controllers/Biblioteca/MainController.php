<?php

namespace App\Http\Controllers\Biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;

class MainController extends Controller
{
    public function __construct()
    {
        /* $this->middleware('auth.role:admin', ['except' => ['login']]);
        //OR
        $this->middleware('auth.role:admin', ['only' => ['blockUser']]); */
    }


    public function catalogo(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_ListadoGeneral');
                break;
            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iGrupoBienesId'  => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_SeleccionaEnviaCesta ?,?', [
                        $data['iGrupoBienesId'],
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'Bien añadido a la cesta.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido añadir a la cesta.'];
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

    public function catalogohome(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_ListadoGeneral_Pagina 1');
                break;
            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iGrupoBienesId'  => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_SeleccionaEnviaCesta ?,?', [
                        $data['iGrupoBienesId'],
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'Bien añadido a la cesta.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido añadir a la cesta.'];
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

    public function selecciona(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;

        $result = null;

        switch ($method) {
        }

        return response()->json($responseJson);
    }

    public function ranking(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_ListadoRanking');
                break;
        }

        return response()->json($responseJson);
    }

    public function localidades(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_Localidades');
                break;

            case 'POST':
                DB::beginTransaction();

                /* $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iLocalId'  => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                } */

                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_Listado_Localidad ?', [
                        $id
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

    public function selectLocal(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_Listado_Localidad ?', [
                        $id
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

    public function busqueda(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();
        //return ($data['iClasificacionMaterial']);
        //return implode(", ", $data['iFiliales']);    //prints 1, 2, 3

        //return json_encode($data['iFiliales']);
        $result = null;


        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                /* $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iFilId'      => 'required',
                    'iCarreraId'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
 */
                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Busqueda_BusquedaAvanzada ?,?,?', [
                        $data['iFiliales'],
                        $data['iCarreras'] ?? null,
                        $data['iClasificacionMaterial'] ?? null,
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

    public function listFilial(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Busqueda_ListadoFiliales');

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
    public function listEscuelas(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Busqueda_ListadoCarreras');

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

    public function listMaterial(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Busqueda_ListadoClasificacionMaterial');

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
