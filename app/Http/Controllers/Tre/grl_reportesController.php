<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_reportesController extends Controller{
    public function grl_reportes_select(Request $data){
        $RepId          = $data->get("iRepId");
        $UniEjeId       = $data->get("UniejeId");
        $MenuId         = $data->get("MenuId");
        $RepNombre      = $data->get("RepNombre");
        $RepAbrev       = $data->get("RepAbrev");
        $RepCode        = $data->get("RepCode");
        $RepEstado      = $data->get("RepEstado");
        $SessKey     = $data->get("SessKey");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit"); 
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[reportes_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?',array($RepId,$UniEjeId,$MenuId,$RepNombre,$RepAbrev,$RepCode,$RepEstado,$SessKey,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}