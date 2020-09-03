<?php

namespace App\Http\Controllers\Biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;

class BibliotecaController extends Controller
{
    public function reserva(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraSolicitudes ?', [
                        $id,
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

    public function resumen(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_ResumenGeneral ?', [
                        $id,
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
    public function solicitudes(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;
        /* if ($id == null || $id == "null" ) {
            $id = null;
        } */
        //return $id;
        switch ($method) {
            case 'GET':
                DB::beginTransaction();
                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraSolicitudes ?', [
                        $id,
                    ]);

                    //foreach ($muestra as $key => $value) {

                    // print_r($value->iPersIdSolicita." ");
                    //$persona = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_DatosSolicitante ?', [$value->iPersIdSolicita]);

                    //$sol = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraSolicitudes ?',[1]);

                    /* $domicilio = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraSolicitudes ?,?',[1,2]); */

                    //return $Pers[0]->Usuario;
                    //return $detalle;
                    //$responseJson[] = array($persona, $sol);

                    /*  if ($Pers->iPersIdSolicita == $detalle->iPersIdSolicita) {
                        } */
                    /* if ($Pers[0]->iPersIdSolicita == $detalle[0]->iPersIdSolicita) {
                            $responseJson[$key] = array("pers" => $Pers, "detalle" => $detalle);
                        } */
                    //}
                    //return $responseJson;
                    /*  if ($muestra[0]->iResult > 0) {
                        //$responseJson = ['error' => false, 'mensaje' => 'Bien añadido a la cesta.'];

                        //$responseJson[] = array_merge_recursive($Pers, $detalle);

                        //$m1[$key] = array("pers" => $Pers, "detalle" => $detalle);
                    }
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido añadir a la cesta.'];
                    } */
                    //14

                    /*  $Pers = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_DatosSolicitante ?', [
                         2992,
                     ]); */
                    //$m1 = array("color" => array("favorito" => "rojo"), 5);
                    //$m2 = array(10, "color" => array("favorito" => "verde", "azul"));
                    // $resultado = array_merge_recursive($m1, $m2);

                    /*  $detalle = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_SolicitanteDetalle ?', [
                         2992,
                     ]); */
                    //$m1 = array("pers" => $Pers, "detalle" => $detalle);
                    //$resultado = array_merge($Pers, $m1);
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
                    'iPersSolicitante' => 'required',
                    'iPersEncargado'   => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_AceptarSolicitud_Global ?,?,?,?,?,?', [
                        $data['iPersSolicitante'],
                        $data['iPersEncargado'],
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

    public function prestamo(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraPrestamos ?', [
                        $id,
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

    public function devolver(Request $request)
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
                    'iBienId'           => 'required',
                    'iPersEncargado'    => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_DevolverBien ?,?,?,?,?,?', [
                        $data['iBienId'],
                        $data['iPersEncargado'],
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
    public function aceptarSolo(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':

                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iGrupoPrestamo' => 'required',
                    'iPersEncargado'   => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_AceptarSolicitud ?,?,?,?,?,?', [
                        $data['iGrupoPrestamo'],
                        $data['iPersEncargado'],
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

            case 'PUT':

                break;

            case 'DELETE':

                break;
        }

        return response()->json($responseJson);
    }

    public function detalles(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_SolicitanteDetalle ?', [
                        $id,
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

    public function apoyo(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Muestra_BienesApoyo_Laptops ?', [
                        $id,
                    ]);

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
                    'cDni'              => 'required',
                    'iBienId'           => 'required',
                    'iPersEncargado'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_BienesApoyo_Laptops ?,?,?,?,?,?,?', [
                        $data['cDni'],
                        $data['iBienId'],
                        $data['iPersEncargado'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido realizar el préstamo.'];
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
    public function apoyoDevolver(Request $request)
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
                    'iBienId'           => 'required',
                    'iPersEncargado'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_DevolverBien ?,?,?,?,?,?', [
                        $data['iBienId'],
                        $data['iPersEncargado'],
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

    public function sanciones(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Sanciones_MuestraSancionados ?', [
                        $data['iLocal'],
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


    public function renovacion(Request $request)
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
                    'iGrupoPrestamo'    => 'required',
                    'iPersEncargado'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Prestamo_Renovacion_SalaXDomicilio ?,?,?,?,?,?', [
                        $data['iGrupoPrestamo'],
                        $data['iPersEncargado'],
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

    public function cambiarTP(Request $request)
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
                    'iGrupoPrestamo'      => 'required',
                    'iTipoPrestamosId'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_BIBLIO_UPD_Prestamo_CambiarTipoPrestamo ?,?', [
                        $data['iGrupoPrestamo'],
                        $data['iTipoPrestamosId']
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


    public function datauser(Request $request)
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
                    $result = DB::select('EXEC bib.Sp_BIBLIO_SEL_General_Muestra_DatosUsuario ?', [
                        $data['dni']
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

    public function listep(Request $request)
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

                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_Listado_EstadoPrestamo');

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


    public function infoHistory(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                /*   $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'dni'       => 'required',
                    'opcion'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
 */
                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Prestamo_MuestraHistorial_Usuario_DNI ?,?', [
                        $data['dni'],
                        $data['opcion'] ?? null
                    ]);

                    /*     if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
                    } */

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

    public function Historylocal(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                /*   $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'dni'       => 'required',
                    'opcion'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
 */
                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Reporte_MuestraHistorial_Local ?,?', [
                        $data['dni'],
                        $data['opcion'] ?? null
                    ]);

                    /*     if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
                    } */

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

    public function Historyuser(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                /*   $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'dni'       => 'required',
                    'opcion'    => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
 */
                try {
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Reporte_MuestraHistorial_Encargado ?,?,?', [
                        $data['Dni'],
                        $data['FechaInicio'] ?? null,
                        $data['FechaFin'] ?? null,

                    ]);

                    /*     if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
                    } */

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


    public function bienUbica(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_INS_Bienes_Ubicacion_SugiereValor ?,?,?,?', [
                        $data['iLocalId'],
                        $data['iUbicaEstante'],
                        $data['iUbicaLado'],
                        $data['iUbicaFila']

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

    public function ubicaestante(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Bienes_Ubicacion_Listado_Estantes ?', [
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

    public function ubicalado(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Bienes_Ubicacion_Listado_Lados ?', [
                        $data['iUbicaEstanteId']

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

    public function ubicafilas(Request $request)
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
                    $responseJson = DB::select('EXEC bib.Sp_BIBLIO_SEL_Bienes_Ubicacion_Listado_Filas ?', [
                        $data['iUbicaLado']

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
