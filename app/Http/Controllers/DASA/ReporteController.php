<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\UraCurriculaCursoDetalle;
use Illuminate\Support\Facades\DB;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PDF;
use App\Http\Controllers\Tram\TramitesController;

use Hashids\Hashids;

class ReporteController extends Controller

{

    /**
     * Reportes Matriculados
     */
    public function obtenerReporteMatriculados($cicloAcad)
    {
        $matriculados = DB::select('exec ura.[Sp_ESTUD_SEL_estudiantes_matriculadosXcicloAcad] ?', array($cicloAcad));

        $proformas = DB::select('exec ura.[Sp_ESTUD_SEL_estudiantes_proformasXcicloAcad] ?', array($cicloAcad));

        $total = DB::select('exec ura.[Sp_ESTUD_SEL_count_matriculados]');

        return response()->json(['matriculados' => $matriculados, 'proformas' => $proformas, 'total' => $total[0]->total]);
    }

    public function matriculadosPorCurso($carrFilId, $curricID)
    {
        $matriculas = DB::select('exec [ura].[Sp_DASA_SEL_matriculadosPorCurso] ?, ?', array($carrFilId, $curricID));

        $hashids = new Hashids('SIGEUN UNAM', 15);

        foreach ($matriculas as $row) {
            $row->hashedId = $hashids->encode($row->iCurricCursoId, $row->iSeccionId, $row->iFilId, $row->iDocenteId, $row->iControlCicloAcad);
        }

        return response()->json($matriculas);
    }

    public function matriculadosPorCursoFilial($carreraId, $filialId, $curricID, $semestre)
    {
        $matriculas = DB::select('exec [ura].[Sp_DASA_SEL_matriculadosPorCursoXiCarrFilIdXiFilIdXiCurricId] ?, ?, ?,?', array($carreraId, $filialId, $curricID,$semestre));

        return response()->json($matriculas);
    }

    public function matriculadosPorCursoFilialDetallado(Request $request)
    {
        #@cCurricCursoCod varchar(25), @iSeccionId
        $matriculas = DB::select('exec [ura].[Sp_DASA_SEL_estudiantesMatriculadosCursos] ?, ?, ?, ?, ?', array($request->filialId, $request->carreraId, $request->curricId, $request->cursoCod, $request->seccionId));

        if (count($matriculas) > 0) {
            $secciones = json_decode($matriculas[0]->json_secciones);
            foreach ($matriculas as $matricula) {
                $matricula->secciones = $secciones;
            }
        }
        return response()->json($matriculas);
    }

    public function matriculadosPorCarrera($carreraId, $semestre)
    {
        $matriculas = DB::select('exec ura.Sp_SEL_Estudiantes_MatriculadosXiCarreraIdXiSemestre ?, ?', array($carreraId, $semestre));

        return response()->json($matriculas);
    }

    public function matriculadosPorSemestre($semestre)
    {
        $matriculas = DB::select('exec ura.Sp_SEL_Estudiantes_MatriculadosXiCarreraIdXiSemestre ?', array($semestre));

        return response()->json($matriculas);
    }

    public function obtenerHorariosDocentes($filCarreraId, $curricId, $cicloAcad)
    {
        $horarios = DB::select('exec ura.[Sp_DOCE_SEL_horarioClasesXiFilialCarreraIdXiCurrIdXiControlCicloAcad] ?, ?, ?', array($filCarreraId, $curricId, $cicloAcad));

        return response()->json($horarios);
    }
    public function matriculadosPorSemestreAll($semestre)
    {
        $matriculas = DB::select('exec ura.Sp_SEL_Carrera_Profesionales_Cantidad_MatriculadosXiSemestre ?', array($semestre));

        return response()->json($matriculas);
    }
    public function matriculadosPorCursoCarrera($filialId, $semestre, $carreraId, $curriId)
    {
        $matriculas = DB::select('exec ura.Sp_SEL_MatriculadosPorCursoXiFilIdXiSemestreXiCarreraIdXiCurricId ?,?,?,?', array($filialId, $semestre, $carreraId, $curriId));
        return response()->json($matriculas);
    }
    public function matriculadosPorIngresantes()
    {
        $ingresantesModalidad = DB::select('exec ura.Sp_SEL_Cantidad_Ingresantes_Por_Modalidad_PIVOT');
        $ingresantesCarrera = DB::select('exec ura.Sp_SEL_Cantidad_Ingresantes_Por_Escuela');
        return response()->json(['modalidad' => $ingresantesModalidad, 'carreras' => $ingresantesCarrera]);
    }

    public function matriculadosPorSemestreCarreraFilialCiclo($iSemestre, $iCarreraId, $iFilId, $cCiclo)
    {
        $matriculas = DB::select('exec ura.Sp_SEL_Estudiantes_Carrera_Profesionales_Cantidad_MatriculadosXiSemestre ?,?,?,?', array($iSemestre, $iCarreraId, $iFilId, $cCiclo));

        return response()->json($matriculas);
    }
    public function ReporteRelacionDocentes($iFilId, $iCarreraId, $iSemestre)
    {
        $matriculas = DB::select('exec ura.Sp_SEL_Relacion_Docentes_por_EscuelaXiFilIdXiCarreraIdXiSemestre ?,?,?', array($iFilId, $iCarreraId, $iSemestre));

        return response()->json($matriculas);
    }
    public function ReporteDetallesMatriculadosXCurso(Request $data)
    {
        $iFilId = $data->iFilId;
        $iSemestre = $data->iSemestre;
        $iCarreraId = $data->iCarreraId;
        $iCurricId = $data->iCurricId;
        $cCurricCursoCod = $data->cCurricCursoCod;
        $iSeccionId = $data->iSeccionId;


        $matriculas = DB::select('exec ura.Sp_SEL_EstudiantesXiFilIdXiSemestreXiCarreraIdXiCurricIdXcCurricCursoCodXiSeccionId ?,?,?,?,?,?', array($iFilId, $iSemestre, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId));
        return response()->json($matriculas);
    }

    public function ingresantesPorModalidadYSemestreIngreso($modalidadCod, $semeIngre, $tipoReturn, $tipo = null)
    {
        $data = DB::select('exec ura.Sp_SEL_Estudiantes_Ingresantes_Por_ModalidadXcModalidadCodXcEstudSemeIngre ?, ?', array($modalidadCod, $semeIngre));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::ingresantesPorModalidadYSemestreIngreso($data, $tipo);
        }
    }

    public function obtenerPlanEstudiosPorCarreraYCurricula($carreraId, $curricId, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[sp_selPlanEstudios_x_carreraId_curricId] ?, ?", array($carreraId, $curricId));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            //return ExportReporteController::obtenerIngresantesMatriculados($data, $request->tipo, $request->all());
        }
    }

    public function obtenerPlanEstudiosEquivalente($carreraId, $curricId, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[Sp_ESCU_REP_planEstudioEquivalenteXiControlCicloAcadXiCarreraIdXiCondicion] ?, ?", array($carreraId, $curricId));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            //return ExportReporteController::obtenerIngresantesMatriculados($data, $request->tipo, $request->all());
        }
    }

    public function getMatriculadosPorNumeroMatricula($semestre, $numMatricula, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_matriculadosPoriMatriculas_ResumenXiControlCicloAcadXiMatriculas] ?, ?", array($semestre, $numMatricula));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::getMatriculadosPorNumeroMatricula($data, $tipo, ['semestre' => $semestre, 'numMatricula' => $numMatricula]);
        }
    }

    public function getMatriculadosNumMatriculaDetallado(Request $request)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_matriculadosPoriMatriculas_DetallesXiControlCicloAcadXiMatriculas] ?, ?, ?", array($request->semestre, $request->carreraId, $request->numMatricula));

        if ($request->tipoReturn == 'json') {
            return response()->json($data);
        } else {
            //return ExportReporteController::obtenerIngresantesMatriculados($data, $request->tipo, $request->all());
        }
    }

    public function getOrdenMeritoModo1(Request $request)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_Estudiantes_Orden_MeritoSuperiorIngresantesXiCarreraIdXcSemestreIngresoXcSemestreActual] ?, ?, ?, ?", array($request->carreraId, $request->semestreIngre, $request->semestreActual, $request->categoriaMerito));

        if ($request->tipoReturn == 'json') {
            return response()->json($data);
        } else {
            //return ExportReporteController::obtenerIngresantesMatriculados($data, $request->tipo, $request->all());
        }
    }

    public function getOrdenMeritoModo2(Request $request)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_Estudiantes_Orden_MeritoXiCarreraIdXcSemestre] ?, ?, ?", array($request->carreraId, $request->semestre, $request->categoriaMerito));

        if ($request->tipoReturn == 'json') {
            return response()->json($data);
        } else {
            //return ExportReporteController::obtenerIngresantesMatriculados($data, $request->tipo, $request->all());
        }
    }

    public function getRecojoInfoMINEDU($tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_recojoInformacion_MINEDU]");

        $carreras = DB::table('ura.carreras')->select('iCarreraId', 'cCarreraDsc')->where('iProgramasAcadId', 1)->get();

        foreach ($data as $row) {
            foreach ($carreras as $carrera) {
                if ($row->iCarreraId == $carrera->iCarreraId) {
                    $carrera->data[] = $row;
                    break;
                }
            }
        }

        if ($tipoReturn == 'json') {
            return response()->json($carreras);
        } else {
            return ExportReporteController::getRecojoInfoMINEDU($carreras, $tipo);
        }
    }

    public function getBachilleresOTitulados($tipoGrado, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec ura.Sp_DASA_REP_bachilleres_tituladosResumenXcTipoGrado ?", array($tipoGrado));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::getBachilleresOTitulados($data, $tipoGrado, $tipo);
        }
    }

    public function getBachilleresOTituladosDetallado($tipoGrado, $carreraId, $year, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec ura.Sp_DASA_REP_bachilleres_tituladosDetalladoXcTipoGrado ?, ?, ?", array($tipoGrado, $carreraId, $year));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::getBachilleresOTituladosDetallado($data, $tipoGrado, $year, $tipo);
        }
    }

    public function getEgresadosDetallado($carreraId, $semestre, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec ura.Sp_DASA_REP_egresadosDetalladoXiCarreraIdXiCicloAcademico ?, ?", array($carreraId, $semestre));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::getEgresadosDetallado($data, $tipo, $semestre);
        }
    }

    public function getRelacionEgresadosBachilleresTitulados($carreraId, $semestre, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec ura.Sp_DASA_REP_egresadosBachillerTituladoDetalladoXiCarreraIdXiCicloAcademico ?, ?", array($carreraId, $semestre));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::getRelacionEgresadosBachilleresTitulados($data, $tipo, $carreraId, $semestre);
        }
    }

    public function getSituacionRacionalizacion($iControlCicloAcad, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_racionalizacionSituacionXiControlCicloAcad] ?", array($iControlCicloAcad));

        foreach ($data[0] as $index => $row) {
            $data[0]->$index = json_decode($row);
        }

        if ($tipoReturn == 'json') {
            return response()->json($data[0]);
        } else {
            return ExportReporteController::getSituacionRacionalizacion($data[0], $iControlCicloAcad, $tipo);
        }
    }

    public function getReporteSIRIES($semestre, $tipoReturn, $tipo = null)
    {
        $data = DB::select("exec ura.Sp_DASA_SEL_reporteSIRIUSXiControlCicloAcad ?", array($semestre));

        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            return ExportReporteController::getReporteSIRIES($data, $tipo, $semestre);
        }
    }

    public function getResumenEgresados($tipoReturn, $tipo = null)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_resumenEgresados]");

        $escuelas = [];

        foreach ($data as $row) {
            $escuelas[$row->cCarreraDsc][$row->cEstudSemeEgresado] = $row->iTotal;
        }

        $data = ['escuelas' => [], 'years' => []];
        $lastRow = [];

        $count = 0;
        $total = 0;

        foreach ($escuelas as $key => $escuela) {
            $data['escuelas'][$count]['cCarrera'] = $key;
            $data['escuelas'][$count]['total'] = 0;
            $data['years'] = [];
            for ($i = 2008; $i <= date('Y'); $i++) {
                $data['years'][] = $i;
                $data['escuelas'][$count]['cCarrera'] = $key;

                $iKey0 = $i . '0';
                $val0 = $escuela[$iKey0] ?? 0;
                $data['escuelas'][$count]['data'][$iKey0] = $val0;
                $data['escuelas'][$count]['total'] += (int) $val0;

                $iKey1 = $i . '1';
                $val1 = $escuela[$iKey1] ?? 0;
                $data['escuelas'][$count]['data'][$iKey1] = $val1;
                $data['escuelas'][$count]['total'] += (int) $val1;

                $iKey2 = $i . '2';
                $val2 = $escuela[$iKey2] ?? 0;
                $data['escuelas'][$count]['data'][$iKey2] = $val2;
                $data['escuelas'][$count]['total'] += (int) $val2;

                if ($count != 0) {
                    $lastRow[$iKey0] += $val0;
                    $lastRow[$iKey1] += $val1;
                    $lastRow[$iKey2] += $val2;
                }
            }
            $total += $data['escuelas'][$count]['total'];

            if ($count == 0) {
                $lastRow = $data['escuelas'][$count]['data'];
            }
            $count++;
        }

        $data['escuelas'][$count]['cCarrera'] = 'TOTAL';
        $data['escuelas'][$count]['data'] = $lastRow;
        $data['escuelas'][$count]['total'] = $total;


        if ($tipoReturn == 'json') {
            return response()->json($data);
        } else {
            //return ExportReporteController::getSituacionRacionalizacion($data[0], $iControlCicloAcad, $tipo);
        }
    }

    public function getCabecerasActasEvalExtraordinaria($carreraId, $filialId, $cicloAcad)
    {
        $data = DB::select("exec [ura].[Sp_DASA_SEL_CabecerasActasEvalExtraordinaria] ?, ?, ?", [$carreraId, $filialId, $cicloAcad]);

        return response()->json($data);
    }

    public function ExportMatriculadoxCurso($carreraId, $filialId, $curricId, $cursoCod, $seccionId)
    {
        header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Methods: *");


        $reporte = DB::select('exec [ura].[Sp_DASA_SEL_estudiantesMatriculadosCursos] ?, ?, ?, ?, ?', array($filialId, $carreraId, $curricId, $cursoCod, $seccionId));

        Excel::create('LISTA', function ($excel) use ($reporte) {



            $excel->sheet('Asistencia', function ($sheet) use ($reporte) {

                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:M1");
                $sheet->cells('B1:M1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C3', 'DOCENTE');
                //$sheet->setCellValue('C4', 'CARRERA');
                $sheet->setCellValue('C5', 'CURSO');
                $sheet->setCellValue('C6', 'SECCION');

                $sheet->setCellValue('I5', 'CODIGO CURSO');
                $sheet->setCellValue('I6', 'PLAN');

                $sheet->cells('C3:D6', function ($cells) {
                    $cells->setAlignment('right');
                });

                $sheet->cells('I5:J6', function ($cells) {
                    $cells->setAlignment('right');
                });

                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->getStyle('I5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);



                $sheet->mergeCells("C3:D3");
                $sheet->mergeCells("C4:D4");
                $sheet->mergeCells("C5:D5");
                $sheet->mergeCells("C6:D6");

                $sheet->mergeCells("I5:J5");
                $sheet->mergeCells("I6:J6");

                $sheet->setCellValue('E3', $reporte[0]->cDocente);
                //$sheet->setCellValue('E4',$reporte[0]->Carrera);
                $sheet->setCellValue('E5', $reporte[0]->cCurricCursoDsc);
                $sheet->setCellValue('E6', $reporte[0]->cSeccionDsc);

                $sheet->setCellValue('K5', $reporte[0]->cCurricCursoCod);
                $sheet->setCellValue('K6', $reporte[0]->cPlan);

                $sheet->mergeCells("E3:H3");
                $sheet->mergeCells("E4:H4");
                $sheet->mergeCells("E5:H5");
                $sheet->mergeCells("E6:H6");

                $sheet->cells('K6', function ($cells) {

                    $cells->setAlignment('left');
                });

                $data = json_decode(json_encode($reporte), true);

                $sheet->setCellValue('C8', 'MATRICULADOS SECCIÓN "' . $reporte[0]->cSeccionDsc . '"');
                $sheet->getStyle('C8')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("C8:M8");
                $sheet->cells('C8:M8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });


                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'CODIGO');
                $sheet->setCellValue('E9', 'APELLIDOS');
                $sheet->setCellValue('H9', 'NOMBRES');
                $sheet->setCellValue('I9', 'NOMBRES');
                $sheet->setCellValue('J9', 'MODALIDAD');
                $sheet->setCellValue('K9', '# MAT');
                $sheet->setCellValue('L9', 'INGRESO');
                $sheet->setCellValue('M9', 'ESTADO');

                $sheet->setWidth(array(
                    'A' => 1,
                    'D' => 15,
                    'H' => 15,
                    'I' => 15,
                    'J' => 45,
                    'K' => 15,
                    'L' => 15,
                    'M' => 15,
                ));

                $sheet->cells('C9:M9', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('L9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);



                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");

                foreach ($reporte as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;
                    $h = "H" . $x;

                    $j = "J" . $x;
                    $k = "K" . $x;
                    $l = "L" . $x;
                    $m = "M" . $x;

                    $sheet->cells('C' . $x . ':D' . $x, function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setFontSize(9);
                    });
                    $sheet->cells('J' . $x . ':M' . $x, function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setFontSize(9);
                    });

                    $sheet->setCellValue($c, $key + 1);
                    $sheet->setCellValue($d, $value->cMatricCodUniv);
                    $sheet->setCellValue($e, $value->cPersPaterno . ' ' . $value->cPersMaterno);
                    $sheet->mergeCells($e . ":" . $g);
                    $sheet->setCellValue($h, $value->cPersNombre);
                    $sheet->setCellValue($j, $value->cModalDsc);
                    $sheet->setCellValue($k, $value->iNumMatricula);
                    $sheet->setCellValue($l, $value->cSemestre_Ingreso);
                    $sheet->setCellValue($m, $value->cTipoEstudiante);


                    # code...
                }

                //$sheet->fromArray($data,'A5');
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');


        //return response()->json( $matriculas );
    }

    public function cantidadDocentesEscuela($cicloAcad, $condicionId)
    {
        $docentes = DB::select('exec [ura].[Sp_DASA_REP_Cantidad_Docentes_Por_EscuelaXiSemestreXiCondicionId] ?, ?', array($cicloAcad, $condicionId));

        return response()->json($docentes);
    }
    //REPORTE DASA: Matriculados por semestre académico
    public function MatriculadoSemestreExcel($semestre)
    {
        $reporte = DB::select('exec ura.Sp_SEL_Carrera_Profesionales_Cantidad_MatriculadosXiSemestre ?', array($semestre));

        header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Methods: *");

        Excel::create('REPORTE', function ($excel) use ($reporte, $semestre) {



            $excel->sheet('MATRICULADOS POR CICLO', function ($sheet) use ($reporte, $semestre) {

                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:Q1");
                $sheet->cells('B1:Q1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);

                $sheet->setCellValue('C3', 'SEMESTRE');
                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->setCellValue('D3', $semestre);

                $sheet->cells('D3', function ($cells) {

                    $cells->setAlignment('left');
                });

                $sheet->setCellValue('C5', 'REPORTE ');

                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("C5:P5");
                $sheet->cells('C5:M5', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });

                $sheet->setCellValue('C6', 'CANTIDAD MATRICULADOS POR CICLO DE ESTUDIOS ');
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("C6:P6");
                $sheet->cells('C6:M6', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });


                $sheet->setCellValue('C7', 'N°');
                $sheet->setCellValue('D7', 'ESCUELA PROFESIONAL');
                $sheet->setCellValue('E7', 'SEDE');
                $sheet->setCellValue('F7', 'I');
                $sheet->setCellValue('G7', 'II');
                $sheet->setCellValue('H7', 'III');
                $sheet->setCellValue('I7', 'IV');
                $sheet->setCellValue('J7', 'V');
                $sheet->setCellValue('K7', 'VI');
                $sheet->setCellValue('L7', 'VII');
                $sheet->setCellValue('M7', 'VIII');
                $sheet->setCellValue('N7', 'IX');
                $sheet->setCellValue('O7', 'X');
                $sheet->setCellValue('P7', 'TOTAL');

                $sheet->setWidth(array(
                    'C' => 15,
                    'D' => 40,
                    'E' => 20,
                    'F' => 8,
                    'G' => 8,
                    'H' => 8,
                    'I' => 8,
                    'J' => 8,
                    'K' => 8,
                    'L' => 8,
                    'M' => 8,
                    'N' => 8,
                    'O' => 8,
                    'P' => 12,
                ));

                $sheet->cells('C7:P7', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('F7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('G7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('L7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('N7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('O7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('P7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->cells('C7', function ($cells) {

                    $cells->setAlignment('center');
                });
                $s = 0;
                $t = 0;
                foreach ($reporte as $key => $value) {
                    $x = ($key + 8);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;
                    $h = "H" . $x;
                    $i = "I" . $x;
                    $j = "J" . $x;
                    $k = "K" . $x;
                    $l = "L" . $x;
                    $m = "M" . $x;
                    $n = "N" . $x;
                    $o = "O" . $x;
                    $p = "P" . $x;

                    /*
                    $sheet->cells('C'.$x.':D'.$x, function ($cells) {
                       $cells->setAlignment('center');
                       $cells->setFontSize(9);
                    });
                    $sheet->cells('J'.$x.':M'.$x, function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setFontSize(9);
                     });
                        */

                    $sheet->cells($c, function ($cells) {

                        $cells->setAlignment('center');
                    });
                    $sheet->setCellValue($c, $key + 1);
                    $sheet->setCellValue($d, $value->cCarreraDsc);
                    $sheet->setCellValue($e, $value->cFilDescripcion);
                    $sheet->setCellValue($f, $value->cCicloI);
                    $sheet->setCellValue($g, $value->cCicloII);
                    $sheet->setCellValue($h, $value->cCicloIII);
                    $sheet->setCellValue($i, $value->cCicloIV);
                    $sheet->setCellValue($j, $value->cCicloV);
                    $sheet->setCellValue($k, $value->cCicloVI);
                    $sheet->setCellValue($l, $value->cCicloVII);
                    $sheet->setCellValue($m, $value->cCicloVIII);
                    $sheet->setCellValue($n, $value->cCicloIX);
                    $sheet->setCellValue($o, $value->cCicloX);
                    $s = ($value->cCicloI + $value->cCicloII + $value->cCicloIII + $value->cCicloIV + $value->cCicloV + $value->cCicloVI + $value->cCicloVII + $value->cCicloVIII + $value->cCicloIX + $value->cCicloX);
                    $t = $t + $s;
                    $sheet->setCellValue($p, $s);




                    # code...
                }
                $x = $x + 1;
                $sheet->setCellValue('C' . $x, 'Total de Matriculados en el Semestre (Matricula Regular) ');
                $sheet->getStyle('C' . $x)->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->mergeCells('C' . $x . ':O' . $x);
                $sheet->cells('C' . $x . ':O' . $x, function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->setCellValue('P' . $x, $t);
                $sheet->getStyle('P' . $x)->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $x += 2;
                $sheet->setCellValue('C' . $x, 'CANTIDAD DE ALUMNOS CON RESERVA EN EL SEMESTRE ' . $semestre . ': ' . $reporte[0]->iReservas);
                $sheet->setCellValue('C' . ++$x, 'CANTIDAD DE ALUMNOS SIN CICLO REFERENCIAL ASIGNADO: ' . $reporte[0]->iRevisar . ' (Si dato mayor a 0, revisar...)');
                $total = (int) $reporte[0]->iReservas + (int) $t + (int) $reporte[0]->iRevisar;
                $sheet->setCellValue('C' . ++$x, 'TOTAL SEMESTRE ' . $semestre . ': ' . $total);
                //$sheet->fromArray($data,'A5');
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }



    public function MatriculadoCursoExcel($filialId, $semestre, $carreraId, $curriId)
    {
        $reporte = DB::select('exec ura.Sp_SEL_MatriculadosPorCursoXiFilIdXiSemestreXiCarreraIdXiCurricId ?,?,?,?', array($filialId, $semestre, $carreraId, $curriId));

        header("Access-Control-Allow-Origin: *");
        //header("Access-Control-Allow-Methods: *");

        Excel::create('REPORTE', function ($excel) use ($reporte) {



            $excel->sheet('MATRICULADOS POR CURSO ', function ($sheet) use ($reporte) {

                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:L1");
                $sheet->cells('B1:L1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);

                $sheet->setCellValue('C3', 'ESCUELA PROFESIONAL');
                $sheet->mergeCells("C3:D3");
                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->setCellValue('E3', $reporte[0]->cCarreraDsc);
                $sheet->mergeCells("E3:G3");
                $sheet->cells('E3', function ($cells) {
                    $cells->setAlignment('left');
                });

                //
                $sheet->setCellValue('C4', 'SEMESTRE');
                $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->setCellValue('D4', $reporte[0]->iControlCicloAcad);

                $sheet->cells('D4', function ($cells) {
                    $cells->setAlignment('left');
                });
                //
                $sheet->setCellValue('I3', 'PLAN');
                $sheet->getStyle('I3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->setCellValue('J3', $reporte[0]->cCurricAnio);

                $sheet->cells('J3', function ($cells) {
                    $cells->setAlignment('left');
                });
                //



                $sheet->setCellValue('C6', 'REPORTE ');
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("C6:K6");
                $sheet->cells('C6:K6', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });


                $sheet->setCellValue('C7', 'N°');
                $sheet->setCellValue('D7', 'CODIGO');
                $sheet->setCellValue('E7', 'CURSO');
                $sheet->setCellValue('F7', 'CURR.');
                $sheet->setCellValue('G7', 'CRED.');
                $sheet->setCellValue('H7', 'CICLO');
                $sheet->setCellValue('I7', 'SECC.');
                $sheet->setCellValue('J7', 'DOCENTE');
                $sheet->setCellValue('K7', 'TOTAL');


                $sheet->setWidth(array(
                    'C' => 15,
                    'D' => 15,
                    'E' => 50,
                    'F' => 8,
                    'G' => 8,
                    'H' => 8,
                    'I' => 8,
                    'J' => 50,
                    'K' => 8,
                    'L' => 8,

                ));

                $sheet->cells('C7:K7', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('F7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('G7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);


                $sheet->cells('C7', function ($cells) {

                    $cells->setAlignment('center');
                });

                $s = 0;
                $t = 0;
                foreach ($reporte as $key => $value) {
                    $x = ($key + 8);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;
                    $h = "H" . $x;
                    $i = "I" . $x;
                    $j = "J" . $x;
                    $k = "K" . $x;


                    /*
                    $sheet->cells('C'.$x.':D'.$x, function ($cells) {
                       $cells->setAlignment('center');
                       $cells->setFontSize(9);
                    });
                    $sheet->cells('J'.$x.':M'.$x, function ($cells) {
                        $cells->setAlignment('center');
                        $cells->setFontSize(9);
                     });
                        */

                    $sheet->cells($c, function ($cells) {

                        $cells->setAlignment('center');
                    });
                    $sheet->setCellValue($c, $key + 1);
                    $sheet->setCellValue($d, $value->cCurricCursoCod);
                    $sheet->setCellValue($e, $value->cCurricCursoDsc);
                    $sheet->setCellValue($f, $value->cCurricAnio);
                    $sheet->setCellValue($g, $value->nCurricDetCredCurso);
                    $sheet->setCellValue($h, $value->cCurricDetCicloCurso);
                    $sheet->setCellValue($i, $value->cSeccionDsc);
                    $sheet->setCellValue($j, $value->cNombreDocente);
                    $sheet->setCellValue($k, $value->iCantidad_Estudiantes);

                    $t = $t + $value->iCantidad_Estudiantes;


                    # code...
                }


                //$sheet->fromArray($data,'A5');
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }
    public function IngresantesModalidadEscuelaExcel()
    {
        $reporteA = DB::select('EXEC ura.Sp_SEL_Cantidad_Ingresantes_Por_Modalidad_PIVOT');
        $semestre = DB::table('ura.controles')->get();
        $reporteB = DB::select('exec ura.Sp_SEL_Cantidad_Ingresantes_Por_Escuela');

        header("Access-Control-Allow-Origin: *");


        Excel::create('REPORTE', function ($excel) use ($reporteA, $reporteB, $semestre) {



            $excel->sheet('MODALIDAD INGRESO', function ($sheet) use ($reporteA, $semestre) {

                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:O1");
                $sheet->cells('B1:O1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);


                $sheet->setCellValue('C5', 'REPORTE ');

                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("C5:N5");
                $sheet->cells('C5:N5', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });

                $sheet->setCellValue('C6', 'MODALIDADES ');
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("C6:N6");
                $sheet->cells('C6:N6', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });

                $sheet->setCellValue('P8', 'LEYENDA ');
                $sheet->mergeCells("P8:Q8");

                $sheet->setCellValue('P9', 'AO');
                $sheet->setCellValue('P10', 'PPP');
                $sheet->setCellValue('P11', 'DC');
                $sheet->setCellValue('P12', 'PCD');
                $sheet->setCellValue('P13', 'HCYT');
                $sheet->setCellValue('P14', 'CP');
                $sheet->setCellValue('P15', 'GTU');
                $sheet->setCellValue('P16', 'TE');
                $sheet->setCellValue('P17', 'CAB');
                $sheet->setCellValue('P18', 'ME');
                $sheet->setCellValue('P19', 'MN');

                $sheet->getStyle('P8:P19')->getFont()->setName('Tahoma')->setBold(true)->setSize(9);

                $sheet->cells('P8:Q8', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->cells('P9:P19', function ($cells) {

                    $cells->setAlignment('right');
                });


                $sheet->setCellValue('Q9', 'ADMISIÓN ORDINARIA');
                $sheet->setCellValue('Q10', 'PRIMEROS PUESTOS COLEGIOS REGIÓN MOQUEGUA');
                $sheet->setCellValue('Q11', 'DEPORTISTAS CALIFICADOS - LEY DEL DEPORTE');
                $sheet->setCellValue('Q12', 'LEY 27050 - PERSONA CON DISCAPACIDAD');
                $sheet->setCellValue('Q13', 'HEROES DEL CENEPA Y VICTIMAS DEL TERRORISMO');
                $sheet->setCellValue('Q14', 'CENTRO PREUNIVERSITARIO');
                $sheet->setCellValue('Q15', 'GRADUADOS Y/O TITULADOS DE UNIVERSIDAD');
                $sheet->setCellValue('Q16', 'TRASLADO EXTERNO');
                $sheet->setCellValue('Q17', 'CONVENIO ANDRES BELLO');
                $sheet->setCellValue('Q18', 'MOVILIDAD EXTRANJERO');
                $sheet->setCellValue('Q19', 'MOVILIDAD NACIONAL');

                $sheet->getStyle('Q9:Q19')->getFont()->setName('Calibri')->setBold(false)->setSize(9);

                $sheet->setCellValue('C7', 'SEMESTRE');
                $sheet->setCellValue('D7', 'AO');
                $sheet->setCellValue('E7', 'PPP');
                $sheet->setCellValue('F7', 'DC');
                $sheet->setCellValue('G7', 'PCD');
                $sheet->setCellValue('H7', 'HCYT');
                $sheet->setCellValue('I7', 'CP');
                $sheet->setCellValue('J7', 'GTU');
                $sheet->setCellValue('K7', 'TE');
                $sheet->setCellValue('L7', 'CAB');
                $sheet->setCellValue('M7', 'ME');
                $sheet->setCellValue('N7', 'MN');


                $sheet->setWidth(array(
                    'C' => 10,
                    'Q' => 40

                ));

                $sheet->cells('C7:N7', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('D7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('E7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('F7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('G7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('H7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('I7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('J7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('K7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('L7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('M7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('N7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);


                $sheet->cells('C7', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->setWidth(array(
                    'B' => 10,
                    'C' => 10,
                    'D' => 10,
                    'E' => 10,
                    'F' => 10,
                    'G' => 10,
                    'H' => 10,
                    'I' => 10,
                    'J' => 10,
                    'K' => 10,
                    'L' => 10,
                    'M' => 10,
                    'N' => 10
                ));

                $nn = count($semestre);
                for ($ii = 0; $ii < $nn; $ii++) {
                    $x = $ii + 8;
                    $sheet->setCellValue('C' . $x, $semestre[$ii]->iControlCicloAcad);

                    $pal = $semestre[$ii]->iControlCicloAcad;

                    for ($j = 0; $j <= 10; $j++) {
                        if (isset($reporteA[$j]->$pal)) {
                        } else {
                            $reporteA[$j]->$pal = 0;
                        }
                    }

                    $sheet->setCellValue('D' . $x, $reporteA[0]->$pal);
                    $sheet->setCellValue('E' . $x, $reporteA[1]->$pal);
                    $sheet->setCellValue('F' . $x, $reporteA[2]->$pal);
                    $sheet->setCellValue('G' . $x, $reporteA[3]->$pal);
                    $sheet->setCellValue('H' . $x, $reporteA[4]->$pal);
                    $sheet->setCellValue('I' . $x, $reporteA[5]->$pal);
                    $sheet->setCellValue('J' . $x, $reporteA[6]->$pal);
                    $sheet->setCellValue('K' . $x, $reporteA[7]->$pal);
                    $sheet->setCellValue('L' . $x, $reporteA[8]->$pal);
                    $sheet->setCellValue('M' . $x, $reporteA[9]->$pal);
                    $sheet->setCellValue('N' . $x, $reporteA[10]->$pal);

                    $sheet->cells('C' . $x . ':N' . $x, function ($cells) {

                        $cells->setAlignment('center');
                    });
                }
            });


            $excel->sheet('MODALIDAD POR ESCUELA', function ($sheet) use ($reporteB) {

                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:R1");
                $sheet->cells('B1:R1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);


                $sheet->setCellValue('B6', 'REPORTE ');
                $sheet->getStyle('B6')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("B6:O6");
                $sheet->cells('B6:O6', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });

                $sheet->setCellValue('Q8', 'LEYENDA ');
                $sheet->mergeCells("Q8:R8");

                $sheet->setCellValue('Q9', 'S');
                $sheet->setCellValue('Q10', 'O');
                $sheet->setCellValue('Q11', 'PP');
                $sheet->setCellValue('Q12', 'LD');
                $sheet->setCellValue('Q13', 'L');
                $sheet->setCellValue('Q14', 'C');
                $sheet->setCellValue('Q15', 'CP');
                $sheet->setCellValue('Q16', 'T');
                $sheet->setCellValue('Q17', 'TE');
                $sheet->setCellValue('Q18', 'CO');
                $sheet->setCellValue('Q19', 'EC');
                $sheet->setCellValue('Q20', 'ME');
                $sheet->setCellValue('Q21', 'MN');

                $sheet->getStyle('Q8:Q21')->getFont()->setName('Tahoma')->setBold(true)->setSize(9);

                $sheet->cells('Q8:R8', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->cells('Q9:Q21', function ($cells) {

                    $cells->setAlignment('right');
                });


                $sheet->setCellValue('R9', 'SEDE');
                $sheet->setCellValue('R10', 'ORDINARIO');
                $sheet->setCellValue('R11', 'PRIMEROS PUESTOS');
                $sheet->setCellValue('R12', 'LEY DEPORTE');
                $sheet->setCellValue('R13', 'LEY 27050');
                $sheet->setCellValue('R14', 'CENEP. TERR');
                $sheet->setCellValue('R15', 'CENTRO PREUNIVERSITARIO');
                $sheet->setCellValue('R16', 'TITULO');
                $sheet->setCellValue('R17', 'TRASLADO EXTERNO');
                $sheet->setCellValue('R18', 'CONVENIO');
                $sheet->setCellValue('R19', 'EGRES_COAR');
                $sheet->setCellValue('R20', 'MOVILIDAD EXTRANJERO');
                $sheet->setCellValue('R21', 'MOVILIDAD NACIONAL');

                $sheet->getStyle('R9:R21')->getFont()->setName('Calibri')->setBold(false)->setSize(9);


                $sheet->setCellValue('B7', 'ESCUELA');
                $sheet->setCellValue('C7', 'S');
                $sheet->setCellValue('D7', 'O');
                $sheet->setCellValue('E7', 'PP');
                $sheet->setCellValue('F7', 'LD');
                $sheet->setCellValue('G7', 'L');
                $sheet->setCellValue('H7', 'C');
                $sheet->setCellValue('I7', 'CP');
                $sheet->setCellValue('J7', 'T');
                $sheet->setCellValue('K7', 'TE');
                $sheet->setCellValue('L7', 'CO');
                $sheet->setCellValue('M7', 'EC');
                $sheet->setCellValue('N7', 'ME');
                $sheet->setCellValue('O7', 'MN');

                $sheet->setWidth(array(
                    'B' => 35,
                    'C' => 10
                ));

                $sheet->cells('B7:O7', function ($cells) {

                    $cells->setAlignment('center');
                });
                $sheet->getStyle('B7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('F7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('G7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('L7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('N7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('O7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);





                foreach ($reporteB as $key => $value) {
                    $x = ($key + 8);
                    $b = "B" . $x;
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;
                    $h = "H" . $x;
                    $i = "I" . $x;
                    $j = "J" . $x;
                    $k = "K" . $x;
                    $l = "L" . $x;
                    $m = "M" . $x;
                    $n = "N" . $x;
                    $o = "O" . $x;

                    $sheet->getStyle('B' . $x . ':C' . $x)->getFont()->setName('Tahoma')->setBold(false)->setSize(8);

                    $sheet->cells('C' . $x . ':O' . $x, function ($cells) {

                        $cells->setAlignment('center');
                    });

                    $sheet->setCellValue($b, $value->cCarreraDsc);
                    $sheet->setCellValue($c, $value->cFilDescripcion);
                    $sheet->setCellValue($d, $value->cORDINARIO);
                    $sheet->setCellValue($e, $value->cP_PUESTO);
                    $sheet->setCellValue($f, $value->cLEY_DEPORTE);
                    $sheet->setCellValue($g, $value->cLEY27050);
                    $sheet->setCellValue($h, $value->cCENEP_TERR);
                    $sheet->setCellValue($i, $value->cCEPRE);
                    $sheet->setCellValue($j, $value->cTITULADO);
                    $sheet->setCellValue($k, $value->cT_EXTERNO);
                    $sheet->setCellValue($l, $value->cCONVENIO);
                    $sheet->setCellValue($m, $value->cEGRES_COAR);
                    $sheet->setCellValue($n, $value->cMOVILIDAD_EXTRANJERO);
                    $sheet->setCellValue($o, $value->cMOVILIDAD);
                }


                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }

    public function HorarioClasesExcel($filCarreraId, $curricId, $cicloAcad)
    {
        $reporte = DB::select('exec ura.[Sp_DOCE_SEL_horarioClasesXiFilialCarreraIdXiCurrIdXiControlCicloAcad] ?, ?, ?', array($filCarreraId, $curricId, $cicloAcad));

        header("Access-Control-Allow-Origin: *");


        Excel::create('HORARIO', function ($excel) use ($reporte, $cicloAcad) {



            $excel->sheet('SEMESTRE ' . $cicloAcad, function ($sheet) use ($reporte, $cicloAcad) {

                $sheet->setCellValue('A1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("A1:O1");
                $sheet->cells('A1:O1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);


                $sheet->setCellValue('C4', 'SEMESTRE');
                $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->setCellValue('D4', $cicloAcad);

                $sheet->cells('D4', function ($cells) {
                    $cells->setAlignment('left');
                });



                $sheet->setCellValue('B6', 'REPORTE ');
                $sheet->getStyle('B6')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);
                $sheet->mergeCells("B6:N6");
                $sheet->cells('B6:N6', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });


                $sheet->setCellValue('B7', 'N°');
                $sheet->setCellValue('C7', 'CICLO');
                $sheet->setCellValue('D7', 'CODIGO');
                $sheet->setCellValue('E7', 'CURSO');
                $sheet->setCellValue('F7', 'DOCENTE.');
                $sheet->setCellValue('G7', 'LUNES.');
                $sheet->setCellValue('H7', 'MARTES');
                $sheet->setCellValue('I7', 'MIERCOLES.');
                $sheet->setCellValue('J7', 'JUEVES');
                $sheet->setCellValue('K7', 'VIERNES');
                $sheet->setCellValue('L7', 'SABADO');
                $sheet->setCellValue('M7', 'DOMINGO');
                $sheet->setCellValue('N7', 'SECCION');



                $sheet->setWidth(array(
                    'B' => 10,
                    'C' => 12,
                    'D' => 15,
                    'E' => 40,
                    'F' => 40,
                    'G' => 20,
                    'H' => 20,
                    'I' => 20,
                    'J' => 20,
                    'K' => 20,
                    'L' => 20,
                    'M' => 20,
                    'N' => 10,

                ));




                $sheet->cells('B7:N7', function ($cells) {

                    $cells->setAlignment('center');
                });

                $sheet->getStyle('B7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('D7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('E7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('F7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('G7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('H7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('I7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('J7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('K7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('L7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('M7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);
                $sheet->getStyle('N7')->getFont()->setName('Tahoma')->setBold(true)->setSize(8);


                $sheet->cells('B7', function ($cells) {

                    $cells->setAlignment('center');
                });


                foreach ($reporte as $key => $value) {
                    $x = ($key + 8);
                    $b = "B" . $x;
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;
                    $h = "H" . $x;
                    $i = "I" . $x;
                    $j = "J" . $x;
                    $k = "K" . $x;
                    $l = "L" . $x;
                    $m = "M" . $x;
                    $n = "N" . $x;

                    $sheet->setHeight(array(
                        $x => 30
                    ));

                    $sheet->getStyle('B' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('C' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('D' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('E' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('F' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('G' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('H' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('I' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('J' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('K' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('L' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('M' . $x)->getFont()->setName('Calibri')->setSize(8);
                    $sheet->getStyle('N' . $x)->getFont()->setName('Calibri')->setSize(8);


                    $sheet->cells('B' . $x . ':D' . $x, function ($cells) {

                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });

                    $sheet->cells('G' . $x . ':N' . $x, function ($cells) {

                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });
                    $sheet->setCellValue($b, $key + 1);
                    $sheet->setCellValue($c, $value->cMatricDetCicloCurso);
                    $sheet->setCellValue($d, $value->cCurricCursoCod);
                    $sheet->setCellValue($e, $value->cCurricCursoDsc);
                    $sheet->setCellValue($f, $value->cPersPaterno . ' ' . $value->cPersMaterno . ', ' . $value->cPersNombre);




                    $sheet->setCellValue($g, $value->lunes);


                    $sheet->setCellValue($h, $value->martes);
                    $sheet->setCellValue($i, $value->miercoles);
                    $sheet->setCellValue($j, $value->jueves);
                    $sheet->setCellValue($k, $value->viernes);
                    $sheet->setCellValue($l, $value->sabado);
                    $sheet->setCellValue($m, $value->domingo);
                    $sheet->setCellValue($n, $value->cSeccionDsc);




                    # code...
                }


                //$sheet->fromArray($data,'A5');
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }

    public function ReporteAsistenciaNotas($iDocenteId, $iCargaHId, $iControlCicloAcad)
    {
        $data = DB::select("exec [ura].[Sp_DASA_SEL_Reporte_Asistencia_NotasxiDocenteIdxiCargaHIdxiControlCicloAcad] ?, ?, ?", array($iDocenteId, $iCargaHId, $iControlCicloAcad));

        return response()->json($data);
    }

    // Reportes Academicos para Dasa
    public function ReporteIngresantesEscuela($tipo)
    {
        $data = DB::select("exec [ura].[Sp_DASA_REP_ingresantesPorEscuela]");
        if ($tipo == 1) {
            return ExportReporteController::getReporteIngresantesFiles($data);
        }
        if ($tipo == 2) {
            return response()->json($data);
        }
    }
    public function ReporteIngresantesEscuelaDetalles($year, $carrera, $fil, $tipo)
    {
        $data = DB::select("exec ura.[Sp_DASA_REP_ingresantesPorEscuela_Detalles] ?, ?, ?", array($year, $carrera, $fil));

        if ($tipo == 2) {
            return ExportReporteController::getReporteIngresantesDetalleFiles($data);
        }
        if ($tipo == 1) {
            return response()->json($data);
        }
    }
    public function ActaEvalucionExtraordinaria($iCarreraId, $iFilId, $iControlCicloAcad, $iDocenteId, $iCurricId, $seccionId, $cursoCod)
    {
        $data = DB::select("exec [ura].[Sp_DASA_SEL_ActaMatriculaExtraordinaria] ?, ?, ?, ?, ?, ?, ?", array($iCarreraId, $iFilId, $iControlCicloAcad, $iDocenteId, $iCurricId, $seccionId, $cursoCod));
        $pdf = PDF::loadView('dasa.actaExtra', compact(['data', 'iControlCicloAcad', 'iCurricId', 'cursoCod']))->setPaper('A4', 'portrait');/*'portrait || landscape'*/
        return $pdf->stream();
    }
    public function UnidadesCerradasCarrera($tipo, $iControlCicloAcad, $iFilId, $iCarreraId)
    {
        $band = 1;
        if ($iCarreraId && $iCarreraId != null && $iCarreraId != '' && $iCarreraId != 'null') {
            $data = DB::select("exec [ura].[Sp_DOCE_SEL_Notas_DASA_MuestraNumero_Unidades_Cerradas_Carrera] ?, ?, ?", array($iControlCicloAcad, $iFilId, $iCarreraId));
            $band = 1;
        } else {
            $data = DB::select("EXEC [ura].[Sp_DOCE_SEL_Notas_DASA_MuestraNumero_Unidades_Cerradas_Filial] ?, ?", array($iControlCicloAcad, $iFilId));
            $band = 0;
        }
        if ($tipo == 1) {
            return response()->json($data);
        } else {
            $pdf = PDF::loadView('dasa.listaUnidades', compact(['data', 'band']))->setPaper('A4', 'portrait');/*'portrait || landscape'*/
            return $pdf->stream();
        }
    }
    public function razDocenteFormatos(Request $request)
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
                    $responseJson = DB::select('EXEC ura.Sp_DASA_REP_racionalizacionFormatosXiFilialXiCarreraIdXiControlCicloAcad ?,?,?', [
                        $data['iFilial'],
                        $data['iCarreraId'],
                        $data['iControlCicloAcad']

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

    public function ReporteRelacionDocentesSilabo($iFilId, $iCarreraId, $iSemestre,$opcion)
    {

        $silabo = DB::select('exec [ura].Sp_DOCE_SEL_Reporte_Silabo ?,?,?', array($iFilId, $iCarreraId, $iSemestre));
        if($opcion=="CONSULTAR") {
        return response()->json($silabo);
        }
        if($opcion=="REPORTE") {
            $T_cursos = 0;
            $T_cursos_c = 0;
            $T_cursos_p = 0;
            $T_cursos_v = 0;

            $docente = \DB::table('ura.cargas_horarias')
            ->where('iControlCicloAcad', $iSemestre)
            ->where('iFilId', $iFilId)
            ->where('iCarreraId', $iCarreraId)
            ->select('ura.cargas_horarias.iDocenteId')
            ->groupBy('iDocenteId')
            ->get();
            
            foreach($silabo as $index=>$sil){
                 $sum = 0;
                 //SECCION 1
                 if($sil->seccion1 == 'COMPLETADO' ){$sum = $sum + 5;}
                 //SECCION 2
                 if($sil->seccion2 == 'COMPLETADO' ){$sum = $sum + 5;}
                 //SECCION 3
                 if($sil->seccion3 == 'COMPLETADO' ){$sum = $sum + 20;}
                 if($sil->seccion3 == 'PROCESO' ){$sum = $sum + 10;}
                 //SECCION 4
                 if($sil->seccion4 == 'COMPLETADO' ){$sum = $sum + 10;}
                 if($sil->seccion4 == 'PROCESO' ){$sum = $sum + 5;}
                 //SECCION 5
                 if($sil->seccion5 == 'COMPLETADO' ){$sum = $sum + 30;}
                 if($sil->seccion5 == 'PROCESO' ){$sum = $sum + 15;}
                 //SECCION 6
                 if($sil->seccion6 == 'COMPLETADO' ){$sum = $sum + 10;}
                 //SECCION 7
                 if($sil->seccion7 == 'COMPLETADO' ){$sum = $sum + 10;}
                 if($sil->seccion7 == 'PROCESO' ){$sum = $sum + 5;}
                 //SECCION 8
                 if($sil->seccion8 == 'COMPLETADO' ){$sum = $sum + 10;}
                
                 $sil->cumplimiento = $sum;

                 $T_cursos =  $T_cursos + 1;    

                if($sil->cumplimiento == 100) {
                    $T_cursos_c = $T_cursos_c  + 1;
                }

                if($sil->cumplimiento < 100 && $sil->cumplimiento > 0) {
                    $T_cursos_p = $T_cursos_p  + 1;
                }

                if($sil->cumplimiento == 0) {
                    $T_cursos_v = $T_cursos_v  + 1;
                }
            }
        $pc = round((($T_cursos_c * 100) / $T_cursos));
        $pp = round((($T_cursos_p * 100) / $T_cursos));
        $pv = round((($T_cursos_v * 100) / $T_cursos));
        $pdf = \PDF::loadView('dasa.ReporteSilabo', compact(['silabo','docente','T_cursos','T_cursos_c','T_cursos_p','T_cursos_v','pc','pp','pv']))->setPaper('a4', 'landscape');
        return $pdf->download("ReporteSilabo.pdf");
        //return response()->json($matriculas);
        }

    }
}
