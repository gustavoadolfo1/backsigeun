<?php

namespace App\Http\Controllers\Tram;

use App\ClasesLibres\Reportes\ReporteExcel;
use App\ClasesLibres\TramiteDocumentario\PdfCreator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use PDF;
use TCPDF_FONTS;

// use PHPExcel;

class ReporteController extends Controller
{
    public function __construct()
    {
        setlocale(LC_TIME, 'Spanish');
    }

    public function generarEXCELTramite(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');
        $salida = $request->get('salida');

        $data = json_decode(json_encode($data));

        header("Access-Control-Allow-Origin: *");

        Excel::create('UNAM-SIGEUN', function ($excel) use ($req, $data) {
            $dataTramite = null;
            switch ($req) {
                case 'por_recepcionar':
                    $excel->sheet('Enviados', function ($sheet) use ($data) {
                        $sheet->setAutoSize(true);
                        //$sheet->setAutoFilter();

                        $arrLeyenda = [
                            ['titulo' => 'FEC. RECEPCION'],
                            ['titulo' => 'DOCUMENTO', 'hijos' => [
                                ['titulo' => 'TIPO'],
                                ['titulo' => 'NUMERO'],
                                ['titulo' => 'N° REG'],
                            ]],
                            ['titulo' => 'REMITENTE'],
                            ['titulo' => 'FOL.'],
                            ['titulo' => 'ASUNTO'],
                            ['titulo' => 'PROVEIDO DOC. SALIDA'],
                            ['titulo' => 'DESTINO'],
                            ['titulo' => 'FIRMA'],
                            ['titulo' => 'OBS'],
                        ];

                        $cellsMergeVertical = [];
                        $existHijos = false;
                        $colInicioTabla = 'A';
                        $rowInicioTabla = 5;

                        $celdasLeyenda = [$colInicioTabla . $rowInicioTabla];

                        foreach ($arrLeyenda as $leya) {

                            if (isset($leya['hijos'])) {

                                if (!$existHijos) {
                                    $existHijos = true;
                                }

                                $sheet->cell($colInicioTabla . $rowInicioTabla, function ($cell) use ($leya) {
                                    $cell->setBackground('#2d366f');
                                    $cell->setFontColor('#ffffff');
                                    $cell->setAlignment('center');
                                    $cell->setValignment('center');
                                    $cell->setValue($leya['titulo']);
                                    $cell->setBorder('thin', 'thin', 'thin', 'thin');
                                });

                                $colInicioMerge = $colInicioTabla;
                                $colFinMerge = '';
                                $lastKey = array_key_last($leya['hijos']);
                                foreach ($leya['hijos'] as $idx => $hijo) {
                                    $sheet->cell($colInicioTabla . ($rowInicioTabla + 1), function ($cell) use ($hijo) {
                                        $cell->setValue($hijo['titulo']);
                                    });
                                    if ($lastKey == $idx) {
                                        $colFinMerge = $colInicioTabla;
                                    }

                                    ++$colInicioTabla;
                                }

                                $sheet->mergeCells($colInicioMerge . $rowInicioTabla . ':' . $colFinMerge . $rowInicioTabla);


                            } else {
                                $sheet->cell($colInicioTabla . $rowInicioTabla, function ($cell) use ($leya) {
                                    $cell->setValue($leya['titulo']);
                                });
                                $cellsMergeVertical[] = $colInicioTabla . $rowInicioTabla;
                                ++$colInicioTabla;
                            }


                            // $sheet->setCellValue('A3', 'Registro');


                        }
                        $celdasLeyenda[1] = $colInicioTabla . $rowInicioTabla;

                        if ($existHijos) {
                            foreach ($cellsMergeVertical as $celV) {
                                $col = substr($celV, 0, 1);
                                $fil = substr($celV, -1);
                                ++$fil;
                                $sheet->mergeCells($celV . ':' . $col . $fil);
                            }
                            $celdasLeyenda[1] = $col . $fil;
                        }

                        /*
                        $sheet->setCellValue('A1', $celdasLeyenda[0]);
                        $sheet->setCellValue('B1', $celdasLeyenda[1]);
                        $sheet->setCellValue('C1', $rowInicioTabla);
                        */

                        $sheet->mergeCells(substr($celdasLeyenda[0], 0, 1) . ($rowInicioTabla - 1) . ':' . substr($celdasLeyenda[1], 0, 1) . ($rowInicioTabla - 1));

                        $sheet->setBorder(substr($celdasLeyenda[0], 0, 1) . ($rowInicioTabla - 1) . ':' . $celdasLeyenda[1], 'thin');

                        $sheet->cell(substr($celdasLeyenda[0], 0, 1) . ($rowInicioTabla - 1), function ($cell) {
                            $cell->setValue('REPORTE DE DOCUMENTOS POR ENTREGAR');

                            $cell->setBackground('#2d366f');
                            $cell->setFontColor('#ffffff');
                            $cell->setAlignment('center');
                            $cell->setValignment('center');
                            $cell->setFont(array(
                                'family' => 'Calibri',
                                'size' => '18',
                                'bold' => true
                            ));
                            $cell->setBorder('thin', 'thin', 'thin', 'thin');
                        });


                        /*
                        $sheet->setCellValue('A1', $celdasLeyenda[0]);
                        $sheet->setCellValue('A2', $celdasLeyenda[1]);
                        */

                        $sheet->cells($celdasLeyenda[0] . ':' . $celdasLeyenda[1], function ($cells) {
                            $cells->setBackground('#2d366f');
                            $cells->setFontColor('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                            $cells->setBorder(array(
                                'bottom' => array(
                                    'style' => 'solid'
                                ),
                            ));
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $dependenciasPendientes = DB::select('EXEC tram.Sp_SEL_dependencias_tramites_pendientes_por_recepcionarXiDepenEmisorId ?', [$data->idDependencia]);

                        // $celInicioContenido = substr($celdasLeyenda[0], 0, 1) . (substr($celdasLeyenda[1], -1) + 1);

                        $colInicioContenido = substr($celdasLeyenda[0], 0, 1);
                        $rowInicioContenido = (substr($celdasLeyenda[1], -1) + 1);

                        foreach ($dependenciasPendientes as $dep) {


                            if (!is_null($dep->iDepenReceptorId)) {
                                // $data[1] = $dep->iDepenReceptorId;
                                $tramDepen = DB::select('EXEC tram.Sp_SEL_tramites_pendientes_por_recepcionarXiDepenEmisorIdXiDepenReceptorId ?, ?', [$data->idDependencia, $dep->iDepenReceptorId]);

                                $tramDepen = $this->filtroFechas($data, $tramDepen, 'dtTramMovFechaHoraEnvio');

                                foreach ($tramDepen as $tram) {

                                    $dataImprimir = [
                                        Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y H:i'),
                                        $tram->cTipoDocDescripcion,
                                        preg_replace('/(.*-\d{4})-(.*)/', '$1' . "\r\n" . '$2', str_replace($tram->cTipoDocDescripcion . ' ', '', $tram->cTramDocumentoTramite)),
                                        $tram->iTramNumRegistro,
                                        preg_replace('/(.*) - (.*)/', '$1' . "\r\n" . '$2', $tram->cAbrev_Emisor),
                                        $tram->iTramFolios,
                                        ($tram->cTramAsunto ?? $tram->cTramAsuntoDocumento),
                                        $tram->cTramMovObsEnvio,
                                        $tram->cDepenReceptorAbrev,
                                        '',
                                        '',
                                        '',
                                    ];

                                    $colInicioContenidoInicial = $colInicioContenido;

                                    foreach ($dataImprimir as $datCel) {
                                        $sheet->cell($colInicioContenido . $rowInicioContenido, function ($cell) use ($datCel) {
                                            $cell->setValue($datCel);
                                            $cell->setValignment('center');
                                            $cell->setBorder('thin', 'thin', 'thin', 'thin');
                                        });
                                        // $sheet->setCellValue($colInicioContenido.$rowInicioContenido, $datCel);
                                        $sheet->getStyle($colInicioContenido . $rowInicioContenido)->getAlignment()->setWrapText(true);
                                        ++$colInicioContenido;
                                    }

                                    $colInicioContenido = $colInicioContenidoInicial;
                                    ++$rowInicioContenido;


                                    /*

                                    // dd($tram);
                                    if ($setDep) {
                                        $pdf->dependenciaPadre = $tram->cDepenEmisorNombre;
                                        $setDep = false;
                                    }

                                    $htmlListaTramites['body'] .= '<tr>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['FEC'].'">' . Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y H:i') . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['TDOC_T'].'">' . $tram->cTipoDocDescripcion . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['TDOC_N'].'">'. preg_replace('/(.*-\d{4})-(.*)/', '$1<br>$2', str_replace($tram->cTipoDocDescripcion . ' ', '', $tram->cTramDocumentoTramite)) .'</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['REG'].'">' . $tram->iTramNumRegistro . '</td>';
                                    $htmlListaTramites['body'] .= '<td width="'.$anchoColumnas['REM'].'">' . preg_replace('/(.*) - (.*)/', '$1<br><strong>$2</strong>', $tram->cAbrev_Emisor) . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['FOL'].'">' . $tram->iTramFolios . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="tam80" width="'.$anchoColumnas['ASU'].'">' . ($tram->cTramAsunto??$tram->cTramAsuntoDocumento) . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['PROV_DOC'].'">' . $tram->cTramMovObsEnvio . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['DEST'].'">' . $tram->cDepenReceptorAbrev . '</td>';
                                    $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['FEC_SAL'].'"></td>';
                                    // $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['FEC_SAL'].'">' . Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y') . '</td>';
                                    $htmlListaTramites['body'] .= '<td width="'.$anchoColumnas['FIRM'].'"></td>';
                                    $htmlListaTramites['body'] .= '<td width="'.$anchoColumnas['OBS'].'"></td>';
                                    $htmlListaTramites['body'] .= '</tr>';

                                    */
                                }
                            }


                        }

                        /*
                        $sheet->cells('A2:E3', function ($cells) {
                            $cells->setBackground('#2d366f');
                            $cells->setFontColor('#ffffff');
                            $cells->setFont(array(
                                'family' => 'Calibri',
                                //'size' => '16',
                                'bold' =>  true
                            ));

                            $cells->setAlignment('center');
                            $cells->setValignment('center');

                            $cells->setBorder(array(
                                'bottom'   => array(
                                    'style' => 'solid'
                                ),
                            ));
                        });
                        $sheet->mergeCells('A2:E2');
                        $sheet->setCellValue('A2', 'REPORTE');
                        $sheet->setCellValue('A3', 'Registro');
                        */


                    });
                    break;
            }
            //})->export('pdf');
        })->download('XLSX');
    }

    public function generarPDFTramite(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');
        $salida = $request->get('salida');

        // dd($data);
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
        height: 70px;
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

        $dataTramite = null;
        switch ($req) {
            case 'por_recepcionar':

                $collectData = collect();
                $setDep = true;
                /*  CAMBIOS RECIENTES  */
                if (isset($data->seleccionados)) {
                    $tramDepen = DB::select('EXEC tram.Sp_SEL_tramites_pendientes_por_recepcionarXiDepenEmisorId ?', [$data->idDependencia]);
                    foreach ($tramDepen as $tram) {
                        foreach ($data->seleccionados as $k => $seleccionado) {
                            if ($tram->iTramMovId == $k && $seleccionado) {
                                if ($setDep) {
                                    $pdf->dependenciaPadre = $tram->cDepenEmisorNombre;
                                    $setDep = false;
                                    $pdf->textoEncabezado->add([ 'text' => $tram->cDepenEmisorNombre, 'font' => $roboto_bold, 'size' => 12, 'esp' => false ]);
                                }
                                $collectData->add($tram);
                            }
                        }
                    }
                } else {
                    $pdf->dependencia = "";
                    $depPend = DB::select('EXEC tram.Sp_SEL_dependencias_tramites_pendientes_por_recepcionarXiDepenEmisorId ?', [$data->idDependencia]);

                    foreach ($depPend as $dP) {
                        $tramDepen = DB::select('EXEC tram.Sp_SEL_tramites_pendientes_por_recepcionarXiDepenEmisorIdXiDepenReceptorId ?, ?', [$data->idDependencia, $dP->iDepenReceptorId]);
                        // $tramDepen = $this->filtroFechas($data, $tramDepen, 'dtTramMovFechaHoraEnvio');
                        foreach ($tramDepen as $tram) {
                            if ($setDep) {
                                $pdf->dependenciaPadre = $tram->cDepenEmisorNombre;
                                $setDep = false;
                                $pdf->textoEncabezado->add([ 'text' => $tram->cDepenEmisorNombre, 'font' => $roboto_bold, 'size' => 12, 'esp' => false ]);
                            }
                            $collectData->add($tram);
                        }
                    }
                }




                // dd($collectData);

                if (!isset($data->seleccionados)) {

                    $collectData = $this->filtroFechas($data, $collectData, 'dtTramMovFechaHoraEnvio');
                    // $collectData = $collectData->sortBy('iTramNumRegistro');
                    if (isset($data->dataFiltroOrden)) {
                        // dd($data->dataFiltroOrden);
                        switch ($data->dataFiltroOrden) {
                            case '1':
                                $collectData = $collectData
                                    ->sortBy('cDepenReceptorAbrev');

                                break;
                            case '2':
                                $collectData = $collectData
                                    ->sortBy('iTramNumRegistro');
                                break;
                            case '3':
                                $collectData = $collectData
                                    ->sortBy('cTramDocumentoTramite');
                                break;
                        }
                    }
                    else {
                        if ($data->idDependencia == 29) {
                            // $collectData->sortBy('iDepenReceptorId'); // ->sortBy('iTramNumRegistro');
                            $collectData->where('iTipoTramId', 2)->sortBy('iTramNumRegistroXiDepenIdXiYearXiTipoTramId'); // ->sortBy('iTramNumRegistro');
                        }
                        else {
                            $collectData->sortBy('iTramNumRegistro');
                        }
                    }
                }
                else {
                    if ($data->idDependencia == 29) {
                        $collectData->where('iTipoTramId', 2)->sortBy('iTramNumRegistroXiDepenIdXiYearXiTipoTramId'); // ->sortBy('iTramNumRegistro');
                    }
                    else {
                        $collectData = $collectData
                            ->sortBy('cTramDocumentoTramite');
                    }
                }






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

                $htmlListaTramites['header'] = '<thead>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REGINT'] . '">SEG</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FEC'] . '">FEC. RECEPCION</th>';
                $htmlListaTramites['header'] .= '<th colspan="3" width="' . $anchoColumnas['TDOC'] . '">DOCUMENTO</th>';
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
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_T'] . '">REF.</th>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_N'] . '">DOC</th>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['REG'] . '">N° REG</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '</thead>';


                $htmlListaTramites['body'] = '<tbody>';

                foreach ($collectData as $tram) {
                    $htmlListaTramites['body'] .= '<tr nobr="true">';
                    $htmlListaTramites['body'] .= '<td class="centrado" style="font-weight: bold; font-size: 120%;" width="' . $anchoColumnas['REGINT'] . '"> <br>' . $tram->iTramNumRegistroXiDepenIdXiYearXiTipoTramId . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC'] . '">' . Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y H:i') . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_T'] . '">' . Str::limit($tram->cDocumento_Referencia, 100) . '</td>';
                    //$htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_N'] . '">' . str_replace($tram->cTipoDocDescripcion . ' ', $tram->cTipoDocDescripcion . '<br>', $tram->cTramDocumentoTramite) . '<br><b>'.$tram->cTramDocumentoTramite.'</b>' . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_N'] . '">' . $tram->cTramDocumentoTramite . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['REG'] . '">' . $tram->iTramNumRegistro . '</td>';
                    $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['REM'] . '">' . preg_replace('/(.*) - (.*)/', '$1<br><strong>$2</strong>', $tram->cAbrev_Emisor) . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FOL'] . '">' . $tram->iTramFolios . '</td>';
                    $htmlListaTramites['body'] .= '<td class="justificado" width="' . $anchoColumnas['ASU'] . '">' . Str::limit($tram->cTramAsunto ?? $tram->cTramAsuntoDocumento, 350) . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['PROV_DOC'] . '">' . $tram->cTramMovObsEnvio . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['DEST'] . '">' . $tram->cDestinoAbrev . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC_SAL'] . '"></td>';
                    // $htmlListaTramites['body'] .= '<td class="centrado" width="'.$anchoColumnas['FEC_SAL'].'">' . Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y') . '</td>';
                    $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['FIRM'] . '"></td>';
                    $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['OBS'] . '"></td>';
                    $htmlListaTramites['body'] .= '</tr>';
                }

                $htmlListaTramites['body'] .= '</tbody>';

                $pdf->SetFont($roboto_regular, '', 7, '', 'default', true);
                $htmlPrintTable = $htmlStyles;
                $htmlPrintTable .= '<table class="normal" cellspacing="0" cellpadding="3" border="1">' . $htmlListaTramites['header'] . $htmlListaTramites['body'] . '</table>';

                $pdf->AddPage('L', 'A4');
                $htmlFooter = '<p style="font-size: 9px; text-align: justify;">Reporte generado por el Módulo de Tramite Documentario. ' . now()->format('d/m/Y H:i') . '<br>';
                $htmlFooter .= 'SIGEUN (http://sigeun.unam.edu.pe)</p>';

                $pdf->addHtmlFooter = $htmlFooter;
                $pdf->writeHTML($htmlPrintTable, true, false, false, true, '');

                break;
            case 'detalles':
                //$respuesta = collect(DB::select('EXEC tram.Sp_SEL_Tramites_Referencias_SeguimientoXiTramId ?', [$data->idTram]))->first();
                $respuesta = TramiteOnController::detalleSeguimiento($data->iTramId, true);

                if (isset($respuesta['data'])){

                    // $pdf->dependenciaPadre = 'Seguimiento de Tramite ' . $respuesta['header']->iTramNumRegistro;

                    $pdf->textoEncabezado = collect();
                    $pdf->textoEncabezado->add([ 'text' => 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 'font' => $roboto_bold, 'size' => 13, 'esp' => true ]);
                    $pdf->textoEncabezado->add([ 'text' => 'Seguimiento de Tramite ' . $respuesta['header']->iTramNumRegistro, 'font' => $roboto_bold, 'size' => 12, 'esp' => false ]);

                    $tablaSrc = [
                        'propiedades' => [
                            'class' => 'darkMode'
                        ],
                        'contenido' => [
                            [
                                ['th', ['width' => 100], 'Documento'],
                                ['td', ['width' => 400], $respuesta['header']->cTramDocumentoTramite],
                                ['th', ['width' => 100], 'Registro'],
                                ['td', ['width' => 339], $respuesta['header']->iTramNumRegistro],
                            ],
                            [
                                ['th', 'Remitente'],
                                ['td', $respuesta['header']->cNombre_Emisor],
                                ['th', 'Dependencia'],
                                ['td', $respuesta['header']->cDepenEmisorNombre],
                            ],
                            [
                                ['th', 'Asunto'],
                                ['td', $respuesta['header']->cTramAsuntoDocumento],
                                ['th', 'Fecha de Creacion'],
                                ['td', $respuesta['headerDate']],
                            ],
                        ]
                    ];
                    $htmlTablaEncabezado = $this->genTable($tablaSrc);


                    $tablaSrcSeguimiento = [
                        'propiedades' => [
                            'class' => 'darkMode'
                        ],
                        'contenido' => [
                            [

                                ['th', ['colspan' => 7, 'class' => 'centrado'], 'Seguimiento'],
                            ],
                            '<thead>',
                            [
                                ['th', ['width' => 20, 'class' => 'centrado'], '', ],
                                ['th', ['width' => 100, 'class' => 'centrado'], 'Fecha y Hora'],
                                ['th', ['width' => 180, 'class' => 'centrado'], 'Dependencia'],
                                ['th', ['width' => 180, 'class' => 'centrado'], 'Documento'],
                                ['th', ['width' => 310, 'class' => 'centrado'], 'Asunto'],
                                ['th', ['width' => 50, 'class' => 'centrado'], 'Estado'],
                                ['th', ['width' => 99, 'class' => 'centrado'], 'Transcurrido'],
                            ],
                            '</thead>'
                        ]
                    ];
                    foreach ($respuesta['data'] as $idx => $reg) {
                        $tablaSrcSeguimiento['contenido'][] = [
                            ['th', ['width' => 20, 'class' => 'centrado'], ($idx + 1)],
                            ['td', ['width' => 100], ($reg->iTramMovId != '') ? '<b>Env.: </b>'.Carbon::parse($reg->dtTramMovFechaHoraEnvio)->format('d/m/Y H:i').($reg->dtTramMovFechaHoraRecibido != '' ? '<br><b>Rec.: </b>'.Carbon::parse($reg->dtTramMovFechaHoraRecibido)->format('d/m/Y H:i') : '')  : ''],
                            ['td', ['width' => 180], ($reg->iTramMovId == '') ? '<b>Creado:</b>'.$reg->cDepenCreadorNombre : '<b>Recibido: </b>'.($reg->cDepenEmisorNombre??' - ').'<br><b>Enviado.:</b>'.($reg->cDestino??' - '). (trim($reg->iPersFirmaReceptorNombre) != ''?' (<small>'. $reg->iPersFirmaReceptorNombre .'</small>)' : '')],
                            ['td', ['width' => 180], 'N° Reg: '.$reg->iTramNumRegistro.'<br>Doc.: '.$reg->cTramDocumentoTramite. ($reg->iTramMovCopia == 1 ? ' <small style="color: red;">COPIA</small>' : '') .'<br>Fecha: '.Carbon::parse($reg->dtTramFechaDocumento)->format('d/m/Y H:i').'<br>Folios: '.$reg->iTramFolios.''.(!is_null($reg->cTramObservaciones) ? '<br><span style="color: red;">'.$reg->cTramObservaciones .'</span>' : '')],
                            ['td', ['width' => 310, 'class' => 'justificado'], '<p>'.$reg->cTramAsuntoDocumento.'</p>'.(!is_null($reg->cTramMovObsArchivado) ? '<small style="color: green;"><b>Archivado: </b>'.$reg->cTramMovObsArchivado.'</small>' : '')],
                            ['td', ['width' => 50, 'class' => 'centrado'], $reg->cEstadoTramiteNombre],
                            ['td', ['width' => 99], $reg->ttr],
                        ];
                    }
                    $htmlTablaSeguimiento = $this->genTable($tablaSrcSeguimiento);

                    $tablaFooterSrc = [
                        'propiedades' => [
                            'class' => 'darkMode'
                        ],
                        'contenido' => [
                            [
                                ['th', ['width' => 300], 'Última Dependencia'],
                                ['td', ['width' => 639], $respuesta['ultimaDep']],
                            ],
                        ]
                    ];
                    $htmlTablaFooter = $this->genTable($tablaFooterSrc);

                    $htmlPrintTable = $htmlStyles;
                    $htmlPrintTable .= $htmlTablaEncabezado;
                    $htmlPrintTable .= '<br><br><br>';
                    $htmlPrintTable .= $htmlTablaSeguimiento;
                    $htmlPrintTable .= '<br><br>';
                    $htmlPrintTable .= $htmlTablaFooter;
                    // dd($htmlPrintTable);

                    $pdf->AddPage('L', 'A4');
                    $htmlFooter = '<p style="font-size: 9px; text-align: justify;">Reporte generado por el Módulo de Tramite Documentario. ' . now()->format('d/m/Y H:i') . '<br>';
                    $htmlFooter .= 'SIGEUN (http://sigeun.unam.edu.pe)</p>';

                    $pdf->addHtmlFooter = $htmlFooter;
                    $pdf->SetFont($roboto_regular, '', 7, '', 'default', true);
                    $pdf->writeHTML($htmlPrintTable, true, false, false, true, '');
                }

                break;
        }


        // print colored table
        // $pdf->ColoredTable($header, $data,$funcion);
        ob_end_clean();
        // ---------------------------------------------------------

        if (isset($salida) && $salida != '') {
            $pdf->Output('UNAM-SIGEUN.pdf', $salida);
        } else {
            $pdf->Output('UNAM-SIGEUN.pdf', 'I');
        }


        //return $pdf;

    }

    public function filtroFechas($filtro, $datos, $campo)
    {
        $coleccionDatos = collect($datos);
        switch ($filtro->option) {
            case 1:
                $fechaSel = Carbon::parse($filtro->fecha);
                $respuesta = $coleccionDatos->whereBetween($campo, [$fechaSel->format('Y-m-d'), $fechaSel->endOfDay()]);

                break;
            case 2:
                $periodoSel = Carbon::parse($filtro->year . '-' . $filtro->month . '-01');
                $respuesta = $coleccionDatos->whereBetween($campo, [$periodoSel->format('Y-m-d'), $periodoSel->endOfMonth()]);
                break;
            case 3:
                $fechaIni = Carbon::parse($filtro->range_1);
                $fechaFin = Carbon::parse($filtro->range_2);
                $respuesta = $coleccionDatos->whereBetween($campo, [$fechaIni->format('Y-m-d'), $fechaFin->endOfDay()]);
                break;
        }
        return $respuesta;
    }

    public function genTable($dataTable) {

        $txtPropiedades = '';
        if (!isset($dataTable['propiedades']['cellspacing'])) {
            $dataTable['propiedades']['cellspacing'] = 0;
        }
        if (!isset($dataTable['propiedades']['cellpadding'])) {
            $dataTable['propiedades']['cellpadding'] = 3;
        }
        if (!isset($dataTable['propiedades']['border'])) {
            $dataTable['propiedades']['border'] = 1;
        }
        foreach ($dataTable['propiedades'] as $propiedad => $propValor) {
            $txtPropiedades .= "{$propiedad}=\"{$propValor}\" ";
        }


        $htmlTabla = '';
        foreach ($dataTable['contenido'] as $fila) {
            if (is_string($fila)) {
                $htmlTabla .= $fila;
            } else {

                $htmlTabla .= '<tr nobr="true">';
                foreach ($fila as $columna) {
                    //dd($columna[1]);
                    $txtPropCol = '';
                    $txtContenido = $columna[1];
                    if (is_array($columna[1])) {
                        foreach ($columna[1] as $propCol => $propColVal) {
                            // dd($propCol);
                            $txtPropCol .= "{$propCol}=\"{$propColVal}\" ";
                        }
                        $txtContenido = $columna[2];
                    }
                    $htmlTabla .= "<{$columna[0]} {$txtPropCol}>{$txtContenido}</{$columna[0]}>";
                }
                $htmlTabla .= '</tr>';
            }
        }

        $htmlCompleto = '<table '. $txtPropiedades .'>' . $htmlTabla . '</table>';

        return $htmlCompleto;
    }


    public function BusquedaFileGeneral(Request $request)
    {
        if (!$request->texto) {
            $request->texto = '';
        }
        $resultado =
            DB::select(
                'EXEC tram.Sp_SEL_tramitesXiDepenEmisorIdXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?',
                array($request->dependencia, '', 0, 0, '', '', $request->periodo, $request->tipo, $request->texto)
            );
        // dd($resultado);
        $head = [
            ['title' => 'Fecha', 'campo' => 'dtTramFechaDocumento', 'width' => '25', 'align' => 'center', 'type' => 'date'],
            ['title' => '#Reg.', 'campo' => 'iTramNumRegistro', 'width' => '15', 'align' => 'center'],
            ['title' => 'Documento', 'campo' => 'cTramDocumentoTramite', 'width' => '35', 'align' => 'center'],
            ['title' => 'Asunto', 'campo' => 'cTramAsuntoDocumento', 'width' => '80', 'align' => 'left'],
            // [ 'title' => 'Contenido', 'campo' => 'cTramContenido', 'width' => '50', 'align' => 'left' ],
            // [ 'title' => 'Emisor Doc', 'campo' => 'cDocumento_Emisor', 'width' => '15', 'align' => 'center' ],
            ['title' => 'Emisor \n Nombre', 'campo' => 'cNombre_Emisor', 'width' => '45', 'align' => 'left'],
        ];
        $leyenda = [
            // ['title' => 'Dependencia' , 'value' => $request->dependencia],
            // ['title' => 'Periodo' , 'value' => $request->periodo],
            // ['title' => 'Tipo' , 'value' => $request->tipo]
        ];

        // return response()->json( $resultado );

        if ($request->exp == 1) {
            $generado = new ReporteExcel;
            $generado->generateExcel('Reporte Consulta', $leyenda, $head, $resultado);
        }
        if ($request->exp == 2) {
            // return response()->json($resultado);

            set_time_limit(180);

            if (count($resultado) > 0) {
                $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->formatoSeleccionado = 6;

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
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



                // ---------------------------------------------------------


                $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
                $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
                $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);

                $pdf->tipoHeader = 'reportes_tramite';

                $collectData = collect($resultado);

                $pdf->textoEncabezado = collect();
                $pdf->textoEncabezado->add([ 'text' => 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 'font' => $roboto_bold, 'size' => 13, 'esp' => true ]);
                $pdf->textoEncabezado->add([ 'text' => $collectData->first()->cDepenNombreEmisor , 'font' => $roboto_bold, 'size' => 12, 'esp' => false ]);
                $pdf->textoEncabezado->add([ 'text' => 'DOCUMENTOS EMITIDOS' , 'font' => $roboto_bold, 'size' => 13, 'esp' => true ]);

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

                    'REGINT' => 60,
                    'FEC' => 55,

                    'TDOC' => 340,

                    'TDOC_T' => 200,
                    'TDOC_N' => 80,
                    'REG' => 60,

                    'REM' => 200,
                    'FOL' => 30,
                    'ASU' => 250,

                ];

                $htmlListaTramites['header'] = '<thead>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REGINT'] . '">REG. INT.</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FEC'] . '">FEC. REGISTRO</th>';
                $htmlListaTramites['header'] .= '<th colspan="2" width="' . $anchoColumnas['TDOC'] . '">DOCUMENTO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REM'] . '">REMITENTE</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FOL'] . '">FOL.</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['ASU'] . '">ASUNTO</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_T'] . '">REF.</th>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_N'] . '">DOC</th>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['REG'] . '">REG</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '</thead>';


                $htmlListaTramites['body'] = '<tbody>';

                foreach ($collectData as $tram) {
                    $htmlListaTramites['body'] .= '<tr nobr="true">';
                    $htmlListaTramites['body'] .= '<td class="centrado" style="font-weight: bold; font-size: 120%;" width="' . $anchoColumnas['REGINT'] . '"> <br>' . $tram->iTramNumRegistroXiDepenIdXiYearXiTipoTramId . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC'] . '">' . Carbon::parse($tram->dtTramFechaHoraRegistro)->format('d/m/Y H:i') . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_T'] . '">' . $tram->cTramAsuntoDocumento . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_N'] . '">' . $tram->cTramDocumentoTramite . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['REG'] . '">' . $tram->iTramNumRegistro . '</td>';
                    $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['REM'] . '">' . $tram->cNombre_Emisor . '</td>';
                    $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FOL'] . '">' . $tram->iTramFolios . '</td>';
                    $htmlListaTramites['body'] .= '<td class="justificado" width="' . $anchoColumnas['ASU'] . '">' . $tram->cTramAsuntoDocumento . '</td>';
                    $htmlListaTramites['body'] .= '</tr>';
                }

                $htmlListaTramites['body'] .= '</tbody>';

                $pdf->SetFont($roboto_regular, '', 7, '', 'default', true);
                $htmlPrintTable = $htmlStyles;
                $htmlPrintTable .= '<table class="normal" cellspacing="0" cellpadding="3" border="1">' . $htmlListaTramites['header'] . $htmlListaTramites['body'] . '</table>';

                $pdf->AddPage('L', 'A4');
                $htmlFooter = '<p style="font-size: 9px; text-align: justify;">Reporte generado por el Módulo de Tramite Documentario. ' . now()->format('d/m/Y H:i') . '<br>';
                $htmlFooter .= 'SIGEUN (http://sigeun.unam.edu.pe)</p>';

                $pdf->addHtmlFooter = $htmlFooter;
                $pdf->writeHTML($htmlPrintTable, true, false, false, true, '');





                ob_end_clean();
                $pdf->Output('unam-SIGEUN.pdf', 'I');

            }

//            $generado = new ReporteExcel;
//            return $generado->generatePDF('horizontal', 'Reporte de Documentos Creados', 'Reporte Consulta', $leyenda, $head, $resultado);
        }
    }

    public function generateHtPdf(Request $request)
    {
        $generado = new ReporteExcel;
        return $generado->generateHtml($request->title, $request->html);
    }

    public function fileDetalles(Request $request)
    {
        $resultado =
            DB::select(
                'EXEC tram.Sp_SEL_Tramites_Referencias_SeguimientoXiTramId ?',
                array($request->idTram)
            );
        $header = [];

        for ($i = 0; $i < count($resultado); $i++) {
            if ($resultado[$i]->bPrincipal == 1) {
                $header = $resultado[$i];
            }
        }
        for ($i = 0; $i < count($resultado); $i++) {

            $estadoTemp = '';
            $estadoTemp = explode("#", $resultado[$i]->cEstadoTramiteNombre);;
            $resultado[$i]->cEstadoTramiteNombre = $estadoTemp[1];

            if ($resultado[$i]->dtTramMovFechaHoraRecibido != null) {
                $resultado[$i]->date = Carbon::parse($resultado[$i]->dtTramMovFechaHoraRecibido)->formatLocalized('%Y/%m/%d  %R');
            } else {
                $resultado[$i]->date = '-';
            }

            $resultado[$i]->ttr = '-';
            if (count($resultado) > 0) {
                if ($resultado[$i]->dtTramMovFechaHoraRecibido != null) {
                    $resultado[$i]->ttr = $this->calculaTime($header->dtTramFechaDocumento, $resultado[$i]->dtTramMovFechaHoraRecibido);
                } else {
                    $resultado[$i]->ttr = '-';
                }

            }
        }

        if (!$header->dtTramFechaDocumento || $header->dtTramFechaDocumento != null) {
            $headerDate = Carbon::parse($header->dtTramFechaDocumento)->formatLocalized('%A %d %B %Y ');
        } else {
            $headerDate = '-';
        }

        $nlast = count($resultado);
        $ultimaDep = $resultado[$nlast - 1]->cDepenReceptorNombre;
        $ultimaHora = $resultado[$nlast - 1]->cDepenReceptorNombre;

        if ($resultado > 0) {
            if ($resultado[$nlast - 1]->dtTramMovFechaHoraRecibido != null) {
                $totalDias = $this->calculaTime($header->dtTramFechaDocumento, $resultado[$nlast - 1]->dtTramMovFechaHoraRecibido);
            } else {
                $totalDias = '-';
            }
        } else {
            if ($resultado[0]->dtTramMovFechaHoraRecibido != null) {
                $totalDias = $this->calculaTime($header->dtTramFechaDocumento, $resultado[0]->dtTramMovFechaHoraRecibido);
            } else {
                $totalDias = '-';
            }
        }
        // return response()->json($resultado);
        // return response()->json(['data'=>$resultado,'header'=>$header ]);
        if (is_string($headerDate) == false) {
            $headerDate = '-';
        }
        $pdf = PDF::loadView('reportes.detallePDF', compact(['resultado', 'header', 'ultimaDep', 'totalDias', 'headerDate']))->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function calculaTime($time1, $time2)
    {
        $totalDataDias =
            DB::select('EXEC grl.Sp_DiasHabilesXiEntIdXdfecha_iniXdFecha_fin 1,?,? ',
                array(Carbon::parse($time1)->formatLocalized('%Y%m%d  %R'), Carbon::parse($time2)->formatLocalized('%Y%m%d  %R'))
            );
        $tiempoResult = '';
        $totalDias = $totalDataDias[0]->nDiasHabiles;
        $separeDate = explode(".", $totalDias);
        $decimalDays = '0.' . $separeDate[1];
        $decimalDays = floatval($decimalDays);
        $decimalDays = $decimalDays * 24;
        $tiempoResult = $separeDate[0] . ' Dias, ' . intval($decimalDays) . ' Horas.';

        return $tiempoResult;
    }

    public function fileListArchivado($tf, $tipo, $dp, $dia, $periodo, $mes, $rango1, $rango2)
    {
        $resultado = [];

        if ($tipo == 1) {
            $resultado =
                DB::select('EXEC tram.Sp_SEL_tramites_archivadosXiDepenIdXcConsultaVariablesCampos ?,?,?,?,?,?',
                    array($dp, $dia, '', '', '', '')
                );
        }
        if ($tipo == 2) {
            $resultado =
                DB::select('EXEC tram.Sp_SEL_tramites_archivadosXiDepenIdXcConsultaVariablesCampos ?,?,?,?,?,?',
                    array($dp, '', $periodo, $mes, '', '')
                );
        }
        if ($tipo == 3) {
            $resultado =
                DB::select('EXEC tram.Sp_SEL_tramites_archivadosXiDepenIdXcConsultaVariablesCampos ?,?,?,?,?,?',
                    array($dp, '', '', '', $rango1, $rango2)
                );
        }
        $head = [
            ['title' => 'Fecha', 'campo' => 'dtTramFechaDocumento', 'width' => '25', 'align' => 'center', 'type' => 'date'],
            ['title' => '#Reg.', 'campo' => 'iTramNumRegistro', 'width' => '15', 'align' => 'center'],
            ['title' => 'Documento', 'campo' => 'cTramDocumentoTramite', 'width' => '35', 'align' => 'center'],
            ['title' => 'Asunto', 'campo' => 'cTramAsuntoDocumento', 'width' => '80', 'align' => 'left'],
            ['title' => 'Contenido', 'campo' => 'cTramContenido', 'width' => '50', 'align' => 'left'],
            ['title' => 'Emisor Doc', 'campo' => 'cDocumento_Emisor', 'width' => '15', 'align' => 'center'],
            ['title' => 'Emisor \n Nombre', 'campo' => 'cNombre_Emisor', 'width' => '45', 'align' => 'left'],
        ];
        // return response()->json($resultado);
        if ($tf == 1) {
            $generado = new ReporteExcel;
            $generado->generateExcel('Reporte Consulta', [], $head, $resultado);
        }
        if ($tf == 2) {
            $generado = new ReporteExcel;
            return $generado->generatePDF('horizontal', 'DASA', 'Reporte Consulta', [], $head, $resultado);
        }
    }
}
