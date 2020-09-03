<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_dependenciasController extends Controller{
    public function grl_dependencias_select(Request $data){
        $DepenId           = $data->get("DepenId");
        $UniEjeId          = $data->get("UniEjeId");
        $TipoDepenId       = $data->get("TipoDepenId");
        $cDepenDependeId   = $data->get("DepenDependeId");
        $DepenNombre       = $data->get("DepenNombre");
        $DepenAbrev        = $data->get("DepenAbrev");
        $DepenSigla        = $data->get("DepenSigla");
        $DepenCode         = $data->get("DepenCode");
        $DepenOrganigrama  = $data->get("DepenOrganigrama");
        $DepenNivel        = $data->get("DepenNivel");
        $DepenEstado       = $data->get("DepenEstado");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit"); 
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[dependencias_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($DepenId,$UniEjeId,$TipoDepenId,$cDepenDependeId,$DepenNombre,$DepenAbrev,$DepenSigla,$DepenCode,$DepenOrganigrama,$DepenNivel,$DepenEstado,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}