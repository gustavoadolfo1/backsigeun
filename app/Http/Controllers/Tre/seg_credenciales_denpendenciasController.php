<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class seg_credenciales_dependenciasController extends Controller{
    public function seg_credenciales_dependencias_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $CredDepenId      = $data->get("CredDepenId");
            $CredDepenKey     = $data->get("CredDepenKey");
            $UniEjeId         = $data->get("UniejeId");
            $CredId           = $data->get("CredId");
            $DepenId          = $data->get("DepenId");
            $CargId           = $data->get("CargId");
            $CredDepenFirma   = $data->get("CredDepenFirma");
            $FechaIni         = $data->get("FechaIni");
            $FechaFin         = $data->get("FechaFin");
            $CredDepenEstado  = $data->get("CredDepenEstado");
            $CredKey          = $data->get("CredKey");
            $PersId           = $data->get("PersId");
            $CredUsuario      = $data->get("CredUsuario");
            $TipoCredId       = $data->get("TipoCredId");
            $CredEstado       = $data->get("CredEstado");
            $UniEjeKey        = $data->get("UniEjeKey");
            $DepenKey         = $data->get("DepenKey");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord");
            $TypeQuery   = $data->get("TypeQuery");
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit"); 
            $RecordStart = $data->get("RecordStart");
        } else {
            $CredDepenId      = isset($param["CredDepenId"]) ? $param["CredDepenId"] : "";
            $CredDepenKey     = isset($param["CredDepenKey"]) ? $param["CredDepenKey"] : "";
            $UniEjeId         = "";
            $CredId           = "";
            $DepenId          = "";
            $CargId           = "";
            $CredDepenFirma   = "";
            $FechaIni         = "";
            $FechaFin         = "";
            $CredDepenEstado  = "";
            $CredKey          = "";
            $PersId           = "";
            $CredUsuario      = "";
            $TipoCredId       = "";
            $CredEstado       = "";
            $UniEjeKey        = "";
            $DepenKey         = "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";
        }

        $_records = \DB::select('exec seg.[credenciales_dependencias_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
            $CredDepenId,$CredDepenKey,$UniEjeId,$CredId,$DepenId,$CargId,$CredDepenFirma,$FechaIni,$FechaFin,$CredDepenEstado,
            $CredKey,$PersId,$CredUsuario,$TipoCredId,$CredEstado,$UniEjeKey,$DepenKey,
            $SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}