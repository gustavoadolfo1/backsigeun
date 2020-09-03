<?php

namespace App\ClasesLibres\Reportes;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadSheetReporteMaker
{
    private $title;
    private $initAxisX;
    private $initAxisY;

    private $currentAxisX;
    private $currentAxisY;

    private $spreadsheet;
    private $sheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function generateSheet($title = 'Reporte', $params, $header, $initAxisX = 'B', $initAxisY = 2)
    {
        $this->setupSheet($title, $initAxisX, $initAxisY);
        $this->setDataInitAxes($params, $initAxisX, $initAxisY);
        $this->setWidth($header, $initAxisX);
    }

    function renderData($header, $subtitle = null, $data)
    {
        $this->currentAxisY += 2;

        if ($subtitle) {    
            $this->sheet->setCellValue($this->currentAxisX . $this->currentAxisY, $subtitle);
            $this->currentAxisY += 1;
        }

        $initX = $this->currentAxisX;
        $initY = $this->currentAxisY;
        $this->setHeader($header, $this->currentAxisX, $this->currentAxisY);

        for ($i = 0; $i < count($data); $i++) {
            $this->currentAxisY += 1;
            $this->sheet->setCellValue($this->currentAxisX . $this->currentAxisY, $i + 1);
            $x = $this->currentAxisX;
            foreach ($header as $head) {
                ++$x;
                $key = $head['key'];
                $this->sheet->setCellValue($x . $this->currentAxisY, $data[$i]->$key);
                $this->sheet->getStyle($x . $this->currentAxisY)->getAlignment()->setHorizontal($head['align']);
            }
        }
        $this->sheet->getStyle("$initX$initY:$x$initY")->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');
        $this->sheet->getStyle("$initX$initY:$x" . $this->currentAxisY)->getBorders()->applyFromArray( array( 'allBorders' => array( 'borderStyle' => 'thin')));
    }

    public function setDataInitAxes($parametros, $initAxisX, $initAxisY)
    {
        $numParams = count($parametros);
        $numRowsParams = ceil($numParams / 2);

        $dataAxisX = $initAxisX;
        $dataAxisY = $initAxisY + $numRowsParams + 2;

        $this->currentAxisX = $dataAxisX;
        $this->currentAxisY = $dataAxisY;
    }

    public function setHeader($header, $axisX, $axisY)
    {
        $x = $axisX;
        $this->sheet->setCellValue($x . $axisY, '#');
        foreach ($header as $key => $head) {
            ++$x;
            $this->sheet->setCellValue($x . $axisY, $head['title']);
        }
    }

    public function setWidth($header, $x)
    {
        foreach ($header as $key => $head) {
            ++$x;
            $this->sheet->getColumnDimension($x)->setWidth($head['width']);
        }
    }

    public function setupSheet($title, $initAxisX, $initAxisY)
    {
        $this->title = $title;
        $this->initAxisX = $initAxisX;
        $this->initAxisY = $initAxisY;
    }

    public function createSheet()
    {
        return $this->spreadsheet->createSheet();
    }

    public function export()
    {
        header("Access-Control-Allow-Origin: *");
        
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}

