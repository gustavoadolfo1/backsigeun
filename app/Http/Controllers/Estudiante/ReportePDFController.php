<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\UraEstudiante;
use Illuminate\Http\Request;

use App\ClasesLibres\TramiteDocumentario\PdfCreator;

use PDF;

class ReportePDFController extends Controller
{
    /**
     * 
     */
    public function getDocumentoPDF($tipo, $codigo, $matricId)
    {
        $estudiante = UraEstudiante::where('cEstudCodUniv', $codigo)->join('ura.carreras', 'ura.carreras.iCarreraId', 'ura.estudiantes.iCarreraId')->join('grl.personas', 'grl.personas.iPersId', 'ura.estudiantes.iPersId')->first();

        $detalles = \DB::select('exec [ura].[Sp_ESTUD_SEL_boletaNotasXmatricId] ?', array($matricId));

        //return response()->json($detalles);

        switch ($tipo) {
            case 1:
                $pdf = PDF::loadView('estudiantes.fichaMatricula', [ 'estudiante' => $estudiante, 'detalles' => $detalles] )->setPaper('A4');
                break;
            
            case 2:
            
                //return view('estudiantes.boletaNotas', [ 'estudiante' => $estudiante, 'detalles' => $detalles] ); 

                $pdf = PDF::loadView('estudiantes.boletaNotas', [ 'estudiante' => $estudiante, 'detalles' => $detalles] )->setPaper('A4');

                break;
            
            default:
                # code...
                break;
        }

        return $pdf->stream();
    }

    public function getHistorialPdf($codigoUniv)
    {
        $estudiante = \DB::select('exec [ura].[Sp_ESTUD_SEL_info_basica_estudiante_x_coduniv] ?', array($codigoUniv));

        $pdf = $this->initPDFReporter();

        // CONFIGURACION PARA ARCHIVO
        //$pdf->qrValue = $dataTramite[0]->cDocQrId;
        //$pdf->fechaAceptado = $dataTramite[0]->cDocFechaDoc;
        //$pdf->porQr = $porQr;

        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16, '', 'default', true);
        $pdf->Cell(0, 0, 'HISTORIAL ACADEMICO', 0, 1, 'C', 0, '', 0);

        $pdf->infoEstudiante($estudiante[0]);

        $pdf->writeHTML('<strong style="font-size: 12px;">DETALLE DE CURSOS</strong>', true, false, false, false, '');

        $pdf->Ln();

        $pdf->historialAcademico($codigoUniv);

        $header = array('ID', 'Descripcion', 'SIAF', 'Clasificador');

        ob_end_clean();
        // ---------------------------------------------------------

        return $pdf->Output('unam-SIGEUN.pdf', 'I');
    }

    public function initPDFReporter()
    {
        $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->formatoSeleccionado = 6;

        header("Access-Control-Allow-Origin: *");

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Antonio Salas');
        $pdf->SetTitle('Reportes UNAM');
        $pdf->SetSubject('Trámites - UNAM');
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

        return $pdf;
    }
}
