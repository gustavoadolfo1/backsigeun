<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DasaController extends Controller
{
    //
    public function getData(Request $request, $tipo) {
        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));
        switch ($tipo) {

            case 'filiales':
                $respuesta = DB::select('EXEC grl.Sp_SEL_filialesXiEntId 1');
                break;
            case 'carreras':
                $respuesta = DB::table('ura.carreras')->get();
                break;
            case 'filiales_carreras':
                $respuesta = DB::select('exec [ura].[Sp_GRAL_SEL_carrerasFiliales]');
                break;



            case 'unidades':
                $respuesta = collect(DB::select('EXEC ura.Sp_DOCE_SEL_Notas_DASA_MuestraUnidades ?, ?, ?, ?, ?, ?, ?', $data));
                break;
            case 'cursos_asistencia_general':
                $respuesta = collect(DB::select('[ura].[Sp_DOCE_SEL_Asistencia_DASA_MuestraNumero_Fechas_EfectuadasXFaltantes_Global__]'));
                if (isset($dataObj->filtro)) {
                    if (isset($dataObj->filtro->filial) && ($dataObj->filtro->filial != '')) {
                        $respuesta = $respuesta->filter(function ($item) use ($dataObj) {
                            // replace stristr with your choice of matching function
                            return false !== stristr($item->Sede, $dataObj->filtro->filial);
                        });
                        // $respuesta = $respuesta->where('Sede', 'LIKE', '%'.$dataObj->filtro->filial.'%');
                    }
                    if (isset($dataObj->filtro->carrera) && ($dataObj->filtro->carrera != '')) {
                        $respuesta = $respuesta->filter(function ($item) use ($dataObj) {
                            // replace stristr with your choice of matching function
                            return false !== stristr($item->Carrera, $dataObj->filtro->carrera);
                        });
                        //$respuesta = $respuesta->where('Carrera', 'LIKE', '%'.$dataObj->filtro->carrera.'%');
                    }
                    if (isset($dataObj->filtro->plan) && ($dataObj->filtro->plan != '')) {
                        $respuesta = $respuesta->where('Plan', $dataObj->filtro->plan);
                    }

                    $rpt = collect([
                        'items' => $respuesta->sortBy('Carrera')->values()->all(),
                    ]);

                    // $respuesta = $respuesta->get();
                }
                else {
                    $f_filiales = $respuesta->unique('Sede');
                    $f_carreras = $respuesta->unique('Carrera');
                    $f_planes = $respuesta->unique('Plan');
                    /*
                    $f_filiales = $respuesta->unique(function($item) {
                        return $item->Sede;
                    });
                    $f_carreras = $respuesta->unique(function($item) {
                        return $item->Carrera;
                    });
                    $f_planes = $respuesta->unique(function($item) {
                        return $item->Plan;
                    });
                    */
                    $rpt = collect([
                        'items' => $respuesta,
                        'filtro' => [
                            'filiales' => $f_filiales->values()->all(),
                            'carreras' => $f_carreras->values()->all(),
                            'planes' => $f_planes->values()->all(),
                        ]
                    ]);



                    /*
                    $respuesta->filtro->filiales = $f_filiales;
                    $respuesta->filtro->carreras = $f_carreras;
                    $respuesta->filtro->planes = $f_planes;
                    */
                }
                $respuesta = $rpt;
                break;
            case 'cursos_asistencia_x_idDocente':
                $respuesta = collect(DB::select('[ura].[Sp_DOCE_SEL_Asistencia_DASA_MuestraNumero_Fechas_EfectuadasXFaltantes_Docente__iDocenteID] ?', $data));
                break;

            case 'estado_cursos_reporte':

                if (isset($dataObj->filtro)) {
                    $filtros = [];
                    if (isset($dataObj->filtro->filial) && ($dataObj->filtro->filial != '')) {
                        //$respuesta = collect(DB::select('[ura].[Sp_DASA_VerEstadoCursos]'));
                        $filtros[] = $dataObj->filtro->filial;
                    }
                    else {
                        $filtros[] = 0;
                    }
                    if (isset($dataObj->filtro->carrera) && ($dataObj->filtro->carrera != '')) {
                        $filtros[] = $dataObj->filtro->carrera;
                    }
                    else {
                        $filtros[] = 0;
                    }
                    if (isset($dataObj->filtro->plan) && ($dataObj->filtro->plan != '')) {
                        $filtros[] = $dataObj->filtro->plan;
                    }
                    else {
                        $filtros[] = 0;
                    }
                    $respuesta = collect(DB::select('[ura].[Sp_DASA_VerEstadoCursos] ?, ?, ?', $filtros));
                    $dataOrigProcesoExcel = collect();
                    foreach ($respuesta as $dataDoc) {
                        $dataOrigProcesoExcel->push([
                            'Docente' => $dataDoc->docenteNombreCompleto,
                            'Condicion' => $dataDoc->cCondicionDsc,
                            'Curso' => $dataDoc->cursoNombre,
                            'Seccion' => $dataDoc->cSeccionDsc,
                            'Unidad_Nombre' => $dataDoc->unidadNombre,
                            'Unidad_Estado' => $dataDoc->unidadEstado,
                            'Sustitutorio' => $dataDoc->cursoSustitutorio,
                            'Asistencia' => ($dataDoc->asistenciasPendientes == 0 ? 'COMPLETO' : ('Falta ' . $dataDoc->asistenciasPendientes . ' de ' . $dataDoc->asistenciasTotal)),
                            // 'General' => ($dataDoc->cursoSustitutorio == 'CERRADO' || ($dataDoc->cursoSustitutorio == 'NO TIENE' && $dataDoc->estadoUnidades == 'CERRADO')) ? 'CERRADO' : 'ABIERTO',
                            'General' => ($dataDoc->iCierreCurso == 1 ? 'ABIERTO' : 'CERRADO')

                        ]);
                    }

                    $retCol = collect();

                    foreach ($respuesta->groupBy(['docenteNombreCompleto', 'cursoNombre']) as $idx => $dat) {
                        $datDocente['docenteNombre'] = $idx;
                        $datDocente['docenteEstado'] = $dat->first()->first()->cCondicionDsc;
                        $datDocente['cursos'] = collect();
                        foreach ($dat as $idx2 => $data2) {
                            // return $this->retornoJson($data2);
                            $retCur['cursoNombre'] = $idx2;
                            $retCur['unidades'] = collect();
                            $cerradoUnidades = true;
                            $data2 = $data2->sortBy('cSeccionDsc');
                            foreach ($data2 as $data3) {
                                $retCur['unidades']->push([
                                    'nombre' => 'Sec '.$data3->cSeccionDsc.': ' . $data3->unidadNombre,
                                    'estado' => $data3->unidadEstado,
                                    'seccion' => $data3->cSeccionDsc,
                                ]);
                                if ($cerradoUnidades && $data3->unidadEstado == 'ABIERTO'){
                                    $cerradoUnidades = false;
                                }
                            }
                            // return response()->json($data2);

                            // $retCur['estadoUnidades'] = $cerradoUnidades ? 'CERRADO' : 'ABIERTO';
                            $retCur['estadoUnidades'] = ($data2->first()->iCierreCurso == 1 ? 'ABIERTO' : 'CERRADO');
                            $retCur['cursoSustitutorio'] = $data2->first()->cursoSustitutorio;

                            $retCur['estadoAsistencia'] = ($data2->first()->asistenciasPendientes == 0 ? 'COMPLETO' : ('Falta ' . $data2->first()->asistenciasPendientes . ' de ' . $data2->first()->asistenciasTotal));
                            if ($retCur['cursoSustitutorio'] == 'CERRADO' || ($retCur['cursoSustitutorio'] == 'NO TIENE' && $retCur['estadoUnidades'] == 'CERRADO')) {
                                $retCur['estadoGeneral'] = 'CERRADO';
                            }
                            else {
                                $retCur['estadoGeneral'] = 'ABIERTO';
                            }
                            // return $this->retornoJson($retCur);
                            $retCur['numUnidades'] = $retCur['unidades']->count();
                            $datDocente['cursos']->push($retCur);
                        }

                        $datDocente['numCursos'] = $datDocente['cursos']->count();
                        $retCol->push($datDocente);
                        // return $this->retornoJson($datDocente);
                    }

                    $rpt = collect([
                        // 'items' => $respuesta->groupBy(['iDocenteId', 'iCurricCursoId']),
                        // 'items' => $respuesta->groupBy(['docenteNombreCompleto', 'cursoNombre'])->toArray(),
                        'items' => $retCol,
                        'original' => $dataOrigProcesoExcel,
                    ]);

                    // $respuesta = $respuesta->get();
                }
                else {
                    // $respuesta = collect(DB::select('[ura].[Sp_DASA_VerEstadoCursos]'));
                    $rpt = collect([
                        'items' => [],
                        'original' => [],
                    ]);
                }
                $respuesta = $rpt;

                break;
        }
        return $this->retornoJson($respuesta);
    }

    public function setData(Request $request, $tipo) {
        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));

        $jsonResponse = [];
        DB::beginTransaction();
        try {
            switch ($tipo) {
                case 'aperturar_unidad':
                    $respuesta = collect(DB::select('EXEC ura.Sp_DOCE_SEL_Notas_DASA_MuestraUnidades_Apertura ?, ?, ?, ?, ?, ?, ?, ?', $data))->first();
                    if (isset($respuesta->iResult) && $respuesta->iResult != 0) {
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
                    break;
                case 'aperturar_curso':
                    $respuesta = collect(DB::select('EXEC ura.Sp_DOCE_UPD_Notas_DASA_Curso_Apertura ?, ?, ?, ?, ?, ?', $data))->first();
                    if (isset($respuesta->iResult) && $respuesta->iResult != 0) {
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
                    break;
            }
            DB::commit();
        }
        catch (\Exception $e) {
            $jsonResponse = $this->returnError($e);
            DB::rollback();
        }
        return $this->retornoJson($jsonResponse);
    }

    private function retornoJson($data){
        return response()->json($data);
    }
    public static function returnError($e){
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
}
