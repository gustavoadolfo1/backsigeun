<?php

namespace App\Http\Controllers\Biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;

class EstandarController extends Controller
{
    public function cesta(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_ReservaBienes_Cesta ?,?', [
                        $data['iTipoPrestamoId'],
                        $data['iPersIdSolicita'],
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

    public function seleccionar(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Muestra_TipoPrestamos');

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;
            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iTipoPrestamoId'  => 'required',
                    'iPersIdSolicita'  => 'required',
                    'iGrupoBienesId'   => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_ReservaBienes ?,?,?,?,?,?,?,?', [
                        $data['iTipoPrestamoId'],
                        $data['iPersIdSolicita'],
                        $data['iGrupoBienesId'],
                        $data['iBienId'] ?? null,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'Bien reservado correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido reservar el bien.'];
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

    public function historial(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraHistorial_Usuario ?', [
                        $data['iPersId'],
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

    public function muestraSanciones(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Sanciones_MuestraSanciones_Usuario ?', [
                        $data['iPersSolicitante']
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
    public function estado(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();
        //return $data;
        $result = null;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraEstadoPrestamo_Usuario ?', [
                        $data['iPersId']
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
    public function cancelarReserva(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();
        //return $data;
        $result = null;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'Ticket_Virtual'   => 'required',
                    'iPersIdSolicita'   => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_RechazarSolicitud ?,?,?,?,?,?', [
                        $data['Ticket_Virtual'],
                        $data['iPersIdSolicita'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
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

    public function apoyo(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_BienesApoyo_Laptops ?', [
                        $data['iLocalId']
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
