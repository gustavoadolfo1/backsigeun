<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;
use TCPDF_FONTS;

use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\Tesoreria\ComprobantesPagoExport;
use App\Exports\Tesoreria\ExcelExport;
use App\Imports\Tesoreria\ExcelImport;

class ReportesController extends Controller
{
    private $dataAll = array();
    private $errorNameReport = "";
    private $dataResult = "";
    private $listResult = "";
    
    // public function excelComprobantesPago($nameReport,$ids){
    //     $excel = new ComprobantesPagoExport($nameReport,$ids);
    //     return Excel::download($excel,'nombre.xlsx');
    // }

    public function rptEXCEL($nameReport,$ids){
        $excel = new ExcelExport($nameReport,$ids);
        return Excel::download($excel,'nombre.xlsx');
    }

    public function importEXCEL(Request $request) 
    {

        $ruta  = $request->archivo->store('TABLAS_GENERALES/CUENTAS_DETRACCION');
        //$rut = 'http://localhost:8089/storage/'.$ruta;
        $rut =  storage_path('app/public/'.$ruta);
        //echo $rut;
        //echo $request->formatImport;
        Excel::import(new ExcelImport($request), $rut);
        //Excel::import(new CuentasDetraccionImport, $rut);
    }    
    public function rptPDF($nameReport,$paper="portrait",$id="",$ids=""){

        $dataAll = $this->dataAllPDF($nameReport,$id,$ids);
        if(!empty($dataAll)){
            $nameReport = $dataAll['nameReport'];
            $data = $dataAll['data'];
            $list = $dataAll['list'];
            $pdf = PDF::loadView('tesoreria.'.$nameReport, compact(['data','list']) )->setPaper('A4',$paper);
            return $pdf->stream();
        }else{
            echo 'array vacio';          
        }
    }

    public function dataAllPDF($nameReport,$id='',$ids='')
    {
        switch($nameReport)
        {
            /**CARTAS FIANZA */
            case 'rptCartasFianza':
                $this->dataResult = '';//id cabecera
                $this->listResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzasXcCodigoCadena ?',[$ids]);//ids items
            break;
            /**DETRACCIONES MASIVAS */
            case 'rptDetraccionesMasivas':
                $this->dataResult = '';
                $this->listResult = DB::select('EXEC tre.Sp_SEL_detracciones_proveedoresXcCodigoCadena ?',[$ids]);
            break;
            case 'rptComprobantesPagoA':
                $this->dataResult = '';
                $this->listResult = DB::select('EXEC tre.Sp_SEL_comprobantes_pagoXcCodigoCadena ?',[$ids]);
            break;
            case 'rptComprobantesPagoB':
                $this->dataResult = '';
                $this->listResult = DB::select('EXEC tre.Sp_SEL_consulta_expedientes_giradosXcCodigoCadena 1,?',[$ids]);
            break;
            case 'rptComprobantesPagoD':
                $this->dataResult = '';
                $this->listResult = DB::select('EXEC tre.Sp_SEL_consulta_expedientes_columnatotalfaseXcCodigoCadena 1,?',[$ids]);                
            break;            
            default:
                echo ' no es un reporte válido';
            break;     
        }
        $this->dataAll = array(
            'nameReport'=>$nameReport,
            'data'=>$this->dataResult,//cabecera
            'list'=>$this->listResult//detalle
        );         
        return $this->dataAll;
    } 



}
