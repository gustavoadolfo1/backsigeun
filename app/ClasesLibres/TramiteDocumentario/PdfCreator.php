<?php


namespace App\ClasesLibres\TramiteDocumentario;


use App\Http\Controllers\Ura\UraPlanCurricularController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use TCPDF_FONTS;

class PdfCreator extends \TCPDF {
    public $dependenciaPadre = "VICEPRESIDENCIA ACADEMICA";
    public $dependencia = "Dirección de Actividades y Servicios Académicos";
    public $formatoSeleccionado;

    public $porQr = false;

    public $qrValue = null;
    public $numeracionValue = null;
    public $fechaAceptado = null;
    public $addHtmlFooter = null;
    public $addHtmlHeader = null;

    public $fontSizeTabla = 8;
    public $fontSizeGeneral = 10;

    public $mostrarLogoHeader = true;
    public $mostrarQr = true;
    public $mostrarNumeracionCustom = true;

    public $tipoFooter = null;
    public $tipoHeader = null;

    public $textoEncabezado = null;

    public function HeaderPropuesta() {
        $this->ImageSVG(public_path('pdf_src/fondo_header.svg'), 0, 0);



        // set style for barcode
        $style = array(
            'border' => 2,
            'padding' => 2,
            'fgcolor' => array(45,54,111),
            'bgcolor' => array(255,255,255),
            // 'bgcolor' => false, //
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        /*
        $style = array(
            'border' => false,
            'padding' => 1,
            'fgcolor' => array(128,0,0),
            'bgcolor' => array(255,255,255),
        );
        */

        $this->write2DBarcode('www.tcpdf.org', 'QRCODE,H', 170, 10, 50, 50, $style, 'T');
        $this->Ln();


        $style5 = array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(87, 199, 194));
        $style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
        // $this->SetLineStyle($style5);
        $this->Circle(17,17,13,0, 360, 'DF', $style5, [255,255,255]);
        $this->ImageSVG(public_path('pdf_src/LogoUnam.svg'), 7, 9, 20);

        $this->SetTextColor(255,255,255);
        $this->SetFont ('helvetica', 'B', $this->pixelsToUnits('50') , '', 'default', true );
        $this->Cell(0, 0, 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 0, 1, 'C', 0, '', 0);

        $this->SetFont ('helvetica', '', $this->pixelsToUnits('45') , '', 'default', true );
        $this->Cell(0, 0, $this->dependenciaPadre, 0, 1, 'C', 0, '', 0);
        $this->SetFont ('helvetica', 'I', $this->pixelsToUnits('40') , '', 'default', true );
        $this->Cell(0, 0, $this->dependencia, 0, 1, 'C', 0, '', 0);




    }

    public function FooterPropuesta() {
        $this->ImageSVG(public_path('pdf_src/fondo_footer.svg'), 0, 275);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        if ($this->getAliasNbPages() > 1)
            $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Header() {


        switch ($this->tipoHeader){
            case 'carpeta':
                if ($this->addHtmlHeader){
                    $this->writeHTML($this->addHtmlHeader, true, false, false, true, '');
                }
                break;
            case 'reportes_tramite':
                $this->ImageSVG(public_path('pdf_src/LogoUnam.svg'), 7, 7, 20);
                foreach ($this->textoEncabezado as $h) {
                    $h = (object) $h;
                    //dd( (object) $h);
                    if ($h->esp) {
                        $this->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
                    }
                    $this->SetFont($h->font, (isset($h->regular) && $h->regular)?'':'B', $h->size, '', 'default', true);
                    $this->Cell(0, 0, $h->text, 0, 1, 'C', 0, '', 0);
                }

                break;
            default:

                switch ($this->formatoSeleccionado){
                    case 6:
                        if ( (!$this->mostrarLogoHeader) && ($this->porQr)) {
                            $this->asignarFondo(public_path('pdf_src/fondo/fondo_certificado.jpg'));
                        } else {
                            if ($this->porQr) {
                                $this->asignarFondo(public_path('pdf_src/fondo/fondo_certificado2.jpg'));
                            }
                        }
                        break;
                }


                if ($this->mostrarQr){
                    if ($this->qrValue) {
                        // set style for barcode
                        $style = array(
                            'border' => 2,
                            'padding' => 2,
                            'fgcolor' => array(45,54,111),
                            'bgcolor' => array(255,255,255),
                            // 'bgcolor' => false, //
                            'module_width' => 1, // width of a single module in points
                            'module_height' => 1 // height of a single module in points
                        );
                        $this->write2DBarcode(route('tramites.pdfPublico', ['iDocIdEncoded' => $this->qrValue]), 'QRCODE,H', 180, 10, 20, 20, $style, 'T');
                        $this->Ln();
                    }
                }
                if ($this->mostrarLogoHeader) {
                    $this->ImageSVG(public_path('pdf_src/LogoUnam.svg'), 7, 9, 25);
                }

                $puntosAdicionales = 3;

                $this->SetTextColor(0,0,0);
                $this->SetFont ('helvetica', 'B', 10 + $puntosAdicionales , '', 'default', true );
                $this->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
                $this->Cell(0, 0, 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 0, 1, 'C', 0, '', 0);
                $this->SetFont ('helvetica', '', 9 + $puntosAdicionales, '', 'default', true );
                $this->Cell(0, 0, $this->dependenciaPadre, 0, 1, 'C', 0, '', 0);
                $this->SetFont ('helvetica', '', 7 + $puntosAdicionales , '', 'default', true );
                $this->Cell(0, 0, $this->dependencia, 0, 1, 'C', 0, '', 0);


                if ( ($this->numeracionValue) && ($this->mostrarQr)) {
                    $this->SetFont('helvetica', 'I', 6);
                    $this->writeHTML('N°:'.$this->numeracionValue, true, false, true, false, 'R');
                }

                break;
        }




    }

    public function Footer() {

        switch ($this->tipoFooter) {
            case 'carpeta':
                $this->FooterCarpeta();
                break;
            default:

                $this->SetY(-20);
                $this->SetFont('helvetica', 'I', 8);
                $cur_y = $this->y;
                $this->SetTextColorArray($this->footer_text_color);
                //set style for cell border
                $line_width = (0.85 / $this->k);
                $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color));
                //print document barcode
                $w_page = isset($this->l['w_page']) ? $this->l['w_page'].' ' : '';
                if (empty($this->pagegroups)) {
                    $pagenumtxt = $w_page.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
                } else {
                    $pagenumtxt = $w_page.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
                }
                $this->SetY($cur_y);
                $this->SetX($this->original_lMargin);
                $this->Cell(0, 0, $this->getAliasRightShift().$pagenumtxt, 'T', 0, 'R');
                if ($this->addHtmlFooter){
                    $this->writeHTML($this->addHtmlFooter, true, false, false, true, '');
                }

                break;
        }


    }

    public function FooterCarpeta() {
        $this->SetY(-20);
        $this->SetFont('helvetica', 'I', 8);
        $cur_y = $this->y;
        $this->SetTextColorArray($this->footer_text_color);
        //set style for cell border
        $line_width = (0.85 / $this->k);
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color));

        if ($this->addHtmlFooter){
            $this->writeHTML($this->addHtmlFooter, true, false, false, true, '');
        }
    }

    public function infoEstudiante($dataTramite) {

        // $this->AddPage();

        // Color and font restoration
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        $this->Ln();

        $dataEstudiante = [
            [
                ['label' => 'CÓDIGO / DNI', 'valor' => $dataTramite->cEstudCodUniv . ' / ' . $dataTramite->cDocumentoEstudiante],
                ['label' => 'SEDE / LUGAR', 'valor' => $dataTramite->cFilDescripcion],
            ],
            [
                ['label' => 'ESTUDIANTE', 'valor' => $dataTramite->cNombreEstudiante],
                ['label' => 'CURRICULA', 'valor' => $dataTramite->cCurricAnio],
            ],
            [
                ['label' => 'CARRERA PROFESIONAL', 'valor' => $dataTramite->cCarreraDsc],
                ['label' => 'REGIMEN', 'valor' => 'FLEXIBLE'],
            ],
        ];


        $this->SetFontSize(6);
        $tablaEstudiante = '<table cellspacing="0" cellpadding="2" border="1">';
        foreach ($dataEstudiante as $rowData) {
            $tablaEstudiante .= '<tr>';

            $tablaEstudiante .= '<td width="130"><strong>'. $rowData[0]['label'] . '</strong></td>';
            $tablaEstudiante .= '<td width="250"><em>'. $rowData[0]['valor'] . '</em></td>';

            $tablaEstudiante .= '<td width="80"><strong>'. $rowData[1]['label'] . '</strong></td>';
            $tablaEstudiante .= '<td width="180"><em>'. $rowData[1]['valor'] . '</em></td>';

            $tablaEstudiante .= '</tr>';
        }
        $tablaEstudiante .= '</table>';

        $this->writeHTML($tablaEstudiante, true, false, false, false, '');

    }

    public function historialAcademico($codEstudiante){
        $datos = app(UraPlanCurricularController::class)->obtenerRecordAcademicoEstudiante($codEstudiante);
        $jsonData = json_decode($datos->getContent());

        // set style for barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        $anchoColumnas = [
            40,
            60,
            265,
            35,
            60,
        ];

        $this->SetFontSize(6);
        $tablaCursosNotas = '<table cellspacing="0" cellpadding="2" border="1">';
        $tablaCursosNotas .= '<tr style="text-align: center">';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[0].'"><strong>CICLO</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[1].'"><strong>CODIGO</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[2].'"><strong>CURSO</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[3].'"><strong>CRED</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[4].'"><strong>NOTA/SEM</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[4].'"><strong>NOTA/SEM</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[4].'"><strong>NOTA/SEM</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[4].'"><strong>NOTA/SEM</strong></td>';
        $tablaCursosNotas .= '</tr>';
        $tablaObligatorios = '';
        $tablaElectivos = '';
        foreach ($jsonData->plan as $curso) {
            $filaCurso = '<tr style="text-align: center">';
            $filaCurso .= '<td width="'.$anchoColumnas[0].'"><em>'.$curso->cCurricDetCicloCurso.'</em></td>';
            $filaCurso .= '<td width="'.$anchoColumnas[1].'"><em>'.$curso->cCurricCursoCod.'</em></td>';
            $filaCurso .= '<td style="text-align: left" width="'.$anchoColumnas[2].'"><em>'.$curso->cCurricCursoDsc.'</em></td>';
            $filaCurso .= '<td width="'.$anchoColumnas[3].'"><em>'.round($curso->nCurricDetCredCurso).'</em></td>';
            foreach ($curso->notas as $idx => $nota) {
                $filaCurso .= '<td width="'.$anchoColumnas[4].'"><em>'. ($nota->nMatricDetNotaCurso??'-') . ' / ' . $nota->iControlCicloAcad.'</em></td>';
            }
            if (count($curso->notas) == 0){
                $idx = -1;
            }
            for ($i = ($idx + 1); $i<=3; $i++) {
                $filaCurso .= "<td width='$anchoColumnas[4]'> ---- </td>";
            }
            $filaCurso .= '</tr>';

            if ($curso->tipo_curso == 'O') {
                $tablaObligatorios .= $filaCurso;
            }
            if ($curso->tipo_curso == 'E') {
                $tablaElectivos .= $filaCurso;
            }
        }
        $tablaCursosNotasFin = '</table>';

        $this->writeHTML($tablaCursosNotas . $tablaObligatorios . $tablaCursosNotasFin, true, false, false, false, '');

        $this->Ln();
        $this->SetFontSize(6);
        $this->writeHTML('<strong style="font-size: 12px;">ELECTIVOS</strong>', true, false, false, false, '');
        $this->writeHTML($tablaCursosNotas . $tablaElectivos . $tablaCursosNotasFin, true, false, false, false, '');
        $this->Ln();

        $secFirma = "<br><br><br><br><br><br><br><br><br><br>______________________________________________<br>{$this->dependencia}<br>UNIDAD DE REGISTRO CENTRAL";
        $secURC = "<br><br><br><br><br>URC / " . $this->fechaAceptado;

        $this->writeHTML($secFirma , true, false, false, false, 'C');
        $this->writeHTML($secURC, true, false, false, false, '');


        // dd($jsonData);
    }

    public function certificadoDeEstudios($dataTramite) {

        // dd($dataTramite[0]);

        //public_path('pdf_src/fondo/fondo_certificado.jpg');


        // $this->MultiCell(32, 4, $this->numeracionValue, 1, '', 0, 1, '170', '52', false);


        // $this->ImageSVG(public_path('pdf_src/fondo_footer.svg'), 0, 275);
        if ($dataTramite[0]->cTramAdjuntarArchivo) {
            $this->Image(public_path('storage/' . $dataTramite[0]->cTramAdjuntarArchivo), 170, 8, 32, 43, 'JPG');
        }


        $this->Rect(170, 8, 32, 43, 'D');


        $fontname = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/BebasNeue-Regular.ttf'), 'TrueTypeUnicode', '', 96);
        $this->SetFont($fontname, 'B', 26, '', 'default', true);
        $this->Cell(0, 0, 'CERTIFICADO DE ESTUDIOS', 0, 1, 'C', 0, '', 0,false, 'T', 'B');
        // $pdf->Ln();

        $this->SetFont('helvetica', 'I', 6);
        $this->writeHTML('<br><br>N°:'.$this->numeracionValue, true, false, true, false, 'R');

        $this->SetFont('helvetica', '', $this->fontSizeGeneral, '', 'default', true);

        $htmlCert = '<p style="text-align: justify;">LA DIRECCIÓN DE ACTIVIDADES Y SERVICIOS ACADÉMICOS DE LA UNIVERSIDAD NACIONAL DE MOQUEGUA CERTIFICA:</p>';
        $htmlCert .= '<p style="text-align: justify;">Que don(ña) <strong>'.$dataTramite[0]->cNombreEstudiante.'</strong> con código  <strong>'.$dataTramite[0]->cEstudCodUniv.'</strong>. Ha cursado las asignaturas que abajo se indican en la escuela profesional de  <strong>'.$dataTramite[0]->cCarreraDsc.'</strong> ubicado en la '.(($dataTramite[0]->iFilId == 1) ? 'sede' : 'filial' ).'  <strong>'.$dataTramite[0]->cFilDescripcion.'</strong>.</p>';
        $htmlCert .= '<p style="text-align: justify;">Habiendo obtenido las calificaciones siguientes: </p>';

        $this->writeHTML($htmlCert, true, false, false, false, '');

        $datos = DB::select('EXEC tram.Sp_SEL_CertificadoEstudiosXcMatricCodUnivXcCadenaCodigoCiclo ?, ?', [$dataTramite[0]->cEstudCodUniv, $dataTramite[0]->cTramContenido]);

        $anchoColumnas = [
            355,
            80,
            100,
            100,
        ];

        $datos = collect($datos);
        $datoPrimero = $datos->first();

        $datos = $datos->groupBy('cNombre_Ciclos');

        // dd($datos);

        //$this->SetFontSize($this->fontSizeTabla);
        $this->SetFont('helvetica', '', $this->fontSizeTabla, '', 'default', true);
        $tablaCursosNotas = '<table cellspacing="0" cellpadding="2" border="0" style="border: 1px solid #000000">';
        $tablaCursosNotas .= '<tr style="text-align: center">';
        $tablaCursosNotas .= '<td style="border-right: 0.1px solid black;" width="'.$anchoColumnas[0].'"><strong>ASIGNATURA</strong></td>';
        $tablaCursosNotas .= '<td style="border-right: 0.1px solid black;" width="'.$anchoColumnas[1].'"><strong>CREDITOS</strong></td>';
        $tablaCursosNotas .= '<td style="border-right: 0.1px solid black;" width="'.$anchoColumnas[2].'"><strong>CALIFICATIVOS</strong></td>';
        $tablaCursosNotas .= '<td width="'.$anchoColumnas[3].'"><strong>SEMESTRE</strong></td>';
        $tablaCursosNotas .= '</tr>';
        $tablaObligatorios = '';
        $tablaElectivos = '';
        foreach ($datos as $idx => $seccion) {
            // dd($seccion);
            $filaHead = '<tr>';
            $filaHead .= '<td style="border-top: 0.1px solid black; border-right: 0.1px solid black;"><strong>'. $idx .'</strong></td>';
            $filaHead .= '<td style="border-top: 0.1px solid black; border-right: 0.1px solid black; text-align: left;"></td>';
            $filaHead .= '<td style="border-top: 0.1px solid black; border-right: 0.1px solid black; text-align: left;"></td>';
            $filaHead .= '<td style="border-top: 0.1px solid black; "></td>';
            $filaHead .= '</tr>';


            $tablaObligatorios .= $filaHead;
            foreach ($seccion as $curso) {
                $filaCurso = '<tr style="text-align: center">';
                $filaCurso .= '<td style="border-right: 0.1px solid black; text-align: left;">'.$curso->cCurricCursoDsc.'</td>';
                $filaCurso .= '<td style="border-right: 0.1px solid black;">'.round($curso->iMatricDetCredCurso).'</td>';
                $filaCurso .= '<td style="border-right: 0.1px solid black; text-align: left;">('.$curso->nMatricDetNotaCurso.') '. $curso->cNumero_a_Letra .'</td>';
                $filaCurso .= '<td>'.$curso->cControlCicloAcademico.'</td>';
                $filaCurso .= '</tr>';

                $tablaObligatorios .= $filaCurso;
            }
        }
        $filaFoot = '<tr>';
        $filaFoot .= '<td style="border-top: 2px solid black; border-right: 0.1px solid black;"><strong>TOTAL DE CREDITOS: '. round($datoPrimero->nTotalCreditos) .'</strong></td>';
        $filaFoot .= '<td style="border-top: 2px solid black; border-right: 0.1px solid black;" colspan="3">';
        if ($datoPrimero->iPromedioPonderado) {
            $filaFoot .= '<strong>PROMEDIO PONDERADO: '. $datoPrimero->nPromedioPonderado .'</strong>';
        }
        $filaFoot .= '</td>';
        $filaFoot .= '</tr>';

        $tablaObligatorios .= $filaFoot;

        $tablaCursosNotasFin = '</table>';

        // $htmlContenido = $tablaCursosNotas . $tablaObligatorios . $tablaCursosNotasFin;

        $this->writeHTML($tablaCursosNotas . $tablaObligatorios . $tablaCursosNotasFin, true, false, false, false, '');
        $this->SetFont('helvetica', '', $this->fontSizeGeneral, '', 'default', true);
        $htmlCert = '<p style="text-align: justify;">Así consta en las actas de evaluación que obran en la '. $this->dependencia .'.</p>';
        $htmlCert .= '<p style="text-align: right;">'. $dataTramite[0]->cDocFechaDoc .'.</p>';

        $htmlContenido = $htmlCert;


        $this->writeHTML($htmlContenido, true, false, false, false, '');
    }

    public function carpetaBachiller($dataTramite) {
        $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);

        $htmlTablaFirma = '<table cellspacing="0" cellpadding="1" border="0">';
        $htmlTablaFirma .= '<tr>';
        $htmlTablaFirma .= '<td colspan="3" style="font-size:9px"><br><br><br>_______________________________________________________________</td>';
        $htmlTablaFirma .= '</tr>';
        $htmlTablaFirma .= '<tr>';
        $htmlTablaFirma .= '<td colspan="3" align="center" style="font-size:9px">FIRMA</td>';
        $htmlTablaFirma .= '</tr>';
        $htmlTablaFirma .= '<tr>';
        $htmlTablaFirma .= '<td width="49%" align="right" style="font-size:9px">Apellidos y Nombres<br>N° DNI</td>';
        $htmlTablaFirma .= '<td width="2%" style="font-size:9px">:<br>:</td>';
        $htmlTablaFirma .= '<td width="49%" align="left" style="font-size:9px">'.$dataTramite->cNombreEstudiante.'<br>'.$dataTramite->cDocumentoEstudiante.'</td>';
        $htmlTablaFirma .= '</tr>';
        $htmlTablaFirma .= '</table>';



        $this->AddPage();
        $this->SetFont($roboto_bold, 'B', 20, '', 'default', true);
        $this->Cell(0, 0, 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 0, 1, 'C', 0, '', 0);
        $this->Ln(3);
        $this->SetFontSize(18);
        $this->Cell(0, 0, 'COMISIÓN ORGANIZADORA', 0, 1, 'C', 0, '', 0);
        $this->Ln(2);
        $this->SetFontSize(16);
        $this->Cell(0, 0, 'VICEPRESIDENCIA ACADÉMICA', 0, 1, 'C', 0, '', 0);
        $this->Ln(150);
        $this->ImageSVG(public_path('pdf_src/LogoUnam.svg'), 65, 110, 80, 0, '', 'c');

        $this->SetFontSize(36);
        $this->Cell(0, 0, 'CARPETA DE GRADUACIÓN', 0, 1, 'C', 0, '', 0);
        $this->Ln(2);
        $this->SetFontSize(20);
        $this->Cell(0, 0, 'MOQUEGUA - PERÚ', 0, 1, 'C', 0, '', 0);

        $this->tipoHeader = 'carpeta';
        $htmlHeader = '<br><br><br>';
        $htmlHeader .= '<table cellspacing="0" cellpadding="2" border="0" style="background-color: rgb(207,207,207); font-weight: bold; text-align: center">';
        $htmlHeader .= '<tr><td style="text-align: center; font-size: 18px;">UNIVERSIDAD NACIONAL DE MOQUEGUA</td></tr>';
        $htmlHeader .= '<tr><td style="text-align: center; font-size: 16px;">VICEPRESIDENCIA ACADÉMICA</td></tr>';
        $htmlHeader .= '</table>';
        $this->addHtmlHeader = $htmlHeader;

        $this->setPrintHeader();

        $this->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP +10, PDF_MARGIN_RIGHT+10, true);


        $this->AddPage();
        $this->tipoFooter = 'carpeta';
        $htmlFooter = '<table cellspacing="0" cellpadding="2" border="0">';
        $htmlFooter .= '<tr>';
        $htmlFooter .= '<td style="text-align: right; color: #0B3970; border-right: 0.1px solid black;" width="400" >'.$dataTramite->cEntWeb.'</td>';
        $htmlFooter .= '<td width="235">'.$dataTramite->cEntDireccion.'<br>'.$dataTramite->cEntTelefono.'</td>';
        $htmlFooter .= '</tr>';
        $htmlFooter .= '</table>';
        $this->addHtmlFooter = $htmlFooter;



        $this->setPrintFooter();


        $requisitos = '
        <li>Solicitud dirigida al Director de la Escuela Profesional</li>
        <li>Cuatro (04) fotografías de frente, tamaño pasaporte, a color, con traje formal y fondo blanco.</li>
        <li>Certificado de Estudios originales, debiendo acreditar el número de créditos exigidos por su Escuela Profesional.</li>
        <li>Constancia de Prácticas Pre-Profesionales, expedido por el Director de la Escuela Profesional respectiva.</li>
        <li>Certificado de estudios del idioma inglés o lengua nativa, nivel intermedio expedido por el Centro de Idiomas de la UNAM.</li>
        <li>Constancia de haber realizado actividades Cocurriculares expedida por la Direccion de Bienestar Universitario y la Direccion de Proyeccion Social y Extensión Universitaria. </li>
        <li>Constancia de "NO ADEUDO" de  Bienes a la Universidad, bienes a los Laboratorios Básicos y de Especialidad, Material Bibliográfico; dichos documentos deberán ser refrendados por los responsables a cargo.</li>
        <li>Recibo de pago por derecho de Grado, determinado en el TUPA y expedido por la Unidad de Tesorería.</li>
        <li>Copia simple de la Constancia de Egresado.</li>
        ';

        $this->SetFontSize(12);
        $this->writeHTML('<U>REQUISITOS PARA OBTENER EL GRADO ACADÉMICO DE BACHILLER</U>', true, false, false, false, 'C');
        $this->Ln();
        //$pdf->SetFont('helvetica', '', 12, '', 'default', true);
        $this->SetFont($roboto_regular, '', 12, '', 'default', true);
        $this->writeHTML('<ul style="text-align: justify; line-height: 150%; list-style-type:img|png|4|4|pdf_src/checkbox_no.png">'.$requisitos.'</ul>', true, false, false, false, '');


        $this->AddPage();
        $this->SetFont($roboto_regular, '', 10, '', 'default', true);

        $htmlP03Req = '<ul style="text-align: justify; font-size: 9px; list-style-type:img|png|4|4|pdf_src/checkbox_no.png">'.$requisitos.'</ul>';
        $this->writeHTML($this->tablaSolicitudesB(
            $dataTramite,
            $htmlTablaFirma,
            'GRADO DE BACHILLER',
            'Director de la Escuela Profesional de: <strong>'.$dataTramite->cCarreraDsc.'</strong>.',
            'Que, habiendo concluido satisfactoriamente la Escuela Profesional de '.$dataTramite->cCarreraDsc.' y cumpliendo con los requisitos que prevé el Reglamento de Grados y Títulos de la Universidad Nacional de Moquegua; solicito a Usted, se sirva a disponer el trámite correspondiente para que se me otorgue el Grado Académico de Bachiller en '.$dataTramite->cCarreraDsc,
            ['bachiller' => 'Adjunto los siguientes documentos:', 'contenido' => $htmlP03Req]
        ), true, false, false, false, '');


        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudesB(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE NO ADEUDO DE MATERIAL BIBLIOGRÁFICO',
            'Responsable de la Unidad de Biblioteca de la Dirección de Actividades y Servicios Académicos',
            'Que, por motivo de cumplir con los requerimientos para el trámite de graduación solicito la expedición de la Constancia de no adeudo de material bibliográfico, para lo cual adjunto el recibo de pago correspondiente. '
            ), true, false, false, false, '');

        $this->AddPage();

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlPag .= '<td width="320"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlPag .= '<td style="font-weight: bold;"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2" style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p>DE NO ADEUDO DE MATERIAL BIBLIOGRÁFICO</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>La Unidad de Biblioteca de la Dirección de Actividades y Servicios Académicos de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Hace constar que:</p>';
        $htmlPag .= '<p style="text-align: center;">El Sr(ta): <strong>'.$dataTramite->cNombreEstudiante.'</strong><br></p>';
        $htmlPag .= '<p>Con código de Matrícula N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, egresado(a) de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.', NO ADEUDA MATERIAL BIBLIOGRÁFICO</strong> al sistema bibliotecario de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Por lo que, a solicitud del interesado, se expide la presente para los fines que considere pertinente.</p>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');


        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudesB(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE ACTIVIDADES COCURRICULARES',
            'Director de Responsabilidad Social y Ambiental,<br>Director de Bienestar Universitario',
            'Que, por motivo de cumplir con los requerimientos para el trámite de graduación solicito la expedición de la Constancia de Actividades Cocurriculares, para lo cual adjunto los requisitos correspondientes.'
            ), true, false, false, false, '');
            $this->AddPage();

            $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
            $htmlPag .= '<tr>';
            $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
            $htmlPag .= '<td width="320"></td>';
            $htmlPag .= '</tr>';
            $htmlPag .= '<tr>';
            $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
            $htmlPag .= '<td style="font-weight: bold;"></td>';
            $htmlPag .= '</tr>';
            $htmlPag .= '<tr><td colspan="2"></td></tr>';
            $htmlPag .= '<tr>';
            $htmlPag .= '<td colspan="2" style="text-align: center; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
            $htmlPag .= '</tr>';
            $htmlPag .= '<tr>';
            $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p></p></td>';
            $htmlPag .= '</tr>';
            $htmlPag .= '<tr><td colspan="2"></td></tr>';
            $htmlPag .= '<tr>';
            $htmlPag .= '<td colspan="2">';
            $htmlPag .= '<p>LA DIRECCION DE BIENESTAR UNIVERSITARIO Y LA DIRECCION DE PROYECCIÓN SOCIAL Y EXTENSION UNIVERSITARIA QUE SUSCRIBIMOS LA PRESENTE, HACEMOS CONSTAR QUE:</p>';
            $htmlPag .= '<p></p>';
            $htmlPag .= '<p style="text-align: justify;">El (la) estudiante: <strong>'.$dataTramite->cNombreEstudiante.'</strong>, Con Código Universitario <strong>'.$dataTramite->cEstudCodUniv.'</strong>. Perteneciente a la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.'</strong>.<br></p>';
            $htmlPag .= '<p>Ha cumplido con realizar y aprobar las Actividades Cocurriculares de Deporte, Arte y Proyección Social de la Universidad Nacional de Moquegua, como requisito complementario a su formación profesional para la obtención del grado académico de Bachiller.</p>';
            $htmlPag .= '<p>Así consta en los archivos correspondientes para su verificación respectiva.<br>Se expide la presente constancia a solicitud del interesado.</p>';
            $htmlPag .= '<p></p>';
            $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__<br><br><br></p><br><br><br><br>';
            $htmlPag .= '<p align="center" style="font-size:9px"> </p>';
            $htmlPag .= '<table cellspacing="0" cellpadding="1" border="0">';
            $htmlPag .= '<tr>';
            $htmlPag .= '<td width="48%" height="10px"><hr><span style="text-align: center">Dirección de Bienestar Universitario</span></td>';
            $htmlPag .= '<td width="4%">&nbsp;</td>';
            $htmlPag .= '<td width="48%" height="10px"><hr><span style="text-align: center">Dirección de Proyección y Extensión Universitaria</span></td>';
            $htmlPag .= '</tr>';
            $htmlPag .= '</table>';
            $htmlPag .= '</td>';
            $htmlPag .= '</tr>';
            $htmlPag .= '</table>';
            $this->writeHTML($htmlPag, true, false, false, false, '');







        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudesB(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE NO ADEUDO DE BIENES A LABORATORIOS',
            'Director de la Escuela Profesional de: <strong>'.$dataTramite->cCarreraDsc.'</strong>.',
            'Que, por motivo de cumplir con los requerimientos para el trámite de graduación solicito la expedición
            de la Constancia de No Adeudo de Bienes a Laboratorios, para lo cual adjunto el recibo de pago
            correspondiente. '
        ), true, false, false, false, '');

        $this->AddPage();

        $htmlTablaCuadros = '<table align="center " style=" text-align: center;" cellspacing="0" width="100%"  cellpadding="2" border="1">';
        $htmlTablaCuadros .= '<tr>';
        $htmlTablaCuadros .= '<td width="93px" height="90px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '</tr>';
        $htmlTablaCuadros .= '<tr>';
        $htmlTablaCuadros .= '<td height="90px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '</tr>';
        $htmlTablaCuadros .= '</table>';

        $htmlTablaCuadroRef = '<table cellspacing="0" cellpadding="0" border="1" style="font-size: 45%; text-align: center;">';
        $htmlTablaCuadroRef .= '<tr style="font-weight: bold">';
        $htmlTablaCuadroRef .= '<td width="58%" >LABORATORIOS</td>';
        $htmlTablaCuadroRef .= '<td width="7%">GPDS</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIA</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIM</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPISI</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIAM</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIP</td>';
        $htmlTablaCuadroRef .= '</tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE QUÍMICA</td><td></td><td>X</td><td>X</td><td></td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE BIOLOGÍA</td><td></td><td>X</td><td></td><td></td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE FÍSICA</td><td></td><td>X</td><td>X</td><td>X</td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE TECNOLOGÍAS DE LOS ALIMENTOS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE INGENIERÍA DE PROCESOS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE MICROBIOLOGÍA</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ANÁLISIS DE PRODUCTOS AGROINDUSTRIALES</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE INVESTIGACIÓN Y DESARROLLO DE PRODUCTOS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO GABINETE DE TOPOGRAFÍA</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO GEOTECNIA</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE BIOTECNOLOGÍA</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">CENTRO DE COMPUTO I (*)</td><td>X</td><td></td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">CENTRO DE COMPUTO II (*)</td><td>X</td><td></td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE PROCESOS PESQUEROS</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ACUICULTURA</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE EXTRACCIÓN PESQUERA</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE DESARROLLO DE SOFTWARE</td><td></td><td></td><td></td><td>X</td><td>X</td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE PROGRAMACIÓN BÁSICA</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ALIMENTO FORMULADO</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ANÁLISIS DE ALIMENTOS</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE INTELIGENCIA ARTIFICIAL</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE PROCESAMIENTO DE IMAGEN Y VIDEO</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE DISEÑO EN INGENIERÍA AGROINDUSTRIAL</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE TECNOLOGÍAS NO ALIMENTARIAS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE DIBUJO TÉCNICO</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER SOFTWARE MINERO</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE MINERALOGÍA Y PETROLOGÍA</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE ROBÓTICA</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE DIBUJO DIGITAL</td><td></td><td></td><td></td><td></td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE BUCEO</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE ARQUITECTURA DE COMPUTADORAS</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE ARTE (FILIAL ICHUÑA)</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '</table>';


        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlPag .= '<td width="320"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlPag .= '<td></td>';
        $htmlPag .= '</tr>';

        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2" style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p>DE NO ADEUDO DE BIENES A LABORATORIOS</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>El que suscribe; hace constar que:</p>';
        $htmlPag .= '<p>El Sr(ta): <strong>'.$dataTramite->cNombreEstudiante.'</strong> con código de Matrícula N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, egresado(a) de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.', NO ADEUDA BIENES A LABORATORIOS</strong> de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.'</strong>.</p>';
        $htmlPag .= '<p>Por lo que, a solicitud del interesado, se expide la presente para los fines que considere pertinente.</p>';
        $htmlPag .= $htmlTablaCuadros;

        $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlPag .= '<table cellpadding="0" cellspacing="0" border="0"><tr><td width="20%">&nbsp;</td><td width="60%">'.$htmlTablaCuadroRef.'</td><td width="20%">&nbsp;</td></tr></table>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p style="font-size: 80%">(*) Firmado por el Director de la Escuela Profesional o Responsable de Centro de Cómputo. Incluye equipos de cómputo y periféricos.</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');


        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudesB(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE NO ADEUDO DE BIENES A LA UNIVERSIDAD',
            'Responsable de la Unidad de Patrimonio de la Oficina de Logística',
            'Que, por motivo de cumplir con los requerimientos para el trámite de graduación solicito la expedición
            de la Constancia de No Adeudo de Bienes a la Universidad, para lo cual adjunto el recibo de pago
            correspondiente. '
        ), true, false, false, false, '');

        $this->AddPage();

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlPag .= '<td width="320"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlPag .= '<td style="font-weight: bold;"></td>';
        $htmlPag .= '</tr>';

        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2" style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p>DE NO ADEUDO DE BIENES A LA UNIVERSIDAD</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>La Unidad de Patrimonio de la Oficina de Logística de la Universidad Nacional de Moquegua, hace constar que:</p>';
        $htmlPag .= '<p style="text-align: center;">El Sr(ta): <strong>'.$dataTramite->cNombreEstudiante.'</strong><br></p>';
        $htmlPag .= '<table cellpadding="0" cellspacing="0" border="0" style="text-align: center;"><tr><td>Estudiante &nbsp;&nbsp;&nbsp; <img src="pdf_src/checkbox_no.png"></td><td>Docente &nbsp;&nbsp;&nbsp; <img src="pdf_src/checkbox_no.png"></td><td>Administrativo &nbsp;&nbsp;&nbsp; <img src="pdf_src/checkbox_no.png"></td></tr></table>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p>Con código de Matrícula N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, DNI N°: <strong>'.$dataTramite->cDocumentoEstudiante.'</strong> de la Unidad Orgánica y/o Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.', NO REGISTRA DEUDA DE BIENES</strong> en ninguna Unidad Operativa ni en el Sistema de Control Patrimonial de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Por lo que, a solicitud del interesado, se expide la presente para los fines que considere conveniente.</p>';

        $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');


    }



    public function carpetaTitulacion($dataTramite) {
        $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);

        $htmlTablaFirma = '<table cellspacing="0" cellpadding="1" border="0">';
        $htmlTablaFirma .= '<tr>';
        $htmlTablaFirma .= '<td colspan="3"><br><br><br>_______________________________________________________________</td>';
        $htmlTablaFirma .= '</tr>';
        $htmlTablaFirma .= '<tr>';
        $htmlTablaFirma .= '<td width="49%" align="right">Apellidos y Nombres<br>N° DNI</td>';
        $htmlTablaFirma .= '<td width="2%">:<br>:</td>';
        $htmlTablaFirma .= '<td width="49%" align="left">'.$dataTramite->cNombreEstudiante.'<br>'.$dataTramite->cDocumentoEstudiante.'</td>';
        $htmlTablaFirma .= '</tr>';
        $htmlTablaFirma .= '</table>';



        $this->AddPage();
        $this->SetFont($roboto_bold, 'B', 20, '', 'default', true);
        $this->Cell(0, 0, 'UNIVERSIDAD NACIONAL DE MOQUEGUA', 0, 1, 'C', 0, '', 0);
        $this->Ln(3);
        $this->SetFontSize(18);
        $this->Cell(0, 0, 'COMISIÓN ORGANIZADORA', 0, 1, 'C', 0, '', 0);
        $this->Ln(2);
        $this->SetFontSize(16);
        $this->Cell(0, 0, 'VICEPRESIDENCIA ACADÉMICA', 0, 1, 'C', 0, '', 0);
        $this->Ln(150);
        $this->ImageSVG(public_path('pdf_src/LogoUnam.svg'), 65, 110, 80, 0, '', 'c');

        $this->SetFontSize(36);
        $this->Cell(0, 0, 'CARPETA DE TITULACIÓN', 0, 1, 'C', 0, '', 0);
        $this->Ln(2);
        $this->SetFontSize(20);
        $this->Cell(0, 0, 'MOQUEGUA - PERÚ', 0, 1, 'C', 0, '', 0);

        $this->tipoHeader = 'carpeta';
        $htmlHeader = '<br><br><br>';
        $htmlHeader .= '<table cellspacing="0" cellpadding="2" border="0" style="background-color: rgb(207,207,207); font-weight: bold; text-align: center">';
        $htmlHeader .= '<tr><td style="text-align: center; font-size: 18px;">UNIVERSIDAD NACIONAL DE MOQUEGUA</td></tr>';
        $htmlHeader .= '<tr><td style="text-align: center; font-size: 16px;">VICEPRESIDENCIA ACADÉMICA</td></tr>';
        $htmlHeader .= '</table>';
        $this->addHtmlHeader = $htmlHeader;

        $this->setPrintHeader();

        $this->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP +10, PDF_MARGIN_RIGHT+10, true);


        $this->AddPage();
        $this->tipoFooter = 'carpeta';
        $htmlFooter = '<table cellspacing="0" cellpadding="2" border="0">';
        $htmlFooter .= '<tr>';
        $htmlFooter .= '<td style="text-align: right; color: #0B3970; border-right: 0.1px solid black;" width="400" >'.$dataTramite->cEntWeb.'</td>';
        $htmlFooter .= '<td width="235">'.$dataTramite->cEntDireccion.'<br>'.$dataTramite->cEntTelefono.'</td>';
        $htmlFooter .= '</tr>';
        $htmlFooter .= '</table>';
        $this->addHtmlFooter = $htmlFooter;



        $this->setPrintFooter();


        $requisitos = '
        <li>Solicitud dirigida al Director de la Escuela Profesional</li>
        <li>Cuatro (04) fotografías de frente, tamaño pasaporte, a color, con traje formal y fondo blanco.</li>
        <li>Copia autenticada por el Secretario General de la UNAM, del grado académico de Bachiller</li>
        <li>Constancia de "NO ADEUDO" de  Bienes a la Universidad, bienes a los Laboratorios Básicos y de Especialidad, Material Bibliográfico; dichos documentos deberán ser refrendados por los responsables a cargo.</li>
        <li>Copia oficial del acta indicando la modalidad de la obtención del título profesional.</li>
        <li>Comprobante de pago de Titulación según sea el caso (Tesis o Trabajo de suficiencia profesional), determinado en el TUPA de la universidad</li>
        <li>Comprobante de pago por derecho de diploma de título profesional, determinado en el TUPA de la universidad</li>
        <li>Dos (02) ejemplares de tesis o del Trabajo de Suficiencia Profesional, uno (1) para la Biblioteca Central y uno (1) para la Biblioteca Especializada de la Escuela Profesional, debidamente empastados y con las firmas del jurado, más un CD con la versión digital.</li>
        <li>Autorizacion escrita del graduado para la publicacion de la Tesis o Trabajo de Suficiencia Profesional en el repositorio</li>
        ';


        $this->SetFontSize(12);
        $this->writeHTML('<U>REQUISITOS PARA OBTENER EL TÍTULO PROFESIONAL</U>', true, false, false, false, 'C');
        $this->Ln();
        //$pdf->SetFont('helvetica', '', 12, '', 'default', true);
        $this->SetFont($roboto_regular, '', 12, '', 'default', true);
        $this->writeHTML('<ul style="text-align: justify; line-height: 150%; list-style-type:img|png|4|4|pdf_src/checkbox_no.png">'.$requisitos.'</ul>', true, false, false, false, '');


        $this->AddPage();
        $this->SetFont($roboto_regular, '', 10, '', 'default', true);

        $htmlP03Req = '<ul style="text-align: justify; list-style-type:img|png|4|4|pdf_src/checkbox_no.png">'.$requisitos.'</ul>';
        $this->writeHTML($this->tablaSolicitudes(
            $dataTramite,
            $htmlTablaFirma,
            'TÍTULO PROFESIONAL',
            'Director de la Escuela Profesional de: <strong>'.$dataTramite->cCarreraDsc.'</strong>.',
            'Que, deseando optar el título profesional de '.$dataTramite->cCarreraPerfilProfesional.' y cumpliendo con los requisitos que prevé el Reglamento de Grados y Títulos de la Universidad Nacional de Moquegua; solicito a Usted, se sirva a disponer el trámite correspondiente para que se me otorgue el título profesional.',
            ['titulo' => 'Adjunto los siguientes documentos:', 'contenido' => $htmlP03Req]
        ), true, false, false, false, '');


        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudes(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE NO ADEUDO DE MATERIAL BIBLIOGRÁFICO',
            'Responsable de la Unidad de Biblioteca de la Dirección de Actividades y Servicios Académicos',
            'Que, por motivo de cumplir con los requerimientos para el trámite de título profesional solicito la expedición de la Constancia de no adeudo de material bibliográfico, para lo cual adjunto el recibo de pago correspondiente.'
            ), true, false, false, false, '');

        $this->AddPage();

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlPag .= '<td width="320"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlPag .= '<td style="font-weight: bold;"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2" style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p>DE NO ADEUDO DE MATERIAL BIBLIOGRÁFICO</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>La Unidad de Biblioteca de la Dirección de Actividades y Servicios Académicos de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Hace constar que:</p>';
        $htmlPag .= '<p style="text-align: center;">El Sr(ta): <strong>'.$dataTramite->cNombreEstudiante.'</strong><br></p>';
        $htmlPag .= '<p>Con código de Matrícula N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, Bachiller de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.', NO ADEUDA MATERIAL BIBLIOGRÁFICO</strong> al sistema bibliotecario de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Por lo que, a solicitud del interesado, se expide la presente para los fines que considere pertinente.</p>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');


        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudes(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE NO ADEUDO DE BIENES A LABORATORIOS',
            'Director de la Escuela Profesional de: <strong>'.$dataTramite->cCarreraDsc.'</strong>.',
            'Que, por motivo de cumplir con los requerimientos para el trámite de Títulación solicito la expedición de la Constancia de No Adeudo de Bienes a Laboratorios, para lo cual adjunto el recibo de pago correspondiente.'),true, false, false, false, '');

        $this->AddPage();



        $htmlTablaCuadros = '<table align="center " style=" text-align: center;" cellspacing="0" width="100%"  cellpadding="2" border="1">';
        $htmlTablaCuadros .= '<tr>';
        $htmlTablaCuadros .= '<td width="93px" height="90px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td width="93px">&nbsp;</td>';
        $htmlTablaCuadros .= '</tr>';
        $htmlTablaCuadros .= '<tr>';
        $htmlTablaCuadros .= '<td height="90px">&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '<td>&nbsp;</td>';
        $htmlTablaCuadros .= '</tr>';
        $htmlTablaCuadros .= '</table>';

        $htmlTablaCuadroRef = '<table cellspacing="0" cellpadding="0" border="1" style="font-size: 45%; text-align: center;">';
        $htmlTablaCuadroRef .= '<tr style="font-weight: bold">';
        $htmlTablaCuadroRef .= '<td width="58%" >LABORATORIOS</td>';
        $htmlTablaCuadroRef .= '<td width="7%">GPDS</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIA</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIM</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPISI</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIAM</td>';
        $htmlTablaCuadroRef .= '<td width="7%">EPIP</td>';
        $htmlTablaCuadroRef .= '</tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE QUÍMICA</td><td></td><td>X</td><td>X</td><td></td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE BIOLOGÍA</td><td></td><td>X</td><td></td><td></td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE FÍSICA</td><td></td><td>X</td><td>X</td><td>X</td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE TECNOLOGÍAS DE LOS ALIMENTOS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE INGENIERÍA DE PROCESOS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE MICROBIOLOGÍA</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ANÁLISIS DE PRODUCTOS AGROINDUSTRIALES</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE INVESTIGACIÓN Y DESARROLLO DE PRODUCTOS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO GABINETE DE TOPOGRAFÍA</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO GEOTECNIA</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE BIOTECNOLOGÍA</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">CENTRO DE COMPUTO I (*)</td><td>X</td><td></td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">CENTRO DE COMPUTO II (*)</td><td>X</td><td></td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE PROCESOS PESQUEROS</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ACUICULTURA</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE EXTRACCIÓN PESQUERA</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE DESARROLLO DE SOFTWARE</td><td></td><td></td><td></td><td>X</td><td>X</td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE PROGRAMACIÓN BÁSICA</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ALIMENTO FORMULADO</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE ANÁLISIS DE ALIMENTOS</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE INTELIGENCIA ARTIFICIAL</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">LABORATORIO DE PROCESAMIENTO DE IMAGEN Y VIDEO</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE DISEÑO EN INGENIERÍA AGROINDUSTRIAL</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE TECNOLOGÍAS NO ALIMENTARIAS</td><td></td><td>X</td><td></td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE DIBUJO TÉCNICO</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER SOFTWARE MINERO</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE MINERALOGÍA Y PETROLOGÍA</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE ROBÓTICA</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE DIBUJO DIGITAL</td><td></td><td></td><td></td><td></td><td>X</td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE BUCEO</td><td></td><td></td><td></td><td></td><td></td><td>X</td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE ARQUITECTURA DE COMPUTADORAS</td><td></td><td></td><td></td><td>X</td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '<tr><td style="text-align: left;">TALLER DE ARTE (FILIAL ICHUÑA)</td><td></td><td></td><td>X</td><td></td><td></td><td></td></tr>';
        $htmlTablaCuadroRef .= '</table>';


        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlPag .= '<td width="320"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlPag .= '<td></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2" style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p>DE NO ADEUDO DE BIENES A LABORATORIOS</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>El que suscribe; hace constar que:</p>';
        $htmlPag .= '<p>El Sr(ta): <strong>'.$dataTramite->cNombreEstudiante.'</strong> con código de Matrícula N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, bachiller de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.', NO ADEUDA BIENES A LABORATORIOS</strong> de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.'</strong>.</p>';
        $htmlPag .= '<p>Por lo que, a solicitud del interesado, se expide la presente para los fines que considere pertinente.</p>';
        $htmlPag .= $htmlTablaCuadros;
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlPag .= '<table cellpadding="0" cellspacing="0" border="0"><tr><td width="20%">&nbsp;</td><td width="60%">'.$htmlTablaCuadroRef.'</td><td width="20%">&nbsp;</td></tr></table>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p style="font-size: 80%">(*) Firmado por el Director de la Escuela Profesional o Responsable de Centro de Cómputo. Incluye equipos de cómputo y periféricos.</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');


        $this->AddPage();
        $this->writeHTML($this->tablaSolicitudes(
            $dataTramite,
            $htmlTablaFirma,
            'CONSTANCIA DE NO ADEUDO DE BIENES A LA UNIVERSIDAD',
            'Responsable de la Unidad de Patrimonio de la Oficina de Logística',
            'Que, por motivo de cumplir con los requerimientos para el trámite de título profesional solicito la expedición de la Constancia de No Adeudo de Bienes a la Universidad, para lo cual adjunto el recibo de pago correspondiente.'
        ), true, false, false, false, '');

        $this->AddPage();

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlPag .= '<td width="320"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlPag .= '<td style="font-weight: bold;"></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2" style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 150%;"><p>CONSTANCIA</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2"style="text-align: center; text-decoration: underline; font-weight: bold; font-size: 140%;"><p>DE NO ADEUDO DE BIENES A LA UNIVERSIDAD</p></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>La Unidad de Patrimonio de la Oficina de Logística de la Universidad Nacional de Moquegua, hace constar que:</p>';
        $htmlPag .= '<p style="text-align: center;">El Sr(ta): <strong>'.$dataTramite->cNombreEstudiante.'</strong><br></p>';
        $htmlPag .= '<table cellpadding="0" cellspacing="0" border="0" style="text-align: center;"><tr><td>Bachiller &nbsp;&nbsp;&nbsp; <img src="pdf_src/checkbox_no.png"></td><td>Docente &nbsp;&nbsp;&nbsp; <img src="pdf_src/checkbox_no.png"></td><td>Administrativo &nbsp;&nbsp;&nbsp; <img src="pdf_src/checkbox_no.png"></td></tr></table>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p>Con código de Matrícula N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, DNI N°: <strong>'.$dataTramite->cDocumentoEstudiante.'</strong> de la Unidad Orgánica y/o Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.', NO REGISTRA DEUDA DE BIENES</strong> en ninguna Unidad Operativa ni en el Sistema de Control Patrimonial de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Por lo que, a solicitud del interesado, se expide la presente para los fines que considere conveniente.</p>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');

        $this->AddPage();

        $htmlPag = '<br><br><br><br><br><br><br><br>';
        $htmlPag .= '<table cellspacing="0" cellpadding="1" border="0">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td style="font-weight: bold; text-align: center; font-size: 40px;"><div style="border-radius: 19px; border: 2px solid #000000; line-height: 150%;">CARPETA DE TITULACIÓN</div></td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');

        $this->tipoHeader = 'reportes_tramite';

        $this->textoEncabezado = collect();
        $this->textoEncabezado->add([ 'text' => '"UNIVERSIDAD NACIONAL DE MOQUEGUA"', 'font' => $ptserif_bold, 'size' => 17, 'esp' => true ]);
        $this->textoEncabezado->add([ 'text' => 'SECRETARÍA GENERAL' , 'font' => $roboto_regular, 'size' => 13, 'esp' => false, 'regular'=>true ]);
        $this->AddPage();

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 125%;">FORMULARIO DE AUTORIZACIÓN DE DATOS PERSONALES PARA REPOSITORIO INSTITUCIONAL DIGITAL DE LA UNAM</td></tr>';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 100%;"></td></tr>';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 125%;">SECRETARÍA GENERAL</td></tr>';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 100%;"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>&nbsp;&nbsp;&nbsp;&nbsp; Formulario de autorización para la publicación electrónica de mis datos personales en la Base de Datos de la Universidad Nacional de Moquegua, de conformidad con el Decreto Legislativo N° 822, sobre la Ley de los Derechos de Autor, Ley N° 30035 del Repositorio Nacional Digital de Ciencia, Tecnología e Innovación de Acceso Abierto de la UNAM aprobada mediante la Resolución de C.O. N°0158-2016-UNAM.</p>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 130%;">1. DATOS PERSONALES DEL BACHILLER</span><br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="3" border="1">';
        $htmlPag .= '<tr><td height="33">Apellidos y Nombres: </td></tr>';
        $htmlPag .= '<tr><td height="33">D.N.I.: </td></tr>';
        $htmlPag .= '<tr><td height="33">Otro Documento: </td></tr>';
        $htmlPag .= '<tr><td height="33">Nacionalidad: </td></tr>';
        $htmlPag .= '<tr><td height="33">Teléfono: </td></tr>';
        $htmlPag .= '<tr><td height="33">E-Mail: </td></tr>';
        $htmlPag .= '<tr><td height="33">Direccion Actual: </td></tr>';
        $htmlPag .= '<tr><td height="33">Centro Laboral: </td></tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '<br>';
        $htmlPag .= '<br>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 130%;">2. DATOS ACADÉMICOS</span><br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="3" border="1">';
        $htmlPag .= '<tr><td height="33">Escuela Profesional: </td></tr>';
        $htmlPag .= '<tr><td height="33">Grado Académico otorgado: </td></tr>';
        $htmlPag .= '<tr><td height="33">Resolución que otorga el grado académico: </td></tr>';
        $htmlPag .= '<tr><td height="33">Fecha de Resolución: </td></tr>';
        $htmlPag .= '<tr><td height="33">Fecha de Ceremonia de Colación: </td></tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '<br>';
        $htmlPag .= '<br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="0" border="0" valign="middle">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="80%">';
        $htmlPag .= '<table cellspacing="0" cellpadding="0" border="0">';
        $htmlPag .= '<tr><td height="20" colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="35%">Firma y huella digital: </td>';
        $htmlPag .= '<td width="65%">___________________________________________</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Fecha de autorización: </td>';
        $htmlPag .= '<td>___________________________________________</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '</td>';
        $htmlPag .= '<td width="20%">';
        $htmlPag .= '<table cellspacing="0" cellpadding="0" border="1" align="center"><tr><td height="95" width="80">&nbsp;</td></tr></table>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';



        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');

        $this->tipoHeader = 'reportes_tramite';

        $this->textoEncabezado = collect();
        $this->textoEncabezado->add([ 'text' => '"UNIVERSIDAD NACIONAL DE MOQUEGUA"', 'font' => $ptserif_bold, 'size' => 17, 'esp' => true ]);
        $this->textoEncabezado->add([ 'text' => 'DIRECCION DE GESTIÓN DE LA INVESTIGACIÓN' , 'font' => $roboto_regular, 'size' => 13, 'esp' => false, 'regular'=>true ]);
        $this->AddPage();

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 125%;">FORMULARIO DE AUTORIZACIÓN DE PUBLICACIÓN ELECTRÓNICA PARA TESIS AL REPOSITORIO INSTITUCIONAL DIGITAL DE CIENCIA, TECNOLOGÍA E INNOVACIÓN DEL ACCESO ABIERTO DE LA UNAM</td></tr>';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 100%;"></td></tr>';
        $htmlPag .= '<tr><td colspan="2" style="text-align: center; font-weight: bold; font-size: 125%;">VICEPRESIDENCIA DE INVESTIGACIÓN</td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>&nbsp;&nbsp;&nbsp;&nbsp; Formulario de autorización para la publicación electrónica en la página web del Repositorio Institucional de la Universidad Nacional de Moquegua, de conformidad con el Decreto Legislativo N° 822, sobre la Ley de los Derechos de Autor, Ley N° 30035 del Repositorio Nacional Digital de Ciencia, Tecnología e Innovación de Acceso Abierto de la UNAM aprobada mediante la Resolución de C.O. N° 0158-2016-UNAM.</p>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 110%;">1. DATOS PERSONALES DELAUTOR</span><br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="3" border="1">';
        $htmlPag .= '<tr><td>Apellidos y Nombres: </td></tr>';
        $htmlPag .= '<tr><td>D.N.I.: </td></tr>';
        $htmlPag .= '<tr><td>Otro Documento: </td></tr>';
        $htmlPag .= '<tr><td>Nacionalidad: </td></tr>';
        $htmlPag .= '<tr><td>Teléfono: </td></tr>';
        $htmlPag .= '<tr><td>E-Mail: </td></tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '<br>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 110%;">2. DATOS ACADÉMICOS</span><br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="3" border="1">';
        $htmlPag .= '<tr><td>Escuela Profesional: </td></tr>';
        $htmlPag .= '<tr><td>Grado Académico otorgado: </td></tr>';
        $htmlPag .= '<tr><td>Título Profesional otorgado: </td></tr>';
        $htmlPag .= '<tr><td>Tesis (Pregrado y/o Postgrado): </td></tr>';
        $htmlPag .= '<tr><td>Modalidad de Titulación: </td></tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '<br>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 110%;">3. DATOS DE LA TESIS</span><br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="3" border="1">';
        $htmlPag .= '<tr><td height="45">Título de la tesis:</td></tr>';
        $htmlPag .= '<tr><td>Fecha de Sustentación:</td></tr>';
        $htmlPag .= '<tr><td>Calificación:</td></tr>';
        $htmlPag .= '<tr><td>Año de Publicación </td></tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '<br>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 110%;">4. DATOS DEL ASESOR</span><br>';

        $htmlPag .= '<table cellspacing="0" cellpadding="3" border="1">';
        $htmlPag .= '<tr><td>Apellidos y Nombres: </td></tr>';
        $htmlPag .= '<tr><td>D.N.I.: </td></tr>';
        $htmlPag .= '<tr><td>Otro Documento: </td></tr>';
        $htmlPag .= '<tr><td>Nacionalidad: </td></tr>';
        $htmlPag .= '<tr><td>Teléfono: </td></tr>';
        $htmlPag .= '<tr><td>E-Mail: </td></tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '<br>';
        $htmlPag .= '<br>';

        $htmlPag .= '<span style="font-weight: bold; font-size: 110%;">5. AUTORIZACIÓN DE PUBLICACIÓN EN VERSIÓNELECTRÓNICA</span>';

        $htmlPag .= '<p>&nbsp;&nbsp;&nbsp;&nbsp; A través de la presente, autorizo la publicación del texto completo de la tesis, en el Repositorio Institucional de la Universidad Nacional de Moquegua para su explotación no exclusiva, ilimitada, perpetua y con alcance mundial, a favor de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>&nbsp;&nbsp;&nbsp;&nbsp; <strong>Según el Reglamento de la Ley Nº 30035, Ley que regula el Repositorio Nacional Digitalde Ciencia, Tecnología e Innovación de Acceso Abierto DECRETO SUPREMO N° 006- 2015-PCM “TÍTULO IV GESTIÓN DE LA PROPIEDAD INTELECTUAL Y DE LOS DATOS PERSONALES;</strong> <i>Artículo 10º.- De los Derechos: Esta cesión de derechos comprende, mas no selimita, a los derechos de reproducción, comunicación al público, distribución, traducción, adaptación, arreglo, edición, modificación, cambio de formato u otra transformación, importación al territorio nacional de copias por cualquier medio incluyendo la transmisión, así como cualquier otra forma de utilización de las obras, datos procesados y estadísticas de monitoreo que no estén contempladas en la ley de la materia como excepción al derecho patrimonial y, en general, para cualquier tipo de utilización y explotación, que la entidad estime pertinentes, en cualquier forma o procedimiento, conocido o por conocerse, pudiendo poner a disposición las obras, datos procesados y estadísticas de monitoreo por medio de autorizaciones o licencias a favor del público engeneral.”</p></i>';
        $htmlPag .= '<i><p>&nbsp;&nbsp;&nbsp;&nbsp; Sin perjuicio de otras obligaciones a su cargo, EL AUTOR deberá entregar una versión digitalfinal de la TESIS sin ninguna medida tecnológica efectiva ni sistema de auto tutela, sin contraseña ni restricción, de acuerdo con los lineamientos establecidos por el Consejo Nacional de Ciencia, Tecnología e Innovación Tecnológica (CONCYTEC) en relación con el Repositorio Nacional Digital de Ciencia, Tecnología e Innovación de Acceso Abierto. Cuando las obras contengan en parte o consistan en programas de ordenador o software, además deberá entregar el código fuente, código objeto, documentación técnica y manuales. Lo  dispuesto en relación con los programas de ordenador no se aplicará cuando la entidad pública sea sólo licenciataria del software.</p></i>';
        $htmlPag .= '<p>&nbsp;&nbsp;&nbsp;&nbsp; Para facilitar la divulgación y alcance nacional e internacional de los resultados de las investigaciones en materia de ciencia, tecnología e innovación contenidos en los repositorios institucionales y en el Repositorio Nacional Digital, se autoriza la traducción automática a otros idiomas y dialectos por medio de programas de ordenador de las obras, datos procesados y estadísticas de monitoreo, así como de los metadatos de los mismos. En caso de ser fijadas o reproducidas, se deberá incluir expresamente que se trata de traducciones hechas por medios informáticos, con fines ilustrativos o referenciales.</p>';

        $htmlPag .= '<table cellspacing="0" cellpadding="0" border="0" valign="middle">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="80%">';
        $htmlPag .= '<table cellspacing="0" cellpadding="0" border="0">';
        $htmlPag .= '<tr><td height="20" colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td width="35%">Firma y huella digital: </td>';
        $htmlPag .= '<td width="65%">___________________________________________</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '<tr><td colspan="2"></td></tr>';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td>Fecha de autorización: </td>';
        $htmlPag .= '<td>___________________________________________</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $htmlPag .= '</td>';
        $htmlPag .= '<td width="20%">';
        $htmlPag .= '<table cellspacing="0" cellpadding="0" border="1" align="center"><tr><td height="95" width="80">&nbsp;</td></tr></table>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';



        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');

    }

    function tablaSolicitudes($dataTramite, $htmlTablaFirma, $sumilla, $destinatario, $cuerpo, $adjuntos = ['titulo' => null, 'contenido' => null]) {

        $htmlSumilla = '<table cellspacing="0" cellpadding="2" border="0">';
        $htmlSumilla .= '<tr>';
        $htmlSumilla .= '<td width="20%" >SOLICITO: </td>';
        $htmlSumilla .= '<td width="80%">'.$sumilla.'</td>';
        $htmlSumilla .= '</tr>';
        $htmlSumilla .= '</table>';

        $htmlSolicitud = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlSolicitud .= '<td width="320"></td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlSolicitud .= '<td style="font-weight: bold;">'.$htmlSumilla.'</td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '<tr><td colspan="2"></td></tr>';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td colspan="2"><p>Señor: <br>'.$destinatario.'.</p></td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '<tr><td colspan="2"></td></tr>';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td colspan="2">';
        $htmlSolicitud .= '<p>Yo, <strong>'.$dataTramite->cNombreEstudiante.'</strong>, Bachiller de la Universidad Nacional de Moquegua, con código de matrícula N°: <strong>'.$dataTramite->cEstudCodUniv.'</strong> de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.'</strong>, con DNI N°: <strong>'.$dataTramite->cDocumentoEstudiante.'</strong>, domiciliado en Departamento '.$dataTramite->cEstudDepartamento.', Provincia de '.$dataTramite->cEstudProvincia.', Distrito de '.$dataTramite->cEstudDistrito.' con Dirección '.$dataTramite->cEstudDirecc.'.</p>';
        $htmlSolicitud .= '<p>A usted respetuosamente me presento y expongo:<br><br>'.$cuerpo.'</p>';
        if ($adjuntos['titulo']) {
            $htmlSolicitud .= '<p>'.$adjuntos['titulo'].':</p>';
            $htmlSolicitud .= $adjuntos['contenido'];
        }
        $htmlSolicitud .= '<p>Por lo tanto, pido a Usted, acceda a mi solicitud por ser de justicia.</p>';
        $htmlSolicitud .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlSolicitud .= '<p align="center">'.$htmlTablaFirma.'</p>';
        $htmlSolicitud .= '</td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '</table>';
        return $htmlSolicitud;

    }

    function tablaSolicitudesB($dataTramite, $htmlTablaFirma, $sumilla, $destinatario, $cuerpo, $adjuntos = ['bachiller' => null, 'contenido' => null]) {

        $htmlSumilla = '<table cellspacing="0" cellpadding="2" border="0">';
        $htmlSumilla .= '<tr>';
        $htmlSumilla .= '<td width="20%" >SOLICITO: </td>';
        $htmlSumilla .= '<td width="80%">'.$sumilla.'</td>';
        $htmlSumilla .= '</tr>';
        $htmlSumilla .= '</table>';

        $htmlSolicitud = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td width="250" >CT N°'.$dataTramite->cDocNumDoc.'</td>';
        $htmlSolicitud .= '<td width="320"></td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td>Rec. N°'.$dataTramite->cDocNumRecibo.'</td>';
        $htmlSolicitud .= '<td style="font-weight: bold;">'.$htmlSumilla.'</td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '<tr><td colspan="2"></td></tr>';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td colspan="2"><p>Señor: <br>'.$destinatario.'.</p></td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '<tr><td colspan="2"></td></tr>';
        $htmlSolicitud .= '<tr>';
        $htmlSolicitud .= '<td colspan="2">';
        $htmlSolicitud .= '<p>Yo, <strong>'.$dataTramite->cNombreEstudiante.'</strong>, egresado(a) de la Universidad Nacional de Moquegua, con código de matrícula N°: <strong>'.$dataTramite->cEstudCodUniv.'</strong> de la Escuela Profesional de <strong>'.$dataTramite->cCarreraDsc.'</strong>, con DNI N°: <strong>'.$dataTramite->cDocumentoEstudiante.'</strong>, domiciliado en Departamento '.$dataTramite->cEstudDepartamento.', Provincia de '.$dataTramite->cEstudProvincia.', Distrito de '.$dataTramite->cEstudDistrito.' Dirección '.$dataTramite->cEstudDirecc.'.</p>';
        $htmlSolicitud .= '<p>A usted respetuosamente me presento y expongo:</p><p>'.$cuerpo.'</p>';
        if ($adjuntos['bachiller']) {
            $htmlSolicitud .= '<p>'.$adjuntos['bachiller'].':</p>';
            $htmlSolicitud .= $adjuntos['contenido'];
        }
        $htmlSolicitud .= '<p>Por lo tanto, pido a Usted, acceda a mi solicitud por ser de justicia.</p>';
        $htmlSolicitud .= '<p align="right">Moquegua, ___ de _______________ del 20__</p>';
        $htmlSolicitud .= '<p align="center">'.$htmlTablaFirma.'</p>';
        $htmlSolicitud .= '</td>';
        $htmlSolicitud .= '</tr>';
        $htmlSolicitud .= '</table>';
        return $htmlSolicitud;

    }

    public function generarTabla($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(47, 71, 194);
        $this->SetTextColor(255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', '', 10);
        // Header
        $w = array(35, 100, 45);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $k => $v) {
            $this->MultiCell(35, 15, '', 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');
            $this->MultiCell(100, 15, $v['cConcepNombre'], 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');
            $this->MultiCell(45, 15, $v['cConcepCodigo'], 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    public function constanciaDeConformidad($dataTramite) {

        $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_regular_italic = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-RegularItalic.ttf'), 'TrueTypeUnicode', '', 96);



        $this->AddPage();

        $this->SetFont($roboto_regular_italic, '', 9, '', 'default', true);
        $this->writeHTML('<p>"' . $dataTramite->cYearOficial . '"</p>', true, false, false, false, 'C');

        $this->SetFont($roboto_regular, '', 16, '', 'default', true);

        $htmlHeader = '<br><br><br>';
        $htmlHeader .= '<table cellspacing="0" cellpadding="2" border="0" style="background-color: rgb(207,207,207); font-weight: bold; text-align: center;">';
        $htmlHeader .= '<tr><td>CONSTANCIA DE CONFORMIDAD DE DOCUMENTOS</td></tr>';
        $htmlHeader .= '<tr><td style="font-size: 90%;">C.CONF.DOC N° ' . sprintf('%06d', $dataTramite->cDocNumDoc) . '-' . $dataTramite->iDocAnioEmision . '</td></tr>';
        $htmlHeader .= '</table>';
        $htmlHeader .= '<br><br>';


        $this->writeHTML($htmlHeader, true, false, false, false, 'C');

        $this->SetFont($roboto_regular, '', 11, '', 'default', true);

        $htmlPag = '<table cellspacing="0" cellpadding="1" border="0" style="text-align: justify; line-height: 140%;">';
        $htmlPag .= '<tr>';
        $htmlPag .= '<td colspan="2">';
        $htmlPag .= '<p>La Dirección de Actividades y Servicios Académicos de la Universidad Nacional de Moquegua.</p>';
        $htmlPag .= '<p>Hace constar que:</p>';
        $htmlPag .= '<p>Los documentos originales correspondientes a los requisitos de admisión a la Universidad Nacional de Moquegua de Don(ña) <strong>'.trim($dataTramite->cNombreEstudiante).'</strong>, de la Escuela Profesional de <strong>'.trim($dataTramite->cCarreraDsc).'</strong>, identificado con DNI N° <strong>'.$dataTramite->cDocumentoEstudiante.'</strong> y código N° <strong>'.$dataTramite->cEstudCodUniv.'</strong>, se encuentran en el archivo de la Unidad de Registro Central - DASA.</p>';
        $htmlPag .= '<p><ul>
<li>Certificado Oficial de Estudios - Original</li>
<li>Acta de Nacimiento - Original</li>
<li>DNI - Cópia Legalizada</li>
</ul></p>';
        $htmlPag .= '<p>Se expide la presente para los fines que considere conveniente.</p>';
        $htmlPag .= '<p></p>';
        $htmlPag .= '<p align="right">Moquegua, '. $dataTramite->cDocFechaDoc .'</p>';
        $htmlPag .= '</td>';
        $htmlPag .= '</tr>';
        $htmlPag .= '</table>';
        $this->writeHTML($htmlPag, true, false, false, false, '');

        $this->addHtmlFooter = '<p>'.$dataTramite->cEntWeb.' | '.$dataTramite->cEntDireccion.' | '.$dataTramite->cEntTelefono.'</p>';



        $this->setPrintFooter();

    }




    public function asignarFondo($imgFondo){
        // -- set new background ---

// get the current page break margin
        $bMargin = $this->getBreakMargin();
// get current auto-page-break mode
        $auto_page_break = $this->getAutoPageBreak();
// disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
// set bacground image
        $img_file = $imgFondo;
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
        $this->setPageMark();
    }

}
