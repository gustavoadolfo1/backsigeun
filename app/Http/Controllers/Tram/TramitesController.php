<?php

namespace App\Http\Controllers\Tram;

use App\ClasesLibres\Generales\UtilControladores;
use App\ClasesLibres\TramiteDocumentario\PdfCreator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Generales\RespuestasApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use TCPDF_FONTS;

class TramitesController extends Controller
{

    // NUEVO
    public function getData(Request $request, $tipos) {

        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));
        $arrTipos = explode('&', $tipos);
        $respuestas = [];

        foreach ($arrTipos as $tipo){
            $prFT = explode('.', $tipo);
            $datPrefix = count($prFT);
            if ($datPrefix > 1) {
                switch ($prFT[0]) {
                    case 'grl':
                        $respuesta = app(RespuestasApiController::class)->getData($request, $prFT[1], true);
                        break;
                }
            }
            else {

                switch ($tipo) {
                    case 'anios':
                        $respuesta = DB::select("tram.Sp_SEL_tramites_iTramYearRegistro");
                        break;
                    case 'mes_anio':
                        $respuesta = DB::select("tram.Sp_SEL_tramites_MesesXiTramYearRegistro  ?", [$dataObj->anio]);
                        break;
                    case 'buscar_tramite_criterios':
                        $respuesta = DB::select('tram.Sp_SEL_Criterio_Busqueda_Tramites');
                        break;
                    case 'notificacion_observados':
                        $respuesta = DB::select('tram.Sp_SEL_tramites_observacionesXiDepenReceptorId ?', [$dataObj->idDependencia]);
                        $collectSalida = collect();
                        foreach ($respuesta as $notif) {
                            $notif->fechaObservacionHumanos = Date::parse($notif->dtTramObsFechaHoraObservado)->diffForHumans(null, false, false, 3);
                            $notif->estadoAlerta = 'Observado';
                            $collectSalida->add($notif);
                        }
                        $respuesta = $collectSalida;
                        break;
                    case 'badge_por_recibir':
                        $respuesta = collect(DB::select('tram.Sp_SEL_cantidad_tramites_porrecibirXiDepenId_Receptor ?', [$dataObj->idDependenciaReceptor]))->first();
                        break;

                    default:
                        abort(503, 'Error: opc NO IMPLEMENTADA Get (' . $tipo . ')' );
                        break;
                }
            }
            $respuestas[$tipo] = $respuesta;
        }
        return response()->json($respuestas);
    }


    public function setData(Request $request, $tipo, $subtipo = null) {
        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));

        $jsonResponse = [];
        DB::beginTransaction();
        try {
            switch ($tipo) {

                case 'recibir_tramite':
                    $dataRecibir = $dataObj->chkPorRecibir;
                    $idxRec = [];
                    foreach ($dataRecibir as $key => $value) {
                        if ($value) {
                            $idxRec[] = $key;
                        }
                    }
                    $idxRec = implode(',', $idxRec);


                    $dataGuardar = [
                        $idxRec,
                        auth()->user()->iCredId,
                        $dataObj->observacion??NULL,
                    ];

                    $dataGuardar = array_merge($dataGuardar, UtilControladores::getAuditoria());
                    $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_movimientos_RecepcionarXcCodigoCadena ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);

                    $jsonResponse = UtilControladores::respuestasSimple($tipo, $respuesta);

                    break;
                case 'rechazar_envio_comentario':
                    $dataGuardar = [
                        $dataObj->idMovimiento,
                        auth()->user()->iCredId,
                        $dataObj->comentario,
                    ];
                    $dataGuardar = array_merge($dataGuardar, UtilControladores::getAuditoria());

                    $dataRetorno = DB::select('EXEC tram.Sp_DEL_tramites_movimientos_Rechazar ?, ?, ?,      ?, ?, ?, ?', $dataGuardar);
                    $jsonResponse = UtilControladores::respuestasSimple($tipo, $dataRetorno);
                    break;

                default:
                    abort(503, 'Error: opc NO IMPLEMENTADA Set (' . $tipo . ')' );
                    break;

            }
            DB::commit();
        }
        catch (\Exception $e) {
            $jsonResponse = self::returnError($e);
            DB::rollback();
        }
        return response()->json($jsonResponse);
    }














    // LO QUE SIGUE ES ANTERIOR




    public function leerDataAnonimo(Request $request) {
        $req = $request->get('tipo');
        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));

        $respuesta = null;
        switch ($req) {
            case 'credenciales':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXcCredUsuario ?', $data);
                break;
            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;
            case 'dependencias_recibo_externo':
                $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiEntIdXcDepenNombre 1, \'%%\'');
                $respuesta = collect($respuesta)->where('iTipoTramId', 2);
                $respuesta = collect($respuesta->values());
                break;
            case 'seguimiento':
                $error = [false];
                $resTram = DB::select("EXEC tram.Sp_SEL_tramitesSeguimientoXiDepenIdXcFechaRegistroXiTramNumRegistroXiDepenIdXiYearXiTipoTramId ?, ?, ?", [
                    $dataObj->idDependencia,
                    Carbon::parse($dataObj->fechaPresentacion)->format('Ymd'),
                    $dataObj->textBuscar
                ]);
                if (count($resTram) > 0) {
                    if ($resTram[0]->iTipoTramId == 2) {
                        $respuesta = TramiteOnController::detalleSeguimiento($resTram[0]->iTramId, true);
                        DB::select("EXEC grl.Sp_INS_historiales_consultas ?,?,?", [
                            null,
                            1,
                            json_encode([
                                'dataFormulario' => [
                                    'idDependencia' => $dataObj->idDependencia,
                                    'fechaTramite' => Carbon::parse($dataObj->fechaPresentacion)->format('Ymd'),
                                    'txtBusqueda' => $dataObj->textBuscar
                                ],
                                'fechaActual' => now()
                            ])
                        ]);
                    }
                    else {
                        $error = [true, 'No se puede hacer seguimiento a este Tramite porque es interno.'];
                    }
                }
                else {
                    $error = [true, 'La consulta ingresada no existe.'];
                }

                if ($error[0]) {
                    $jsonResponse = [
                        'error' => true,
                        'msg' => $error[1],
                        'data' => null
                    ];
                    abort(503, $error[1]);
                }
                break;
        }
        return response()->json($respuesta);
    }
    //
    public function leerData(Request $request)
    {
        $req = $request->get('tipo');
        $data =  $request->get('data') ;


        $respuesta = null;
        switch ($req) {
            case 'credenciales_todos':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', $data);
                $respuesta = collect($respuesta)->where('cPersRazonSocialNombre', null)->values();
                break;
            case 'filiales':
                $respuesta = DB::select('EXEC grl.Sp_SEL_filialesXiEntId 1');
                break;
                /*
            case 'anios':
                //regresa anios con registros;
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_iTramYearRegistro");
                break;
                *//*
            case 'mes_anio':
                //regresa Meses con registro segun año;
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_MesesXiTramYearRegistro  ?", $data);
                break;*/
            case 'data_fecha':
                //regresa registros en una fecha especificada
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos ?,?,0,0,'','', 0,", $data);
                break;
            case 'data_dias':
                //regresa registros en una fecha especificada
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos ?,'',0,0,'','', ?",$data);
                break;
            case 'data_mes':
                //regresa registros en un mes de año especifico
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos  ?,'',?,?,'','', 0", $data);
                break;
            case 'data_rango':
                //regresa registros en un rango de fechas.
                $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos ?,'',0,0,?,?, 0", $data);
                break;
            case 'data_personas':
                //Buscar personas por num documento o nombre
                //dd($data[0]);
                if (count($data)>1) {
                    $respuesta = DB::select("EXEC grl.Sp_SEL_personasXiTipoPersIdXcDocumento_cDescripcion ? ,?", $data);
                } elseif($data[0] == '%%') {
                    $respuesta = DB::select("EXEC grl.Sp_SEL_personasXcDocumento_cDescripcion ?", $data);
                } else {
                    $respuesta = DB::select("EXEC grl.Sp_SEL_personasXiPersId ?", $data);
                }
                break;
            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;
            case 'data_jefe_oficina':
                $respuesta = DB::select("EXEC seg.Sp_SEL_Tramites_DatosIniciales_NuevoTramiteXiDepenId ?", $data);
                break;
            case 'data_personal_oficina':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiDepenId ?", $data);
                break;
            case 'data_tipo_documentos':
                $respuesta = DB::select("EXEC grl.Sp_SEL_tipo_documentosXiTipoTramId ?", $data);
                break;
            case 'data_tipo_documentos_dependencia':
                $respuesta = DB::select("EXEC grl.Sp_SEL_tipo_documentosXiTipoTramIdXiDepenId ?, ?", $data);
                break;
            case 'data_indicaciones':
                $respuesta = DB::select("EXEC tram.Sp_SEL_indicaciones");
                break;
            case 'data_observaciones':
                $respuesta = DB::select("EXEC tram.Sp_SEL_observaciones");
                break;
            case 'data_tupa':
                $respuesta = DB::select("EXEC grl.Sp_SEL_conceptosXiDepenIniciaId ?", $data);
                //$respuesta = DB::select("EXEC grl.Sp_SEL_tupasXiEntIdXcCodigo_cDenominacion ?,?", $data);
                break;
            case 'data_tupa_requisitos':
                $respuesta = DB::select("EXEC grl.Sp_SEL_conceptos_requisitosXiConcepId ?", $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_prioridades':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tipo_prioridades', $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_tramites_proceso':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos ?, ?, ?, ?, ?, ?', $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_destinatario_obligado':
                $respuesta = DB::select('EXEC tram.Sp_SEL_movimientos_DatosIniciales_EnvioXiTipoTramIdXiConcepIdXiTipoDocId ?, ?, ?, ?', $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_destinatarios_posibles':
                $respuesta = DB::select('EXEC tram.Sp_SEL_dependencias_EnvioXiTipoTramIdXiConcepIdXiTipoDocId ?, ?, ?, ?, ?', $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_numeracion':
                $respuesta = DB::select('EXEC tram.Sp_SEL_iTramNumeroDocumentoXiDepenEmisorIdXiTipoDocIdXiTramYearDocumento ?, ?, ?', $data);
                // $respuesta = DB::select("EXEC grl.Sp_SEL_tupas_requisitosXiTupaId ?", $data);
                break;
            case 'data_credencial':
                if (!isset($data[0])) {
                    $data[0] = auth()->user()->iCredId;
                }
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', $data);
                break;
            case 'data_tramites_por_recibir':
                $data = json_decode(json_encode($data));
                switch ($data->option) {
                    case 1:
                        $dataEnviar = [ $data->idDependencia, Carbon::parse($data->fecha)->format('Ymd'), '', '', '', '', '', '', '',];
                        break;
                    case 2:
                        $dataEnviar = [$data->idDependencia, '', $data->year, $data->month, '', '', '', '', '',];
                        break;
                    case 3:
                        $dataEnviar = [$data->idDependencia, '', '', '', Carbon::parse($data->range_1)->format('Ymd'), Carbon::parse($data->range_2)->format('Ymd'), '', '', '',];
                        break;
                    case 4:
                        $dataEnviar = [$data->idDependencia, '', '', '', '', '', $data->yearCriterio, $data->idCriterio, $data->variableCriterio??'%%',];
                        break;
                }
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_porrecibirXiDepenId_ReceptorXcConsultaVariablesCampos ?,     ?,  ?, ?    , ?, ?,     ?, ?, ?', $dataEnviar);

                // $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_porrecibirXiDepenId_Receptor ?', $data);
                break;
            case 'buscar_tramite_entidad':
                $data = json_decode(json_encode($data));
                switch ($data->option) {
                    case 1:
                        $dataEnviar = [1, Carbon::parse($data->fecha)->format('Ymd'), '', '', '', '', '', '', '',];
                        break;
                    case 2:
                        $dataEnviar = [1, '', $data->year, $data->month, '', '', '', '', '',];
                        break;
                    case 3:
                        $dataEnviar = [1, '', '', '', Carbon::parse($data->range_1)->format('Ymd'), Carbon::parse($data->range_2)->format('Ymd'), '', '', '',];
                        break;
                    case 4:
                        $dataEnviar = [1, '', '', '', '', '', $data->yearCriterio, $data->idCriterio, $data->variableCriterio??'%%',];
                        break;
                }
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramitesXiEntIdXcConsultaVariablesCampos ?,     ?,  ?, ?    , ?, ?,     ?, ?, ?', $dataEnviar);

                // $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_porrecibirXiDepenId_Receptor ?', $data);
                break;
            case 'data_tramites_archivados':
                $data = json_decode(json_encode($data));
                switch ($data->option) {
                    case 1:
                        $dataEnviar = [ $data->idDependencia, Carbon::parse($data->fecha)->format('Ymd'), '', '', '', '', '', '', '',];
                        break;
                    case 2:
                        $dataEnviar = [$data->idDependencia, '', $data->year, $data->month, '', '', '', '', '',];
                        break;
                    case 3:
                        $dataEnviar = [$data->idDependencia, '', '', '', Carbon::parse($data->range_1)->format('Ymd'), Carbon::parse($data->range_2)->format('Ymd'), '', '', '',];
                        break;
                    case 4:
                        $dataEnviar = [$data->idDependencia, '', '', '', '', '', $data->yearCriterio, $data->idCriterio, $data->variableCriterio??'%%',];
                        break;
                }
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_archivadosXiDepenId_ReceptorXcConsultaVariablesCampos ?,     ?,  ?, ?    , ?, ?,     ?, ?, ?', $dataEnviar);

                // $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_porrecibirXiDepenId_Receptor ?', $data);
                break;
            case 'data_reniec':
                $respuesta = DB::select('EXEC grl.Sp_SEL_reniecXcReniecDni ?', $data);
                break;


            case 'dependencias_filial':
                $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiFilId ?', $data);
                $respuesta = collect($respuesta);
                $respuesta = $this->makeNested($respuesta); //PERMITE FORMAR LA JERARQUIA
                break;
            case 'dependencias_buscar':
                // dd($data);
                if ($data[0] == '$$$') {
                    $data[0] = '';
                } else {
                    $data[0] = ($data[0]??'%%');
                }
                $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiEntIdXcDepenNombre 1, ?', $data);
                $respuesta = collect($respuesta);
                if ($data[0] == '%%') {
                    $respuesta = $this->makeNested($respuesta); //PERMITE FORMAR LA JERARQUIA
                }
                break;
            case 'tipo_dependencias':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_dependencias');
                break;
            case 'tipo_tramite':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tipo_tramites');
                break;


            case 'cargos':
                $respuesta = DB::select('EXEC rhh.Sp_SEL_cargosXcCargosNombre ?', $data);
                break;
            case 'credenciales':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXcCredUsuario ?', $data);
                break;
            case 'tipo_documentos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_documentosXcDescripcion ?', $data);
                break;
            case 'grupo_documentos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_grupo_tramites');
                break;

            case 'dependencias_tdocumento':
                $respuesta = DB::select('EXEC grl.Sp_SEL_dependencias_tipo_documentosXiDepenId ?', $data);
                break;

            case 'dependencias_comunicaciones':
                $respuesta = DB::select('EXEC grl.Sp_SEL_depedencias_comunicacionesXiDepenId ?', $data);
                break;

            case 'contactos_persona':
                $respuesta = DB::select('EXEC grl.Sp_SEL_persona_tipo_contactosXiPersId ?', $data);
                break;



            case 'conceptos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_conceptosXiEntIdXcCodigo_cNombre 1, ?', $data);
                $respuesta = collect($respuesta);
                foreach ($respuesta as $concept) {
                    $datReq = DB::select("EXEC grl.Sp_SEL_conceptos_requisitosXiConcepId ?", [$concept->iConcepId]);
                    $concept->requisitos = collect($datReq)->sortBy('iConcepReqNumero');
                    //dd($datReq);
                }
                //dd($respuesta);
                break;

            case 'tipo_conceptos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Conceptos');
                break;

            case 'tipo_persona':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_personas');
                break;
            case 'tipo_identificacion':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Identificaciones');
                break;


            case 'envios_dependencias':
                $respuesta = DB::select('EXEC tram.Sp_SEL_dependencias_tramites_pendientes_por_recepcionarXiDepenEmisorId ?', $data);
                break;
            case 'envios_dependencias_tramites':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_pendientes_por_recepcionarXiDepenEmisorIdXiDepenReceptorId ?, ?', $data);
                break;


            case 'buscar_tramite':

                $data = json_decode(json_encode($data));
                switch ($data->option) {
                    case 1:
                        $dataEnviar = [ $data->idDependencia, Carbon::parse($data->fecha)->format('Ymd')];
                        $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos ?,?,0,0,'','', 0", $dataEnviar);
                        break;
                    case 2:
                        $dataEnviar = [$data->idDependencia, $data->year, $data->month];
                        $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos  ?,'',?,?,'','', 0", $dataEnviar);
                        break;
                    case 3:
                        $dataEnviar = [$data->idDependencia, Carbon::parse($data->range_1)->format('Ymd'), Carbon::parse($data->range_2)->format('Ymd')];
                        $respuesta = DB::select("EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos ?,'',0,0,?,?, 0", $dataEnviar);
                        // EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXcConsultaVariablesCampos 29,'',0,0,'20190917','20190917', 0
                        break;
                    case 4:
                        $dataEnviar = [$data->idDependencia, $data->yearCriterio, $data->idCriterio, $data->variableCriterio??''];
                        $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_enprocesosXiDepenIdXCriterio_Busqueda_Tramite ?, ?, ?, ?', $dataEnviar);
                        break;
                }

                break;
/*
            case 'buscar_tramite_criterios':
                $respuesta = DB::select('EXEC tram.Sp_SEL_Criterio_Busqueda_Tramites');
                break;*/

            case 'buscar_tramite_referencia':
                $data = json_decode(json_encode($data));
                switch ($data->option) {
                    case 1:
                        $dataEnviar = [ $data->idDependencia, Carbon::parse($data->fecha)->format('Ymd'), '', '', '', '', '', '', '',];
                        break;
                    case 2:
                        $dataEnviar = [$data->idDependencia, '', $data->year, $data->month, '', '', '', '', '',];
                        break;
                    case 3:
                        $dataEnviar = [$data->idDependencia, '', '', '', Carbon::parse($data->range_1)->format('Ymd'), Carbon::parse($data->range_2)->format('Ymd'), '', '', '',];
                        break;
                    case 4:
                        $dataEnviar = [$data->idDependencia, '', '', '', '', '', $data->yearCriterio, $data->idCriterio, $data->variableCriterio??'%%',];
                        break;
                }
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_enreferenciasXiDepenIdXcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?, ?', $dataEnviar);
                break;

            case 'detalle_tramite':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramitesXiTramId ?', $data);
                break;

            case 'detalles_tipo_documento':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_documentosXiTipoDocId ?', $data);
                break;

            case 'tramite_referencias':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_referenciasXiTramId ?', $data);
                break;

            case 'movimientos_observaciones':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_observacionesXiTramMovId ?', $data);
                break;

            /*case 'notificacion_observados':
                $respuesta = DB::select('EXEC tram.Sp_SEL_tramites_observacionesXiDepenReceptorId ?', $data);
                $collectSalida = collect();
                foreach ($respuesta as $notif) {
                    $notif->fechaObservacionHumanos = Date::parse($notif->dtTramObsFechaHoraObservado)->diffForHumans(null, false, false, 3);
                    $notif->estadoAlerta = 'Observado';
                    $collectSalida->add($notif);
                }
                $respuesta = $collectSalida;
                break;*/

            case 'tramite_numeracion_dependencia':
                $respuesta = DB::select('EXEC tram.Sp_SEL_iTramNumRegistroXiDepenIdXiTipoTramId  ?, ?', $data);
                break;

            case 'dashboard_top_porrecibir':
                $respuesta = DB::select('EXEC tram.Sp_SEL_reporte_dependencias_porrecibir');
                break;

            case 'dashboard_top_poratender':
                $respuesta = DB::select('EXEC tram.Sp_SEL_reporte_dependencias_poratender');
                break;

            case 'dashboard_doc_porrecibir':
                $respuesta = DB::select('EXEC tram.Sp_SEL_reporte_tramites_porrecibirXiDepenReceptorId ?', $data);
                break;

            case 'dashboard_doc_poratender':
                $respuesta = DB::select('EXEC tram.Sp_SEL_reporte_tramites_poratenderXiDepenReceptorId ?', $data);
                break;

            case 'estadisticas':
                $data = json_decode(json_encode($data));
                $respuesta = collect(DB::select('exec tram.Sp_SEL_Cantidad_Tramites_CreadosXiEntIdXiYear ?, ?', [1, $data->anio]));
                if (isset($data->idDependencia)) {
                    $respuesta = $respuesta->where('iDepenId', $data->idDependencia)->values();
                }

                break;

            /*case 'badge_por_recibir':
                $data = json_decode(json_encode($data));
                // print_r($data);
                $respuesta = collect(DB::select('tram.Sp_SEL_cantidad_tramites_porrecibirXiDepenId_Receptor ?', [$data->idDependenciaReceptor]))->first();
                break;*/







                // CONSULTAS GENERALES

            case 'consulta_existencia':
                /**
                 * Buscar si existe un valor en una tabla y campo especifico
                 *
                 * $data = array('tabla', 'campo', 'valor');
                 */
                $respuesta = DB::select('EXECUTE grl.Sp_SEL_Verificar_Existe_Campo ?, ?, ?', $data);

                if ($respuesta[0]->iResult == 0){
                    $respuesta = ['error' => false, 'msg' => ''];
                } else {
                    $respuesta = ['error' => true, 'msg' => 'Ya se encuentra registrado'];
                }

                // dd($respuesta[0]->iResult);
                break;




        }
        //dd($data);
        //dd(DB::getQueryLog());

        return response()->json($respuesta);
    }

    function makeNested($source) {

        $newData = collect();
        foreach ($source as &$or) {
            if (is_null($or->iDepenDependeId)) {
                $newData->add($or);
            } else {
                $pid = $or->iDepenDependeId;
                $dPadre = $source->where('iDepenId', $pid)->first();
                if ($dPadre) {
                    if (!isset($dPadre->hijos)) {
                        $source->where('iDepenId', $pid)->first()->hijos = collect();
                    }
                    $source->where('iDepenId', $pid)->first()->hijos->add($or);
                }

            }
        }

        return $newData;
        $nested = array();

        foreach ( $source as &$s ) {

            if ( is_null($s->iDepenDependeId) ) {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
            }
            else {
                $pid = $s->iDepenDependeId;
                if ( isset($source[$pid]) ) {
                    // If the parent ID exists in the source array
                    // we add it to the 'children' array of the parent after initializing it.

                    if ( !isset($source[$pid]['children']) ) {
                        $source[$pid]['children'] = array();
                    }

                    $source[$pid]['children'][] = &$s;
                }
            }
        }
        return $nested;
    }
    public function guardarData(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data') ;

        $data = json_decode(json_encode($data));

        if ( (is_object($data)) && (auth()->user()->iCredId != $data->auditoria->credencial_id) ) {
            return response()->json(['error' => true, 'msg' => 'Usuario NO AUTENTICADO' . '#' . auth()->user()->iCredId . '#$' . $data->auditoria->credencial_id . '$']);
        }

        $respuesta = null;
        switch ($req) {
            case 'nuevo_expediente_frm':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC tram.Sp_DEL_tramitesXiTramId ?', $data );
                    } else {

                        $procAdjunto = null;

                        if (isset($data->adjunto)){
                            // abort(505, gettype($data->adjunto));
                            if (is_array($data->adjunto)){
                                foreach ($data->adjunto as $file){
                                    $pos = strpos($file, 'tramitesAdjuntos/');
                                    if ($pos === false) {
                                        $file = str_replace('storage/', '', $file);
                                        $nuevaUbicacion = 'tramitesAdjuntos/'.basename($file);
                                        Storage::disk('public')->move($file, $nuevaUbicacion);
                                    }
                                    else {
                                        $nuevaUbicacion = $file;
                                    }

                                    $procAdjunto[] = $nuevaUbicacion;
                                    // abort(503, $arch);
                                }
                                $procAdjunto = json_encode($procAdjunto);
                            }
                            elseif ($data->adjunto != '') {

                                $procAdjunto = self::guardarImagen($data->adjunto, 'tramitesAdjuntos');
                                if (isset($procAdjunto['error'])  && $procAdjunto['error']) {
                                    abort(503, $procAdjunto['msg']);
                                    //return response()->json($procAdjunto, 503);
                                }
                            }
                            elseif ($data->adjunto == 'null') {
                                $procAdjunto = null;
                            }
                        }







                        if ($data->iTramId) {
                            // return $data->iTramId;
                            // array_unshift($dataGuardar, $data->iTramId);
                            $detRegistroAct = DB::select('EXEC tram.Sp_SEL_tramitesXiTramId ?', [$data->iTramId]);
                            if (isset($detRegistroAct[0]) && $detRegistroAct[0]->cTramAdjuntarArchivo != '') {
                                Storage::disk('public')->delete($detRegistroAct[0]->cTramAdjuntarArchivo);
                            }

                            $dataGuardar = [
                                $data->iTramId,

                                $data->idTipoTramite,
                                $data->idPersonaFirmanteEmisor??null,
                                $data->idPersonaNaturalEmisor??null,
                                $data->nombrePersonaNaturalEmisor??null,
                                $data->idPersonaJuridicaEmisor??null,
                                $data->nombrePersonaJuridicaEmisor??null,

                                Carbon::parse($data->fechaDocumento)->format('Y-d-m H:i:s.v'),
                                $data->idTipoDocumento->iTipoDocId??null,
                                $data->numeroDocumento??null,
                                $data->siglaDocumento??null,

                                $data->concepto?1:0,
                                $data->idConcepto??null,
                                $data->asunto,
                                $data->contenido??null,
                                $data->folios??null,

                                $data->idObservacion??null,
                                $data->idPersonaObservacion??null,
                                $data->observacion??null,

                                $procAdjunto, //$data->adjunto? self::guardarImagen($data->adjunto, 'tramitesAdjuntos') : null,
                                json_encode($data->adjuntoFisico),

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip??null,
                                null,

                            ];

                            $dataReferencias = $data->referencias;
                            $arrReferencias = [];
                            foreach ($dataReferencias as $referencia){
                                if (!isset($referencia->iTramRefId)) {
                                    // $arrReferencias[] = $referencia->iTramRefTramIdRef;
                                    $arrReferencias[] = $referencia->iTramId;
                                }
                            }

                            DB::select('EXEC tram.Sp_INS_tramites_referenciasXiTramIdXcCodigoCadena ?, ?,       ?, ?, ?, ?', [
                                $data->iTramId,
                                implode(',', $arrReferencias),

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip??null,
                                null,
                            ]);

                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC tram.Sp_UPD_tramites ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            $dataGuardar = [
                                $data->idTipoTramite,
                                $data->idCredencialEmisor,
                                $data->idDependenciaEmisor??null,
                                $data->idPersonaFirmanteEmisor??null,

                                $data->idPersonaNaturalEmisor??null,
                                $data->nombrePersonaNaturalEmisor??null,
                                $data->idPersonaJuridicaEmisor??null,
                                $data->nombrePersonaJuridicaEmisor??null,

                                // $data->fechaDocumento, // ? Carbon::parse($data->fecha_documento) : null,
                                // str_replace('T', ' ', $data->fechaDocumento),
                                Carbon::parse($data->fechaDocumento)->format('Y-d-m H:i:s.v'),
                                $data->idTipoDocumento->iTipoDocId??null,
                                $data->numeroDocumento??null,
                                $data->siglaDocumento??null,

                                $data->concepto?1:0,
                                $data->idConcepto??null,
                                $data->asunto,
                                $data->contenido??null,
                                $data->folios??null,

                                $data->idObservacion??null,
                                $data->idPersonaObservacion??null,
                                $data->observacion??null,

                                // $data->adjunto??null,
                                $procAdjunto, //$data->adjunto? self::guardarImagen($data->adjunto, 'tramitesAdjuntos') : null,
                                json_encode($data->adjuntoFisico),

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip??null,
                                null,
                            ];
                            $respuesta = DB::select('EXEC tram.Sp_INS_tramites ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );


                            // ", $dataIngreso); //
                            // $jsonResponse = $dataIngreso;
                            // $respuesta = DB::select('EXEC tram.Sp_INS_tramites ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $dataIngreso);
                            // DB::rollback();
                            $reqDB = DB::select('EXEC tram.Sp_SEL_tramites_requisitosXiTramId ?', [$respuesta[0]->iTramId]);

                            foreach ($reqDB as $req) {
                                // return response()->json($data->chk_tupa_requisitos->{$req->iConcepReqId});
                                DB::select('EXEC tram.Sp_UPD_tramites_requisitos ?, ?, ?, ?, ?, ?', [
                                    $req->iTramReqId,
                                    $data->chkRequisitos->{$req->iConcepReqId}??0,


                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ]);
                            }



                            $dataRequisitos = $data->chkRequisitos;
                            foreach ($dataRequisitos as $key => $value) {
                                $rptaChk = DB::select('EXEC tram.Sp_UPD_tramites_requisitos ?, ?, ?, ?, ?, ?', [
                                    $key,
                                    $value,


                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ]);
                            }


                            $dataDestinatarios = $data->destinatarios;
                            foreach ($dataDestinatarios as $destino){

                                if (isset($data->envio_externo) && ($data->envio_externo)) {

                                    $dataDestino = [

                                        $respuesta[0]->iTramId,
                                        $data->idCredencialEmisor,
                                        $data->idDependenciaEmisor??null,
                                        $data->idPersonaFirmanteEmisor??null,

                                        $destino->idPersonaNaturalReceptor,
                                        $destino->nombrePersonaNaturalReceptor,
                                        $destino->idPersonaNJuridicaReceptor??null,
                                        $destino->nombrePersonaJuridicaReceptor??null,

                                        $destino->idPrioridad,

                                        $destino->copia,
                                        $destino->folios,
                                        json_encode($destino->archivoFisico),
                                        $destino->observacion??null,
                                        $destino->plazo,

                                        auth()->user()->iCredId,
                                        null,
                                        $data->auditoria->ip??null,
                                        null,
                                    ];
                                    DB::select('EXEC tram.Sp_INS_tramites_movimientos_Enviar_Externo ?, ?, ?, ?,     ?, ?, ?, ?,    ?,     ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataDestino);

                                } else {
                                    $dataDestino = [
                                        $respuesta[0]->iTramId,
                                        $data->idCredencialEmisor,
                                        $data->idDependenciaEmisor??null,
                                        $data->idPersonaFirmanteEmisor??null,

                                        $destino->idDependencia,
                                        $destino->idJefeDependencia,
                                        $destino->idPrioridad,
                                        $destino->copia,

                                        $destino->folios,
                                        json_encode($destino->archivoFisico),

                                        $destino->atencion??null,
                                        $destino->plazo,

                                        $data->tipoEnvio,

                                        auth()->user()->iCredId,
                                        null,
                                        $data->auditoria->ip??null,
                                        null,
                                    ];
                                    DB::select('EXEC tram.Sp_INS_tramites_movimientos_Enviar_Tramite ?, ?, ?, ?,     ?, ?, ?, ?,    ?, ?,     ?, ?,     ?,       ?, ?, ?, ?', $dataDestino);
                                }


                            }


                            $dataReferencias = $data->referencias;
                            $arrReferencias = [];
                            foreach ($dataReferencias as $referencia){
                                $arrReferencias[] = $referencia->iTramId;
                            }

                            DB::select('EXEC tram.Sp_INS_tramites_referenciasXiTramIdXcCodigoCadena ?, ?,       ?, ?, ?, ?', [
                                $respuesta[0]->iTramId,
                                implode(',', $arrReferencias),

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip??null,
                                null,
                            ]);




                            // return $respuesta[0]->iTramId;
                            $detNuevoReg = DB::select('EXEC tram.Sp_SEL_tramitesXiTramId ?', [$respuesta[0]->iTramId]);

                            // return response()->json($detNuevoReg);

                            $respuesta[0] = (object) [
                                'iResult' => 1,
                                'tramite_id' => $respuesta[0]->iTramId,
                                'numeracion' => $respuesta[0]->cTramNumeroDocumento,
                                'qr' => $respuesta[0]->cTramQrNumRegistro,
                                'codigo' => $detNuevoReg[0]->iTipoTramId == 2 ? $detNuevoReg[0]->cTramCodigoBusqueda : null,
                                'detalle_tramite' => $respuesta[0],
                                'detalle_registro' => $detNuevoReg[0]
                            ];

                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {

                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }



                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'enviar_tramite':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    $dataDestinatarios = $data->destinatarios;
                    foreach ($dataDestinatarios as $destino){
                        $dataDestino = [
                            $data->idTramite,
                            $data->idCredencialEmisor,
                            $data->idDependenciaEmisor??null,
                            $data->idPersonaFirmanteEmisor??null,

                            $destino->dependencia,
                            $destino->persona,
                            $destino->prioridad??1,
                            $destino->copia,

                            $destino->folios,
                            json_encode($destino->archivoFisico),

                            $destino->atencion??null,
                            $destino->plazo,

                            $data->tipoEnvio,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        DB::select('EXEC tram.Sp_INS_tramites_movimientos_Enviar_Tramite ?, ?, ?, ?,     ?, ?, ?, ?,    ?, ?,     ?, ?,     ?,       ?, ?, ?, ?', $dataDestino);
                    }



                    // return $respuesta[0]->iTramId;
                    $detNuevoReg = DB::select('EXEC tram.Sp_SEL_tramitesXiTramId ?', [$data->idTramite]);

                    // return response()->json($detNuevoReg);

                    $dataRetorno = [
                        'tramite_id' => $detNuevoReg[0]->iTramId,
                        'numeracion' => $detNuevoReg[0]->cTramNumeroDocumento,
                        'qr' => $detNuevoReg[0]->cTramQrNumRegistro,
                        'codigo' => $detNuevoReg[0]->cTramCodigoBusqueda
                    ];

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'eliminar_referencia':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    $respuesta = DB::select('EXEC tram.Sp_DEL_tramites_referenciasXiTramRefId ?', $data );

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'editar_envio':
                DB::beginTransaction();
                try {
                    $dataGuardar = [
                        $data->idMovimiento,

                        $data->folios,
                        json_encode($data->archivoFisico),
                        $data->atencion,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null
                    ];

                    $dataRetorno = DB::select('EXEC tram.Sp_UPD_tramites_movimientos_EnviarXiTramMovId ?,       ?, ?, ?,          ?, ?, ?, ?', $dataGuardar);

                    if (isset($dataRetorno[0]) && ($dataRetorno[0]->iResult == 1)) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $dataRetorno
                        ];
                    }
                    else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error al guardar Data',
                            'data' => $dataRetorno
                        ];
                    }


                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'eliminar_envio':
                DB::beginTransaction();
                try {
                    $dataRetorno = DB::select('EXEC tram.Sp_DEL_tramites_movimientos_Enviar ?', $data);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;

            case 'eliminar_tramite':
                DB::beginTransaction();
                try {
                    $dataRetorno = DB::select('EXEC tram.Sp_DEL_tramitesXiTramId ?', $data);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'archivar_movimiento':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    $movimientos = [];
                    foreach ($data->idMovimiento as $mov) {
                        $dataGuardar = [
                            $data->idDependencia,
                            $mov, // movID
                            $data->observacion,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        $dataRetorno = DB::select('EXEC tram.Sp_UPD_tramites_movimientos_Archivado ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);

                        $movimientos[] = [$mov, $dataRetorno];
                    }


                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $movimientos
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'archivar_movimiento_sin_envio':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    $dataGuardar = [
                        $data->idTramite,
                        $data->accion,
                        $data->observacion,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ];
                    $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_ArchivadoXiTramIdXiTramArchivado ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;

            case 'atender_desatender':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    $dataGuardar = [
                        $data->idTramite,
                        $data->accion,
                        $data->observacion,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ];
                    $respuesta = DB::select('EXEC tram.Sp_UPD_tramites_ParaReferenciaXiTramIdXiTramParaReferencia ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;

            case 'unarchive_movimiento':
                DB::beginTransaction();
                try {
                    $dataGuardar = [
                        $data->idMovimiento,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ];
                    $dataRetorno = DB::select('EXEC tram.Sp_UPD_tramites_movimientos_Quitar_Archivado ?,       ?, ?, ?, ?', $dataGuardar);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;
            case 'observar_subsanar':
                DB::beginTransaction();
                $procAdjunto = null;
                if($data->adjunto) {
                    $procAdjunto = self::guardarImagen($data->adjunto, 'tramitesAdjuntos/observaciones');
                    if (isset($procAdjunto['error'])  && $procAdjunto['error']) {
                        abort(503, $procAdjunto['msg']);
                        //return response()->json($procAdjunto, 503);
                    }
                }
                try {
                    $dataGuardar = [
                        $data->idTramite,
                        $data->idMovimiento,
                        $data->idDependencia,
                        $data->estado,
                        $data->observacion,
                        $procAdjunto,

                        $data->idDependenciaNotificacion,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ];
                    $dataRetorno = DB::select('EXEC tram.Sp_UPD_tramites_ObservadoXiTramIdXiEstadoObservado ?, ?, ?, ?, ?, ?,   ?,       ?, ?, ?, ?', $dataGuardar);

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $dataRetorno
                    ];
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;





            case 'mantenimiento_dependencia':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC grl.Sp_DEL_dependencias ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idFilial,
                            $data->idTipoDependencia,
                            $data->idDependenciaPadre,
                            $data->nombreDependencia,
                            $data->abreviaturaDependencia,
                            $data->siglaDependencia??NULL,
                            $data->correoDependencia??NULL,
                            $data->idTipoTramite,
                            $data->diasPlazoResolver,
                            $data->trazabilidad?1:0,
                            $data->derivar?1:0,


                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if ($data->idDependencia) {
                            array_unshift($dataGuardar, $data->idDependencia);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_UPD_dependencias ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_INS_dependencias ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_dependencia_credenciales':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC seg.Sp_DEL_credenciales_dependencias ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idCredencial,
                            $data->idDependencia,
                            $data->idCargo,
                            $data->firma,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if ($data->idCredencialDependencia) {
                            array_unshift($dataGuardar, $data->idCredencialDependencia);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC seg.Sp_UPD_credenciales_dependencias ?,     ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC seg.Sp_INS_credenciales_dependencias ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_tipo_documento':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC grl.Sp_DEL_tipo_documentos ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->descripcion,
                            $data->sigla,
                            $data->sunat??NULL,

                            $data->entrada,
                            $data->interno,
                            $data->salida,

                            $data->superior_a_inferior,
                            $data->inferior_a_superior,
                            $data->mismo_nivel,

                            $data->multiple,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if ($data->idTipoDoc) {
                            array_unshift($dataGuardar, $data->idTipoDoc);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_UPD_tipo_documentos ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_INS_tipo_documentos ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_dependencia_tipodocumentos':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        // return response()->json($data);
                        $respuesta[0] = DB::select('EXEC grl.Sp_DEL_dependencias_tipo_documentos ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idDependencia,
                            $data->idTipoDocumento,
                             '1',

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if (isset($data->iDepenTipoDocId)) {
                            //return response()->json($data, 500);
                            // array_unshift($dataGuardar, $data->iDepenTipoDocId);
                            $dataGuardar = [
                                $data->iDepenTipoDocId,
                                $data->incrementable ? '1' : '0',

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip??null,
                                null,
                            ];



                            //return response()->json($dataGuardar);
                            $respuesta[0] = DB::select('EXEC grl.Sp_UPD_dependencias_tipo_documentos ?,     ?,      ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            // $dataComas = explode(',', $data->idTipoDocumento);
                            $dRespuestas = [];
                            foreach ($data->idTipoDocumento as $dataComa) {
                                $dataGuardar[1] = $dataComa;
                                $dRespuestas[] = DB::select('EXEC grl.Sp_INS_dependencias_tipo_documentos ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            }
                            // return 'nuevo';
                            $respuesta = $dRespuestas;
                        }
                    }

                    if ($respuesta[0][0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_dependencia_comunicaciones':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        // return response()->json($data);
                        $respuesta[0] = DB::select('EXEC grl.Sp_DEL_depedencias_comunicaciones ?', $data );
                    } else {

                        $dataGuardar = [
                            $data->idDependencia,
                            'foreach_ids',

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        // return response()->json($dataGuardar);
                        // $dataComas = explode(',', $data->idTipoDocumento);
                        $dRespuestas = [];
                        foreach ($data->idsDependencias as $dataComa) {
                            $dataGuardar[1] = $dataComa;
                            $dRespuestas[] = DB::select('EXEC grl.Sp_INS_depedencias_comunicaciones ?, ?,        ?, ?, ?, ?', $dataGuardar );
                        }
                        // return 'nuevo';
                        $respuesta = $dRespuestas;

                    }

                    if ($respuesta[0][0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_concepto':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC grl.Sp_DEL_conceptos ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idDependencia,
                            $data->tipo,
                            $data->codigo??null,
                            $data->nombre,
                            $data->silencio,
                            $data->plazo,
                            $data->idDependenciaInicial,
                            $data->idDependenciaResuelve,
                            $data->grupo_afecto,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if ($data->iConcepId) {
                            array_unshift($dataGuardar, $data->iConcepId);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_UPD_conceptos ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_INS_conceptos ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar );
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_concepto_requisito':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC grl.Sp_DEL_conceptos_requisitos ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idConcepto,
                            $data->numero,
                            $data->nombre??null,
                            $data->caso,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if ($data->iConcepReqId) {
                            array_unshift($dataGuardar, $data->iConcepReqId);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_UPD_conceptos_requisitos ?,     ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_INS_conceptos_requisitos ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar );
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_persona':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC grl.Sp_DEL_personas ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idTipoPersona,
                            $data->idTipoIdentidad,
                            $data->numeroDocumento,

                            $data->apellidoPaterno,
                            $data->apellidoMaterno,
                            $data->nombres,
                            $data->sexo,
                            $data->fechaNacimiento??null,

                            $data->razonSocial??null,
                            $data->razonSocialCorto??null,
                            $data->razonSocialSigla??null,
                            $data->representanteLegal??null,


                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
                        if ($data->iPersId) {
                            //return response()->json($dataGuardar);
                            //array_slice($dataGuardar,0, 1);
                            //array_unshift($dataGuardar, $data->iPersId);
                            $dataGuardar[0] = $data->iPersId;

                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_UPD_personas ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                            //return response()->json($respuesta);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC grl.Sp_INS_personas ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar );
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;


            case 'actualizarDatosInicial':
                /*
                 * [ura].[Sp_GRAL_UPD_cambioContrasenia]
@_cPersDocumento varchar(20), @cUsuarioSis VARCHAR(50),@_cEquipoSis VARCHAR(50), @_cIpSis VARCHAR(15), @_cMacNicSis VARCHAR(35), @cContrasena varchar(20)
                 */
                // return response()->json(auth()->user()->cCredUsuario);
                DB::beginTransaction();
                try{

                    $dataGuardar = [
                        auth()->user()->cCredUsuario,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,

                        $data->new_password
                    ];

                    $respuesta = DB::select('EXEC ura.Sp_GRAL_UPD_cambioContrasenia ?, ?,           ?, ?, ?, ? ', $dataGuardar );

                    $dataContacto = [
                        auth()->user()->iPersId,
                        $data->celular,
                        $data->email,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip??null,
                        null,
                    ];
                    $respuesta2 = DB::select('EXEC grl.Sp_INS_UPD_TelefonoMovilCorreoElectronicoXiPersId ?, ?, ?        , ?, ?, ?, ? ', $dataContacto );

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => ['cambiar_pwd' => $respuesta, 'actualizar_contacto' => $respuesta2]
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
        }
        //dd($data);
        //dd(DB::getQueryLog());

        return response()->json($jsonResponse);
    }


    public static function returnError($e){
        $msgResuelto = '';
        if (isset($e->errorInfo)){
            $msgResuelto = substr($e->errorInfo[2], 54); //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
        }
        abort(503, ($msgResuelto != '' ? $msgResuelto : $e->getMessage()));
    }

    public static function guardarImagen($origen, $carpeta, $nombre = null, $mimetypes = []) {

        $nombreOriginal = $origen->filename;
        $mimeType = $origen->filetype;
        $dataValue = $origen->value;

        $chkMine = false;
        if (count($mimetypes) > 0) {
            foreach ($mimetypes as $mimePermitido) {
                if ($mimeType == $mimePermitido) {
                    $chkMine = true;
                    break;
                }
            }
        }
        else {
            $chkMine = true;
        }

        if ($chkMine) {
            $data = substr($dataValue, strpos($dataValue, ',') + 1);
            $ext = '.' . pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $soloNombre = str_replace($ext, '', $nombreOriginal);



            $filename = ((!is_null($nombre)) ? $nombre . '-'  : '') . $soloNombre .'-'.time().'.'.pathinfo($nombreOriginal, PATHINFO_EXTENSION);;
            $data = base64_decode($data);
            $filePath = $carpeta . '/' . $filename;
            Storage::disk('public')->put($filePath, $data);
            return $filePath;
        }
        else {
            $jsonResponse = [
                'error' => true,
                'msg' => 'El archivo no es aceptado',
                'data' => []
            ];
            return $jsonResponse;
        }
    }

    public function genPDF(Request $request) {

    }






    public function prueba()
    {
        $url = "https://ws5.pide.gob.pe/Rest/Reniec/Consultar?nuDniConsulta=43177406&nuDniUsuario=41395590&nuRucUsuario=20449347448&password=41395590";
        $result = file_get_contents($url, false);
        dd($result);
    }
}
