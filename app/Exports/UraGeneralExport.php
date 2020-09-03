<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UraGeneralExport implements FromArray, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    use Exportable;

    private $header;
    private $data;
    private $params;
    private $keys;
    private $title;

    private $letters;

    private $lastLetterData;
    private $lastNumberData;

    public function __construct($header, $data, $params, $keys, $title = 'REPORTE') {
        $this->header = $header;
        $this->data = $data;
        $this->params = $params;
        $this->keys = $keys;
        $this->title = $title;

        foreach (range('A', 'Z') as $idx => $char) {
            $this->letters[$idx] = $char;
        }

        $this->lastLetterData = $this->letters[count($keys) - 1];
        $this->lastNumberData = count($data) + 1;
    }

    public function array(): array {
        return $this->data;
    }

    public function headings(): array {
        return $this->header;
    }

    public function map($data): array 
    {
        $map = [];

        foreach ($this->keys as $key) {
            $map[] = $data->$key;
        }

        return $map;
    }

    public function registerEvents(): array 
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->getStyle("A1:" . $this->lastLetterData . $this->lastNumberData)->applyFromArray(
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                    ]
                );

                $event->sheet->getStyle("A1:" . $this->lastLetterData . "1" )->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');

                $event->sheet->insertNewRowBefore(1, count($this->params) + 3);

                /**
                 * Poniendo el title del archivo
                 */
                $event->sheet->setCellValue('A1', $this->title);
                $event->sheet->mergeCells('A1:' . $this->lastLetterData . "1" );
                $event->sheet->mergeCells('A2:' . $this->lastLetterData . "2" );
                /**
                 * Poniendo los params del archivo
                 */

                $y = 2;
                foreach ($this->params as $param) {
                    $y += 1;

                    $event->sheet->setCellValue('A' . $y, $param[0]);
                    $event->sheet->setCellValue('B' . $y, $param[1]);
                    $event->sheet->getStyle('B' . $y . ":" . $this->lastLetterData . $y )
    ->getAlignment()->setHorizontal('left');
                    $event->sheet->mergeCells('B' . $y . ":" . $this->lastLetterData . $y );
                }
             
                $event->sheet->getStyle("A3:A" . $y )->getFill()->setFillType('solid')->getStartColor()->setARGB('c3c3c3c3');

                $event->sheet->mergeCells('A'. ($y + 1) . ":" . $this->lastLetterData . ($y + 1) );
            },
        ];
    }

    public function setTitle($sheet)
    {
        $sheet->setCellValue('B1', $this->title);
        $sheet->getDelegate()->mergeCells('B1:' . $this->lastLetterData . "1" );
    }

    public function setParams()
    {
        
    }

}
