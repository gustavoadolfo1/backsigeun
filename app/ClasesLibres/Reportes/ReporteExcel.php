<?php
namespace App\ClasesLibres\Reportes;

use PDF;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use Carbon\Carbon;

class ReporteExcel 
{   
    public function __construct()
    {
        Carbon::setLocale(LC_TIME, 'Spanish');
    }
    
    public function generateExcel($title,$leyenda,$head,$data)
    {   
        
        header("Access-Control-Allow-Origin: *");
        $nFilHead = 0;
        $custom = [
            'color' => '#2d366f'
        ];
        Excel::create($title, function($excel) use($data, $title, $head, $custom, $nFilHead,$leyenda){
            $excel->sheet($title, function($sheet) use($data, $head, $custom, $nFilHead, $title,$leyenda) {
                $positionXY = '';
                $indice = 1;
                $headRow = 5;
                $xCanvas = 'A';
                $nColumnas = count($head);
                for ($i=0; $i <= $nColumnas + 1; $i++) { 
                    ++$xCanvas;
                }
                $xCanvas = $xCanvas;
                $asLetra = 'B';
                
                $positionX = 'C';
                $positionY = $nFilHead;
                $preFilHead = $nFilHead - 1 ;
                $headGrid = count($data) + count($leyenda) + 8;
                $post= '';

                $sheet->cells( "A1:$xCanvas$headGrid", function ($cells) {
                    $cells->setBackground('FFFFFF');
                });
                
                foreach ($leyenda as &$rowHead) { 
                    $sheet->mergeCells('B'.$headRow.':C'.$headRow);
                    $sheet->setCellValue('B'.$headRow,$rowHead['title']);
                    $sheet->getStyle('B'.$headRow)->getFont()->setName('Tahoma')->setBold(true)->setSize(9);

                    $sheet->setCellValue('D'.$headRow,$rowHead['value']);
                    $sheet->cells( 'D'.$headRow , function ($cells){
                        $cells->setAlignment('left');
                    });
                    $headRow++;
                }
                $headRow = $headRow + 3;

                foreach ($head as &$valor) {
                    ++$asLetra;
                    $positionXY = $positionX.$headRow;
                    $sheet->setCellValue('B'.$headRow, 'N°');

                    $sheet->cells('B'.$headRow, function ($cells) use($custom) {
                        $cells->setBackground($custom['color']);
                        $cells->setFontColor('FFFFFF');
                        $cells->setAlignment('center');
                    });
                    $sheet->setWidth('B', 5);

                    $sheet->setCellValue($positionXY, $valor['title']);
                    $sheet->setWidth($positionX ,$valor['width']);

                    $sheet->cells($positionXY, function ($cells) use($custom) {
                        $cells->setAlignment('center');
                        $cells->setBackground($custom['color']);
                        $cells->setFontColor('FFFFFF');
                    });

                    $sheet->getStyle($positionXY)->getFont()->setName('Tahoma')->setBold(true)->setSize(9);
                    $dataPositionY = $headRow + 1;

                    
                    foreach ($data as $key=>$row) {
                        $positionXY = $positionX.$dataPositionY;
                        $cKey = $valor['campo'];
                        $cAlign = $valor['align'];
                        $crow = (array) $row;

                        $sheet->setCellValue('B'.$dataPositionY, $key + 1);

                        $sheet->cells( 'B'.$dataPositionY , function ($cells) use($custom,$cAlign) {
                            $cells->setAlignment('center');
                            $cells->setBorder('thin','thin','thin','thin');
                        });

                        $sheet->setCellValue($positionXY, $crow[$cKey]);
                        $sheet->cells( $positionXY, function ($cells) use($custom,$cAlign) {
                            $cells->setAlignment($cAlign);
                            $cells->setBorder('thin','thin','thin','thin');
                            // $cells->setHeight(20);
                        });
                        $indice++;
                        ++$dataPositionY;
                    }
                    ++$positionX;
                }


                $sheet->cells('B'.$nFilHead.':'.$positionX.$nFilHead, function ($cells) use($custom) {
                    $cells->setBackground($custom['color']);
                    $cells->setFontColor('FFFFFF');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                // cabezera
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:".$asLetra."1");
                $sheet->cells('B1:'.$asLetra.'1', function ($cells) use($custom) {
                    $cells->setAlignment('center');
                    $cells->setFontColor($custom['color']);
                }); 
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(14);
                // cabezera
                $posHeaderData = count($leyenda) + 7;
                $sheet->mergeCells('B'.$posHeaderData.':'.$asLetra.''.$posHeaderData);
                $sheet->setCellValue('B'.$posHeaderData , $title );
                $sheet->cells('B'.$posHeaderData .':' .$asLetra.''.$posHeaderData, function ($cells) use($custom) {
                    $cells->setAlignment('center');
                    $cells->setBackground($custom['color']);
                    $cells->setFontColor('FFFFFF');
                }); 
                $sheet->getStyle('B'.$posHeaderData)->getFont()->setName('Tahoma')->setBold(true)->setSize(11);

                $sheet->setHeight(array('1'=> 50,$posHeaderData =>28));

                $sheet->setShowGridlines(false);
                $sheet->setOrientation('landscape');

            });

        })->download('XLSX');
    }
    public function generatePDF($hr,$ofi,$title,$leyenda,$head,$data)
    {
        Carbon::setLocale(LC_TIME, 'Spanish');
        $header = array_chunk($leyenda, 2);
        // dd($header);

        $data = json_decode(json_encode($data),true);
        $data = (array) $data;
        $convert = [];
        $rowFinal = [];
        $rows = [];
        foreach ($data as $key=>$row){ 
            foreach ($head as &$valor) {
                $cType = '';
                $cKey = $valor['campo'];
                if(array_key_exists('type', $valor)){
                    $cType = $valor['type'];
                } 
                $rowFinal = (array) $row;

                if($cType != '' && $cType == 'date')
                {
                    $value = $rowFinal[$cKey];
                    $date = Carbon::parse($value);
                    $fomateado =  $date->format('M D Y, h:m a');
                    $rowFinal[$cKey] = $fomateado;

                }
            }
            $convert[] = $rowFinal; 
        }
        $data = $convert; 
        if(count($head) % 4 == 0){
            $nheadD = count($head) / 4;
            $dimenciones = [ $nheadD, $nheadD, $nheadD, $nheadD , ( $nheadD + $nheadD + $nheadD ) ];
        }else{
            $nhead1 = intval(count($head) / 2);
            $nhead2 = count($head) -  $nhead1;

            $nheadA = intval( $nhead1 / 2) + 1;
            $nheadB = $nhead1 -  $nheadA;
            if($nheadB == 0){
                $nheadT = $nheadA;
                $nheadA = intval($nheadT / 2);
                $nheadB = intval($nheadT / 2);
            }
            $nheadC = intval( $nhead2 / 2);
            $nheadD = $nhead2 -  $nheadC;
            
            $dimenciones = [ $nheadA, $nheadB, $nheadC, $nheadD , ( $nheadB + $nheadC + $nheadD ) ];
        }

        $thead = [];
        $index = 0;
        foreach($header as $key=>$rt){
            $header[$key][0]['colspan'] = [ $dimenciones[0],$dimenciones[1] ]; 
            if(count($rt) > 1){
                $header[$key][1]['colspan'] = [ $dimenciones[2],$dimenciones[3] ]; 
            }  
        }
        // return response()->json($dimenciones);
        if($hr == 'vertical'){
            $pdf = PDF::loadView('reportes.generadorPDF', compact(['ofi','title','header','head','data','dimenciones']) )->setPaper('A4','portrait');/*'portrait || landscape'*/
        }else{
            $pdf = PDF::loadView('reportes.generadorPDF', compact(['ofi','title','header','head','data','dimenciones']) )->setPaper('A4','landscape');/*'portrait || landscape'*/
        }
        
        return $pdf->stream();

    }
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    public function generateHtml($title,$html)
    {
        $pdf = PDF::loadView('reportes.htmlPDF', compact(['title','html']) )->setPaper('A4','portrait');/*'portrait || landscape'*/
        return $pdf->stream();
    }

    public function generateRow($x, $y, $data, $key = null, $sheet)
    {
        if ($key == null) {
            foreach ($data as $index => $value) {
                $cellValue = $value;
                $sheet->setCellValue($x.$y, $cellValue);
                if ($index + 1 < count($data)) {
                    ++$x;
                }
                
            }
        }
        else {
            foreach ($data as $index => $value) {
                $cellValue = $value[$key];
                $sheet->setCellValue($x.$y, $cellValue);
                if ($index + 1 < count($data)) {
                    ++$x;
                }
            }
        }

        return [ $sheet, $x ];
    }

    public function setWidthColumns($columns, $sheet)
    {
        foreach ($columns as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
        return $sheet;
    }
}
// $sheet->getStyle($positions)->applyFromArray(
//     array(
//         'borders' => array(
//             'bottom'     => array(
//                 'style' => 'thin',
//                 'color' => array(
//                     'rgb' => 'D13B3B'
//                 )
//             ),
//             'top'     => array(
//                 'style' => 'thin',
//                 'color' => array(
//                     'rgb' => 'D13B3B'
//                 )
//             ),
//             'left'     => array(
//                 'style' => 'thin',
//                 'color' => array(
//                     'rgb' => 'D13B3B'
//                 )
//             ),
//             'right'     => array(
//                 'style' => 'thin',
//                 'color' => array(
//                     'rgb' => 'D13B3B'
//                 )
//             ),
//         ),
//         'quotePrefix'    => true
//     )
// ); 