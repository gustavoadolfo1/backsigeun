<?php

namespace App\Http\Controllers\Biblioteca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ImageRequest;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;
use Illuminate\Support\Facades\Storage;

class MantenimientoController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth.role:ADMIN');
        //OR
        //$this->middleware('auth.role:admin', ['only' => ['blockUser']]); */
    }
    /**
     * function mantenimiento
     * Métodos: GET, POST, PUT, DELETE
     *
     * @param \Illuminate\Http\Request $request
     *
     * @SWG\Get(
     *     path="/api/biblioteca",
     *     tags={"préstamo"},
     *     summary="Lista tipos de préstamo",
     *     @SWG\Response(
     *          response=200,
     *          description="Success: List all préstamo",
     *          @SWG\Schema(ref="#/préstamo")
     *      ),
     *     @SWG\Response(
     *          response="404",
     *          description="Not Found"
     *   )
     * ),
     * @return void
     */
    public function tipoprestamo(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_tipo_prestamos');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cDescriTipoPrestamo' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_tipo_prestamos ?,?,?,?,?', [
                        $data['cDescriTipoPrestamo'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iTipoPrestamoId'     => 'required',
                    'cDescriTipoPrestamo' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_tipo_prestamos ?,?,?,?,?,?', [
                        $data['iTipoPrestamoId'],
                        $data['cDescriTipoPrestamo'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_tipo_prestamos ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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


    public function tipobien(Request $request)
    {
        $method = $request->method();
        $data   = $request->get('data');

        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_tipo_bien');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cDescriTipoBien' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_tipo_bien ?,?,?,?,?,?', [
                        $data['cDescriTipoBien'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iTipoBienId'     => 'required',
                    'cDescriTipoBien' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_tipo_bien ?,?,?,?,?,?,?', [
                        $data['iTipoBienId'],
                        $data['cDescriTipoBien'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_tipo_bien ?', [
                        $data['iTipoBienId']
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function estadobien(Request $request)
    {
        $method = $request->method();
        $data   = $request->get('data');

        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_estado_bien');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cDescriEstadoBien' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_estado_bien ?,?,?,?,?,?', [
                        $data['cDescriEstadoBien'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iEstadoBienId'     => 'required',
                    'cDescriEstadoBien' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_estado_bien ?,?,?,?,?,?,?', [
                        $data['iEstadoBienId'],
                        $data['cDescriEstadoBien'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_estado_bien ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function estadoprestamo(Request $request)
    {
        $method = $request->method();
        $data   = $request->get('data');

        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_estado_prestamos');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cDescriEstadoPrestamo' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_estado_prestamos ?,?,?,?,?', [
                        $data['cDescriEstadoPrestamo'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iEstadoPrestamoId'     => 'required',
                    'cDescriEstadoPrestamo' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_estado_prestamos ?,?,?,?,?,?', [
                        $data['iEstadoPrestamoId'],
                        $data['cDescriEstadoPrestamo'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_estado_prestamos ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function autores(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_autores');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cNombreAutores' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_autores ?,?,?,?,?', [
                        $data['cNombreAutores'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iAutorId'       => 'required',
                    'cNombreAutores' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_autores ?,?,?,?,?,?', [
                        $data['iAutorId'],
                        $data['cNombreAutores'],
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_autores ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function bienes(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();
        //return $data;
        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_bienes ?', [
                    $data['iLocalId'] ?? 0
                ]);
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cTitulo' => 'required',
                    'numero_ejemplares' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {

                    $image = $data['image']['0']['src'];
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $filePath = '/biblioteca/portadas/';
                    $iGrupoBienesId = DB::table('bib.bienes')->max('iGrupoBienesId');
                    $max = $iGrupoBienesId + 1;
                    $imageName = $max . '.' . 'jpg';
                    Storage::put($filePath . $imageName, base64_decode($image));

                    $result = DB::select('EXEC bib.Sp_BIBLIO_INS_Bienes_Ingresar_BienesBiblio ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [

                        $data['iLocalId'] ?? null,
                        $data['cCodPatrimonial'] ?? null,
                        $data['cISBN'] ?? null,
                        $data['cISSN'] ?? null,
                        $data['iTipoBienId'] ?? null,
                        $data['iClasificacionMaterialId'] ?? null,
                        $data['iClasiMaterialDetId'] ?? null,
                        $data['cTitulo'],
                        $data['cMateriaTema'] ?? null,
                        $data['iAutorId'] ?? null,
                        $data['cVolumenTomo'] ?? null,
                        $data['cIncluye'] ?? null,
                        $data['iAnhoPublicacion'] ?? null,
                        $data['cNumeroEdicion'] ?? null,
                        $data['iAnhoEdicion'] ?? null,
                        $data['iEditorialId'] ?? null,
                        $data['cCiudad'] ?? null,
                        $data['cPais'] ?? null,
                        $data['iNumPaginas'] ?? null,
                        $data['iEstadoBienId'] ?? null,
                        $data['iCarreraId'] ?? null,
                        $data['cObservaciones'] ?? null,
                        $data['cDescripcion_Mat'] ?? null,
                        $data['cSerial_Mat'] ?? null,
                        $data['cModelo_Mat'] ?? null,
                        $data['cMarca_Mat'] ?? null,
                        $data['cColor'] ?? null,
                        $data['Precio_Total'] ?? null,
                        $data['numero_ejemplares'] ?? null,

                        auth()->user()->iCredId,
                        $request->server->get('REMOTE_ADDR'),
                        $request->getClientIp(),
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iBienId'       => 'required',
                    'cTitulo' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {

                    $image = $data['image']['0']['src']; //$request->image;  // your base64 encoded
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $filePath = '/biblioteca/portadas/';
                    $imageName = $data['iGrupoBienesId'] . '.' . 'jpg';
                    //$archivo = base64_decode($image);
                    //$archivo->storePubliclyAs($filePath, $imageName);
                    // \File::put($archivo, base64_decode($image));

                    Storage::put($filePath . $imageName, base64_decode($image));

                    $resultData = DB::table('bib.bienes')
                        ->where('iGrupoBienesId', $data['iGrupoBienesId'])
                        ->update(['cPortada' => $data['iGrupoBienesId']]);
                    //->update(['cPortada' => $data['image']['0']['src']]);

                    $data['cPortada'] = $data['iGrupoBienesId']; //$imageName; //$data['image']['0']['src'];


                    $result = DB::select('EXEC bib.Sp_UPD_bienes ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $data['iBienId'],
                        $data['iGrupoBienesId'] ?? null,
                        $data['iLocalId'] ?? null,
                        $data['iUbicaBienId'] ?? null,
                        $data['cCodPatrimonial'] ?? null,
                        $data['cISBN'] ?? null,
                        $data['cISSN'] ?? null,
                        $data['iTipoBienId'] ?? null,
                        $data['iClasificacionMaterialId'] ?? null,
                        $data['iClasiMaterialDetId'] ?? null,
                        $data['cTitulo'],
                        $data['cMateriaTema'] ?? null,
                        $data['iAutorId'] ?? null,
                        $data['cVolumenTomo'] ?? null,
                        $data['cIncluye'] ?? null,
                        $data['iAnhoPublicacion'] ?? null,
                        $data['cNumeroEdicion'] ?? null,
                        $data['iAnhoEdicion'] ?? null,
                        $data['iEditorialId'] ?? null,
                        $data['cCiudad'] ?? null,
                        $data['cPais'] ?? null,
                        $data['iNumPaginas'] ?? null,
                        $data['iEstadoBienId'] ?? null,
                        $data['iCarreraId'] ?? null,
                        // $data['dFechaBaja'] ?? '',
                        //$data['dFechaIngreso'] ?? '',
                        //$data['dFechaEgreso'] ?? '',
                        $data['cObservaciones'] ?? null,
                        $data['cDescripcion_Mat'] ?? null,
                        $data['cSerial_Mat'] ?? null,
                        $data['cModelo_Mat'] ?? null,
                        $data['cMarca_Mat'] ?? null,
                        $data['cColor'] ?? null,
                        $data['cPortada'] ?? null,
                        $data['bHabilitado'] ?? 1,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_autores ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function locales(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_Locales');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iFilId'        => 'required',
                    'iCodigoLocal'  => 'required',
                    'cDescriLocal'  => 'required',
                    'bHabilitado'   => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_locales ?,?,?,?,?,?,?,?', [
                        $data['iFilId'],
                        $data['iCodigoLocal'],
                        $data['cDescriLocal'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iLocalId'         => 'required',
                    'iFilId'         => 'required',
                    'iCodigoLocal'   => 'required',
                    'cDescriLocal'   => 'required',
                    'bHabilitado'   => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_Locales ?,?,?,?,?,?,?,?,?', [
                        $data['iLocalId'],
                        $data['iFilId'],
                        $data['iCodigoLocal'],
                        $data['cDescriLocal'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_Locales ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function config(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_config');
                break;

            case 'PUT':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'MinutosMaxReserva_Sala'        => 'required',
                    'MinutosMaxReserva_Domicilio'   => 'required',
                    'NumeroMaxMateriales'           => 'required',
                    'NumeroMaxRechazos'             => 'required',
                    'MinutosBloqueoTemporal'        => 'required',
                    'MaximoTopRanking'              => 'required',
                    'cRutaPathPortadas'             => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_config ?,?,?,?,?,?,?', [
                        $data['MinutosMaxReserva_Sala'],
                        $data['MinutosMaxReserva_Domicilio'],
                        $data['NumeroMaxMateriales'],
                        $data['NumeroMaxRechazos'],
                        $data['MinutosBloqueoTemporal'],
                        $data['MaximoTopRanking'],
                        $data['cRutaPathPortadas'],
                        /*
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null, */
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
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

    public function diasprestamo(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_dias_prestamo');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cDescriDiasPrestamos'  => 'required',
                    'cTipo_Persona'         => 'required',
                    'iNumeroDias'           => 'required',
                    //'bHabilitado'           => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_dias_prestamo ?,?,?,?,?,?,?,?', [
                        $data['cDescriDiasPrestamos'],
                        $data['cTipo_Persona'],
                        $data['iNumeroDias'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iDiasPrestamoId'       => 'required',
                    'cDescriDiasPrestamos'  => 'required',
                    'cTipo_Persona'         => 'required',
                    'iNumeroDias'           => 'required',
                    'bHabilitado'           => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_dias_prestamo ?,?,?,?,?,?,?,?,?', [
                        $data['iDiasPrestamoId'],
                        $data['cDescriDiasPrestamos'],
                        $data['cTipo_Persona'],
                        $data['iNumeroDias'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_dias_prestamo ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function ubicacion(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_ubicacion_bien');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iEstante'          => 'required',
                    'cEstante'          => 'required',
                    'iLado'             => 'required',
                    'cLado'             => 'required',
                    'iFila'             => 'required',
                    'cFila'             => 'required',
                    'iColumna'          => 'required',
                    'cColumna'          => 'required',
                    'cUbicacion'        => 'required',
                    'iBienId'           => 'required',
                    'iLocalId'          => 'required',
                    'bHabilitado'       => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_ubicacion_bien ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $data['iEstante'] ?? null,
                        $data['cEstante'] ?? null,
                        $data['iLado'] ?? null,
                        $data['cLado'] ?? null,
                        $data['iFila'] ?? null,
                        $data['cFila'] ?? null,
                        $data['iColumna'] ?? null,
                        $data['cColumna'] ?? null,
                        $data['cUbicacion'],
                        $data['iBienId'],
                        $data['iLocalId'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iUbicacionBienId'  => 'required',
                    'iEstante'          => 'required',
                    'cEstante'          => 'required',
                    'iLado'             => 'required',
                    'cLado'             => 'required',
                    'iFila'             => 'required',
                    'cFila'             => 'required',
                    'iColumna'          => 'required',
                    'cColumna'          => 'required',
                    'cUbicacion'        => 'required',
                    'iBienId'           => 'required',
                    'iLocalId'          => 'required',


                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_ubicacion_bien ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $data['iUbicacionBienId'],
                        $data['iEstante'],
                        $data['cEstante'],
                        $data['iLado'],
                        $data['cLado'],
                        $data['iFila'],
                        $data['cFila'],
                        $data['iColumna'],
                        $data['cColumna'],
                        $data['cUbicacion'],
                        $data['iBienId'],
                        $data['iLocalId'],
                        $data['bHabilitado'] ?? 1,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_ubicacion_bien ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function material(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_clasificacion_material');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iClasiMaterialId'         => 'required',
                    'cDescriMaterial'          => 'required',
                    'cAbreviadoClasiMat'       => 'required',


                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_clasificacion_material ?,?,?,?,?,?,?', [

                        $data['cDescriMaterial'],
                        $data['cAbreviadoClasiMat'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iClasiMaterialId'         => 'required',
                    'cDescriMaterial'          => 'required',
                    'cAbreviadoClasiMat'       => 'required',


                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_clasificacion_material ?,?,?,?,?,?,?,?', [

                        $data['iClasiMaterialId'],
                        $data['cDescriMaterial'],
                        $data['cAbreviadoClasiMat'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_ubicacion_bien ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function materialDetalle(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_clasificacion_material_detalle');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iClasiMaterialId'         => 'required',
                    'cDescriClasiMaterialDet'          => 'required',
                    'cAbreviadoClasiMatDet'       => 'required',


                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_clasificacion_material_detalle ?,?,?,?,?,?,?,?', [

                        $data['iClasiMaterialId'],
                        $data['cDescriClasiMaterialDet'],
                        $data['cAbreviadoClasiMatDet'],
                        $data['bHabilitado'] ?? 1,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iClasiMaterialDetId' => 'required',
                    'iClasiMaterialId'         => 'required',
                    'cDescriClasiMaterialDet'          => 'required',
                    'cAbreviadoClasiMatDet'       => 'required',
                    'bHabilitado '             => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_clasificacion_material_detalle ?,?,?,?,?,?,?,?,?', [
                        $data['iClasiMaterialDetId'],
                        $data['iClasiMaterialId'],
                        $data['cDescriClasiMaterialDet'],
                        $data['cAbreviadoClasiMatDet'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_clasificacion_material_detalle ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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

    public function editoriales(Request $request)
    {
        $method = $request->method();
        $data   = $request->all(); //$request->get('data');
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'GET':
                $responseJson = DB::select('EXEC bib.Sp_SEL_editoriales');
                break;

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'cDescriEditorial'         => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_INS_editoriales ?,?,?,?,?,?', [

                        $data['cDescriEditorial'],
                        $data['bHabilitado'] ?? 1,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
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
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iEditorialId'    => 'required',
                    'cDescriEditorial' => 'required',
                    'bHabilitado '             => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $result = DB::select('EXEC bib.Sp_UPD_editoriales ?,?,?,?,?,?,?', [
                        $data['iEditorialId'],
                        $data['cDescriEditorial'],
                        $data['bHabilitado'] ?? 1,
                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se actualizo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido actualizo el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;

            case 'DELETE':
                DB::beginTransaction();

                try {
                    $result = DB::select('EXEC bib.Sp_DEL_editoriales ?', [
                        $id
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se elimino correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido eliminar el registro.'];
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
}
