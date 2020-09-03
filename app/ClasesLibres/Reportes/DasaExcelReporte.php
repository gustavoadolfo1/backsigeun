<?php

namespace App\ClasesLibres\Reportes;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class DasaExcelReporte extends ReporteExcel
{
    public function __construct()
    {

    }

    public function matriculadosPorNumMatricula($params)
    {
        $documento = new Spreadsheet();
        $sheet = $documento->getActiveSheet();

        $sheet = $this->setWidthColumns([ 'B' => 40, 'C' => 15, 'D' => 15 ], $sheet);

        $sheet->setCellValue('B6', 'Escuela Profesional');
        $sheet->setCellValue('C6', 'Sede');
        $sheet->setCellValue('D6', 'Total Estudiantes');

        $return = $this->generateRow('E', '6', $params['ciclos'], 'romano', $sheet);
        $sheet = $return[0];

        $sheet->getStyle("B6:" . $return[1]. "6")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');
        $sheet->getStyle("B6:" . $return[1]. "6")->getAlignment()->setHorizontal('center');

        $y = 7;
        for ($i=0; $i < count($params['data']); $i++) { 
            $sheet->setCellValue("B$y", $params['data'][$i]->cCarrera);
            $sheet->setCellValue("C$y", $params['data'][$i]->cFilial);
            $sheet->setCellValue("D$y", $params['data'][$i]->total);
            $x = 'D';
            foreach ($params['ciclos'] as $ciclo) {
                ++$x;
                $numero = $ciclo['numero'];
                $sheet->setCellValue($x.$y, $params['data'][$i]->$numero ?? 0);
            }
            $y++;
        }

        $sheet->setCellValue("B$y", "TODAS LAS CARRERAS");
        $sheet->setCellValue("D$y", $params['total']);

        $sheet->getStyle("B6:".$x.$y)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => 'thin')));
        $sheet->getStyle("D7:".$x.$y)->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('B2', 'ESTUDIANTES CON ' . $params['params']['numMatricula'] . ' MATRÍCULAS DESAPROBADAS');
        $sheet->mergeCells('B2:'.$return[1].'2');
        $sheet->getStyle('B2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B2')->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');

        $sheet->setCellValue('B4', 'Semestre: ' . $params['params']['semestre']);

        header("Access-Control-Allow-Origin: *");
        
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function situacionRacionalizacion($params)
    {
        $documento = new Spreadsheet();
        $sheet = $documento->getActiveSheet();

        $sheet = $this->setWidthColumns([ 'B' => 5, 'C' => 40, 'D' => 15, 'E' => 40, 'F' => 15, 'G' => 15, 'H' => 15, 'I' => 15, ], $sheet);

        $sheet->setCellValue('B6', '#');
        $sheet->setCellValue('C6', 'Docente');
        $sheet->setCellValue('D6', 'Sede');
        $sheet->setCellValue('E6', 'Carrera adscrita');
        $sheet->setCellValue('F6', 'Datos Básicos');
        $sheet->setCellValue('G6', 'Carga horaria lectiva y no lectiva');
        $sheet->setCellValue('H6', 'Carga lectiva');
        $sheet->setCellValue('I6', 'Carga no lectiva');

        $y = 6;
        for ($i=0; $i < count($params['detalles']); $i++) { 
            $y++;
            $sheet->setCellValue("B$y", $i + 1);
            $sheet->setCellValue("C$y", $params['detalles'][$i]->Docente);
            $sheet->setCellValue("D$y", $params['detalles'][$i]->Sede);
            $sheet->setCellValue("E$y", $params['detalles'][$i]->Carrera);
            $sheet->setCellValue("F$y", $params['detalles'][$i]->Datos_Basicos);
            $sheet->setCellValue("G$y", $params['detalles'][$i]->Carga_Horaria_Lectiva_NoLectiva);
            $sheet->setCellValue("H$y", $params['detalles'][$i]->Carga_Lectiva);
            $sheet->setCellValue("I$y", $params['detalles'][$i]->Carga_NoLectiva);
        }

        $sheet->getStyle("B6:I6")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');
        $sheet->getStyle("B6:I6")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("B6:I".$y)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => 'thin')));

        $y += 2;
        $sheet->setCellValue("B$y", "CUADRO RESUMEN");
        $y += 1;
        $sheet->setCellValue("B$y", '#');
        $sheet->setCellValue("C$y", 'Sede');
        $sheet->setCellValue("D$y", 'Por llenar');
        $sheet->setCellValue("E$y", 'Comenzado a llenar');
        $sheet->setCellValue("F$y", 'Completado');
        $sheet->setCellValue("G$y", 'Total');
        
        $sheet->getStyle("B$y:G$y")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');
        $sheet->getStyle("B$y:G$y")->getAlignment()->setHorizontal('center');
        
        $y2 = $y;
        for ($i=0; $i < count($params['resumen']); $i++) { 
            $y++;
            $sheet->setCellValue("B$y", $i + 1);
            $sheet->setCellValue("C$y", $params['resumen'][$i]->Sede);
            $sheet->setCellValue("D$y", $params['resumen'][$i]->Por_llenar);
            $sheet->setCellValue("E$y", $params['resumen'][$i]->Comenzado_a_Llenar);
            $sheet->setCellValue("F$y", $params['resumen'][$i]->Lleno_Completo);
            $sheet->setCellValue("G$y", $params['resumen'][$i]->Total_Sede);
        }

        $sheet->getStyle("B$y2:G".$y)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => 'thin')));

        $sheet->setCellValue('B2', 'SITUACIÓN RACIONALIZACIÓN DOCENTES');
        $sheet->mergeCells('B2:I2');
        $sheet->getStyle('B2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B2')->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');

        $sheet->setCellValue('B4', 'Semestre: ' . $params['semestre']);

        header("Access-Control-Allow-Origin: *");
        
        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;

    }


}
