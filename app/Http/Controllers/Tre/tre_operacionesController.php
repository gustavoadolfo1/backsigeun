<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_operacionesController extends Controller{
    public function tre_operaciones_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $OperId       = $data->get("OperId");
            $OperKey      = $data->get("OperKey");
            $UniEjeId     = $data->get("UniEjeId");
            $FilId        = $data->get("FilId");
            $TipOperId    = $data->get("TipOperId");
            $FechaIni     = $data->get("FechaIni");
            $FechaFin     = $data->get("FechaFin");
            $CredDepenId  = $data->get("CredDepenId");
            $OperEstado   = $data->get("OperEstado");
            $CredDepen    = $data->get("iCredDepen");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord"); 
            $TypeQuery   = $data->get("TypeQuery"); 
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit");
            $RecordStart = $data->get("RecordStart");
        } else {
            $OperId       = isset($param["OperId"]) ? $param["OperId"] : "";
            $OperKey      = isset($param["OperKey"]) ? $param["OperKey"] : "";
            $UniEjeId     = "";
            $FilId        = "";
            $TipOperId    = "";
            $FechaIni     = isset($param["FechaIni"]) ? $param["FechaIni"] : "";
            $FechaFin     = isset($param["FechaFin"]) ? $param["FechaFin"] : "";
            $CredDepenId  = "";
            $OperEstado   = "";
            $CredDepen    = "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";
        }
        $_records = \DB::select("exec tre.[operaciones_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?",
        array($OperId,$OperKey,$UniEjeId,$FilId,$TipOperId,$FechaIni,$FechaFin,$CredDepenId,$OperEstado,$CredDepen,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json( $_records );
    }

    public function tre_operaciones_update(Request $data){
        $OperId      = $data->get("OperId");
        $OperKey     = $data->get("OperKey");
        $Type        = $data->get("Type");
        $OperFecha   = $data->get("OperFecha");
        $Password    = $data->get("Password");
        $OperObserv  = $data->get("OperObserv");
        $SessKey     = $data->get("SessKey");
        $MenuId      = 0; //$data->get("MenuId");

        $_id = \DB::select('exec tre.[operaciones_sp_update] ?,?,?,?,?,?,?,?',array($OperId,$OperKey,$Type,$OperFecha,$Password,$OperObserv,$SessKey,$MenuId));
        return $_id;
    }
}