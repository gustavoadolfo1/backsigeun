<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Docente\Docente;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_Shared_Font;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Generales\GrlPersonasController;
use App\GrlConfiguracionGeneral;
use App\GrlPersona;
use App\Http\Controllers\PideController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;



class AsistenciaController extends Controller
{
    public function datosDocente($iPersId)
    {
        $persona = GrlPersona::findOrFail($iPersId);

        //$docente = Docente::findOrFail($persona->iPersId);
        $docente = Docente::where('iPersId', $persona->iPersId)->with('categoria')->get();

        //$docente = $docente->load('categoria', 'condicion', 'dedicacion', 'notas');
        //$notas = $docente->notas->load('notasdetalle')->groupBy('cCurricCursoCod');

        $reniec = DB::select('EXEC grl.Sp_SEL_reniecXcReniecDni ?', [$persona->cPersDocumento]);

        if (empty($reniec)) {
            //Iniciamos una sesion
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            //Indicamos que queremos imprimir el resultado
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //Hacemos uso de un User Agent
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            //Enviamos los datos por post
            curl_setopt($ch, CURLOPT_URL, "http://200.48.160.218:8081/api/pide/reniec?dni=" . $persona->cPersDocumento);
            //Ejecutamos e imprimimos el resultado
            $pide = json_decode(curl_exec($ch));
        } else {
            $pide = json_encode(['msg' => 1]);
        }

        $rutas = GrlConfiguracionGeneral::all();

        return Response::json(['results' => $docente, 'reniec' => $reniec, 'rutas' => $rutas, 'persona' => $persona, 'pide' => $pide], 200);
    }

    public function docentenotas($iPersId)
    {

        $persona = GrlPersona::findOrFail($iPersId);
        $docente = Docente::findOrFail($persona->iPersId)->load('notas');

        //$docente = Docente::where('iPersId', $persona->iPersId)->with('categoria')->get();

        // return $docente;


        $notas = $docente->notas->load('notasdetalle')->groupBy('cCurricCursoCod');

        return Response::json(['notas' => $notas], 200);
    }
    public function generarAsistencia($iDocenteId, $ControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId)
    {
        try {
            $asistencia = DB::select('exec ura.Sp_DOCE_INS_Asistencia_Genera_TodosListados_UnicaVez ?,?,?,?,?,?,?', array($iDocenteId, $ControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId));

            $response = ['validated' => true, 'mensaje' => 'Sp_DOCE_INS_Asistencia_Genera_TodosListados_UnicaVez.'];
        } catch (\Exception $e) {
            $asistencia = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json([$asistencia, 'res' => $response]);
    }
    public function generarAsistenciaEQ(Request $equivalente)
    {
        try {
            $asistencia = DB::select('exec ura.Sp_DOCE_INS_Asistencia_Genera_TodosListados_UnicaVez_Equivalente ?,?,?,?,?,?,?,?,?,?', [
                $equivalente['iDocenteId'],
                $equivalente['iControlCicloAcad'],
                $equivalente['iCurricId'],
                $equivalente['iFilId'],
                $equivalente['iCarreraId'],
                $equivalente['cCurricCursoCod'],
                $equivalente['iSeccionId'],
                $equivalente['iCurricIdEquiv'],
                $equivalente['cCurricCursoCodEquiv'],
                $equivalente['iSeccionIdEquiv']
            ]);

            $response = ['validated' => true, 'mensaje' => 'save equivalente.'];
        } catch (\Exception $e) {
            $asistencia = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json([$asistencia, 'res' => $response]);
    }

    public function faltantesAsistencia(
        $iDocenteId,
        $ControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        //return $iDocenteId;
        try {
            $faltan = DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Cabecera ?,?,?,?,?,?,?', array($iDocenteId, $ControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId));

            $response = ['validated' => true, 'mensaje' => 'Sp_DOCE_UPD_Asistencia_ActualizaListadoAPendiente.', 'result' => $faltan];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $faltan = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e];
        }

        return response()->json(['fecha' => $faltan, 'res' => $response]);
    }
    public function listAsistencias(Request $request)
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
                    $responseJson = DB::select('EXEC ura.Sp_DOCE_SEL_Asistencia_Listado_Cabecera ?,?,?,?,?,?,?,?,?,?,?,?,?', [

                        $data['iDocenteId'],
                        $data['ControlCicloAcad'],
                        $data['iCurricId'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],

                        $data['iCurricIdEquiv01'],
                        $data['cCurricCursoCodEquiv01'],
                        $data['iSeccionIdEquiv01'],

                        $data['iCurricIdEquiv02'],
                        $data['cCurricCursoCodEquiv02'],
                        $data['iSeccionIdEquiv02'],
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


    public function enviarAsistencia(Request $request)
    {

        $method = $request->method();
        $data   = $request->all();
        //return  $data;

        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'POST':
                DB::beginTransaction();

                /*  $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($data->all(), [
                    'iControlCicloAcad' => 'required',
                    'iFilId'            => 'required',
                    'iCarreraId'        => 'required',
                    'iCurricId'         => 'required',
                    'cCurricCursoCod'   => 'required',
                    'iSeccionId'        => 'required',
                    'iDocenteId'        => 'required',
                    'dFechaAsistencia'  => 'required',
                    'alumnos'           => 'required|json',
                    'iHorariosId'       => 'required'
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                } */

                try {
                    $result = DB::select('EXEC ura.Sp_DOCE_INS_Asistencia_InsertaListadoConCheck_copy1 ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $data['iControlCicloAcad'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['iCurricId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],
                        $data['iDocenteId'],
                        $data['dFechaAsistencia'],
                        $data['alumnos'],
                        $data['iHorariosId'],

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        'N',
                        null,

                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'msg' => 'El registro se guardo correctamene.'];
                    } else {
                        $responseJson = ['error' => true, 'msg' => 'No se ha podido guardar el registro.'];
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

    public function editEstudianteAsistencia(
        $iControlCicloAcad,
        $iCarreraId,
        $iCurricId,
        $cCurricCursoCod,
        $iSeccionId,
        $iDocenteId,
        $iFilId,
        $dfechaAsis
    ) {
        try {
            $edit = DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_AsistenciaNotas_ConData ?,?,?,?,?,?,?,?', [
                $iControlCicloAcad,
                $iCarreraId,
                $iCurricId,
                $cCurricCursoCod,
                $iSeccionId,
                $iDocenteId,
                $iFilId,
                $dfechaAsis
            ]);

            $response = ['validated' => true, 'mensaje' => 'Sp_DOCE_SEL_Asistencia_Listado_AsistenciaNotas_ConData.', 'result' => $edit];
        } catch (\Exception $e) {

            $edit = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json([$edit, 'res' => $response]);
    }

    public function editEstudianteAsistenciaEq(Request $request)
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
                    $responseJson = DB::select('EXEC ura.Sp_DOCE_SEL_Asistencia_Listado_AsistenciaNotas_ConData_Equivalente ?,?,?,?,?,?,?,?,?,?,?', [

                        $data['iControlCicloAcad'],
                        $data['iCarreraId'],
                        $data['iCurricId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],
                        $data['iDocenteId'],
                        $data['iFilId'],
                        $data['dfechaAsis'],

                        $data['iCurricIdEquiv'],
                        $data['cCurricCursoCodEquiv'],
                        $data['iSeccionIdEquiv'],
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
    public function updEstudianteAsistencia(Request $upd)
    {

        $this->validate(
            $upd,
            [
                'iFilId'            => 'required',
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCarreraId'        => 'required',
                'iCurricId'         => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'cEstudCodUniv'     => 'required',
                'dFechaAsis'        => 'required',
                'bAsistenciaEstado' => 'required',
            ],
            [
                'iFilId.required'            => 'ID de filial requerido.',
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'iCurricId.required'         => 'ID de currícula requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'cEstudCodUniv.required'     => 'Sección requerida',
                'dFechaAsis.required'        => 'Sección requerida',
                'bAsistenciaEstado.required' => 'Asistencia requerida',
            ]
        );
        $ip = $upd->getClientIp();
        $parametros = [

            $upd->iFilId            ?? NULL,
            $upd->iDocenteId        ?? NULL,
            $upd->iControlCicloAcad ?? NULL,
            $upd->iCarreraId        ?? NULL,
            $upd->iCurricId            ?? NULL,
            $upd->cCurricCursoCod   ?? NULL,
            $upd->iSeccionId        ?? NULL,
            $upd->cEstudCodUniv     ?? NULL,
            $upd->dFechaAsis        ?? NULL,
            $upd->bAsistenciaEstado ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        try {
            $upd = DB::select('exec ura.Sp_DOCE_UPD_Asistencia_Actualiza_EstadoAsistencia ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'msg' => 'Registro actualizado correctamente.', 'result' => $upd];
        } catch (\Exception $e) {

            $upd = 0;
            $response = ['validated' => false, 'msg' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json(['upd' => $upd, 'res' => $response]);
    }
    public function unidadesDocente()
    {
        $unidades = ['Unidad 1', 'Unidad 2', 'Unidad 3', 'Unidad 4'];

        return response()->json(['Unidades' => $unidades]);
    }


    public function DeleteEstudianteAsistencia(Request $data)
    {
        $this->validate(
            $data,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'dFechaAsistencia'      => 'required',
            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'dFechaAsistencia.required'      => 'La Fecha es requerida',
            ]
        );
        $ip = $data->getClientIp();
        $parametros = [

            $data->iDocenteId        ?? NULL,
            $data->iControlCicloAcad ?? NULL,
            $data->iCurricId         ?? NULL,
            $data->iFilId            ?? NULL,
            $data->iCarreraId        ?? NULL,
            $data->cCurricCursoCod   ?? NULL,
            $data->iSeccionId        ?? NULL,
            $data->dFechaAsistencia  ?? NULL,


            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        try {
            $del = DB::select('exec ura.[Sp_DOCE_UPD_Asistencia_ActualizaListadoAPendiente_Eliminar] ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se eliminó la asistencia ' . $data->dFechaAsistencia . ' correctamente.'];
        } catch (\Exception $e) {
            $del = [];
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json(['del' => $del, 'res' => $response]);
        // return $data->all();

        // dump($data);
    }

    public function numeroClasesDocente(Request $request)
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
                    $responseJson = DB::select('EXEC ura.Sp_DOCE_SEL_Asistencia_Cuenta_NumeroClasesXCurso ?,?,?,?,?,?,?,?,?,?,?,?,?', [

                        $data['iDocenteId'],
                        $data['ControlCicloAcad'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['iCurricId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],

                        $data['iCurricIdEquiv01'],
                        $data['cCurricCursoCodEquiv01'],
                        $data['iSeccionIdEquiv01'],

                        $data['iCurricIdEquiv02'],
                        $data['cCurricCursoCodEquiv02'],
                        $data['iSeccionIdEquiv02'],
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

    public function resumenAsistencia(
        $ControlCicloAcad,
        $iFilId,
        $iCarreraId,
        $iCurricId,
        $cCurricCursoCod,
        $iSeccionId,
        $iDocenteId
    ) {
        try {
            $resumen = DB::select('exec ura.[Sp_DOCE_SEL_Asistencia_MuestraResumen_AsistentesXFaltantes_Porcentajes] ?,?,?,?,?,?,?', array(
                $ControlCicloAcad,
                $iFilId,
                $iCarreraId,
                $iCurricId,
                $cCurricCursoCod,
                $iSeccionId,
                $iDocenteId
            ));
            $response = ['validated' => true, 'mensaje' => 'resumen Asistencia.', 'result' => $resumen];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $resumen = 0;
            $response = ['validated' => false, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json(['resumen' => $resumen, 'res' => $response]);
    }

    public function asistencialistadogeneral(
        $iCargaHId,
        $iDocenteId
    ) {
        //try {
        $general = DB::select('EXEC ura.[Sp_DASA_SEL_ActaDocente_PorcentajeAsistencia] ?,?', array(
            $iCargaHId,
            $iDocenteId,
        ));

        $general = ['validated' => true, 'mensaje' => 'Sp_DASA_SEL_ActaDocente_PorcentajeAsistencia', 'result' => $general];
        $response = 200;

        /*   Excel::create('Listado general asistencia', function($excel) use($general){
                $excel->sheet('registro de asistencia', function($sheet) use($general) {

                    $sheet->loadView('docente.PdfGeneral');
                     /* $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                     $sheet->mergeCells("B1:O1");
                     $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);

                });
            })->export('xlsx'); */

        //} catch (\Exception $e) {
        //    $general = 0;
        //    $response = ['validated' => false, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        //}
        return response()->json(['general' => $general, 'res' => $response]);
    }



    public function exportlistEstudiantes($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId)
    {
        $list = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls_copy1 ?,?,?,?,?,?,?', array($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId));

        //return (new EstudiantesExport(collect($list)))->download('estudiantes-list.xlsx');
    }


    public function equivalente(Request $request)
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
                    $responseJson = DB::select('EXEC ura.Sp_DOCE_SEL_Asistencia_Listado_Xls_Equivalente ?,?,?,?,?,?,?,?,?,?', [

                        $data['iControlCicloAcad'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['iCurricId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],
                        $data['iDocenteId'],

                        $data['iCurricIdEquiv'],
                        $data['cCurricCursoCodEquiv'],
                        $data['iSeccionIdEquiv'],
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
