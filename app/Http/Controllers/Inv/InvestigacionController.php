<?php

namespace App\Http\Controllers\Inv;

use App\ClasesLibres\TramiteDocumentario\PdfCreator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use TCPDF_FONTS;

class InvestigacionController extends Controller
{
    public function guardarDataArchivo(Request $request)
    {
        $data = $request->get('data');
        $data = json_decode(($data));
        $arrkeyArchivo = $data->controlArchivo;
        $carpeta = '/' . $data->carpeta;
        $prefijo = $data->prefijo;
        $sufijo = $data->sufijo;
        //return response()->json(($data->idProyecto) );
        if (is_array($arrkeyArchivo)){
            foreach ($arrkeyArchivo as $keyArchivo) {
                if ($request->hasFile($keyArchivo)) {
                    $archivo = $request->file($keyArchivo);

                    $nuevoNombreArchivo = ($prefijo ?? '') . (str_Replace('.' . $archivo->getClientOriginalExtension(), '', $archivo->getClientOriginalName())) . '-' . time() . ($sufijo ? '-' . $sufijo : '') . '.' . $archivo->getClientOriginalExtension();
                    $rutaArchivo[$keyArchivo] = ('storage/' . $archivo->storePubliclyAs('inv' . $carpeta, $nuevoNombreArchivo));
                } else {
                    $rutaArchivo[$keyArchivo] =$data->$keyArchivo;
                    //   abort(503, 'No se adjuntaron archivos'); quitar
                }
            }
        }else{
            if ($request->hasFile($arrkeyArchivo)) {
                $archivo = $request->file($arrkeyArchivo);

                $nuevoNombreArchivo = ($prefijo ?? '') . (str_Replace('.' . $archivo->getClientOriginalExtension(), '', $archivo->getClientOriginalName())) . '-' . time() . ($sufijo ? '-' . $sufijo : '') . '.' . $archivo->getClientOriginalExtension();
                $rutaArchivo = ('storage/' . $archivo->storePubliclyAs('inv' . $carpeta, $nuevoNombreArchivo));
            } else {
                //   abort(503, 'No se adjuntaron archivos'); quitar
            }
        }

       // return response()->json(($rutaArchivo) );

        $req = $request->get('tipo');

        $data = json_decode(json_encode($data));
        if ((is_object($data)) && (auth()->user()->iCredId != $data->auditoria->credencial_id)) {
            return response()->json(['error' => true, 'msg' => 'Usuario NO AUTENTICADO' . '#' . auth()->user()->iCredId . '#$' . $data->auditoria->credencial_id . '$']);
        }
        $respuesta = null;

        switch ($req) {
            case 'mantenimiento_observacion':
                // return response()->json($data);
                // return response()->json($rutaArchivo);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_DEL_estado_proyecto ?', $data);
                    } else {
                        $dataGuardar = [
                            null, // $idInfoAvTec,
                            $data->iHitoId,
                            $data->iTipoObservacionId,
                            $data->iEstadoObservacionId,
                            $data->dtFechaActa,
                            $data->cNumActa,
                            $data->cLugar,
                            $data->cRecomendacion,
                            $data->cResultado ?? null,
                            $rutaArchivo['cArchivoActa'],

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                       // return response()->json($dataGuardar);
                        if ($data->iObservacionHitoId) {
                            array_unshift($dataGuardar, $data->iObservacionHitoId);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_observacion_hito_monitor ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_observacion_hito ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,     ?, ?, ?, ?', $dataGuardar);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_avance_tecnico_detalle':
                //return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //  $respuesta = DB::select('EXEC inv. ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->idProyecto,
                            $data->idActividad,
                            $data->idCalendario,
                            $data->avanceCantidad,
                            $data->docSustentatorio,
                            $rutaArchivo,
                            $data->observacion,
                            $data->fueraFecha,
                            $data->fechaAvanceTecnico,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        // return response()->json($dataGuardar);
                        if ($data->idAvanTecDet) {
                            array_unshift($dataGuardar, $data->idAvanTecDet);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_avance_tecnico_detalle ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            //  return response()->json($dataGuardar);

                            $respuesta = DB::select('EXEC inv.Sp_INS_avance_tecnico_detalle ?, ?, ?, ?, ?, ?, ?, ?, ?,         ?, ?, ?, ?', $dataGuardar);
                            // return response()->json($respuesta);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_informe_avance':
                //return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //  $respuesta = DB::select('EXEC inv. ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->iProyectoId,
                            $data->iTipoInformeId,
                            $data->dtFechaInicio,
                            $data->dtFechaFin,
                            '', // $data->dtFechaInfAvan,
                            $rutaArchivo,
                            '', // $data->iEstado,
                            $data->iInfSistAceptada,
                            $data->nPresupuestoProyecto,
                            $data->nPresupuestoRubros,
                            $data->nPresupuestoDisponible,
                            $data->nPresupuestoEjecutado,
                            $data->nPresupuestoPorcAvance,
                            $data->nPorcAvanceTenico,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        // return response()->json($dataGuardar);
                        if ($data->iInformeAvanceId) {
                            array_unshift($dataGuardar, $data->iInformeAvanceId);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_informe_avance ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            //  return response()->json($dataGuardar);

                            $respuesta = DB::select('EXEC inv.Sp_INS_informe_avance ?, ?, ?, ?, ?, ?, ?, ?, ?,         ?, ?, ?, ?', $dataGuardar);
                            // return response()->json($respuesta);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_convocatoria':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_DEL_estado_proyecto ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->iTipoProyectoId,
                            $data->iFuenteProyectoId,
                            $data->descripcion,
                            $data->detalle,
                            $data->fechaInicio, //Carbon::parse($data->fechaInicio)->format('Y-m-d H:i:s'),
                            $data->fechaFin, //Carbon::parse($data->fechaFin)->format('Y-m-d H:i:s'),
                            $data->fechaFinPostulacion, //'2020-05-23 01:01:00', //Carbon::parse($data->fechaFinPostulacion)->format('Y-m-d H:i:s'),
                            '1',
                            $data->iNumMesesProyecto,
                            $data->iNumMesesHito,
                            $data->cResolucion,
                            $data->iNumIntegrantes,
                            $data->nPresupuesto,

                            $rutaArchivo['cArchivoBases'],
                            $rutaArchivo['cArchivoCronograma'],
                            $rutaArchivo['cArchivoFormato'],
                            $rutaArchivo['cArchivoResEvalExp'],
                            $rutaArchivo['cArchivoRectResEvaExp'],
                            $rutaArchivo['cArchivoResEvTec'],
                            $rutaArchivo['cArchivoRecResEvaTec'],
                            $rutaArchivo['cArchivoResFinal'],

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ]; //return response()->json($dataGuardar);
                        if ($data->idConvocatoria) {
                            array_unshift($dataGuardar, $data->idConvocatoria);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_convocatoria ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,     ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_convocatoria ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_curriculum':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_DEL_estado_proyecto ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->iPersId,
                            $rutaArchivo['cDoc'],

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ]; //return response()->json($dataGuardar);
                        $respuesta = DB::select('EXEC inv.Sp_INS_UPD_miembro_cv ?, ?,              ?, ?, ?, ?', $dataGuardar);

                        /*
                        if ($data->iPersId) {
                            array_unshift($dataGuardar, $data->iMiembroId);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_UPD_miembro_cv ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,     ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_convocatoria ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar);
                            // return 'nuevo';
                        }*/

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
                // return response()->json($data);
                break;

            case 'mantenimiento_informe_final':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                       //  $respuesta = DB::select('EXEC inv. ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->iProyectoId,
                            $data->dtFechaInicio,
                            $data->dtFechaFin,
                            '', // $data->dtFechaInfAvan,
                            $rutaArchivo,
                            '', // $data->iEstado,
                            $data->iInfSistAceptada[0] ?? 0,
                            $data->nPresupuestoProyecto,
                            $data->nPresupuestoRubros,
                            $data->nPresupuestoDisponible,
                            $data->nPresupuestoEjecutado,
                            $data->nPresupuestoPorcAvance,
                            $data->nPorcAvanceTenico,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        // return response()->json($dataGuardar);
                        if ($data->iInformeFinalId) {
                            array_unshift($dataGuardar, $data->iInformeFinalId);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_informe_final ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            //  return response()->json($dataGuardar);

                            $respuesta = DB::select('EXEC inv.Sp_INS_informe_avance ?, ?, ?, ?, ?, ?, ?, ?,         ?, ?, ?, ?', $dataGuardar);
                            // return response()->json($respuesta);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_evaluacion':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_DEL_proyectoXiProyectoId ?', $data );
                    }
                    else {
                        $dataGuardar = [
                            $data->idParEvaluador,
                            $data->idEstadoEvaluacion,
                            $data->iProyectoId,
                            $rutaArchivo['cDoc'],
                            $data->cResultados,
                            $data->nPuntajeTotal,
                            $data->nPuntajePonderado,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        // return response()->json($dataGuardar);

                        if ($data->idEvaluacion) {
                            array_unshift($dataGuardar, $data->idEvaluacion);
                            //return response()->json($dataGuardar);
                         //   $respuesta = DB::select('EXEC inv.Sp_UPD_gasto_proyecto ?,             ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_Evaluacion_ProyectoId ?, ?, ?, ?, ?,  ?,?,    ?, ?, ?, ?', $dataGuardar);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }

                break;

            case 'mantenimiento_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_DEL_proyectoXiProyectoId ?', $data );
                    } else {
                        if ($data->iProyectoId) {
                            // return $data->iTramId;
                            // array_unshift($dataGuardar, $data->iTramId);
                            $iProyectoId = $data->iProyectoId;
                            $dataGuardar = [
                                $data->iProyectoId,
                                $data->idConvocatoria,
                                $data->idCarrera,
                                $data->idLineaInvestigacion,
                                $data->idTipoProyecto,
                                $data->idEstadoProyecto,
                                $data->idFuenteProyecto,
                                $data->objetivoGeneral ?? null,
                                null,
                                $data->idAnyoAprobado,
                                $data->resolucionProyecto,
                                $data->nombreProyecto,
                                $data->presupuestoProyecto,

                                $data->observacionProyecto ?? null,
                                $data->idDependencia ?? null,
                                $data->iEstadoPropuesta,
                                $rutaArchivo['archivoProyecto'],

                                $data->resumen,
                                $data->antecedentes,
                                $data->justificacion,
                                $data->problema,
                                $data->hipotesis,
                                $data->bibliografia,
                                $data->palabrasClave,
                                $data->resultadosEsperados,
                                $rutaArchivo['cArchivoPlanOp'],
                                $rutaArchivo['archivoAnexo'],

                                $rutaArchivo['cArchivoProyectoDoc'],
                                $rutaArchivo['cArchivoPlanOpDoc'],
                                $rutaArchivo['cArchivoAnexoDoc'],
                                $rutaArchivo['cArchivoSimilitud'],
                                $rutaArchivo['cArchivoContrato'],

                                $rutaArchivo['cArchivoDJPostulacion'],
                                $data->iPersIdPostulacion,

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip ?? null,
                                null,
                            ];

                           /******insr y upd objetivo*****/
                            $listaObjEspId = "";
                            $dataObjEspecificos = $data->objEspecifico;
                            foreach ($dataObjEspecificos as $objEsp) {
                                $dataObjEsp = [
                                    $data->iProyectoId,
                                    2,
                                    $objEsp->cObjetivo,
                                    null,
                                    null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($objEsp->iObjetivoId <> NULL) {
                                    $listaObjEspId .= $objEsp->iObjetivoId . "*";
                                    array_unshift($dataObjEsp, $objEsp->iObjetivoId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_objetivo ?,     ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataObjEsp);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_objetivo ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataObjEsp);
                                    $listaObjEspId .= $respuesta[0]->iObjetivoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaObjEspId = substr($listaObjEspId, 0, -1);


                      /*****del ojetivo***/
                            if ($listaObjEspId <> "") {
                                $dataIdObj = [
                                    $listaObjEspId,
                                    $data->iProyectoId,
                                    2
                                ];
                                $respuesta = DB::select('EXEC inv.Sp_DEL_objetivo ?, ?, ?', $dataIdObj);
                            }


                            /*********************del miembro proyecto****/
                            // para registrar miembros del proyecto
                            $dataBorrar = [$data->iProyectoId];
                            $respuesta = DB::select('EXEC inv.Sp_DEL_miembro_proyectoXiProyectoId ?', $dataBorrar);


                       /*****insr miembro proyecto     y upd proyecto*****/
                            $dataMiembros = $data->miembros;
                            foreach ($dataMiembros as $miembro) {
                                $dataMiembro = [
                                    $miembro->idMiembro,
                                    $data->iProyectoId,
                                    $miembro->idTipoMiembro,

                                    // $miembro->cResMiembro,
                                    //  $miembro->cEstadoMiembro,
                                    null,
                                    null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                DB::select('EXEC inv.Sp_INS_miembro_proyecto ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataMiembro);
                            }
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_proyecto ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        }


                       /*********insert proyecto*********/
                        else {
                            $dataGuardar = [
                                $data->idConvocatoria,
                                $data->idCarrera,
                                $data->idLineaInvestigacion,
                                $data->idTipoProyecto,
                                $data->idEstadoProyecto,
                                $data->idFuenteProyecto,
                                $data->objetivoGeneral ?? null,
                                $data->objetivoEspecifico ?? null,
                                $data->idAnyoAprobado,
                                $data->resolucionProyecto,
                                $data->nombreProyecto,
                                $data->presupuestoProyecto,

                                $data->observacionProyecto ?? null,
                                $data->idDependencia ?? null,
                                10,
                                $rutaArchivo['archivoProyecto'],

                                $data->resumen,
                                $data->antecedentes,
                                $data->justificacion,
                                $data->problema,
                                $data->hipotesis,
                                $data->bibliografia,
                                $data->palabrasClave,
                                $data->resultadosEsperados,
                                $rutaArchivo['cArchivoPlanOp'],
                                $rutaArchivo['archivoAnexo'],

                                $rutaArchivo['cArchivoProyectoDoc'],
                                $rutaArchivo['cArchivoPlanOpDoc'],
                                $rutaArchivo['cArchivoAnexoDoc'],
                                $rutaArchivo['cArchivoSimilitud'],
                                $rutaArchivo['cArchivoContrato'],

                                $rutaArchivo['cArchivoDJPostulacion'],
                                $data->iPersIdPostulacion,

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip ?? null,
                                null,
                            ];

                            $respuesta = DB::select('EXEC inv.Sp_INS_Proyecto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar);


                            $iProyectoId = $respuesta[0]->iProyectoId;
                            /* -------- para objetivos general y especifio ---------------*/
                            // return response()->json($respuesta);

                            /****upd y insr ojetivo**/
                            $listaObjEspId = "";
                            $dataObjEspecificos = $data->objEspecifico;
                            foreach ($dataObjEspecificos as $objEsp) {
                                $dataObjEsp = [
                                    $respuesta[0]->iProyectoId,
                                    2,
                                    $objEsp->cObjetivo,
                                    null,
                                    null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];

                                if ($objEsp->iObjetivoId <> NULL) {
                                    $listaObjEspId .= $objEsp->iObjetivoId . "*";
                                    array_unshift($dataObjEsp, $objEsp->iObjetivoId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_objetivo ?,     ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataObjEsp);
                                    // return 'editar';
                                }
                                else {
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_objetivo ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataObjEsp);
                                    $listaObjEspId .= $respuesta2[0]->iObjetivoId . "*";
                                    // return 'nuevo';
                                }

                            }


                            /*******del objetivo***/
                            $listaObjEspId = substr($listaObjEspId, 0, -1);
                            if ($listaObjEspId <> "") {
                                $dataIdObj = [
                                    $listaObjEspId,
                                    $respuesta[0]->iProyectoId,
                                    2
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_objetivo ?, ?, ?', $dataIdObj);
                            }



                            /*******insrt miembro proyecto******/
                            $dataMiembros = $data->miembros;
                            foreach ($dataMiembros as $miembro) {
                                $dataMiembro = [
                                    $miembro->idMiembro,
                                    $respuesta[0]->iProyectoId,
                                    $miembro->idTipoMiembro,
                                    // $miembro->cResMiembro,
                                    //  $miembro->cEstadoMiembro,
                                    null,
                                    null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                DB::select('EXEC inv.Sp_INS_miembro_proyecto ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataMiembro);
                            }

                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'iProyectoId' => $iProyectoId,
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    }
                    else {

                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }

                    DB::commit();
                }

                catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;


            case 'mantenimiento_informe_tec_hito':
              // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_DEL_proyectoXiProyectoId ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->iHitoId,
                            0, // iEstado
                            $data->dtFechaInfoAvTec,
                            $data->cResumenEjecutivo,
                            $data->cDetAvIndHito,
                            $data->cDetAvIndNoCompletoHitoAnt,
                            $data->cConclusion,
                            $data->cRecomendacion,
                            $rutaArchivo['cArchivoAnexo'],

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];

                        if ($data->iInfoAvTecId) {
                            array_unshift($dataGuardar,  $data->iInfoAvTecId);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_informe_avance_tecnico ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            $idInfoAvTec = $data->iInfoAvTecId;
                            // return 'editar';
                        } else {
                            $respuesta = DB::select('EXEC inv.Sp_INS_informe_avance_tecnico ?, ?, ?, ?, ?, ?, ?, ?, ?,         ?, ?, ?, ?', $dataGuardar);
                            $idInfoAvTec = $respuesta[0]->iInfoAvTecId;

                            // return response()->json($respuesta);
                        }


                            /* -------- 2. avances logrados al hito ---------------*/
                            $dataAvLogHitoObjGral = $data->objGral;
                            foreach ($dataAvLogHitoObjGral as $avLogHtObjGral) {
                                $dataLogObjGral = [
                                    $idInfoAvTec,
                                    $avLogHtObjGral->iIndicadorId,
                                    $avLogHtObjGral->iCantidad,
                                    $avLogHtObjGral->cMedioVerificacion,
                                    $rutaArchivo['cArchivo'. $avLogHtObjGral->iIndicadorId] ?? null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($avLogHtObjGral->iAvLogradoHitoId <> NULL) {
                                    array_unshift($dataLogObjGral, $avLogHtObjGral->iAvLogradoHitoId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_avance_logrado_hito ?,     ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataLogObjGral);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_avance_logrado_hito ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataLogObjGral);
                                    // return 'nuevo';
                                }
                            }
                            $dataAvLogHitoObjEsp = $data->objEspecifico;
                            foreach ($dataAvLogHitoObjEsp as $avLogHtObjEsp) {
                                $dataLogObjEsp = [
                                    $idInfoAvTec,
                                    $avLogHtObjEsp->iIndicadorId,
                                    $avLogHtObjEsp->iCantidad,
                                    $avLogHtObjEsp->cMedioVerificacion,
                                    $rutaArchivo['cArchivo'. $avLogHtObjEsp->iIndicadorId] ?? null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($avLogHtObjEsp->iAvLogradoHitoId <> NULL) {
                                    array_unshift($dataLogObjEsp, $avLogHtObjEsp->iAvLogradoHitoId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_avance_logrado_hito ?,     ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataLogObjEsp);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_avance_logrado_hito ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataLogObjEsp);
                                    // return 'nuevo';
                                }
                            }

                            // actividades
                            $dataAvLogHitoAct = $data->actividades;
                            foreach ($dataAvLogHitoAct as $avLogHtAct) {
                                $dataLogAct = [
                                    $idInfoAvTec,
                                    $avLogHtAct->iActividadId,
                                    $avLogHtAct->iCantidad,
                                    $avLogHtAct->cMedioVerificacion,
                                    $rutaArchivo['cArchivoAct'. $avLogHtAct->iActividadId] ?? null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($avLogHtAct->iAvLogradoHitoActId <> NULL) {
                                    array_unshift($dataLogAct, $avLogHtAct->iAvLogradoHitoActId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_avance_logrado_hito_actividad ?,     ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataLogAct);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_avance_logrado_hito_actividad ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataLogAct);
                                    // return 'nuevo';
                                }
                            }


                            /* -------- 3. avances en la ejecucion ---------------*/
                            /* -------- 3.2 indicadores de hito ---------------*/
                            $dataAvIndHito = $data->hito;
                            foreach ($dataAvIndHito as $avIndHt) {
                                $dataAvIndHt = [
                                    $idInfoAvTec,
                                    $data->iHitoId,
                                    $avIndHt->iIndicadorHitoId,
                                    $avIndHt->iCantidad,
                                    $avIndHt->cMedioVerificacion,

                                    $avIndHt->iAfectaIndSgtHito,
                                    $avIndHt->dtFechaCumplir,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($avIndHt->iAvIndHitoId <> NULL) {
                                    array_unshift($dataAvIndHt, $avIndHt->iAvIndHitoId);
                                 //   return response()->json($dataAvIndHt);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_avance_indicador_hito ?,     ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataAvIndHt);
                                    // return 'editar';
                                } else {
                                  //  return response()->json($dataAvIndHt);
                                    $respuesta = DB::select('EXEC inv.Sp_INS_avance_indicador_hito ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataAvIndHt);
                                    // return 'nuevo';
                                }
                            }
                            /* -------- 3.3 indicadores de hito anterior---------------*/
                            $dataAvIndHitoAnt = $data->hitoAnt;
                            // return response()->json($dataAvIndHito);

                            foreach ($dataAvIndHitoAnt as $avIndHtAnt) {
                                $dataAvIndHtAnt = [
                                    $idInfoAvTec,
                                    $data->iHitoId,
                                    $avIndHtAnt->iIndicadorHitoId,
                                    $avIndHtAnt->iCantidad,
                                    $avIndHtAnt->cMedioVerificacion,

                                    $avIndHtAnt->iAfectaIndSgtHito,
                                    $avIndHtAnt->dtFechaCumplir,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($avIndHtAnt->iAvIndHitoId <> NULL) {
                                    array_unshift($dataAvIndHtAnt, $avIndHtAnt->iAvIndHitoId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_avance_indicador_hito ?,     ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataAvIndHtAnt);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_avance_indicador_hito ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataAvIndHtAnt);
                                    // return 'nuevo';
                                }
                            }

                            /* -------- 4. otros resultados---------------*/
                            $listaResultados = "";
                            $dataResultadoLogrado = $data->resultadoLogrado;
                            foreach ($dataResultadoLogrado as $resLog) {
                                $dataResLog = [
                                    $idInfoAvTec,
                                    $resLog->cResultadoHito,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($resLog->iResultadoHitoId <> NULL) {
                                    $listaResultados .=  $resLog->iResultadoHitoId . "*";
                                    array_unshift($dataResLog, $resLog->iResultadoHitoId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_resultado_hito ?,     ?, ?,        ?, ?, ?, ?', $dataResLog);
                                    // return 'editar';
                                } else {
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_resultado_hito ?, ?,      ?, ?, ?, ?', $dataResLog);
                                    $listaResultados .= $respuesta2[0]->iResultadoHitoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaResultados = substr($listaResultados, 0, -1);
                            if ($listaResultados <> "") {
                                $dataIdResultados = [
                                    $listaResultados,
                                    $idInfoAvTec
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_resultado_hito ?, ?', $dataIdResultados);
                            }

                            /* -------- 5. riesgos para el cumplimiento---------------*/
                            $listaRiesgos = "";
                            $dataRiesgoHito = $data->riesgoIndicador;
                            foreach ($dataRiesgoHito as $riesgoHt) {
                                $dataRiesgo = [
                                    $idInfoAvTec,
                                    $riesgoHt->iEstadoRiesgoId,
                                    $riesgoHt->cRiesgoHito,
                                    $riesgoHt->cAccionTomada,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($riesgoHt->iRiesgoHitoId <> NULL) {
                                    $listaRiesgos .=  $riesgoHt->iRiesgoHitoId . "*";
                                    array_unshift($dataRiesgo, $riesgoHt->iRiesgoHitoId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_riesgo_hito ?,     ?, ?, ?, ?,        ?, ?, ?, ?', $dataRiesgo);
                                    // return 'editar';
                                } else {
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_riesgo_hito ?, ?, ?, ?,      ?, ?, ?, ?', $dataRiesgo);
                                    $listaRiesgos .= $respuesta2[0]->iRiesgoHitoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaRiesgos = substr($listaRiesgos, 0, -1);
                            if ($listaRiesgos <> "") {
                                $dataIdRiesgos = [
                                    $listaRiesgos,
                                    $idInfoAvTec
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_riesgo_hito ?, ?', $dataIdRiesgos);
                            }

                            /* -------- 6. otros problemas manifestados---------------*/
                            $listaProblemas = "";
                            $dataProblemaHito = $data->problemaHito;
                            foreach ($dataProblemaHito as $problemaHt) {
                                $dataProblema = [
                                    $idInfoAvTec,
                                    $problemaHt->iTipoObservacionId,
                                    $problemaHt->cProblemaHito,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($problemaHt->iProblemaHitoId <> NULL) {
                                    $listaProblemas .=  $problemaHt->iProblemaHitoId . "*";
                                    array_unshift($dataProblema, $problemaHt->iProblemaHitoId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_problema_hito ?,     ?, ?, ?,        ?, ?, ?, ?', $dataProblema);
                                    // return 'editar';
                                } else {
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_problema_hito ?, ?, ?,      ?, ?, ?, ?', $dataProblema);
                                    $listaProblemas .= $respuesta2[0]->iProblemaHitoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaProblemas = substr($listaProblemas, 0, -1);
                            if ($listaProblemas <> "") {
                                $dataIdProblemas = [
                                    $listaProblemas,
                                    $idInfoAvTec
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_problema_hito ?, ?', $dataIdProblemas);
                            }

                            /* -------- 7. implementación de recomendaciones ---------------*/
                        $listaObsHt = "";
                        $dataObsHito = $data->observacionHito;
                            foreach ($dataObsHito as $obsHt) {
                                $dataObsdHt = [
                                    $idInfoAvTec,
                                    $obsHt->iHitoId,
                                    $obsHt->iTipoObservacionId,
                                    $obsHt->iEstadoObservacionId,
                                    $obsHt->dtFechaActa,
                                    $obsHt->cNumActa,
                                    $obsHt->cLugar,
                                    $obsHt->cRecomendacion,
                                    $obsHt->cResultado,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($obsHt->iObservacionHitoId <> NULL) {
                                    $listaObsHt .=  $obsHt->iObservacionHitoId . "*";
                                    array_unshift($dataObsdHt, $obsHt->iObservacionHitoId);
                                    //   return response()->json($dataAvIndHt);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_observacion_hito ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataObsdHt);
                                    // return 'editar';
                                } else {
                                    //  return response()->json($dataAvIndHt);
                                    $respuesta = DB::select('EXEC inv.Sp_INS_observacion_hito ?, ?, ?, ?, ?, ?, ?, ?, ?,     ?, ?, ?, ?', $dataObsdHt);
                                    $listaObsHt .= $respuesta[0]->iObservacionHitoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaObsHt = substr($listaObsHt, 0, -1);
                            /*if ($listaObsHt <> "") {
                                $dataObs = [
                                    $listaObsHt,
                                    $idInfoAvTec
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_observacion_hito ?, ?', $dataObs);
                            }*/

                            /* --------  9. Equipo Técnico del proyecto---------------*/
                            $listaEqTec = "";
                            $dataEqTecHito = $data->equipoTec;
                            foreach ($dataEqTecHito as $eqTecHt) {
                                $dataEtTec = [
                                    $idInfoAvTec,
                                    $eqTecHt->iMiembroId,
                                    $eqTecHt->iTipoMiembroId,
                                    $eqTecHt->nDedicacionPorcentaje,
                                    $eqTecHt->cGrado,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($eqTecHt->iMiembroHitoId <> NULL) {
                                    $listaEqTec .=  $eqTecHt->iMiembroHitoId . "*";
                                    array_unshift($dataEtTec, $eqTecHt->iProblemaHitoId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_miembro_hito ?,     ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataEtTec);
                                    // return 'editar';
                                } else {
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_miembro_hito ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataEtTec);
                                    $listaEqTec .= $respuesta2[0]->iMiembroHitoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaEqTec = substr($listaEqTec, 0, -1);
                            if ($listaEqTec <> "") {
                                $dataIdEqTec = [
                                    $listaEqTec,
                                    $idInfoAvTec
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_miembro_hito ?, ?', $dataIdEqTec);
                            }

                            /****/
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



        }

        return response()->json($jsonResponse);
    }

    public function leerDataAnonimo(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');

        $respuesta = null;
        switch ($req) {
            case 'credenciales':
                // return response()->json(['base' => env('APP_NAME')]);
                $respuesta = collect(DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', $data));

                if ($respuesta->count() > 0) {
                    $credencial = $respuesta->first();
                    $respuestaPerfiles = collect(DB::select('seg.Sp_SEL_credenciales_perfilesXiCredId ?', [$credencial->iCredId]));
                    $credencial->perfiles = $respuestaPerfiles;
                    $respuesta = $credencial;
                }

                break;

            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;
            case 'data_nacionalidades12':
                $respuesta = DB::select("EXEC inv.Sp_SEL_nacionalidadesXcNacionNombre ?", $data);
                break;
            case 'tipo_persona12':
                $respuesta = DB::select("EXEC grl.Sp_SEL_tipo_personas ", $data);
                break;
            case 'tipo_identificacion12':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Identificaciones');
                break;
            case 'data_nacionalidades_anonimo':
                $respuesta = DB::select("EXEC inv.Sp_SEL_nacionalidadesXcNacionNombre ?", $data);
                break;
            case 'fuente_proyecto_anonimo':
                $respuesta = DB::select('EXEC inv.Sp_SEL_fuente_proyectoXcDescripcion ?', $data);
                break;
            case 'tipo_proyecto_anonimo':
                $respuesta = DB::select('EXEC inv.Sp_SEL_tipo_proyectoXcDescripcion ?', $data);
                break;
            case 'data_personas_anonimo':
                $respuesta = DB::select("EXEC inv.Sp_SEL_personasXiTipoPersIdXcDocumento_cDescripcion1  ?, ?", $data);
                break;
            case 'data_tipo_contactos_anonimo':
                $respuesta = DB::select("EXEC grl.Sp_SEL_tipo_contactos", $data);
                break;
            case 'data_persona_contacto_anonimo':
                $respuesta = DB::select("EXEC inv.Sp_SEL_persona_tipo_contactosXiPersId_lista ? ", $data);
                break;
        }
        return response()->json($respuesta);
    }

    public function leerData(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');

        $respuesta = null;
        switch ($req) {

            case 'get_preData_principal':
                $anyos = DB::select("EXEC inv.Sp_SEL_yearsXiYearId ?", $data);
                $convActivas = DB::select('EXEC inv.Sp_SEL_convocatorias_activas ', $data);

                $respuesta = [ 'convActivas' => $convActivas, 'anyos' => $anyos, ];
                break;

            case 'get_preData_filtro':
                $convocatorias = DB::select('EXEC inv.Sp_SEL_convocatoriaXcDescripcion ?', $data);
                $anyos = DB::select("EXEC inv.Sp_SEL_yearsXiYearId ?", $data);
                $critBusProy = DB::select('EXEC inv.Sp_SEL_Criterio_Busqueda_Proyectos');

                $respuesta = [ 'convocatorias' => $convocatorias, 'anyos' => $anyos, 'critBusProy' => $critBusProy ];
                break;

            case 'informes_pendientes_fecha_vigente':
                $data = json_decode(json_encode($data));
                // return response()->json($data);
                $docMiembro = $data->docMiembro;
                $docParEvaluador = $data->docParEvaluador;
                $idDependencia = $data->idDependencia;

                $dataEnviar1 = [$idDependencia, $docMiembro, $docParEvaluador, 0, 7];
                $dataEnviar2= [$idDependencia, $docMiembro, $docParEvaluador, 8, 30];
                $pendiente0a7 = DB::select('EXEC inv.Sp_REP_informes_pendientes_fecha_vigente ?, ?, ?, ?, ?', $dataEnviar1);
                $pendiente8a30 = DB::select('EXEC inv.Sp_REP_informes_pendientes_fecha_vigente ?, ?, ?, ?, ?', $dataEnviar2);
                $respuesta = [ 'pendiente0a7' => $pendiente0a7, 'pendiente8a30' => $pendiente8a30 ];
                break;

            case 'get_preData_proyecto':
                $carreras = DB::select("EXEC inv.Sp_SEL_carrerasxcCarreraDsc ?", $data);
                $estadoProy = DB::select('EXEC inv.Sp_SEL_estado_proyectoXcDescripcion ?', $data);
                $fuenteProy = DB::select('EXEC inv.Sp_SEL_fuente_proyectoXcDescripcion ?', $data);

                $convocatorias = DB::select('EXEC inv.Sp_SEL_convocatoriaXcDescripcion ?', $data);
                $convActivas = DB::select('EXEC inv.Sp_SEL_convocatorias_activas ', $data);
                $anyos = DB::select("EXEC inv.Sp_SEL_yearsXiYearId ?", $data);
                $tipoMiemb = DB::select("EXEC inv.Sp_SEL_tipo_miembroXcTipoMiembroDescripcion ?", $data);
                $tipoContacto = DB::select("EXEC grl.Sp_SEL_tipo_contactos", $data);
                $nacionalidades = DB::select("EXEC inv.Sp_SEL_nacionalidadesXcNacionNombre ?", $data);

                $tipoProyNoConc = DB::select('EXEC inv.Sp_SEL_tipo_proyectoXcDescripcionXbConcursable ?, ?', ['%%', 0]);
                $tipoProy = DB::select('EXEC inv.Sp_SEL_tipo_proyectoXcDescripcion ?', $data);


                $respuesta = [
                    'carreras' => $carreras, 'estadoProy' => $estadoProy,
                    'fuenteProy' => $fuenteProy, 'convocatorias' => $convocatorias,
                    'convActivas' => $convActivas, 'anyos' => $anyos,
                    'tipoMiemb' => $tipoMiemb, 'tipoContacto' => $tipoContacto,
                    'nacionalidades' => $nacionalidades, 'tipoProyNoConc' => $tipoProyNoConc,
                    'tipoProy' => $tipoProy, 'tipoProyNoConc' => $tipoProyNoConc,
                    ];
                break;



            /******************************gustavo*******************************************/
            case 'data_postulantes':
                //Buscar miembros por num documento o nombre
                //dd($data[0]);
                if (count($data) > 1) {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_postulanteXiTipoPersIdXcDocumento_cDescripcion ? ,?", $data);
                } elseif ($data[0] == '%%') {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_postulanteXcDocumento_cDescripcion ?", $data);
                } else {
                    $respuesta = DB::select("EXEC hy ?", $data);
                }
                break;
            case 'data_miembro_cv':
                $respuesta = DB::select('EXEC inv.Sp_SEL_miembroXcDocumento_cDescripcion_cv ?', $data);
                break;
            case 'rubro_presupuesto': ////exec inv.Sp_SEL_presupuestoXgasto_actividad///muestra rubro
                $respuesta = DB::select('EXEC inv.Sp_SEL_presupuesto2   ?', $data);
                break;   ////ESTA EN FRM-REGISTRO-MODEL

            case 'actividad_presupuesto': ////exec inv.Sp_SEL_presupuestoXgasto_actividad///muestra rubro
                $respuesta = DB::select('EXEC inv.Sp_SEL_actividadXcDescripcion_presupuesto ?, ?', $data);
                break;   ////ESTA EN FRM-REGISTRO-MODEL
            case 'monto_presupuesto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_presupuesto ?, ?', $data);
                break;   ////ESTA EN FRM-REGISTRO-MODEL


            case 'fuente_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_fuente_proyectoXcDescripcion ?', $data);
                break;
            case 'tipo_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_tipo_proyectoXcDescripcion ?', $data);
                break;
            case 'tipo_proyecto_bConcursable':
                $respuesta = DB::select('EXEC inv.Sp_SEL_tipo_proyectoXcDescripcionXbConcursable ?, ?', $data);
                break;
            case 'estado_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_estado_proyectoXcDescripcion ?', $data);
                break;

            case 'lista_presupuesto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_lista_presupuestoXresolucion ?', $data);
                break;

            case 'data_carreras':
                $respuesta = DB::select("EXEC inv.Sp_SEL_carrerasxcCarreraDsc ?", $data);
                break;
            case 'data_linea_inv':
                $respuesta = DB::select("EXEC inv.Sp_SEL_linea_investigacionXiCarreraId ?", $data);
                break;
            case 'data_linea_inv_postulacion':
                $respuesta = DB::select("EXEC inv.Sp_SEL_linea_investigacionXiCarreraId_postulacion ?", $data);
                break;
            case 'data_anyo':
                $respuesta = DB::select("EXEC inv.Sp_SEL_yearsXiYearId ?", $data);
                break;
            case 'tipo_miembro':
                $respuesta = DB::select("EXEC inv.Sp_SEL_tipo_miembroXcTipoMiembroDescripcion ?", $data);
                break;

            case 'data_miembros':
                //Buscar miembros por num documento o nombre
                //dd($data[0]);
                if (count($data) > 1) {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_miembroXiTipoPersIdXcDocumento_cDescripcion ? ,?", $data);
                } elseif ($data[0] == '%%') {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_miembroXcDocumento_cDescripcion ?", $data);
                } else {
                    $respuesta = DB::select("EXEC hy ?", $data);
                }
                break;

            case 'data_monitores':
                // return response()->json($data);
                //Buscar miembros por num documento o nombre
                //dd($data[0]);
                if (count($data) > 1) {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_monitorXiTipoPersIdXcDocumento_cDescripcion ? ,?", $data);
                } elseif ($data[0] == '%%') {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_monitorXcDocumento_cDescripcion ?", $data);
                } else {
                    $respuesta = DB::select("EXEC hy ?", $data);
                }
                break;
            /*********sel para evaluadores***********/
            case 'pares_evaluadores':
                if (count($data) > 1) {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_evaluador ?, ? ", $data);
                } elseif ($data[0] == '%%') {
                      $respuesta = DB::select("EXEC inv.Sp_SEL_evaluador2 ?", $data);
                } else {
                    $respuesta = DB::select("EXEC inv.Sp_SEL_evaluador3 ?", $data);
                }
                break;
            case 'data_presupuesto_disponible':
                //regresa registros miembros de proyectos
                $respuesta = DB::select("EXEC inv.Sp_SEL_presupuesto_disponible  ?", $data);
                break;

            case 'data_miembros_proyecto':
                //regresa registros miembros de proyectos
                $respuesta = DB::select("EXEC inv.Sp_SEL_miembro_proyecto_XiProyectoId  ?", $data);
                break;

            case 'data_obj_especifico_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_objetivo_especifico_proyecto_XiProyectoId  ?", $data);
                break;

            case 'data_objetivos_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_objetivos_proyecto_XiProyectoId  ?, ?", $data);
                break;
            case 'data_rubro_proyecto1':
                $respuesta = DB::select("EXEC inv.Sp_SEL_rubro_proyecto_XiProyectoId1  ?", $data);
                break;
              /**************/

            case 'data_presupuestos_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_presupuesto_rubro_XiProyectoId ?, ?", $data);
                break;
            case 'data_cronograma_presupuesto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_cronogramaXiPresupuestoId  ?", $data);
                break;

             /**************/

            case 'data_indicador_obj_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_indicador_objetivo_proyecto_XiProyectoId_iObjetivoId  ?, ?", $data);
                break;

            case 'data_indicador_obj_proyecto_det_avance':
                $respuesta = DB::select("EXEC inv.Sp_SEL_indicador_objetivo_proyecto_det_avance_XiProyectoId_iObjetivoId  ?, ?, ?", $data);
                break;

            case 'data_actividad_obj_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_actividad_objetivo_proyecto_XiProyectoId_iObjetivoId  ?, ?", $data);
                break;

            case 'data_actividad_presupuesto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_actividad_presupuesto  ?", $data);
                break;
            case 'data_actividad_obj_proyecto_det_avance':
                $respuesta = DB::select("EXEC inv.Sp_SEL_actividad_objetivo_proyecto_det_avance_XiProyectoId_iObjetivoId  ?, ?, ?", $data);
                break;

            case 'data_cronograma_actividad':
                $respuesta = DB::select("EXEC inv.Sp_SEL_cronogramaXiActividadId  ?", $data);
                break;

            case 'data_cronograma_presupuesto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_cronogramaXiPresupuestoId  ?", $data);
                break;
            case 'data_convocatoria_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_convocatoriaXiProyectoId  ?", $data);
                break;
            case 'data_convocatoria':
                $respuesta = DB::select("EXEC inv.Sp_SEL_convocatoriaXiConvocatoriaId  ?", $data);
                break;
            case 'duracion_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_duracion_proyecto  ?", $data);
                break;

/*******************select evaluador por id project*******/
            case 'data_evaluador':
               $respuesta = DB::select("EXEC inv.Sp_SEL_paresevaluadores_proyectoId  ? ,?", $data);
                break;

            case 'data_monitores_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_monitoresXproyectoId  ? ,?", $data);
                break;


            case 'data_pares_evaluadores':
                //Buscar miembros por num documento o nombre
                //dd($data[0]);
                if (count($data) > 1) {
                   $respuesta = DB::select("EXEC inv.Sp_SEL_parevaluadorXiParEvaluadorIdXcDocumento_cDescripcion ? ,?", $data);
                } elseif ($data[0] == '%%') {
                  //  $respuesta = DB::select("EXEC inv.Sp_SEL_parevaluadorXcDocumento_cDescripcion ?", $data);
                } else {
                   //$respuesta = DB::select("EXEC inv.Sp_SEL_parevaluadorXiParEvaluadorId ?", $data);
                }
                break;

            case 'data_proyecto':
                //regresa registros proyectos
                $data = json_decode(json_encode($data));
                //  $dataEnviar = [$data[0], $data[1], '', '', '',];
                $dataEnviar = ['', '', '', '', '', '', 9, $data[0]];

                $respuesta = DB::select('EXEC inv.Sp_SEL_proyecto_XcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?', $dataEnviar);
                break;


            case 'data_unidad_medida':
                $respuesta = DB::select("EXEC inv.Sp_SEL_unidadMedidaXcDescripcion  ?", $data);
                break;

            case 'data_hitos_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_hitos_proyecto_XiProyectoId_iHitoId  ?, ?, ?", $data);
                break;

            case 'data_indicador_hito_proyecto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_indicador_hito_proyecto_XiProyectoId_iHitoId  ?, ?", $data);
                break;

            case 'data_avance_tecnico_obj_ind':
                $respuesta = DB::select("EXEC inv.Sp_REP_avance_tecnico_obj_ind_XiProyectoId_iHitoId  ?, ?, ?", $data);
                break;

            case 'data_avance_tecnico_obj_act':
                $respuesta = DB::select("EXEC inv.Sp_REP_avance_tecnico_obj_act_XiProyectoId_iHitoId  ?, ?, ?, ?", $data);
                break;

            case 'data_avance_tecnico_ht_ind':
                $respuesta = DB::select("EXEC inv.Sp_REP_avnace_tecnico_ht_ind_XiProyectoId_iHitoId  ?, ?, ?", $data);
                break;

            case 'data_avance_tecnico_htAnt_ind_noCumplido':
                $respuesta = DB::select("EXEC inv.Sp_REP_avnace_tecnico_htAnt_ind_noCumplidoXiProyectoId_iHitoId  ?, ?, ?", $data);
                break;

            case 'estado_riesgo':
                $respuesta = DB::select('EXEC inv.Sp_SEL_estado_riesgoXcDescripcion ?', $data);
                break;

            case 'tipo_observacion':
                $respuesta = DB::select('EXEC inv.Sp_SEL_tipo_observacionXcDescripcion ?', $data);
                break;

            case 'estado_observacion':
                $respuesta = DB::select('EXEC inv.Sp_SEL_estado_observacionXcDescripcion ?', $data);
                break;

            case 'estado_revision':
                $respuesta = DB::select('EXEC inv.Sp_SEL_estado_revisionXcDescripcion ?', $data);
                break;

            case 'data_avance_tecnico_obs':
                $respuesta = DB::select("EXEC inv.Sp_REP_avnace_tecnico_obs_XiProyectoId_iHitoId  ?, ?", $data);
                break;

            case 'data_avance_tecnico_obsXMonitor':
                $respuesta = DB::select("EXEC inv.Sp_REP_avnace_tecnico_obs_XiProyectoId_iHitoId_iMonitorId  ?, ?, ?", $data);
                break;

            case 'data_avance_tecnico_obs_evaluada':
                $respuesta = DB::select("EXEC inv.Sp_REP_avnace_tecnico_obs_evaluada_XiProyectoId_iHitoId  ?, ?", $data);
                break;

            case 'data_miembros_hito':
                //regresa registros miembros de proyectos
                $respuesta = DB::select("EXEC inv.Sp_SEL_miembro_hito_XiInfoAvTecId  ?", $data);
                break;

            case 'data_informe_avance_tec':
                $respuesta = DB::select("EXEC inv.Sp_SEL_informe_avance_tecnico_XiHitoId  ?", $data);
                break;

            case 'data_avance_tecnico_resultado':
                $respuesta = DB::select("EXEC inv.Sp_SEL_resultado_hito_XiHitoId  ?", $data);
                break;

            case 'data_avance_tecnico_riesgo':
                $respuesta = DB::select("EXEC inv.Sp_SEL_riesgo_hito_XiHitoId  ?", $data);
                break;

            case 'data_avance_tecnico_problema':
                $respuesta = DB::select("EXEC inv.Sp_SEL_problema_hito_XiHitoId  ?", $data);
                break;

            case 'data_tipo_documento_gasto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_tipo_documento_gastoXcDescripcion  ?", $data);
                break;

            case 'data_personas':
                $respuesta = DB::select("EXEC inv.Sp_SEL_personasXiTipoPersIdXcDocumento_cDescripcion ? ,?, ?", $data);
                break;
            case 'data_personas_evaluadores':
                $respuesta = DB::select("EXEC inv.Sp_SEL_personas_evaluadores ? ,?, ?", $data);
                break;

            case 'data_personas_x_documento':
                $respuesta = DB::select("EXEC inv.Sp_SEL_personasXiTipoPersIdXcDocumento ? ,?", $data);
                break;

            case 'data_persona_contacto':
                $respuesta = DB::select("EXEC inv.Sp_SEL_persona_tipo_contactosXiPersId_lista ? ", $data);
                break;

            case 'data_tipo_contactos':
                $respuesta = DB::select("EXEC grl.Sp_SEL_tipo_contactos", $data);
                break;
            case 'data_nacionalidades':
                $respuesta = DB::select("EXEC inv.Sp_SEL_nacionalidadesXcNacionNombre ?", $data);
                break;

            case 'buscar_proyecto_criterios':
                $respuesta = DB::select('EXEC inv.Sp_SEL_Criterio_Busqueda_Proyectos');
                break;

            case 'buscar_proyecto_filtro':
                $data = json_decode(json_encode($data));
                // return response()->json($data);
                $docMiembro = $data->docMiembro;
                $docParEvaluador = $data->docParEvaluador;
                $docMonitor = $data->docMonitor;
                $docPostulante = $data->docPostulante;
                $idDependencia = $data->idDependencia;
                switch ($data->option) {
                    /* case 1:
                         $dataEnviar = [ $data->idDependencia, Carbon::parse($data->fecha)->format('Ymd'), '', '', '', '', '', '', '',];
                         break;*/
                    case 2:
                        $dataEnviar = [$idDependencia, $docMiembro, $docParEvaluador, $docMonitor, '', $data->year, $data->month, '', '', '', '', '',$docPostulante];
                        break;
                    /* case 3:
                         $dataEnviar = [$data->idDependencia, '', '', '', Carbon::parse($data->range_1)->format('Ymd'), Carbon::parse($data->range_2)->format('Ymd'), '', '', '',];
                         break;*/

                    case 4:
                        if ($data->variableCriterio == '') {
                            $data->variableCriterio = "%";
                        }
                        $dataEnviar = [$idDependencia, $docMiembro, $docParEvaluador, $docMonitor, '', $data->yearCriterio, $data->idCriterio, $data->variableCriterio,$docPostulante];
                        break;
                    case 5:
                        $dataEnviar = [$idDependencia, $docMiembro, $docParEvaluador, $docMonitor, '', '', 8,  $data->idConvocatoria,$docPostulante];
                        break;
                }
                $respuesta = DB::select('EXEC inv.Sp_SEL_proyecto_XcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?, ?', $dataEnviar);
                break;

            case 'objetivo_actividad_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_objetivo_actividad_proyectoXiProyectoId_iTipoObjetivoId ?, ?', $data);
                break;

                case 'actividad_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_actividad_proyectoXiProyectoId_iObjetivoId ?, ?, ?', $data);
                break;

            case 'datos_actividad_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_datos_actividadesXiProyectoId ?', $data);
                break;

            case 'datos_gasto_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_datos_gastosXiProyectoId ?', $data);
                break;

            case 'datos_presupuesto_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_datos_presupuestosXiProyectoId ?', $data);
                break;

            case 'verifica_presupuesto_gasto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_verifica_presupuesto_gastoXiActividadId ?', $data);
                break;

            case 'rubros':
                $respuesta = DB::select('EXEC inv.Sp_SEL_rubroXcDescripcion ?', $data);
                break;

            case 'informe_final_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_informe_finalXiProyectoId ?', $data);
                break;

            case 'informe_proyecto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_informe_proyectoXiProyectoId ?', $data);
                break;

            case 'convocatoria':
                $respuesta = DB::select('EXEC inv.Sp_SEL_convocatoriaXcDescripcion ?', $data);
                break;

            case 'evaluaciones':
                $respuesta = DB::select('EXEC inv.Sp_SEL_evaluacionesXresultados ?,?', $data);
                break;
            case 'evaluaciones_oficina':
                $respuesta = DB::select('EXEC inv.Sp_SEL_evaluacionesXoficina ?', $data);
                break;

            case 'fechas_entregas':
                $respuesta = DB::select('EXEC inv.Sp_SEL_fechasEntregasXevaluacion ?, ?', $data);
                break;

            case 'convocatorias_activa':
                $respuesta = DB::select('EXEC inv.Sp_SEL_convocatorias_activas ?', $data);
                break;

            case 'notificaciones':
                $respuesta = DB::select('EXEC inv.Sp_SEL_notificaciones ?, ?', $data);
                break;

            case 'data_calendario_anyos':
                $data = json_decode(json_encode($data));
                /* @_iActividadId INTEGER,
                 * /* @_iActividadId INTEGER,
                 * @_cCaleAnyo VARCHAR(2),
                 * @_cCaleMes VARCHAR (5)
                 */
                $dataEnviar = [1, $data[0], '', ''];
                $respuesta = DB::select('EXEC inv.Sp_SEL_calendario_XcConsultaVariablesCampos ?, ?, ?, ?', $dataEnviar);
                break;
            case 'data_calendario_meses':
                $data = json_decode(json_encode($data));
                /* @_iActividadId INTEGER,
                 * 148
                 * @_cCaleAnyo VARCHAR(2),
                 * 149
                 * @_cCaleMes VARCHAR (5)
                 */
                $dataEnviar = [0, $data[0], $data[1], ''];
                $respuesta = DB::select('EXEC inv.Sp_SEL_calendario_XcConsultaVariablesCampos ?, ?, ?, ?', $dataEnviar);
                break;
            case 'data_calendario_id':
                $data = json_decode(json_encode($data));
                /* @_iActividadId INTEGER,
                 * @_cCaleAnyo VARCHAR(2),
                 * @_cCaleMes VARCHAR (5)
                 */
                $dataEnviar = [0, $data[0], $data[1], $data[2]];
                $respuesta = DB::select('EXEC inv.Sp_SEL_calendario_XcConsultaVariablesCampos ?, ?, ?, ?', $dataEnviar);
                break;
            /*case 'data_presupuesto':
                $data = json_decode(json_encode($data));
                $dataEnviar = [$data[0], $data[1], $data[2], $data[3], 0, ''];
                $respuesta = DB::select('EXEC inv.Sp_SEL_presupuestoXcConsultaVariablesCampos ?, ?, ?, ?, ?, ?', $dataEnviar);
                break;*/
            case 'busca_presupuesto':
                $respuesta = DB::select('EXEC inv.Sp_SEL_presupuestoXiProyectoId_iRubroId ?, ?', $data);
                break;

            case 'data_resumen_presupuesto':

                $respuesta = DB::select('EXEC inv.Sp_SEL_descripcion_presupuesto_resumen ?', $data);
                break;

            case 'data_rubro':

                $respuesta = DB::select('EXEC inv.Sp_SEL_rubro ?', $data);
                break;
            case 'data_gasto':
                $data = json_decode(json_encode($data));
                $dataEnviar = [1, $data[0]];
                $respuesta = DB::select('EXEC inv.Sp_SEL_gastoXcConsultaVariablesCampos ?, ?', $dataEnviar);
                break;
            //Gustavo proc
            case 'data_parevaluador_proyecto_resumen':
                $respuesta = DB::select('EXEC inv.Sp_SEL_parevaluador_proyecto ?,?', $data);
                break;
//
            case 'data_rubro_presupuesto_gasto_resumen':
                $respuesta = DB::select('EXEC inv.Sp_SEL_rubro_presupuesto_gasto_resumen ?', $data);
                break;
            case 'data_rubro_presupuesto_gasto_detalle':
                $respuesta = DB::select('EXEC inv.Sp_SEL_rubro_presupuesto_gasto_detalle ?, ?', $data);
                break;
            case 'data_avance_presupuestal':
                $respuesta = DB::select('EXEC inv.Sp_SEL_avance_presupuestal ?', $data);
                break;

            case 'data_avance_financiero':
                $respuesta = DB::select('EXEC inv.Sp_SEL_avance_financiero ?, ?', $data);
                break;
            case 'data_avance_financiero_resumen':
                $respuesta = DB::select('EXEC inv.Sp_SEL_avance_financiero_resumen ?, ?', $data);
                break;
            case 'data_pedidos_SIGA':
                $data = json_decode(json_encode($data));
                /*   @_ANO_EJE INTEGER,
                 * @_SEC_EJEC INTEGER,
                 * @_NRO_PEDIDO VARCHAR (5),
                 * @_docEMPLEADO VARCHAR (8),
                 * @_TIPO_BIEN VARCHAR (2),
                 * @_CENTRO_COSTO  VARCHAR (15),
                 *
                 * @_iCritId INTEGER=0,
                 * @_cCritVariable VARCHAR(MAX)=''
                 * [2019, 1230, '','23984603','B','1230.19.05','',''];*/
                $dataEnviar = [$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]];
                $respuesta = DB::select('EXEC inv.Sp_SEL_pedidosSIGA_XcConsultaVariablesCampos ?, ?, ?, ?, ?, ?,   ?, ?', $dataEnviar);
                break;


            case 'data_avance_tecnico_detalle':
                $respuesta = DB::select('EXEC inv.Sp_SEL_avance_tecnico_detalle ?, ?', $data);
                break;

            case 'tipo_persona':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_personas');
                break;
            case 'tipo_identificacion':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Identificaciones');
                break;

            case 'data_oficinas_usuario_dgi':
                $respuesta = DB::select("EXEC inv.Sp_SEL_credenciales_dgiXiCredId ?", $data);
                break;

            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;




            case 'data_credencial':
                if (!isset($data[0])) {
                    $data[0] = auth()->user()->iCredId;
                }
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', $data);
                break;

            case 'data_reniec':
                $respuesta = DB::select('EXEC grl.Sp_SEL_reniecXcReniecDni ?', $data);
                break;


            case 'data_BD':
                $respuesta = DB::select('EXEC lab.Sp_data   ?, ?, ?', $data);
                break;



            case 'credenciales':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', $data);
                break;



            case 'conceptos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_conceptosXiEntIdXcCodigo_cNombre 1, ?', $data);
                $respuesta = collect($respuesta);
                foreach ($respuesta as $concept) {
                    $datReq = DB::select("EXEC grl.Sp_SEL_conceptos_requisitosXiConcepId ?", [$concept->iConcepId]);
                    $concept->requisitos = collect($datReq)->sortBy('iConcepReqNumero');
                    //dd($datReq);
                }
                break;

            case 'tipo_conceptos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Conceptos');
                break;









            case 'verificarCambioContraseña':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', [auth()->user()->iCredId]);
                // return response()->json(['res' => [sha1($data[0]), $respuesta[0]->password]]);
                $retorno = ['cambiado' => true];
                if (sha1(auth()->user()->cCredUsuario) == $respuesta[0]->password) {
                    $retorno = ['cambiado' => false];
                }

                $respuesta = $retorno;

                break;




            // CONSULTAS GENERALES

            case 'consulta_existencia':
                /**
                 * Buscar si existe un valor en una tabla y campo especifico
                 *
                 * $data = array('tabla', 'campo', 'valor');
                 */
                $respuesta = DB::select('EXECUTE grl.Sp_SEL_Verificar_Existe_Campo ?, ?, ?', $data);

                if ($respuesta[0]->iResult == 0) {
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

    function makeNested($source)
    {

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

        foreach ($source as &$s) {

            if (is_null($s->iDepenDependeId)) {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
            } else {
                $pid = $s->iDepenDependeId;
                if (isset($source[$pid])) {
                    // If the parent ID exists in the source array
                    // we add it to the 'children' array of the parent after initializing it.

                    if (!isset($source[$pid]['children'])) {
                        $source[$pid]['children'] = array();
                    }

                    $source[$pid]['children'][] = &$s;
                }
            };
        }
        return $nested;
    }

    public function guardarDataAnonimo(Request $request)
    {
        // return response()->json($request);
        $req = $request->get('tipo');
        $data = $request->get('data');
        $data = json_decode(json_encode($data));
        $respuesta = null;
        switch ($req) {
            /********************************************gustavo*/

            case 'mantenimiento_persona':
                 //return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                   //     $respuesta = DB::select('EXEC inv.Sp_DEL_miembro ?', $data );
                    }
                    else {
                        $dataGuardar1 = [
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
                            $data->idNacionalidad??null,


                            $data->iCredId??null,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];
/*
                        if ($data->iPersId) {

                            array_unshift($dataGuardar1, $data->iPersId);
                            // return response()->json($dataGuardar1);
                          $respuesta = DB::select('EXEC inv.Sp_UPD_personas ?,   ?, ?, ?,     ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?', $dataGuardar1 );

                        } else {*/
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_personas_cerd_perfil_postulante ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar1 );

                       // }
                        //return response()->json($respuesta);
                        switch ($data->opTipoPersona??null && $respuesta[0]->iPersId??null){

                            case 'postulante':
                                $dataMiembro = [
                                    $respuesta[0]->iPersId,

                                    $data->grado,

                                    $data->iCredId??null,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];
                                if ($data->iPostulanteId) {
                                    array_unshift($dataMiembro, $data->iPostulanteId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_postulante ?, ?, ?,     ?, ?, ?, ?', $dataMiembro );
                                } else {
                                    // return response()->json($dataGuardar);
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_postulante ?, ?,     ?, ?, ?, ?', $dataMiembro);

                                }

                                // $respuesta2 = DB::select('EXEC inv.Sp_INS_miembro ?, ?,     ?, ?, ?, ?', $dataMiembro);
                                break;


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
        }

        return response()->json($jsonResponse);
    }

    public function guardarData(Request $request)
    {
        // return response()->json($request);
        $req = $request->get('tipo');
        $data = $request->get('data');
        $data = json_decode(json_encode($data));
        if ((is_object($data)) && (auth()->user()->iCredId != $data->auditoria->credencial_id)) {
            return response()->json(['error' => true, 'msg' => 'Usuario NO AUTENTICADOoo' . '#' . auth()->user()->iCredId . '#$' . $data->auditoria->credencial_id . '$']);
        }
        $respuesta = null;
        switch ($req) {

            case 'mantenimiento_persona':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC inv.Sp_DEL_miembro ?', $data );
                    }
                    else {
                        $dataGuardar1 = [
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
                            $data->idNacionalidad??null,


                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];

                        if ($data->iPersId) {

                            array_unshift($dataGuardar1, $data->iPersId);
                            // return response()->json($dataGuardar1);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_personas ?,   ?, ?, ?,     ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?', $dataGuardar1 );

                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_personas ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar1 );

                        }

                        /** inser y upd persona tipo contacto**/
                        $listaContactoId = "";
                        $dataContacto = $data->contacto??[];
                        foreach ($dataContacto as $cont) {
                            $dataCont = [
                                $cont->iTipoConId,
                                ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),
                                $cont->cPersTipoConDescripcion,
                                ($cont->bPersTipoConPrincipal == 'true' ? 1 : 0),

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip ?? null,
                                null,
                            ];

                            if ($cont->iPersTipoConId <> NULL) {
                                $listaContactoId .= $cont->iPersTipoConId . "*";
                                array_unshift($dataCont, $cont->iPersTipoConId);
                                $respuesta2 = DB::select('EXEC inv.Sp_UPD_persona_tipo_contactos ?, ?,     ?, ?, ?,       ?, ?, ?, ?', $dataCont);
                                // return 'editar';
                            } else {
                                $respuesta2 = DB::select('EXEC inv.Sp_INS_persona_tipo_contactos ?, ?, ?, ?,       ?, ?, ?, ?', $dataCont);
                                $listaContactoId .= $respuesta2[0]->iPersTipoConId . "*";
                                // return 'nuevo';
                            }
                        }

                        /****tipo contact****/
                        $listaContactoId = substr($listaContactoId, 0, -1);
                        if ($listaContactoId <> "") {
                            $dataIdCont = [
                                $listaContactoId,
                                ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId)
                            ];
                            $respuesta2 = DB::select('EXEC inv.Sp_DEL_persona_tipo_contactos ?, ?', $dataIdCont);
                        }

                        switch ($data->opTipoPersona??null){

                            case 'miembro':
                                $dataMiembro = [
                                    ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),

                                    $data->grado,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];
                                if ($data->iMiembroId) {
                                    array_unshift($dataMiembro, $data->iMiembroId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_miembro ?, ?, ?,     ?, ?, ?, ?', $dataMiembro );
                                } else {
                                    // return response()->json($dataGuardar);
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_miembro ?, ?,     ?, ?, ?, ?', $dataMiembro);

                                }

                                // $respuesta2 = DB::select('EXEC inv.Sp_INS_miembro ?, ?,     ?, ?, ?, ?', $dataMiembro);
                                break;
                            case 'monitor':
                                $dataMonitor = [
                                    ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),

                                    $data->grado,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];
                                if ($data->iMonitorId) {
                                    array_unshift($dataMonitor, $data->iMonitorId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_monitor ?, ?, ?,     ?, ?, ?, ?', $dataMonitor );
                                } else {
                                    // return response()->json($dataGuardar);
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_monitor ?, ?,     ?, ?, ?, ?', $dataMonitor);

                                }

                                // $respuesta2 = DB::select('EXEC inv.Sp_INS_miembro ?, ?,     ?, ?, ?, ?', $dataMiembro);
                                break;

                            case 'evaluador':


                                $dataMiembro = [
                                    ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),

                                    $data->regina,
                                    $data->institucion,
                                    $data->grado_academico,
                                    $data->h,
                                    $data->linea_investigacion,
                                    $data->cuenta_bancaria,
                                    $data->banco,
                                    $data->cci,
                                    $data->observacion,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];


                                if ($data->iParEvaluadorId) {
                                    array_unshift($dataMiembro, $data->iParEvaluadorId);
                                    $respuesta2 = DB::select('EXEC inv.Sp_UPD_evaluador  ?,?, ?  ,?,?,?,?,?,?,?,?,     ?, ?, ?, ?', $dataMiembro );
                                } else {
                                    // return response()->json($dataGuardar);
                                    $respuesta2 = DB::select('EXEC inv.Sp_INS_evaluador ?, ?,  ?,?,?,?,?,?,?,?,   ?, ?, ?, ?', $dataMiembro);

                                }


                                break;

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
            /********************************************gustavo*/
            case 'evaluadores_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                       $respuesta = DB::select('EXEC inv.Sp_DEL_evaluadores ?', $data);
                    } else {

                        $dataGuardar = [
                            $data->idProyecto,
                            $data->idParEvaluador,
                            Carbon::parse($data->dtFechaEntrega)->format('Y-m-d H:i:s'),

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];

                        if ($data->idParEvaluadorProyecto) {
                            array_unshift($dataGuardar, $data->idParEvaluadorProyecto);

                           // $respuesta = DB::select('EXEC inv.Sp_UPD_evaluadores_proyecto ?,     ?, ?, ?, ?, ?,    ?,?, ?, ?,    ?', $dataGuardar);

                        } else {


                           $respuesta = DB::select('EXEC inv.Sp_INS_evaluador_proyecto ?,?, ?,       ?,?, ?, ?', $dataGuardar);

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
                // return response()->json($data);
                break;

            case 'presupuesto_proyecto':

                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    //    $respuesta = DB::select('EXEC inv.Sp_DEL_presupuesto ?', $data);
                    } else {

                        $dataGuardar = [

                            $data->idProyecto,
                            $data->idRubro,
                            $data->idCalendario,
                            $data->monto,
                            null,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,

                            $data->idActividad,
                        ];


                        if ($data->idPresupuesto) {
                            array_unshift($dataGuardar, $data->idPresupuesto);

                            $respuesta = DB::select('EXEC inv.Sp_UPD_presupuesto_proyecto ?,     ?, ?, ?, ?, ?,    ?,?, ?, ?,    ?', $dataGuardar);

                        } else {

                            $respuesta = DB::select('EXEC inv.Sp_INS_Presupuesto ?,?,?,?,?,        ?,?, ?, ?,   ?', $dataGuardar);

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
                // return response()->json($data);
                break;


            case 'saldo_presupuestal':

                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //        $respuesta = DB::select('EXEC inv.Sp_DEL_presupuesto ?', $data);
                    }
                    else {

                        foreach ($data->rubrosValores as $idx => $val) {
                            $dataGuardar = [
                                $data->idProyecto,
                                $idx,
                                $val,


                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip ?? null,
                                null,
                            ];


                           // $respuesta = DB::select('EXEC inv.Sp_UPD_SaldoPresupuestal  ?, ?, ?, ?,    ?, ?, ?, ?', $dataGuardar)
                            //                             $respuesta = DB::select('EXEC inv.Sp_INS_SaldoPresupuestal ?,?,?,      ?, ?, ?, ?', $dataGuardar);

                            if ($data->rubrosValoresPresupuestoId->{$idx} != null) {
                                array_unshift($dataGuardar, $data->rubrosValoresPresupuestoId->{$idx});

                                $respuesta = DB::select('EXEC inv.Sp_UPD_SaldoPresupuestal  ?, ?, ?, ?,    ?, ?, ?, ?', $dataGuardar);

                            } else {

                                $respuesta = DB::select('EXEC inv.Sp_INS_SaldoPresupuestal ?,?,?,      ?, ?, ?, ?', $dataGuardar);

                            }
                        }

                    }
// return response()->json($respuesta);

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
                // return response()->json($data);
                break;

            case 'saldo_presupuestal6666':
                DB::beginTransaction();
                try {

                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    }
                    else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;
                        //actualiza fechas del inicio y fin del proyecto
                        //actualiza fechas del inicio y fin del proyecto


                        if ($data->objEspecifico) {
                            // return response()->json($data->objEspecifico);
                            // recorre objetivo
                            $dataObjetivo = $data->objEspecifico;

                            /******inser , seel, del, upd********/

                            foreach ($dataObjetivo as $obj) {

                                $iProyectoId = $obj->iProyectoId;
                                $iRubroId = $obj->iRubroId;


                                /******insert des presupuesto********/
                                //K return response()->json($obj->actividad);
                                $listaActividadId = "";
                                $dataActividades = $obj->actividad;
                                // return response()->json($dataObjetivo[1]->actividad);
                                foreach ($dataActividades as $act) {
                                    $dataGuardar = [

                                        $iProyectoId,
                                        $obj->iRubroId,
                                        $obj->totalPresupuesto,

                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    //
                                    if (isset($act->iPresupuestoId)) {
                                        $idAct = $act->iPresupuestoId;
                                        $listaActividadId .= $act->iPresupuestoId . "*";
                                        array_unshift($dataGuardar, $act->iPresupuestoId);
                                        $respuesta = DB::select('EXEC inv.Sp_UPD_SaldoPresupuestal  ?, ?, ?, ?,    ?, ?, ?, ?', $dataGuardar);

                                        // return 'editar';
                                    } else {
                                        $respuesta = DB::select('EXEC inv.Sp_INS_SaldoPresupuestal ?,?,?,      ?, ?, ?, ?', $dataGuardar);
                                        $listaActividadId .= $respuesta[0]->iPresupuestoId . "*";
                                        $idAct = $respuesta[0]->iPresupuestoId;
                                        // return 'nuevo';
                                    }

                                }
                                /*********delete presupuesto********/
                                $listaActividadId = substr($listaActividadId, 0, -1);
                                if ($listaActividadId <> "") {
                                    $dataIdAct = [
                                        $listaActividadId,
                                        $iProyectoId,
                                        $iRubroId,
                                    ];
                                    //  $respuesta = DB::select('EXEC inv.Sp_DEL_presupuesto ?, ?, ?', $dataIdAct);
                                }
                            }

                        }

                    }
                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    }
                    else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }

                    DB::commit();
                }

                catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;


            //Carlos Chong Silva

            case 'mantenimiento_fuente_proyecto':
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC inv.Sp_DEL_fuente_proyecto ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->descripcion,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->idFuentProy) {
                            array_unshift($dataGuardar, $data->idFuentProy);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_fuente_proyecto ?,     ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_fuente_proyecto ?,        ?, ?, ?, ?', $dataGuardar);
                            // return response()->json($respuesta);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }

                // return response()->json($data);
                break;

            case 'mantenimiento_tipo_proyecto':
                //return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->descripcion,
                            $data->bConcursable,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->idTipoProy) {
                            array_unshift($dataGuardar, $data->idTipoProy);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_tipo_proyecto ?, ?,     ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_tipo_proyecto ?,?,        ?, ?, ?, ?', $dataGuardar);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

            case 'mantenimiento_estado_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC inv.Sp_DEL_estado_proyecto ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->descripcion,
                            $data->detalle,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->idEstadoProy) {
                            array_unshift($dataGuardar, $data->idEstadoProy);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_estado_proyecto ?,?,     ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_estado_proyecto ?,?,        ?, ?, ?, ?', $dataGuardar);
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
/*update***/
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
/*****gustavo****/
            case 'mantenimiento_descripcion_presupuesto_cronograma_indicador':
                // return response()->json($data);
                DB::beginTransaction();
                try {

                    if (!is_object($data)) {
                       // $respuesta = DB::select('EXEC inv.Sp_DEL_presupuesto ?, ?, ?', $data);
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    } else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;
                        //actualiza fechas del inicio y fin del proyecto
                        //actualiza fechas del inicio y fin del proyecto


                        if ($data->objEspecifico) {
                            // return response()->json($data->objEspecifico);
                            // recorre objetivo
                            $dataObjetivo = $data->objEspecifico;

                            /******inser , seel, del, upd********/

                            foreach ($dataObjetivo as $obj) {

                                $iProyectoId = $obj->iProyectoId;
                                $iRubroId = $obj->iRubroId;


                                /******insert des presupuesto********/
                                //K return response()->json($obj->actividad);
                                $listaActividadId = "";
                                $dataActividades = $obj->actividad;
                                // return response()->json($dataObjetivo[1]->actividad);
                                foreach ($dataActividades as $act) {
                                    $dataGuardar = [


                                        $obj->iRubroId,
                                        $iProyectoId,
                                        $act->cUnidadMedida,
                                        $act->nMonto,
                                        $act->iCantidad,
                                        $act->cDetalle,

                                        /// $act->nTotal,

                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    //
                                    if (isset($act->iDescripcionPresupuestoId)) {
                                        $idAct = $act->iDescripcionPresupuestoId;
                                        $listaActividadId .= $act->iDescripcionPresupuestoId . "*";
                                        array_unshift($dataGuardar, $act->iDescripcionPresupuestoId);
                                         $respuesta = DB::select('EXEC inv.Sp_UPD_Presupuesto ?,  ?,?,?,?, ?, ?,     ?, ?, ?, ?', $dataGuardar);
                                        // return 'editar';
                                    } else {
                                        $respuesta = DB::select('EXEC inv.Sp_INS_presupuesto ?, ?, ?,?,?,?,   ?, ?, ?, ?', $dataGuardar);
                                        $listaActividadId .= $respuesta[0]->iDescripcionPresupuestoId . "*";
                                        $idAct = $respuesta[0]->iDescripcionPresupuestoId;
                                        // return 'nuevo';
                                    }


                                }
                                /*********delete presupuesto********/
                                $listaActividadId = substr($listaActividadId, 0, -1);
                                if ($listaActividadId <> "") {
                                    $dataIdAct = [
                                        $idAct,
                                        $iProyectoId,
                                        $iRubroId,
                                    ];
                                    // return response()->json($dataIdAct);
                                    // echo json_encode( $dataIdAct) . '\n';
                                 //   $respuesta = DB::select('EXEC inv.Sp_DEL_presupuesto ?, ?, ?', $dataIdAct);
                                }
                            }

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
                // return response()->json($data);
                break;

            case 'cronograma':
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //$respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    } else {
                                    $dataGuardar = [

                                        $data->iDescripcionPresupuestoId,
                                        $data->nMes,
                                        ($data->iEstado ? 1 : 0),

                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    // return response()->json($dataGuardar);
                                    if (isset($data->iCronogramaPresId)) {
                                        array_unshift($dataGuardar, $data->iCronogramaPresId);
                                        $respuesta = DB::select('EXEC inv.Sp_UPD_cronograma_presupuestal ?,    ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);

                                    } else {

                                        $respuesta = DB::select('EXEC inv.Sp_INS_cronograma_presupuestal ?, ?, ?,          ?, ?, ?, ?', $dataGuardar);

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
                // return response()->json($data);
                break;

            case 'mantenimiento_cronograma_descripcion_presupuesto':
                // return response()->json($data);
                DB::beginTransaction();
                try {

                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    }
                    else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;
                        //actualiza fechas del inicio y fin del proyecto
                        //actualiza fechas del inicio y fin del proyecto


                        if ($data->objEspecifico) {
                            // return response()->json($data->objEspecifico);
                            // recorre objetivo
                            $dataObjetivo = $data->objEspecifico;

                            /******inser , seel, del, upd********/

                            foreach ($dataObjetivo as $obj) {

                                $iProyectoId = $obj->iProyectoId;
                                $iRubroId = $obj->iRubroId;


                                /******insert des presupuesto********/
                                //K return response()->json($obj->actividad);
                                $listaActividadId = "";
                                $dataActividades = $obj->actividad;
                                // return response()->json($dataObjetivo[1]->actividad);
                                foreach ($dataActividades as $act) {
                                    $dataGuardar = [


                                        $obj->iRubroId,
                                        $iProyectoId,
                                        $act->nTotal,
                                        $act->cDetalle,
                                        $act->iActividadId,



                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    //
                                    if (isset($act->iDescripcionPresupuestoId)) {
                                        $idAct = $act->iDescripcionPresupuestoId;
                                        $listaActividadId .= $act->iDescripcionPresupuestoId . "*";
                                        array_unshift($dataGuardar, $act->iDescripcionPresupuestoId);
                                          $respuesta = DB::select('EXEC inv.Sp_UPD_presupuesto1 ?,    ?,?,?, ?, ?,    ?, ?, ?, ?', $dataGuardar);
                                        // return 'editar';
                                    } else {
                                        $respuesta = DB::select('EXEC inv.Sp_INS_presupuesto1 ?, ?, ?,?, ?,  ?, ?, ?, ?', $dataGuardar);
                                        $listaActividadId .= $respuesta[0]->iDescripcionPresupuestoId . "*";
                                        $idAct = $respuesta[0]->iDescripcionPresupuestoId;
                                        // return 'nuevo';
                                    }


                                }
                                /*********delete presupuesto********/
                                $listaActividadId = substr($listaActividadId, 0, -1);
                                if ($listaActividadId <> "") {
                                    $dataIdAct = [
                                        $listaActividadId,
                                        $iProyectoId,
                                        $iRubroId,
                                    ];
                                    //  $respuesta = DB::select('EXEC inv.Sp_DEL_presupuesto ?, ?, ?', $dataIdAct);
                                }
                            }

                        }

                    }
                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    }
                    else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }

                    DB::commit();
                }

                catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

/*****gustavo***/

            case 'mantenimiento_actividad_cronograma_indicador':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    }
                    else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;
                        //actualiza fechas del inicio y fin del proyecto

                        if ($data->objEspecifico) {
                            // return response()->json($data->objEspecifico);
                            // recorre objetivo
                            $dataObjetivo = $data->objEspecifico;

                            foreach ($dataObjetivo as $obj) {
                                $iProyectoId = $obj->iProyectoId;
                                $iObjetivoId = $obj->iObjetivoId;


                                $listaIndicadorId = "";
                                $dataIndicadores = $obj->indicador;
                                //   return response()->json($dataObjetivo[1]->actividad);


                                /***********inser upd indicador****/
                                foreach ($dataIndicadores as $ind) {
                                    $dataGuardar = [
                                        $iObjetivoId,
                                        $ind->cIndicador,
                                        $ind->iMeta,

                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    if (isset($ind->iIndicadorId)) {
                                        $idInd = $ind->iIndicadorId;
                                        $listaIndicadorId .= $ind->iIndicadorId . "*";
                                        array_unshift($dataGuardar, $ind->iIndicadorId);
                                        $respuesta = DB::select('EXEC inv.Sp_UPD_indicador ?,     ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                        // return 'editar';
                                    } else {
                                        $respuesta = DB::select('EXEC inv.Sp_INS_indicador ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                        $listaIndicadorId .= $respuesta[0]->iIndicadorId . "*";
                                        $idInd = $respuesta[0]->iIndicadorId;
                                        // return 'nuevo';
                                    }
                                }

                                /********del indicador***********/
                                $listaIndicadorId = substr($listaIndicadorId, 0, -1);
                                if ($listaIndicadorId <> "") {
                                    $dataIdInd = [
                                        $listaIndicadorId,
                                        $iObjetivoId
                                    ];
                                    $respuesta = DB::select('EXEC inv.Sp_DEL_indicador ?, ?', $dataIdInd);
                                }


                                $listaActividadId = "";
                                $dataActividades = $obj->actividad;
                                //   return response()->json($dataObjetivo[1]->actividad);
                                foreach ($dataActividades as $act) {
                                    $dataGuardar = [
                                        $iProyectoId,
                                        $obj->iObjetivoId,
                                        $act->cActividadDescripcion,
                                        $act->iCantidad,
                                        null, // $act->nCantPorcentaje,
                                        null, // $act->iAvanceCantidad,
                                        null, // $act->nAvanceCantidadPorcentaje,
                                        null, // $act->dtInicio,
                                        null, // $act->dtFin,
                                        $act->cUnidadMedida,

                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    if (isset($act->iActividadId)) {
                                        $idAct = $act->iActividadId;
                                        $listaActividadId .= $act->iActividadId . "*";
                                        array_unshift($dataGuardar, $act->iActividadId);
                                        $respuesta = DB::select('EXEC inv.Sp_UPD_actividad ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                        // return 'editar';
                                    }
                                    else {
                                        $respuesta = DB::select('EXEC inv.Sp_INS_actividad ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                        $listaActividadId .= $respuesta[0]->iActividadId . "*";
                                        $idAct = $respuesta[0]->iActividadId;
                                        // return 'nuevo';
                                    }

                                    /********insert cronograma****/
                                    // mantenimiento de cronogramas
                                    $listaCronogramaId = "";
                                    $dataCronogramas = $act->cronograma;
                                    //   return response()->json($dataObjetivo[1]->actividad);
                                    foreach ($dataCronogramas as $cro) {
                                        $dataGuardar = [
                                            $idAct,
                                            $cro->nMes,
                                            ($cro->iEstado ? 1 : 0),

                                            auth()->user()->iCredId,
                                            null,
                                            $auditoriaIp ?? null,
                                            null,
                                        ];
                                        // return response()->json($dataGuardar);
                                        if (isset($cro->iCronogramaId)) {
                                            $idCro = $cro->iCronogramaId;
                                            $listaCronogramaId .= $cro->iCronogramaId . "*";
                                            array_unshift($dataGuardar, $cro->iCronogramaId);
                                            $respuesta = DB::select('EXEC inv.Sp_UPD_cronograma ?,    ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);
                                            // return 'editar';
                                        }
                                        else {
                                            // return response()->json($cro);
                                            $respuesta = DB::select('EXEC inv.Sp_INS_cronograma ?, ?, ?,          ?, ?, ?, ?', $dataGuardar);
                                            $listaCronogramaId .= $respuesta[0]->iCronogramaId . "*";
                                            $idCro = $respuesta[0]->iCronogramaId;
                                            // return 'nuevo';
                                        }


                                    }
                                    /*****del cronograma***/
                                    $listaCronogramaId = substr($listaCronogramaId, 0, -1);
                                    if ($listaCronogramaId <> "") {
                                        $dataIdCro = [
                                            $listaCronogramaId,
                                            $iObjetivoId,
                                            $idAct
                                        ];
                                        $respuesta = DB::select('EXEC inv.Sp_DEL_cronograma ?, ?, ?', $dataIdCro);
                                    }

                                }


                                $listaActividadId = substr($listaActividadId, 0, -1);
                                if ($listaActividadId <> "") {
                                    $dataIdAct = [
                                        $listaActividadId,
                                        $iProyectoId,
                                        $iObjetivoId,
                                    ];
                                    $respuesta = DB::select('EXEC inv.Sp_DEL_actividad ?, ?, ?', $dataIdAct);
                                }
                                // return response()->json($dataGuardar);
                            }
                        }
                    }
                    /*             $dataInfAv = [
                                     $iProyectoId,

                                     auth()->user()->iCredId,
                                     null,
                                     $data->auditoria->ip ?? null,
                                     null,
                                 ];
                                 $respuesta = DB::select('EXEC inv.Sp_INS_informe_avanceXfechaInicioFinProyecto ?,   ?, ?, ?, ?', $dataInfAv);*/

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
                // return response()->json($data);
                break;


            case 'mantenimiento_hito':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    } else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;

                        if ($data->hito) {
                            // return response()->json($data->objEspecifico);
                            // recorre hito
                            $listaHitoId = "";
                            $dataHitos = $data->hito;
                            foreach ($dataHitos as $ht) {
                                $dataGuardar = [
                                    $ht->iProyectoId,
                                    $ht->iNumeroHito,
                                    $ht->cNombre,
                                    $ht->cHitoAnyoInicio,
                                    $ht->cHitoMesInicio,
                                    $ht->cHitoAnyoFin,
                                    $ht->cHitoMesFin,
                                    $ht->iNumeroMeses,
                                    $ht->dtFechaInicio,
                                    $ht->dtFechaFin,

                                    auth()->user()->iCredId,
                                    null,
                                    $auditoriaIp ?? null,
                                    null,
                                ];
                                if (isset($ht->iHitoId)) {
                                    $idHt = $ht->iHitoId;
                                    $listaHitoId .= $ht->iHitoId . "*";
                                    array_unshift($dataGuardar, $ht->iHitoId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_hito ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_hito ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                    $listaHitoId .= $respuesta[0]->iHitoId . "*";
                                    $idHt = $respuesta[0]->iHitoId;
                                    // return 'nuevo';
                                }
                            }
                            $listaHitoId = substr($listaHitoId, 0, -1);
                            if ($listaHitoId <> "") {
                                $dataIdHt = [
                                    $listaHitoId,
                                    $ht->iProyectoId
                                ];
                                $respuesta = DB::select('EXEC inv.Sp_DEL_hito ?, ?', $dataIdHt);
                            }
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
                // return response()->json($data);
                break;

            case 'mantenimiento_indicador_hito':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    } else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;
                        //actualiza fechas del inicio y fin del proyecto
                        if ($data->hito) {
                            // return response()->json($data->objEspecifico);
                            // recorre objetivo
                            $dataHito = $data->hito;
                            foreach ($dataHito as $ht) {
                                $iProyectoId = $ht->iProyectoId;
                                $iHitoId = $ht->iHitoId;

                                $listaIndicadorId = "";
                                $dataIndicadores = $ht->indicadorHito;
                                //   return response()->json($dataObjetivo[1]->actividad);
                                foreach ($dataIndicadores as $ind) {
                                    $dataGuardar = [
                                        $iHitoId,
                                        $ind->cIndicadorHito,
                                        $ind->iMeta,
                                        $ind->iNumero,

                                        auth()->user()->iCredId,
                                        null,
                                        $auditoriaIp ?? null,
                                        null,
                                    ];
                                    if (isset($ind->iIndicadorHitoId)) {
                                        $idInd = $ind->iIndicadorHitoId;
                                        $listaIndicadorId .= $ind->iIndicadorHitoId . "*";
                                        array_unshift($dataGuardar, $ind->iIndicadorHitoId);
                                        $respuesta = DB::select('EXEC inv.Sp_UPD_indicador_hito ?,     ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                        // return 'editar';
                                    } else {
                                        $respuesta = DB::select('EXEC inv.Sp_INS_indicador_hito ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                        $listaIndicadorId .= $respuesta[0]->iIndicadorHitoId . "*";
                                        $idInd = $respuesta[0]->iIndicadorHitoId;
                                        // return 'nuevo';
                                    }
                                }
                                $listaIndicadorId = substr($listaIndicadorId, 0, -1);
                                if ($listaIndicadorId <> "") {
                                    $dataIdInd = [
                                        $listaIndicadorId,
                                        $iHitoId
                                    ];
                                    //$respuesta = DB::select('EXEC inv.Sp_DEL_indicador_hito ?, ?', $dataIdInd);
                                }

                            }
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
                // return response()->json($data);
                break;

            case 'mantenimiento_aprobar_propuesta':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            0, //$data->iEstadoPropuesta,
                            $data->iYearId,
                            $data->cResProyecto,
                            $data->dtInicio,
                            $data->dtFin,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iProyectoId) {
                            array_unshift($dataGuardar, $data->iProyectoId);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_proyecto_aprobar ?,      ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        }

                        $dataHito = $data->hito;
                        foreach ($dataHito as $ht) {
                            $dataGuardar = [
                                $ht->iProyectoId,
                                $ht->iNumeroHito,
                                $ht->cNombre,
                                $ht->cHitoAnyoInicio,
                                $ht->cHitoMesInicio,
                                $ht->cHitoAnyoFin,
                                $ht->cHitoMesFin,
                                $ht->iNumeroMeses,
                                $ht->dtFechaInicio,
                                $ht->dtFechaFin,

                                auth()->user()->iCredId,
                                null,
                                $auditoriaIp ?? null,
                                null,
                            ];

                            if (isset($ht->iHitoId)) {
                                array_unshift($dataGuardar, $ht->iHitoId);
                                // return response()->json($dataGuardar);
                                $respuesta = DB::select('EXEC inv.Sp_UPD_hito ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);
                                // return 'editar';
                            }
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
                // return response()->json($data);
                break;

            case 'mantenimiento_actividad':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    } else {
                        //actualiza fechas del inicio y fin del proyecto
                        if ($data->iProyectoId) {
                            $dataProyGuardar = [
                                $data->iProyectoId,
                                $data->dtInicio,
                                $data->dtFin,

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip ?? null,
                                null,
                            ];

                            $respuesta = DB::select('EXEC inv.Sp_UPD_proyecto_fechas ?,     ?, ?,        ?, ?, ?, ?', $dataProyGuardar);

                            $listaActividadId = "";
                            $dataActividades = $data->infoAct;
                            foreach ($dataActividades as $act) {
                                $dataGuardar = [
                                    $act->iProyectoId,
                                    $act->iObjetivoId,
                                    $act->cActividadDescripcion,
                                    $act->iCantidad,
                                    $act->nCantPorcentaje,
                                    $act->iAvanceCantidad,
                                    $act->nAvanceCantidadPorcentaje,
                                    $act->dtInicio,
                                    $act->dtFin,

                                    auth()->user()->iCredId,
                                    null,
                                    $act->auditoria->ip ?? null,
                                    null,
                                ];

                                if ($act->iActividadId <> NULL) {
                                    $idAct = $act->iActividadId;
                                    $listaActividadId .= $act->iActividadId . "*";
                                    array_unshift($dataGuardar, $act->iActividadId);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_actividad ?,     ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                    // return 'editar';
                                } else {
                                    $respuesta = DB::select('EXEC inv.Sp_INS_actividad ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                                    $listaActividadId .= $respuesta[0]->iActividadId . "*";
                                    $idAct = $respuesta[0]->iActividadId;
                                    // return 'nuevo';
                                }

                                // mantenimiento de los calendarios
                                $f1 = $act->dtInicio;
                                $f2 = $act->dtFin;

                                list($año1, $mes1, $dia1) = explode('-', $f1);
                                list($año2, $mes2, $dia2) = explode('-', $f2);

                                $numMeses = ($año2 * 12 + $mes2) - ($año1 * 12 + $mes1) + 1;
                                if ($año2 < $año1) {
                                    $numMeses = $numMeses - 1;
                                }

                                $listaCalendarioId = "";
                                do {
                                    $dataCal = [
                                        $idAct,
                                        $año1,
                                        str_pad($mes1, 2, "0", STR_PAD_LEFT)
                                    ];

                                    $respuesta = DB::select('EXECUTE inv.Sp_SEL_calendarioXiActividadIdXanyoXmes ?, ?, ?', $dataCal);

                                    if (isset($respuesta[0]->iCalendarioId)) {
                                        $listaCalendarioId .= $respuesta[0]->iCalendarioId . "*";
                                    } else {
                                        $dataCalendario = [
                                            $idAct,
                                            $año1,
                                            str_pad($mes1, 2, "0", STR_PAD_LEFT),
                                            NULL,

                                            auth()->user()->iCredId,
                                            null,
                                            $act->auditoria->ip ?? null,
                                            null,
                                        ];
                                        $respuesta2 = DB::select('EXEC inv.Sp_INS_calendario ?, ?, ?, ?,        ?, ?, ?, ?', $dataCalendario);
                                        $listaCalendarioId .= $respuesta2[0]->iCalendarioId . "*";
                                    }
                                    $mes1++;
                                    if ($mes1 > 12) {
                                        $mes1 = 1;
                                        $año1++;
                                    }
                                    $numMeses--;
                                } while ($numMeses > 0);

                                if ($listaCalendarioId <> "") {
                                    $dataIdAct = [
                                        $listaCalendarioId,
                                        $data->iProyectoId,
                                        $idAct
                                    ];

                                    $respuesta = DB::select('EXEC inv.Sp_DEL_calendario ?, ?, ?', $dataIdAct);
                                }
                            }
                            $listaActividadId = substr($listaActividadId, 0, -1);

                            if ($listaActividadId <> "") {
                                $dataIdAct = [
                                    $listaActividadId,
                                    $data->iProyectoId
                                ];
                                $respuesta = DB::select('EXEC inv.Sp_DEL_actividad ?, ?', $dataIdAct);
                            }
                            // return response()->json($dataGuardar);
                        }
                    }
                    $dataInfAv = [
                        $data->iProyectoId,

                        auth()->user()->iCredId,
                        null,
                        $data->auditoria->ip ?? null,
                        null,
                    ];
                    $respuesta = DB::select('EXEC inv.Sp_INS_informe_avanceXfechaInicioFinProyecto ?,   ?, ?, ?, ?', $dataInfAv);

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
                // return response()->json($data);
                break;

            case 'mantenimiento_gasto_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        $respuesta = DB::select('EXEC inv.Sp_DEL_gasto_proyecto ?', $data);
                    }
                    else {
                        $dataGuardar = [
                            $data->idProyecto,
                            $data->iHitoId,
                            $data->iRubroId,
                            $data->iActividadId,
                            $data->iCalendarioId,
                            $data->iPresupuestoId,
                            $data->iTipoDocGastoId,
                            $data->iPersId,
                            $data->cDocAprueba,
                            $data->cNroDocGasto,
                            $data->dtGasto,
                            $data->nGasto,
                            $data->cAccion,
                            $data->cDocRend,
                            $data->cRendDeta,
                            $data->cFueraFecha,
                            $data->iAvanceAct,

                            $data->cSigaAnoEje,
                            $data->cSigaSecEjec,
                            $data->cSigaTipoBien,
                            $data->cSigaNroPedido,
                            $data->cSigaFechaPedido,
                            $data->cSigaCodigoEstadoPed,
                            $data->cSigaMotivoPedido,
                            $data->cSigaCentroCosto,
                            $data->cSigaEmpleado,
                            $data->cSigaFteFto,
                            $data->cSigaActProy,
                            $data->cSigaTipoActProy,
                            $data->cSigaMetaPresupuestal,
                            $data->cSigaCodigoTarea,
                            $data->cSigaNroOrden,
                            $data->cSigaFechaCompra,
                            $data->cSigaNroRequer,
                            $data->cSigaSubTotalSoles,
                            $data->cSigaIgvSoles,
                            $data->cSigaTotalFactSoles,
                            $data->cSigaEstadoSiaf,
                            $data->cSigaExpSiaf,
                            $data->cSigaExpSiga,
                            $data->cSigaNroCertifica,
                            $data->cSigaNroRuc,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];

                        if ($data->idGastoProy ) {
                            array_unshift($dataGuardar, $data->idGastoProy);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_gasto_proyecto ?,    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,             ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_gasto_proyecto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                        }

                        /* -------- para avance tecnico de indicador de objetivo  gasto_avance_indicador---------------*/
                        // return response()->json($respuesta);
                        $listaGtAvIndId = "";
                        $dataGtAvIndicadores = $data->indicador??[];
                        foreach ($dataGtAvIndicadores as $gtAvInd) {
                            $dataObjEsp = [
                                ($respuesta[0]->iGastoId ? $respuesta[0]->iGastoId : $data->idGastoProy),
                                $gtAvInd->iIndicadorId,
                                $gtAvInd->iAvance,

                                auth()->user()->iCredId,
                                null,
                                $data->auditoria->ip ?? null,
                                null,
                            ];

                            if ($gtAvInd->iGastoAvanIndtId <> NULL) {
                                $listaGtAvIndId .= $gtAvInd->iGastoAvanIndtId . "*";
                                array_unshift($dataObjEsp, $gtAvInd->iGastoAvanIndtId);
                                $respuesta2 = DB::select('EXEC inv.Sp_UPD_gasto_avance_indicador ?,     ?, ?, ?,       ?, ?, ?, ?', $dataObjEsp);
                                // return 'editar';
                            } else {
                                $respuesta2 = DB::select('EXEC inv.Sp_INS_gasto_avance_indicador ?, ?, ?,        ?, ?, ?, ?', $dataObjEsp);
                                $listaGtAvIndId .= $respuesta2[0]->iGastoAvanIndtId . "*";
                                // return 'nuevo';
                            }
                        }

                        $listaGtAvIndId = substr($listaGtAvIndId, 0, -1);

                        if ($listaGtAvIndId <> "") {
                            $dataIdObj = [
                                $listaGtAvIndId,
                                ($respuesta[0]->iGastoId ? $respuesta[0]->iGastoId : $data->idGastoProy),
                                2
                            ];
                            $respuesta2 = DB::select('EXEC inv.Sp_DEL_objetivo ?, ?, ?', $dataIdObj);
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
                // return response()->json($data);
                break;


            case 'registrarPlanAvanTec':
                 // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //   $respuesta = DB::select('EXEC inv.Sp_DEL_proyectoXiProyectoId ?', $data );
                    } else {
                        if ($data->idProyecto) {
                            $dataActiAvanTec = $data->actividades;
                            foreach ($dataActiAvanTec as $actiAvanTec) {
                                $dataAvanTec = [
                                    $actiAvanTec->iActividadId,
                                    $actiAvanTec->iProyectoId,
                                    $actiAvanTec->iCantidad,
                                    $actiAvanTec->nCantPorcentaje,
                                    $actiAvanTec->iAvanceCantidad,
                                    $data->porcentajeAutomatico,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                $respuesta = DB::select('EXEC inv.Sp_UPD_actividad_planAvanceTecnico ?,     ?, ?, ?, ?, ?,         ?, ?, ?, ?', $dataAvanTec);
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
                        }
                    }
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                break;

            case 'mantenimiento_avance_tecnico_detalle':
                //  return response()->json($data);
                DB::beginTransaction();
                try {
                    if ($data->iAvanTecDetId && $data->accionBd == 'borrar') {
                        $dataE = [
                            $data->iAvanTecDetId,
                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null
                        ];
                        $respuesta = DB::select('EXEC inv.Sp_DEL_avance_tecnico_detalle ?,    ?, ?, ?, ?', $dataE);
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
                // return response()->json($data);
                break;

            case 'mantenimiento_observacion':
                //  return response()->json($data);
                DB::beginTransaction();
                try {
                    if ($data->iObservacionHitoId && $data->accionBd == 'borrar') {
                        $dataE = [
                            $data->iObservacionHitoId,
                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null
                        ];
                        $respuesta = DB::select('EXEC inv.Sp_DEL_observacion_hito ?,    ?, ?, ?, ?', $dataE);
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
                // return response()->json($data);
                break;
            case 'mantenimiento_convocatoria':
                //  return response()->json($data);
                DB::beginTransaction();
                try {
                    if ($data->idConvocatoria && $data->accionBd == 'borrar') {
                        $dataE = [
                            $data->idConvocatoria,
                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null
                        ];
                        $respuesta = DB::select('EXEC inv.Sp_DEL_convocatoria ?,    ?, ?, ?, ?', $dataE);
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
                // return response()->json($data);
                break;

            case 'mantenimiento_proyecto':
                //return response()->json($data);
                DB::beginTransaction();
                try {
                    if ($data->iProyectoId && $data->accionBd == 'borrar') {
                        $dataE = [
                            $data->iProyectoId,
                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null
                        ];
                        $respuesta = DB::select('EXEC inv.Sp_DEL_proyectoXiProyectoId ?,    ?, ?, ?, ?', $dataE);
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
                // return response()->json($data);
                break;



            case 'mantenimiento_observacion_hito':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        //       $respuesta = DB::select('EXEC inv.Sp_DEL_tipo_proyecto ?', $data );
                    } else {
                        $auditoriaNombreEq = $data->auditoria->nombre_equipo;
                        $auditoriaIp = $data->auditoria->ip;
                        $auditoriaMac = $data->auditoria->mac;

                        if ($data->observacion) {
                            $listaObsHt = "";
                            $dataObsHito = $data->observacion;
                            foreach ($dataObsHito as $obsHt) {
                                $dataObsdHt = [
                                    null, // $idInfoAvTec,
                                    $data->iHitoId,
                                    $obsHt->iTipoObservacionId,
                                    $obsHt->iEstadoObservacionId,
                                    $obsHt->dtFechaActa,
                                    $obsHt->cNumActa,
                                    $obsHt->cLugar,
                                    $obsHt->cRecomendacion,
                                    $obsHt->cResultado ?? null,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip ?? null,
                                    null,
                                ];
                                if ($obsHt->iObservacionHitoId <> NULL) {
                                    $listaObsHt .=  $obsHt->iObservacionHitoId . "*";
                                    array_unshift($dataObsdHt, $obsHt->iObservacionHitoId);
                                    //   return response()->json($dataAvIndHt);
                                    $respuesta = DB::select('EXEC inv.Sp_UPD_observacion_hito ?,     ?, ?, ?, ?, ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataObsdHt);
                                    // return 'editar';
                                } else {
                                    //  return response()->json($dataAvIndHt);
                                    $respuesta = DB::select('EXEC inv.Sp_INS_observacion_hito ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataObsdHt);
                                    $listaObsHt .= $respuesta[0]->iObservacionHitoId . "*";
                                    // return 'nuevo';
                                }
                            }
                            $listaObsHt = substr($listaObsHt, 0, -1);
                            if ($listaObsHt <> "") {
                                $dataObs = [
                                    $listaObsHt,
                                    $data->iHitoId
                                ];
                                $respuesta2 = DB::select('EXEC inv.Sp_DEL_observacion_hito ?, ?', $dataObs);
                            }
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
                // return response()->json($data);
                break;

            case 'enviar_informe_avance_tecnico':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            $data->iHitoId,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iInfoAvTecId) {
                            array_unshift($dataGuardar, $data->iInfoAvTecId);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_informe_avance_tecnico_enviar ?, ?,     ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
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
                // return response()->json($data);
                break;

            case 'revisar_informe_avance_tecnico':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            $data->iEstadoRevisionId,
                            $data->iHitoId,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iInfoAvTecId) {
                            array_unshift($dataGuardar, $data->iInfoAvTecId);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_informe_avance_tecnico_revisar ?, ?, ?,    ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
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
                // return response()->json($data);
                break;

            case 'archivar_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            2, //$data->iEstadoPropuesta,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iProyectoId) {
                            array_unshift($dataGuardar, $data->iProyectoId);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_proyecto_archivar ?,      ?,       ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
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
                // return response()->json($data);
                break;
            case 'actualizar_requisitos_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            $data->iEstadoRequisito,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iProyectoId) {
                            array_unshift($dataGuardar, $data->iProyectoId);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_proyecto_requisitos ?,      ?,       ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
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
                // return response()->json($data);
                break;

            case 'terminar_postulacion_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            1, //$data->iEstadoPropuesta,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iProyectoId) {
                            array_unshift($dataGuardar, $data->iProyectoId);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_proyecto_archivar ?,      ?,       ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
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
                // return response()->json($data);
                break;

            /******mi case     */
            case 'distribucion_presupuesto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                      //  $respuesta = DB::select('EXEC inv.Sp_DEL_presupuestoXresolucion ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->descripcion,
                            /**documento aprobatorio */
                            /*****mis datos de mi input del fronted */

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];

                        if ($data->idTipoProy) {
                            /****id presupuesto */
                            /****esto muestra los id en la lista */
                            array_unshift($dataGuardar, $data->idTipoProy);

                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_presupuestoXresolucion ?,     ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_presupuesto ?,      ?, ?, ?, ?', $dataGuardar);
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
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
                return response()->json($jsonResponse);
            case 'monitores_proyecto_estado':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                    } else {
                        $dataGuardar = [
                            $data->cEstadoMonitor,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iMonitorProyectoId) {
                            array_unshift($dataGuardar, $data->iMonitorProyectoId);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_monitor_proyecto_estado ?,      ?,        ?, ?, ?, ?', $dataGuardar);
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
                // return response()->json($data);
                break;
            case 'monitores_proyecto':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                        // $respuesta = DB::select('EXEC inv.Sp_UPD_monitor_proyecto_estado ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->iProyectoId,
                            $data->iMonitorId,
                            $data->cDocAsignacion,
                            Carbon::parse($data->dtFechaAsignacion)->format('Y-m-d H:i:s'),

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];
                        if ($data->iMonitorProyectoId) {
                            array_unshift($dataGuardar, $data->iMonitorProyectoId);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_monitor_proyecto ?,     ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar);
                        } else {
                            $respuesta = DB::select('EXEC inv.Sp_INS_monitor_proyecto ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar);
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
                // return response()->json($data);
                break;
        }

        return response()->json($jsonResponse);
    }

    private function returnError($e)
    {
        $msgResuelto = '';
        if (isset($e->errorInfo)) {
            $msgResuelto = substr($e->errorInfo[2], 54); //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
        }

        $jsonResponse = [
            'error' => true,
            'msg' => $msgResuelto,
            //'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode(),
            'errorLaravel' => $e->getMessage(),
            'data' => null
        ];
        return $jsonResponse;
    }

    public function genPDF(Request $request)
    {

    }

    public function generarPDF(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');

        $data = json_decode(json_encode($data));

        $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        header("Access-Control-Allow-Origin: *");

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Antonio Salas');
        $pdf->SetTitle('Reportes UNAM');
        $pdf->SetSubject('Tramites - UNAM');
        $pdf->SetKeywords('UNAM, Moquegua, Ilo, EPISI');

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 5, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);


        $dataTramite = null;
        switch ($req) {
            case 'por_recepcionar':
                $pdf->dependenciaPadre = "SECRETARÍA GENERAL";
                $setDep = true;
                //$pdf->dependencia = ;

                $dependenciasPendientes = DB::select('EXEC tram.Sp_SEL_dependencias_tramites_pendientes_por_recepcionarXiDepenEmisorId ?', $data);
                // dd($dependenciasPendientes);

                $htmlStyles = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    th {
        background-color: #a9a9a9;
        font-weight: bold;
        text-align: center;
    }
    td {
        font-size: 85%;
        height: 70px;
    }

    .negrita {
        font-weight: bold;
    }
    .centrado {
        text-align: center;
    }
</style>
EOF;

                $anchoColumnas = [
                    'REG' => 40,
                    'FEC' => 70,
                    'TDOC' => 155,
                    'TDOC_T' => 75,
                    'TDOC_N' => 80,
                    'REM' => 120,
                    'FOL' => 30,
                    'ASU' => 180,
                    'PROV_DOC' => 70,
                    'DEST' => 70,
                    'FEC_SAL' => 70,
                    'FIRM' => 100,
                    'OBS' => 50,
                ];


                $htmlListaTramites['header'] = '<thead>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REG'] . '">N° REG</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FEC'] . '">FEC. RECEPCION</th>';
                $htmlListaTramites['header'] .= '<th colspan="2" width="' . $anchoColumnas['TDOC'] . '">DOCUMENTO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REM'] . '">REMITENTE</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FOL'] . '">FOL.</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['ASU'] . '">ASUNTO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['PROV_DOC'] . '">PROVEIDO <br> DOC. SALIDA </th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['DEST'] . '">DESTINO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FEC_SAL'] . '">FEC. SALIDA</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FIRM'] . '">FIRMA</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['OBS'] . '">OBS</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_T'] . '">TIPO</th>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_N'] . '">NUMERO</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '</thead>';


                $htmlListaTramites['body'] = '<tbody>';

                foreach ($dependenciasPendientes as $dep) {
                    /*
                     *
  +"iDepenReceptorId": "3"
  +"cDepenNombre": "ORGANO DE CONTROL INSTITUCIONAL"
  +"iCantidad_Tramites": "1"
                     */

                    if (!is_null($dep->iDepenReceptorId)) {
                        $data[1] = $dep->iDepenReceptorId;
                        $tramDepen = DB::select('EXEC tram.Sp_SEL_tramites_pendientes_por_recepcionarXiDepenEmisorIdXiDepenReceptorId ?, ?', $data);

                        foreach ($tramDepen as $tram) {
                            // dd($tram);
                            if ($setDep) {
                                $pdf->dependencia = $tram->cDepenEmisorNombre;
                                $setDep = false;
                            }

                            $htmlListaTramites['body'] .= '<tr>';
                            $htmlListaTramites['body'] .= '<td valign="middle" class="negrita centrado" width="' . $anchoColumnas['REG'] . '">' . $tram->iTramNumRegistro . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC'] . '">' . Carbon::parse($tram->dtTramFechaDocumento)->format('d/m/Y H:i') . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_T'] . '">' . $tram->cTipoDocDescripcion . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_N'] . '">' . preg_replace('/(.*-\d{4})-(.*)/', '$1<br>$2', str_replace($tram->cTipoDocDescripcion . ' ', '', $tram->cTramDocumentoTramite)) . '</td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['REM'] . '">' . $tram->cAbrev_Emisor . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FOL'] . '">' . $tram->iTramFolios . '</td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['ASU'] . '">' . $tram->cTramAsunto . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['PROV_DOC'] . '">' . $tram->cTramMovObsEnvio . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['DEST'] . '">' . $tram->cDepenReceptorAbrev . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC_SAL'] . '">' . Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y') . '</td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['FIRM'] . '"></td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['OBS'] . '"></td>';
                            $htmlListaTramites['body'] .= '</tr>';

                        }
                    }


                }
                $htmlListaTramites['body'] .= '</tbody>';

                $pdf->SetFont($roboto_regular, '', 9, '', 'default', true);
                $htmlPrintTable = $htmlStyles;
                $htmlPrintTable .= '<table cellspacing="0" cellpadding="3" border="1">' . $htmlListaTramites['header'] . $htmlListaTramites['body'] . '</table>';


                //dd($dependenciasPendientes);

                //


                $pdf->AddPage('L', 'A4');
                $htmlFooter = '<p style="font-size: 11px; text-align: justify;">Reporte generado por el Módulo de Tramite Documentario.<br>';
                $htmlFooter .= 'SIGEUN</p>';

                $pdf->addHtmlFooter = $htmlFooter;
                $pdf->writeHTML($htmlPrintTable, true, false, false, true, '');

                break;
        }


        // print colored table
        // $pdf->ColoredTable($header, $data,$funcion);
        ob_end_clean();
        // ---------------------------------------------------------
        $pdf->Output('unam-SIGEUN.pdf', 'I');
        //return $pdf;

    }

    public function prueba()
    {
        $url = "https://ws5.pide.gob.pe/Rest/Reniec/Consultar?nuDniConsulta=43177406&nuDniUsuario=41395590&nuRucUsuario=20449347448&password=41395590";
        $result = file_get_contents($url, false);
        dd($result);
    }

    public function actualizarDatosReniec($FN)
    {
        return 'ffffff';

    }
}
