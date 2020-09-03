<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_departamentosController extends Controller{
    public function grl_departamentos_select(Request $data){
        $DptoId        = $data->get("DptoId");
        $PaisId        = $data->get("PaisId");
        $DptoNombre    = $data->get("DptoNombre");
        $DptoAbrev     = $data->get("DptoAbrev");
        $DptoEstado    = $data->get("DptoEstado");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit"); 
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[departamentos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?', array($DptoId,$PaisId,$DptoNombre,$DptoAbrev,$DptoEstado,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}