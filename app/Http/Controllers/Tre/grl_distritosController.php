<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_distritosController extends Controller{
    public function grl_distritos_select(Request $data){
        $DsttId        = $data->get("DsttId");
        $PrvnId        = $data->get("PrvnId");
        $DsttNombre    = $data->get("DsttNombre");
        $DsttAbrev     = $data->get("DsttAbrev");
        $DsttCode      = $data->get("DsttCode");
        $DsttRegion    = $data->get("DsttRegion");
        $DsttEstado    = $data->get("DsttEstado");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit"); 
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[distritos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($DsttId,$PrvnId,$DsttNombre,$DsttAbrev,$DsttCode,$DsttRegion,$DsttEstado,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}