<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_conceptos_enlacesController extends Controller{
    public function tre_conceptos_enlaces_select(Request $data){
        $ConcepEnlacId      = $data->get("ConcepEnlacId");
        $ConcepEnlacFlga    = $data->get("ConcepEnlacFlga");
        $ConcepEnlacNombre  = $data->get("ConcepEnlacNombre");
        $ConcepEnlacAbrev   = $data->get("ConcepEnlacAbrev");
        $ConcepEnlacCode    = $data->get("ConcepEnlacCode");
        $ConcepEnlacEstado  = $data->get("ConcepEnlacEstado");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec tre.[conceptos_enlaces_sp_select] ?,?,?,?,?,?,?,?,?,?,?', 
        array($ConcepEnlacId,$ConcepEnlacFlga,$ConcepEnlacNombre,$ConcepEnlacAbrev,$ConcepEnlacCode,$ConcepEnlacEstado,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}