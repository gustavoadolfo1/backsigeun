<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_conceptosController extends Controller{
    public function grl_conceptos_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $ConcepId      = $data->get("ConcepId");
            $UniEjeId      = $data->get("UniEjeId");
            $DocGestId     = $data->get("DocGestId");
            $ConcepNombre  = $data->get("ConcepNombre");
            $ConcepAbrev   = $data->get("ConcepAbrev");
            $ConcepCodigo  = $data->get("ConcepCodigo");
            $DepenId       = $data->get("DepenId");
            $DepenIniciaId = $data->get("DepenIniciaId");
            $ConcepEstado  = $data->get("ConcepEstado");
            $TipDocGestId  = $data->get("TipDocGestId");
            $DocGestYear   = $data->get("DocGestYear");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord"); 
            $TypeQuery   = $data->get("TypeQuery"); 
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit"); 
            $RecordStart = $data->get("RecordStart");
        } else {
            $ConcepId      = isset($param["ConcepId"]) ? $param["ConcepId"] : "";
            $UniEjeId      = isset($param["UniEjeId"]) ? $param["UniEjeId"] : "";
            $DocGestId     = isset($param["DocGestId"]) ? $param["DocGestId"] : "";
            $ConcepNombre  = isset($param["ConcepNombre"]) ? $param["ConcepNombre"] : "";
            $ConcepAbrev   = isset($param["ConcepAbrev"]) ? $param["ConcepAbrev"] : "";
            $ConcepCodigo  = isset($param["ConcepCodigo"]) ? $param["ConcepCodigo"] : "";
            $DepenId       = isset($param["DepenId"]) ? $param["DepenId"] : "";
            $DepenIniciaId = isset($param["DepenIniciaId"]) ? $param["DepenIniciaId"] : "";
            $ConcepEstado  = isset($param["ConcepEstado"]) ? $param["ConcepEstado"] : "";
            $TipDocGestId  = isset($param["TipDocGestId"]) ? $param["TipDocGestId"] : "";
            $DocGestYear   = isset($param["DocGestYear"]) ? $param["DocGestYear"] : "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";

        }
        $_records = \DB::select('exec grl.[conceptos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($ConcepId,$UniEjeId,$DocGestId,$ConcepNombre,$ConcepAbrev,$ConcepCodigo,$DepenId,$DepenIniciaId,$ConcepEstado,$TipDocGestId,$DocGestYear,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
    public function grl_conceptos_update(Request $data){
        $TypeEdit     = ($data->get("ConcepId")*1 > 0 ? 2 : 1);
        $ConcepId     = $data->get("ConcepId");
        $ConcepKey    = $data->get("ConcepKey");
        $DocGestId    = $data->get("DocGestId");
        $ConcepCode   = $data->get("ConcepCode");
        $ConcepNombre = $data->get("ConcepNombre");
        $DepenId      = $data->get("DepenId");
        $ConcepObserv = $data->get("ConcepObserv");
        $ConcepEstado = $data->get("ConcepEstado");
        $SessKey = $data->get("SessKey");
        $MenuId  = $data->get("MenuId");

        $_id = \DB::select('exec grl.[conceptos_sp_update] ?,?,?,?,?,?,?,?,?,?,?',array(
            $TypeEdit,$ConcepId,$ConcepKey,$DocGestId,$ConcepCode,$ConcepNombre,$DepenId,$ConcepObserv,$ConcepEstado,$SessKey,$MenuId));
        return $_id;
    }
}