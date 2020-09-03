<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_provinciasController extends Controller{
    public function grl_provincias_select(Request $data){
        $PrvnId        = $data->get("PrvnId");
        $DptoId        = $data->get("DptoId");
        $PrvnNombre    = $data->get("PrvnNombre");
        $PrvnAbrev     = $data->get("PrvnAbrev");
        $PrvnCode      = $data->get("PrvnCode");
        $PrvnEstado    = $data->get("PrvnEstado");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit"); 
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[provincias_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?', array($PrvnId,$DptoId,$PrvnNombre,$PrvnAbrev,$PrvnCode,$PrvnEstado,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}