<?php

namespace App\Http\Controllers\Ura;

use App\ClasesLibres\TramiteDocumentario\PdfCreator;
use App\Exports\DasaExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UraCurricula;
use App\UraControlCicloAcademico;
use Maatwebsite\Excel\Facades\Excel;
use TCPDF_FONTS;

class UraHorarioController extends Controller {
    /**
     * Obtiene los planes y los ciclos académicos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPlanesCiclosAcademicos($ignoreVerano = 0) {

        $planes = UraCurricula::all();
        $ciclosAcademicos = UraControlCicloAcademico::orderBy('iControlCicloAcad', 'desc');
        if ($ignoreVerano == 1) {
            $ciclosAcademicos->where('iControlCicloAcad', 'not like', "%0");
        }
        $ciclos = $ciclosAcademicos->get();

        $data = ['planes' => $planes, 'ciclosAcademicos' => $ciclos];

        return response()->json($data);
    }


    /**
     * Obtiene los planes y los ciclos académicos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerHorariosPorCarreraFilialCiclo(Request $request) {
        //$horarios = collect(\DB::select('ura.Sp_HORA_SEL_registroHorariosCompletos ?, ?, ?, ?', [$request->iControlCicloAcad, $request->iCarreraId, $request->iCurricId, $request->iFilId]));
        //$horarios = $horarios->sortBy('cCurricDetCicloCurso')->groupBy('cCurricDetCicloCurso')->values()->all();
        $iC = NULL;
        $iSec = NULL;
        if (isset($request->idCiclo) && isset($request->idSeccion)){
            $iC = $request->idCiclo;
            $iSec = $request->idSeccion;
        }

        $horarios = collect(\DB::select('exec ura.Sp_SEL_horariosXiCarreraIdXiFilIdXiControlCicloAcad ?, ?, ?, ?, ?, ?', array($request->iControlCicloAcad, $request->iFilId, $request->iCarreraId, $request->iCurricId, $iC, $iSec)));
        $horarios = $horarios->sortBy('cCurricDetCicloCurso')->groupBy('cCurricDetCicloCurso')->values()->all();
        return response()->json($horarios);
    }

    public function obtenerHorarios2do(Request $request) {
        $data = [$request->iControlCicloAcad, $request->iFilId, $request->iCarreraId, $request->iCurricId, $request->cCiclo, $request->iSeccion,];
        $horarios = \DB::select('exec ura.Sp_SEL_horariosXiCarreraIdXiFilIdXiControlCicloAcad ?, ?, ?, ?, ?, ? ', $data);

        return response()->json($horarios);
    }

    /**
     * Obtiene los cursos activos, aulas (disponibles y no) y secciones
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerCursosAulasSecciones(Request $request) {
        $secciones = \DB::select('exec ura.SP_SEL_secciones');
        $cursos = \DB::select('exec ura.sp_SEL_PlanEstudiosXiCarreraIdXiEstado ?, ?', array($request->carreraId, 1));// 1 para Cursos activos
        $aulas = \DB::select('exec ura.Sp_SEL_Aulas_Disponible_NoDisponible_XiCarreraIdXiFilIdXhIniXhFinXiDiaSemId ?, ?, ?, ?, ?, ?', array($request->carreraId, $request->filialId, $request->horaInicio, $request->horaFin, $request->dia, $request->cicloAcad));

        $tiposCurso = \DB::select('exec [ura].[Sp_GRAL_SEL_tipoCursosCargas]');


        $data = ['cursos' => $cursos, 'aulas' => $aulas, 'secciones' => $secciones, 'tiposCursoCarga' => $tiposCurso];

        return response()->json($data);
    }

    /**
     * Obtiene los cursos activos, aulas (disponibles y no) y secciones
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPlanesConCursosActivosPorCarrera($carreraId) {
        $cursos = \DB::select('exec ura.sp_SEL_PlanEstudiosXiCarreraIdXiEstado ?, ?', array($carreraId, 1));// 1 para Cursos activos

        $planes = UraCurricula::all();

        foreach ($planes as $plan) {
            $cursosPlan = [];
            foreach ($cursos as $curso) {
                if ($plan->iCurricId == $curso->iCurricId) {
                    $cursosPlan[] = $curso;
                }
            }
            $plan->cursos = $cursosPlan;
        }

        return response()->json($planes);
    }

    /**
     * Obtiene los docentes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPaginacionDocentesPorCarrera(Request $request) {
        $data = \DB::select('exec ura.Sp_SEL_docentesPaginadoXiCarreraIdXcBusquedaXsSortDirXpageNumberXpageSize ?, ?, ?, ?, ?', array($request->carrera, $request->busqueda, $request->orden, $request->pagina, $request->nRegistros));

        return response()->json($data);
    }

    /**
     * Inserta un registro en la tabla horarios
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarBloqueHorario(Request $request) {
        $this->validate($request, ['aulaCod' => 'required', 'carreraId' => 'required', 'filialId' => 'required', 'cicloAcad' => 'required', 'curriculaId' => 'required', 'cursoCod' => 'required', 'seccionId' => 'required', 'dia' => 'required', 'horaInicio' => 'required', 'horaFin' => 'required',], ['aulaCod.required' => 'Debe seleccionar un aula.', 'carreraId.required' => 'Hubo un problema al verificar la carrera.', 'filialId.required' => 'Hubo un problema al verificar la filial.', 'cicloAcad.required' => 'Hubo un problema al verificar la filial.', 'curriculaId.required' => 'Hubo un problema al verificar el plan curricular.', 'cursoCod.required' => 'Debe seleccionar un curso.', 'seccionId.required' => 'Debe seleccionar una sección.', 'dia.required' => 'Hubo un problema al verificar el día.', 'horaInicio.required' => 'Debe seleccionar una hora de inicio.', 'horaFin.required' => 'Debe seleccionar una hora de término.',]);

        $parametros = array($request->aulaCod, $request->carreraId, $request->filialId, $request->cicloAcad, $request->curriculaId, $request->cursoCod, $request->seccionId, $request->dia, $request->horaInicio, $request->horaFin, auth()->user()->cCredUsuario, 'equipo', $request->server->get('REMOTE_ADDR'), 'mac');

        try {
            $data = \DB::select('exec ura.Sp_HORA_INS_HorarioCurso ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            if ($data[0]->id > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó el horario exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar el horario.'];
                $codeResponse = 500;
            }
        }
        catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];

            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Obtiene la configuración de horario por carrera y filial
     */
    public function obtenerConfigHorarioCarrera(Request $request) {
        $data = \DB::select('exec ura.Sp_HORA_SEL_horariosConfig ?, ?', array($request->carreraId, $request->filialId));

        return response()->json($data);
    }

    /**
     * Obtiene la configuración de horario por carrera y filial
     */
    public function guardarConfigHorarioCarrera(Request $request) {
        $this->validate($request, ['horaInicio' => 'required', 'horaFin' => 'required', 'carreraId' => 'required', 'filialId' => 'required', 'lunes' => 'boolean', 'martes' => 'boolean', 'miercoles' => 'boolean', 'jueves' => 'boolean', 'viernes' => 'boolean', 'sabado' => 'boolean', 'domingo' => 'boolean',], ['horaInicio.required' => 'Debe ingresar una hora de inicio.', 'horaFin.required' => 'Debe ingresar una hora de término.', 'carreraId.required' => 'Hubo un problema al verificar la carrera.', 'filialId.required' => 'Hubo un problema al verificar la filial.',]);

        $parametros = [$request->horaInicio, $request->horaFin, $request->lunes, $request->martes, $request->miercoles, $request->jueves, $request->viernes, $request->sabado, $request->domingo, $request->carreraId, $request->filialId, 'user', //auth()->user()->cCredUsuario,
            'equipo', $request->server->get('REMOTE_ADDR'), 'mac'];


        $data = \DB::select('exec ura.Sp_HORA_INS_UPD_ConfigHoraria ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

        if ($data[0]->resultado == 1) {
            $response = ['validated' => true, 'mensaje' => 'Se guardó la configuración de horario exitosamente.'];
            $codeResponse = 200;
        } elseif ($data[0]->resultado == 2) {
            $response = ['validated' => true, 'mensaje' => 'Se editó la configuración de horario exitosamente.'];
            $codeResponse = 200;
        } else {
            $response = ['validated' => true, 'mensaje' => 'Hubo un problema al editar o gurdar.'];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Elimina un bloque de horario
     */
    public function eliminarBloqueHorario($id) {
        
        $data = \DB::select('exec ura.Sp_HORA_DEL_HorarioCurso ?', array($id));
        if ($data[0]->eliminados > 0) {
            $res = \DB::select('exec [ura].[Sp_DOCE_DEL_AsistenciaXCambioHorario] ?', array( $id ));
            $response = ['validated' => true, 'mensaje' => 'Se eliminó el horario exitosamente.', 'eliminados' => $data[0]->eliminados];
            $codeResponse = 200;
        } else {
            $response = ['validated' => true, 'mensaje' => 'El horario no se ha podido eliminar o no existe.', 'eliminados' => $data[0]->eliminados];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Guarda una carga horaria
     */
    public function guardarCargaHoraria(Request $request) {
        $this->validate($request, ['docenteId' => 'required|integer', 'ciclo' => 'required|integer', 'filialId' => 'required|integer', 'carreraId' => 'required|integer', 'curriculaId' => 'required|integer', 'cursoCod' => 'required', 'seccionId' => 'required|integer', 'condicionId' => 'required|integer', 'categoriaId' => 'required|integer', 'dedicId' => 'required|integer', 'dedicHoras' => 'required|integer', 'docApru' => 'required', 'tipoAperturaId' => 'required|integer',], ['docenteId.required' => 'Debe seleccionar un docente', 'ciclo.required' => 'Debe seleccionar un ciclo', 'filialId.required' => 'Hubo un problema al verificar la filial.', 'carreraId.required' => 'Hubo un problema al verificar la carrera.', 'curriculaId.required' => 'Hubo un problema al verificar el plan curricular.', 'cursoCod.required' => 'Debe seleccionar un curso.', 'seccionId.required' => 'Debe seleccionar una sección.', 'condicionId.required' => 'Seleccione la Clasificación del Docente', 'categoriaId.required' => 'Seleccione la Sub Clasificación del Docente', 'dedicId.required' => 'Seleccione la Dedicación del Docente', 'dedicHoras.required' => 'Ingrese el Numero de Horas de dedicación', 'docApru.required' => 'Ingrese el Documento de aprobación.', 'tipoAperturaId.required' => 'Seleccione un tipo de apertura de curso.',]);

        $parametros = [$request->docenteId, $request->ciclo, $request->filialId, $request->carreraId, $request->curriculaId, $request->cursoCod, $request->seccionId, $request->condicionId, $request->categoriaId, $request->dedicId, $request->dedicHoras, $request->docApru, $request->cargaId, $request->tipoAperturaId, auth()->user()->cCredUsuario, 'equipo', $request->server->get('REMOTE_ADDR'), 'mac'];

        try {
            $data = \DB::select('exec ura.Sp_HORA_INS_cargasHorarias ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);
            $res = \DB::select('exec [ura].[Sp_DOCE_DEL_AsistenciaXCambioCargaHoraria] ?', array($request->cargaId));
            $response = ['validated' => true, 'mensaje' => 'Se guardó la carga académica exitosamente.', 'response' => $data];
            $codeResponse = 200;
        }
        catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];

            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /**
     * Selecciona los planes con cargas académicas
     */
    public function obtenerPlanesConCargasAcademicas(Request $request) {
        $cargas = \DB::select('exec ura.Sp_HORA_SEL_cargaAcademica ?, ?, ?', array($request->carreraId, $request->filialId, $request->ciclo));

        $planes = UraCurricula::all();

        foreach ($planes as $plan) {
            $cargasPlan = [];
            foreach ($cargas as $carga) {
                if ($plan->iCurricId == $carga->iCurricId) {
                    $cargasPlan[] = $carga;
                }
            }
            $plan->cargas = $cargasPlan;
        }

        return response()->json($planes);
    }

    /**
     * Obtiene condiciones, categorías, dedicación, estados de Acta para la Carga horaria
     */
    public function obtenerSelectsCargaHoraria() {
        $condiciones = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?', array('grl', 'condicion'));

        $categorias = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?', array('grl', 'categoria'));

        $dedicacion = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?', array('grl', 'dedicacion'));

        //$estadosActa = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?',array('ura', 'estado_acta'));

        $data = ['condiciones' => $condiciones, 'categorias' => $categorias, 'dedicacion' => $dedicacion];

        return response()->json($data);
    }


    public function obtenerReporteHorarios(Request $request, $tipo = 'pdf') {
        $horarios = collect(\DB::select('ura.Sp_HORA_SEL_registroHorariosCompletos ?, ?, ?, ?', [$request->iControlCicloAcad, $request->iCarreraId, $request->iCurricId, $request->iFilId]));
        $horarios = $horarios->sortBy('cCurricDetCicloCurso')->values();
        // dd($horarios->first()->cCarrera);
        if ($horarios->count() > 0) {
        switch ($tipo) {
            case 'pdf':

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
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


                $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
                $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
                $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);

                $pdf->tipoHeader = 'reportes_tramite';
                $pdf->textoEncabezado = collect();
                $pdf->textoEncabezado->add([ 'text' => 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 'font' => $roboto_bold, 'size' => 13, 'esp' => true ]);
                $pdf->textoEncabezado->add([ 'text' => 'HORARIOS DE ' . trim($horarios->first()->cCarrera), 'font' => $roboto_bold, 'size' => 10, 'esp' => true ]);

                $htmlStyles = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    .normal th {
        background-color: #a9a9a9;
        font-weight: bold;
        text-align: center;
    }
    .normal td {
        font-size: 85%;
        /*height: 70px;*/
    }

    .negrita {
        font-weight: bold;
    }
    .centrado {
        text-align: center;
    }
    .justificado {
        text-align: justify;
    }
    .tam80 {
        font-size: 70%;
    }

    .darkMode th {
        background-color: #2d366f;
        color: white;
        font-weight: bold;
        text-transform: capitalize;
    }
    .darkMode td {
        font-size: 85%;
    }
</style>
EOF;

                $anchoColumnas = [

                    'REGINT' => 40,
                    'FEC' => 55,
                    'TDOC' => 180,
                    'TDOC_T' => 70,
                    'TDOC_N' => 70,
                    'REG' => 40,
                    'REM' => 120,
                    'FOL' => 30,
                    'ASU' => 210,
                    'PROV_DOC' => 75,
                    'DEST' => 60,
                    'FEC_SAL' => 60,
                    'FIRM' => 70,
                    'OBS' => 50,
                ];
                $aCol = [
                    20,
                    200,
                    50,
                    50,
                    30,
                    30,
                    20,
                    120,
                    120,
                ];

                $htmlListaTramites['header'] = '<thead>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="'.$aCol[0].'">Ciclo</th>';
                $htmlListaTramites['header'] .= '<th colspan="3" width="'.($aCol[1] + $aCol[2] + $aCol[3]).'">Curso</th>';
                $htmlListaTramites['header'] .= '<th colspan="2" width="'.($aCol[4] + $aCol[5]).'">Horas</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="'.$aCol[6].'">Sección</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="'.$aCol[7].'">Estado</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="'.$aCol[8].'">Carga Académica</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th width="'.$aCol[1].'">Nombre</th>';
                $htmlListaTramites['header'] .= '<th width="'.$aCol[2].'">Código</th>';
                $htmlListaTramites['header'] .= '<th width="'.$aCol[3].'">Plan</th>';
                $htmlListaTramites['header'] .= '<th width="'.$aCol[4].'">Total</th>';
                $htmlListaTramites['header'] .= '<th width="'.$aCol[5].'">Plan</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '</thead>';


                $htmlListaTramites['body'] = '<tbody>';

                foreach ($horarios as $item) {
                    $htmlListaTramites['body'] .= '<tr nobr="true">';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[0].'">' . $item->cCurricDetCicloCurso . '</td>';
                    $htmlListaTramites['body'] .= '<td width="'.$aCol[1].'">' . trim($item->cCurricCursoDsc) . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[2].'">' .$item->cCursoCod . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[3].'">' . $item->cPlan . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[4].'">' . $item->nTotalHoras . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[5].'">' . $item->num_horas_plan . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[6].'">' . $item->cSeccionDsc . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[7].'">' . $item->obs_horario . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$aCol[8].'">' . $item->obs_carga_acad . '</td>';
                    $htmlListaTramites['body'] .= '</tr>';
                }

                $htmlListaTramites['body'] .= '</tbody>';

                $pdf->SetFont($roboto_regular, '', 7, '', 'default', true);
                $htmlPrintTable = $htmlStyles;
                $htmlPrintTable .= '<table class="normal" cellspacing="0" cellpadding="3" border="1">' . $htmlListaTramites['header'] . $htmlListaTramites['body'] . '</table>';

                $pdf->AddPage('P', 'A4');
                $htmlFooter = '<p style="font-size: 9px; text-align: justify;">Reporte generado por el Módulo de Tramite Documentario. ' . now()->format('d/m/Y H:i') . '<br>';
                $htmlFooter .= 'SIGEUN (http://sigeun.unam.edu.pe)</p>';

                $pdf->addHtmlFooter = $htmlFooter;
                $pdf->writeHTML($htmlPrintTable, true, false, false, true, '');


                // print colored table
                // $pdf->ColoredTable($header, $data,$funcion);
                ob_end_clean();
                // ---------------------------------------------------------


                $pdf->Output('UNAM-SIGEUN.pdf', 'I');




                break;
            case 'excel':


                    $encabezados = [['Ciclo', 'Curso', '', '', 'Horas', '', 'Sección', 'Estado', 'Carga Académica',], ['', 'Nombre', 'Código', 'Plan', 'Total', 'Plan', '', '', '',]];
                    $arrData = [];
                    $horarios->each(function ($item) use (&$arrData) {
                        $arrData[] = [$item->cCurricDetCicloCurso, trim($item->cCurricCursoDsc), $item->cCursoCod, $item->cPlan, $item->nTotalHoras, $item->num_horas_plan, $item->cSeccionDsc, $item->obs_horario, $item->obs_carga_acad,];
                    });

                    $dasaExport = new DasaExport('horariosResumen', $encabezados, $arrData);

                    $dasaExport->inicioTablaColumna = 'A';
                    $dasaExport->inicioTablaFila = '4';

                    $dasaExport->rangeTitulos = sprintf("%s%s:%s%s", $dasaExport->inicioTablaColumna, $dasaExport->inicioTablaFila, $dasaExport->addLetters($dasaExport->inicioTablaColumna, 8), $dasaExport->inicioTablaFila + 1);

                    $dasaExport->merge = [sprintf("%s%s:%s%s", $dasaExport->inicioTablaColumna, $dasaExport->inicioTablaFila, $dasaExport->inicioTablaColumna, $dasaExport->inicioTablaFila + 1), sprintf("%s%s:%s%s", $dasaExport->addLetters($dasaExport->inicioTablaColumna, 1), $dasaExport->inicioTablaFila, $dasaExport->addLetters($dasaExport->inicioTablaColumna, 3), $dasaExport->inicioTablaFila), sprintf("%s%s:%s%s", $dasaExport->addLetters($dasaExport->inicioTablaColumna, 4), $dasaExport->inicioTablaFila, $dasaExport->addLetters($dasaExport->inicioTablaColumna, 5), $dasaExport->inicioTablaFila), sprintf("%s%s:%s%s", $dasaExport->addLetters($dasaExport->inicioTablaColumna, 6), $dasaExport->inicioTablaFila, $dasaExport->addLetters($dasaExport->inicioTablaColumna, 6), $dasaExport->inicioTablaFila + 1), sprintf("%s%s:%s%s", $dasaExport->addLetters($dasaExport->inicioTablaColumna, 7), $dasaExport->inicioTablaFila, $dasaExport->addLetters($dasaExport->inicioTablaColumna, 7), $dasaExport->inicioTablaFila + 1), sprintf("%s%s:%s%s", $dasaExport->addLetters($dasaExport->inicioTablaColumna, 8), $dasaExport->inicioTablaFila, $dasaExport->addLetters($dasaExport->inicioTablaColumna, 8), $dasaExport->inicioTablaFila + 1)];

                    $dasaExport->headers = [['text' => 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 'size' => 16, 'cellInicio' => $dasaExport->inicioTablaColumna . '1', 'cellFin' => $dasaExport->addLetters($dasaExport->inicioTablaColumna, 8) . '1', 'style' => 'titulos'], ['text' => 'HORARIOS DE ' . trim($horarios->first()->cCarrera), 'size' => 12, 'cellInicio' => $dasaExport->inicioTablaColumna . ($dasaExport->inicioTablaFila - 1), 'cellFin' => $dasaExport->addLetters($dasaExport->inicioTablaColumna, 8) . ($dasaExport->inicioTablaFila - 1), 'style' => 'titulos',],];

                    return $dasaExport->download('arc.xlsx');
//         return $dasaExport->download('arc.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
//         return $dasaExport->download('arc.pdf', \Maatwebsite\Excel\Excel::TCPDF);
                    //return $dasaExport->download('arc.pdf', \Maatwebsite\Excel\Excel::MPDF);
                    //return $dasaExport->download('arc.html', \Maatwebsite\Excel\Excel::HTML);

                    //SECCION DE MAATWEBSITE V 2,1
                    Excel::create('Horarios', function ($excel) use ($horarios) {


                        $excel->sheet('General', function ($sheet) use ($horarios) {
                            $fuenteTitulos = 'Cambria';

                            $sheet->setCellValue('A1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                            $sheet->mergeCells("A1:I1");
                            $sheet->cells('A1:I1', function ($cells) use ($fuenteTitulos) {
                                $cells
                                    //->setBackground('#0a2537')
                                    ->setAlignment('center')
                                    //->setFontColor('#ffffff')
                                    ->setFontFamily($fuenteTitulos)->setFontWeight('bold')->setFontSize(14);
                            });

                            $sheet->setCellValue('A2', 'HORARIOS DE ' . trim($horarios->first()->cCarrera))->mergeCells("A2:I2");

                            $sheet->cells('A2:I2', function ($cells) use ($fuenteTitulos) {
                                $cells->setBackground('#0a2537')->setAlignment('center')->setFontColor('#ffffff')->setFontFamily($fuenteTitulos)->setFontWeight('bold')->setFontSize(10)->setBorder('solid', 'solid', 'solid', 'solid');
                            });

                            $sheet->setCellValue('A3', 'Ciclo')->mergeCells("A3:A4");

                            $sheet->setCellValue('B3', 'Curso')->mergeCells("B3:D3");
                            $sheet->setCellValue('B4', 'Nombre');
                            $sheet->setCellValue('C4', 'Código');
                            $sheet->setCellValue('D4', 'Plan');

                            $sheet->setCellValue('E3', 'HORAS')->mergeCells("E3:F3");
                            $sheet->setCellValue('E4', 'Total');
                            $sheet->setCellValue('F4', 'Plan');

                            $sheet->setCellValue('G3', 'Sección')->mergeCells("G3:G4");

                            $sheet->setCellValue('H3', 'Estado')->mergeCells("H3:H4");
                            $sheet->setCellValue('I3', 'Carga Académica')->mergeCells("I3:I4");

                            $sheet->cells('A3:I4', function ($cells) {
                                $cells->setBackground('#0a2537')->setAlignment('center')->setFontColor('#ffffff')->setFontFamily('Cambria')->setFontWeight('bold')->setFontSize(10)->setBorder('solid', 'solid', 'solid', 'solid');
                            });

                            foreach ($horarios as $key => $value) {
                                $filaInicio = 5 + $key;
                                $sheet->setCellValue('A' . $filaInicio, $value->cCurricDetCicloCurso);
                                $sheet->setCellValue('B' . $filaInicio, $value->cCurricCursoDsc);
                                $sheet->setCellValue('C' . $filaInicio, $value->cCursoCod);
                                $sheet->setCellValue('D' . $filaInicio, $value->cPlan);
                                $sheet->setCellValue('E' . $filaInicio, $value->nTotalHoras);
                                $sheet->setCellValue('F' . $filaInicio, $value->num_horas_plan);
                                $sheet->setCellValue('G' . $filaInicio, $value->cSeccionDsc);
                                $sheet->setCellValue('H' . $filaInicio, $value->obs_horario);
                                $sheet->setCellValue('I' . $filaInicio, $value->obs_carga_acad);
                                $sheet->cells("A{$filaInicio}:I{$filaInicio}", function ($cells) {
                                    $cells
                                        //->setBackground('#0a2537')
                                        //->setAlignment('center')
                                        //->setFontColor('#ffffff')
                                        ->setFontFamily('Calibri')
                                        //->setFontWeight('bold')
                                        ->setFontSize(10)//->setBorder('solid', 'solid', 'solid', 'solid')
                                    ;
                                });
                            }

// Add before first row
                            $sheet->prependRow(2, []);
                            $sheet->setAllBorders('hair');


                            //$sheet->fromArray($data,'A5');
                            //$sheet->setOrientation('landscape');
                        });
                    })->export('pdf');
                    break;
                }
        }

    }
}
