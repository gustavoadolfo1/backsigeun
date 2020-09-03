<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPExcel;
use PHPExcel_Shared_Font;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;

use App\ClasesLibres\Reportes\ReporteExcel;
class ExportController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }

    public static function getArchivoReporteAsistentesReunion($data, $parametros, $tipo)
    {
        $head = [
            [ 'title' => 'Id', 'campo' => 'id', 'width' => '30', 'align' => 'left' ],
            [ 'title' => 'User Id', 'campo' => 'user_id', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Nombre', 'campo' => 'name', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Email', 'campo' => 'user_email', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Ingreso', 'campo' => 'join_time', 'width' => '25', 'align' => 'left' ],
            [ 'title' => 'Salida', 'campo' => 'leave_time', 'width' => '25', 'align' => 'left' ],
            [ 'title' => 'Duración (minutos)', 'campo' => 'duration', 'width' => '15', 'align' => 'left' ]
        ];
        
        $leyenda = [
            ['title' => 'Escuela Profesional', 'value' => $parametros->cCarreraDsc ],
            ['title' => 'Curso', 'value' => $parametros->cCurricCursoCod],
            ['title' => 'Sección', 'value' => $parametros->cSeccionDsc],
            ['title' => 'Tema', 'value' => $parametros->cTema]
        ];

        $reporte = new ReporteExcel;
        return $reporte->generateExcel('Reporte Consulta', $leyenda, $head, $data);
    }

    public function exportCalificaciones(Request $request)
    {
        
        $x = 'A';
        for ($i=0; $i < ($request->params['x'] - 1); $i++) { 
            ++$x;
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($request->data);

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(60);

        $sheet->getStyle("A1:".$x.$request->params['y'])->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => 'thin')));
        $sheet->getStyle("A1:" . $x. "4")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:" . $x. "4")->getAlignment()->setVertical('center');
        $sheet->getStyle("A1:" . $x. "4")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');

        $sheet->insertNewRowBefore(1, 8);

        /**
         * Poniendo el title del archivo
         */
        $sheet->setCellValue('A1', 'Reporte de calificaciones');
        $sheet->mergeCells('A1:' . $x . "1" );
        $sheet->mergeCells('A2:' . $x . "2" );
        $sheet->getStyle("A1:" . $x. "1")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1:" . $x. "1")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');
        /**
         * Poniendo los params del archivo
         */

        $sheet->setCellValue('A3', 'Docente:');
        $sheet->setCellValue('B3', $request->params['docente']);
        $sheet->getStyle('B3' . ":" . $x . "3" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B3' . ":" . $x . "3" );

        $sheet->setCellValue('A4', 'Curso:');
        $sheet->setCellValue('B4', $request->params['curso']);
        $sheet->getStyle('B4' . ":" . $x . "4" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B4' . ":" . $x . "4" );

        $sheet->setCellValue('A5', 'Sección');
        $sheet->setCellValue('B5', $request->params['seccion']);
        $sheet->getStyle('B5' . ":" . $x . "5" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B5' . ":" . $x . "5" );

        $sheet->setCellValue('A6', 'Carrera profesional:');
        $sheet->setCellValue('B6', $request->params['carrera']);
        $sheet->getStyle('B6' . ":" . $x . "6" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B6' . ":" . $x . "6" );

        $sheet->setCellValue('A7', 'Fecha:');
        $sheet->setCellValue('B7', date('d-m-Y'));
        $sheet->getStyle('B7' . ":" . $x . "7" )->getAlignment()->setHorizontal('left');
        $sheet->mergeCells('B7' . ":" . $x . "7" );

        $sheet->getStyle("A3:A7")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');


        header("Access-Control-Allow-Origin: *");

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function AsistenciaVideoconferenciaExcel($iControlCicloAcad,$iDocenteId,$iActividadesId,$iReunionProgId)
    {
        
        $data = \DB::select('exec aula.Sp_SEL_asistenciaVideoConferenciasXiReunionAsistId ?', array($iReunionProgId));

        Excel::create('Asistencia Videoconferencia', function ($excel) use ($iControlCicloAcad,$data) {

            $excel->sheet('REPORTE', function ($sheet) use ($iControlCicloAcad,$data) {
                
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C3', 'DOCENTE');
                //$sheet->setCellValue('C4', 'CARRERA');
                $sheet->setCellValue('C5', 'CURSO');
                $sheet->setCellValue('C6', 'CICLO');
                $sheet->setCellValue('C7', 'SECCION');

                $sheet->setCellValue('I4', 'SEMESTRE');
                $sheet->setCellValue('I5', 'CODIGO CURSO');
                $sheet->setCellValue('I6', 'PLAN');

                $sheet->cells('B1:K1', function ($cells) {
                    $cells->setAlignment('center');
                });

                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->getStyle('I4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);



                $sheet->mergeCells("C3:D3");
                $sheet->mergeCells("C4:D4");
                $sheet->mergeCells("C5:D5");
                $sheet->mergeCells("C6:D6");
                $sheet->mergeCells("C7:D7");

                $sheet->mergeCells("C4:D4");
                $sheet->mergeCells("I5:J5");
                $sheet->mergeCells("I6:J6");
                if(count($data)>0) {
                $sheet->setCellValue('E3', $data[0]->cDocente);
                //$sheet->setCellValue('E4', $data[0]->Carrera);
                $sheet->setCellValue('E5', $data[0]->cCurricCursoDsc);
                $sheet->setCellValue('E6', $data[0]->cCurricDetCicloCurso);
                $sheet->setCellValue('E7', $data[0]->cSeccionDsc);

                $sheet->setCellValue('K4', $data[0]->iControlCicloAcad);
                $sheet->setCellValue('K5', $data[0]->cCurricCursoCod);
                $sheet->setCellValue('K6', $data[0]->cCurricAnio);
                
                }
                $sheet->cells('K4', function ($cells) {

                    $cells->setAlignment('left');
                });
                $sheet->cells('K6', function ($cells) {

                    $cells->setAlignment('left');
                });

                $sheet->mergeCells("E3:H3");
                $sheet->mergeCells("E4:H4");
                $sheet->mergeCells("E5:H5");
                $sheet->mergeCells("E6:H6");
                $sheet->mergeCells("E7:H7");


               // $data = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'CODIGO');
                $sheet->setCellValue('E9', 'APELLIDOS');
                $sheet->setCellValue('H9', 'NOMBRES');
                $sheet->setCellValue('J9', 'INGRESOS');
                $sheet->setCellValue('K9', 'PRIMER INGRESO');
                $sheet->setCellValue('M9', 'ÚLTIMO INGRESO');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('K9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('M9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");
                $sheet->mergeCells("K9:L9");
                $sheet->mergeCells("M9:N9");

                $sheet->cells('C9:N9', function ($cells) {

                    $cells->setFontSize(9);
                    $cells->setAlignment('center');
                });

                foreach ($data as $key => $value) {
                    $x = ($key + 10);
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

                    $sheet->setCellValue($c, $key + 1);
                    $sheet->setCellValue($d, $value->cCodEstudiante);
                    $sheet->setCellValue($e, $value->cPersPaterno.' '.$value->cPersMaterno);
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, $value->cPersNombre);
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, $value->iNumIngresos);
                   
                    $sheet->setCellValue($k, $value->iPrimerIngreso);
                    $sheet->mergeCells($k . ":" . $l);

                    $sheet->setCellValue($m, $value->iUltimoIngreso);
                    $sheet->mergeCells($m . ":" . $n);

                   
                }

                $sheet->setOrientation('landscape');
            });

            
        })->download('xlsx');
    }
}
