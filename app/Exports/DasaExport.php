<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DasaExport implements FromArray,WithHeadings,ShouldAutoSize,WithEvents,WithCustomStartCell{
    use Exportable;

    private $tipoReporte;
    private $dataEncabezado;
    private $arrayContenido;
    private $arrayTitulos;

    private $posicionTitulo;

    public $letrasABC;

    public $merge;
    public $rangeTitulos;
    public $inicioTablaFila;
    public $inicioTablaColumna;
    public $headers;


    public function __construct($tipoReporte, $arrayTitulos, $arrayContenido) {
        $this->tipoReporte = $tipoReporte;
        $this->arrayTitulos = $arrayTitulos;
        $this->arrayContenido = $arrayContenido;

        foreach (range('A', 'Z') as $idx => $char) {
            $this->letrasABC[$idx] = $char;
        }

        $this->encabezados();
    }

    public function encabezados(){
        switch ($this->tipoReporte){
            case 'horariosResumen':

                //dd(count($this->arrayTitulos[0]));

                $this->dataEncabezado = (object) [
                    'titulo' => 'UNIVERSIDAD NACIONAL DE MOQUEGUA',
                    'subtitulo' => 'Reporte de Horarios',
                    'titulos' => $this->arrayTitulos,
                    'posicion' => $this->letrasABC[count($this->arrayTitulos[0]) - 1],
                    'merge' => []
                ];

                break;

        }
    }
    public function array():array{
        return $this->arrayContenido;
        /*switch ($this->tipoReporte){
            case 'horariosTesumen':
                $this->dataEncabezado = (object) [
                    'titulo' => 'UNIVERSIDAD NACIONAL DE MOQUEGUA',
                    'subtitulo' => 'Reporte de Horarios',
                    'titulos' => $this->arrayContenido
                ];

                break;

        }*/
    }

    public function startCell(): string {
        return $this->inicioTablaColumna . $this->inicioTablaFila;//'A8';
    }

    public function headings(): array {
        return $this->dataEncabezado->titulos;
    }


    public function registerEvents(): array {

        // dd($this->letrasABC);
        return [
            /*
            BeforeExport::class => function(BeforeExport $event) {
            $event->writer->getProperties()->setCreator('Antony Salas');
            },
            */
            AfterSheet::class    => function(AfterSheet $event) {

                $styleArrayGrl = [
                    'titulos' => [
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]
                ];




                $cellRange = 'A4:'.$this->dataEncabezado->posicion.'4'; // All headers
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],/*
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],*/
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'dee2e6'],
                    ],
                ];
                /*
                $event->sheet->getDelegate()->setCellValue('A1', 'ENTIDAD:')->getStyle('A1')->getFont()->setSize(10);
                $event->sheet->getDelegate()->setCellValue('A2', 'REPORTE:')->getStyle('A2')->getFont()->setSize(10);
                $event->sheet->getDelegate()->setCellValue('A3', 'FECHA:')->getStyle('A3')->getFont()->setSize(10);
                $event->sheet->getDelegate()->mergeCells('B1:'.$this->dataEncabezado->posicion.'1');
                $event->sheet->getDelegate()->mergeCells('B2:'.$this->dataEncabezado->posicion.'2');
                $event->sheet->getDelegate()->mergeCells('B3:'.$this->dataEncabezado->posicion.'3');

                $event->sheet->getDelegate()->setCellValue('B2', $this->dataEncabezado->titulo)->getStyle('B2')->getFont()->setSize(12);
                $event->sheet->getDelegate()->setCellValue('B3', $this->dataEncabezado->subtitulo )->getStyle('B3')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
*/

                //$event->sheet->getDelegate()->setCellValue('A1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA')
                 //   ->getStyle('A1')->getFont()->setSize(12);


                if (isset($this->headers)){
                    foreach ($this->headers as $header){
                        $event->sheet->getDelegate()->setCellValue($header['cellInicio'], $header['text'])
                            ->getStyle($header['cellInicio'])->getFont()->setSize($header['size']);
                        if ($header['cellInicio'] != $header['cellFin']){
                            $this->merge[] = $header['cellInicio'] .':'. $header['cellFin'];
                            //dd($header['cellInicio'] .':'. $header['cellFin']);
                            //$event->sheet->getDelegate()->mergeCells($header['cellInicio'] .':'. $header['cellFin']);
                        }

                        if (isset($header['style'])){
                            $event->sheet->getDelegate()->getStyle($header['cellInicio'])->applyFromArray($styleArrayGrl[$header['style']]);
                        }
                    }

                }


                //dd($this->startCell());
                if (isset($this->merge)){
                    $event->sheet->getDelegate()->setMergeCells($this->merge)
                        //->fromArray($this->dataEncabezado->titulos, '', 'A49')
                    ;
                }
                if (isset($this->rangeTitulos)){
                    $event->sheet->getDelegate()->getStyle($this->rangeTitulos)->applyFromArray($styleArray);
                }

                $event->sheet->getDelegate()->getStyle(
                    $this->startCell() . ':' . $event->sheet->getDelegate()->getHighestDataColumn() . $event->sheet->getDelegate()->getHighestDataRow()
                )->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

/*
                $event->sheet->getDelegate()->setCellValue(
                    $this->inicioTablaColumna . ($event->sheet->getDelegate()->getHighestDataRow() + 3),
                    'Fecha : ' .today()
                );
                */


                /*dd();



                dd([
                    $event->sheet->getDelegate()->getHighestDataColumn(),
                    $event->sheet->getDelegate()->getHighestDataRow(),
                    $event->sheet->getDelegate()->calculateWorksheetDataDimension(),
                    $event->sheet->getDelegate()->getDataValidationCollection(),

                ]);
                dd($event->sheet->getDelegate()->getHighestRowAndColumn());*/

                //$event->sheet->getDelegate()->getStyle('A49:I50')->applyFromArray($styleArray);

            },
            BeforeSheet::class => function(BeforeSheet $event) {

            },
        ];
    }

    /**
     * Sumar LETRAS
     *
     * @param $letter
     * @param $lettersToAdd
     *
     * @return mixed
     */

    public function addLetters($letter,$lettersToAdd){
        for ($i=0;$i<$lettersToAdd;$i++){
            $letter++;
        }
        return $letter;
    }
}
