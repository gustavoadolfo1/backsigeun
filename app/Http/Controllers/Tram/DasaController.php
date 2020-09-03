<?php

namespace App\Http\Controllers\Tram;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DasaController extends Controller
{
    //
    public function leerData(Request $request){
        $req = $request->get('tipo');
        $data =  $request->get('data') ;

        if (!$data) {
            $data = [];
        }

        DB::enableQueryLog();
        $respuesta = null;
        switch ($req){
            // DATA PARA FORMULARIO DE ESTUDIANTE
            case 'data_ciclos_estudiante':
                $respuesta = DB::select('EXECUTE tram.Sp_SEL_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
                break;
            case 'data_ciclos_estudiante_json':
                $respuesta = DB::select('EXECUTE tram.Sp_JSON_CiclosAcademicosMatricXcEstudCodUniv ?', $data);
                break;
            case 'data_semestres_estudiante':
                $respuesta = DB::select('EXECUTE tram.Sp_SEL_SemestresAcademicosMatricXcEstudCodUniv ?', $data);
                break;
                // FIN DATA PARA FORMULARIO ESTUDIANTE


            case 'data_anios_generados':
                $respuesta = DB::select('EXECUTE tram.Sp_SEL_documentos_iDocYearDocumento');
                break;
            case 'data_meses_generados':
                $respuesta = DB::select('EXECUTE tram.Sp_SEL_documentos_MesesXiDocYearDocumento ?', $data);
                break;
            case 'data_filtro_generados':
                $respuesta = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcConsultaVariablesCampos 1,0,'',0,'',?,?,'','',0", $data);
                break;
            case 'data_rango_generados':
                $respuesta = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcConsultaVariablesCampos 1,0,'',0,'',0,0, ?, ?,0", $data);
                break;
            case 'data_codigo_generados':
                $respuesta = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcConsultaVariablesCampos 1, 0, ?, 0, '', 0, 0, '', '', 0", $data);
                break;
            case 'data_anios_tramitados':
                $respuesta = DB::select('EXECUTE tram.Sp_SEL_tramites_estudiantes_iTramYearDocumento');
                break;
            case 'data_meses_tramitados':
                $respuesta = DB::select('EXECUTE tram.Sp_SEL_tramites_estudiantes_MesesXiTramYearDocumento ?', $data);
                break;
            case 'data_filtro_tramitados':
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_estudiantes_DASAXcConsultaVariablesCampos 0,'',0,'',?,?,'','',0", $data);
                break;
            case 'data_rango_tramitados':
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_estudiantes_DASAXcConsultaVariablesCampos 0,'',0,'',0,0, ?, ?,0", $data);
                break;
            case 'data_codigo_tramitados':
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_estudiantes_DASAXcConsultaVariablesCampos 0, ?, 0, '', 0, 0, '', '', 0", $data);
                break;
            case 'data_por_recepcionar':
                //regresa anios con registros;
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_DASA_por_Recepcionar");
                break;
            case 'data_credencial':
                if (!isset($data[0])) {
                    $data[0] = auth()->user()->iCredId;
                }
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_documentos_estudiantes_X_dias':
                //regresa anios con registros;
                $respuesta = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcConsultaVariablesCampos 1,0,'',0,'',0,0,'','', ?", $data);
                break;
            case 'data_tramites_estudiantes_X_dias':
                //regresa anios con registros;
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_estudiantes_DASAXcConsultaVariablesCampos 0,'',0,'',0,0,'','', ?", $data);
                break;

        }
        //dd($data);
        //dd(DB::getQueryLog());

        return response()->json($respuesta);
    }

    public function guardarData(Request $request){
        $req = $request->get('tipo');
        $data = $request->get('data') ;

        $data = json_decode(json_encode($data));
        // $data = json_encode($data);
        // return $data->tipo_tramite_id;

        // return response()->json($data);

        /*
        if (auth()->user()->iCredId != $data->auditoria->credencial_id) {
            return response()->json(['error' => true, 'msg' => 'Usuario NO AUTENTICADO']);
        }
        */




        //dd($data);

        //DB::enableQueryLog();

        $respuesta = null;
        switch ($req){
            case 'archivar_documento':
                DB::beginTransaction();
                try{
                    $respuesta = DB::select('EXEC ura.Sp_UPD_documentos_ArchivadoXiDocId ?,     ?,?,?,?', [
                        $data[0],


                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ]);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta,
                    ];
                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'archivar_tramite':
                DB::beginTransaction();
                try{
                    $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_estudiantes_DASA_ArchivadoXcCodigoCadena ?, ?, ?,     ?,?,?,?', [
                        $data[0],
                        $data[1],
                        1,


                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ]);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta,
                    ];
                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'acciones_fotografia':
                $accion = ($data[0] == 'aceptar') ? 6 : ( ($data[0] == 'rechazar') ? 5 : 4 );
                DB::beginTransaction();
                try{
                    $respuesta = DB::select('EXEC tram.Sp_UPD_Tramites_Observacion_Fotografia ?, ?,     ?,?,?,?', [
                        $data[1],
                        $accion,



                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ]);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta,
                    ];
                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'cambiar_foto_certificado':

                if (preg_match('/^data:image\/(\w+);base64,/', $data[2])) {

                    $dataTramite = DB::select('EXEC tram.Sp_SEL_tramitesXiTramId ?', [$data[1]]);

                    $dataImg = substr($data[2], strpos($data[2], ',') + 1);
                    $filename = md5($data[0]).'.jpg';
                    $dataImg = base64_decode($dataImg);
                    // $filePath = 'certEstudios/fotos/' . $filename;
                    $filePath = $dataTramite[0]->cTramAdjuntarArchivo;

                    Storage::delete($filePath);
                    Storage::put($filePath, $dataImg);

                    DB::beginTransaction();
                    try{
                        $respuesta = DB::select('EXEC tram.Sp_UPD_Tramites_Observacion_Fotografia ?, ?,     ?,?,?,?', [
                            $data[1],
                            4,



                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ]);

                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta,
                        ];
                        DB::commit();
                    }
                    catch(\Exception $e){
                        $jsonResponse = $this->returnError($e);
                        DB::rollback();
                    }

                }
                else {
                    $jsonResponse = [
                        'error' => true,
                        'msg' => 'Error en carga de archivo',
                        'data' => null,
                    ];
                }
                break;
            case 'recibir_tramite':
                $dataRecibir = $data->chkPorRecibir;
                $idxRec = [];
                foreach ($dataRecibir as $key => $value) {
                    if ($value) {
                        $idxRec[] = $key;
                    }
                }
                $idxRec = implode(',', $idxRec);

                DB::beginTransaction();
                try{
                    $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_recepcionado_DASAXcCodigoCadena ?,       ?, ?, ?, ?', [
                        $idxRec,


                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ]);

                    $dataRetorno = [];

                    foreach ($respuesta as $tram) {
                        if ($tram->iTramId) {
                            $dataTramites = DB::select("EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcConsultaVariablesCampos 1,?,'',0,'',0,0,'','', 0", [$tram->iTramId]);
                            $dataRetorno[] = ['iTramId' => $tram->iTramId, 'documentos' => $dataTramites];
                        }
                    }

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno,
                    ];
                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'recibir_tramite_masivo':
                $dataRecibir = $data->chkPorRecibir;
                $idxRec = [];
                foreach ($dataRecibir as $key => $value) {
                    if ($value) {
                        $idxRec[] = $key;
                    }
                }
                $idxRec = implode(',', $idxRec);
                DB::beginTransaction();
                try{
                    $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_recepcionado_DASAXiTramMovIdXiCredId ?,       ?, ?, ?, ?', [
                        $idxRec,
                        $data->credencial_receptor,
                        $data->observacion??NULL,


                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ]);

                    $dataRetorno = [
                    ];

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno
                    ];
                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = [
                        'error' => true,
                        'msg' =>  substr($e->errorInfo[2], 54), //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
                        //'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode(),
                        'errorLaravel' => $e->getMessage(),
                        'data' => null
                    ];
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'cambiar_numeracion':
                DB::beginTransaction();
                try{
                    $respuesta = DB::select('EXEC ura.Sp_UPD_documentosXiDocId ?, ?,       ?, ?, ?, ?', [
                        $data->documento_id,
                        $data->numeracion,


                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ]);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta,
                    ];
                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
        }
        //dd($data);
        //dd(DB::getQueryLog());

        return response()->json($jsonResponse);
    }

    private function returnError($e){
        $msgResuelto = '';
        if (isset($e->errorInfo)){
            $msgResuelto = substr($e->errorInfo[2], 54); //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
        }

        $jsonResponse = [
            'error' => true,
            'msg' =>  $msgResuelto,
            //'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode(),
            'errorLaravel' => $e->getMessage(),
            'data' => null
        ];
        return $jsonResponse;
    }

    private function recibirTramite($iMovIdx = [], $audit = null) {
        DB::beginTransaction();
        try{
            $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_recepcionado_DASAXiTramMovIdXiCredId ?,       ?, ?, ?, ?', [
                $idxRec,
                $data->credencial_receptor,
                $data->observacion??NULL,


                auth()->user()->iCredId,
                null,
                $data->auditoria->ip??null,
                null,
            ]);

            $dataRetorno = [
            ];

            $jsonResponse = [
                'error' => false,
                'msg' => 'Se guardo Correctamente',
                'data' => $dataRetorno
            ];
            DB::commit();
        }
        catch(\Exception $e){
            $jsonResponse = [
                'error' => true,
                'msg' =>  substr($e->errorInfo[2], 54), //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
                //'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode(),
                'errorLaravel' => $e->getMessage(),
                'data' => null
            ];
            DB::rollback();
        }
        // return response()->json($data);
    }

}
