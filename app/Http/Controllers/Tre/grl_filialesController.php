<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_filialesController extends Controller{
    public function grl_filiales_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $FilId         = $data->get("FilId");
            $FilKey        = $data->get("FilKey");
            $UniEjeId      = $data->get("UniEjeId");
            $FilPrincipal  = $data->get("FilPrincipal");
            $FilNombre     = $data->get("FilNombre");
            $FilAbrev      = $data->get("FilAbrev");
            $FilSigla      = $data->get("FilSigla");
            $PaisId        = $data->get("PaisId");
            $DptoId        = $data->get("DptoId");
            $PrvnId        = $data->get("PrvnId");
            $DsttId        = $data->get("DsttId");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord"); 
            $TypeQuery   = $data->get("TypeQuery"); 
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit");
            $RecordStart = $data->get("RecordStart");
        } else {
            $FilId         = isset($param["FilId"]) ? $param["FilId"] : "";
            $FilKey        = isset($param["FilKey"]) ? $param["FilKey"] : "";
            $UniEjeId      = isset($param["UniEjeId"]) ? $param["UniEjeId"] : "";
            $FilPrincipal  = isset($param["FilPrincipal"]) ? $param["FilPrincipal"] : "";
            $FilNombre     = isset($param["FilNombre"]) ? $param["FilNombre"] : "";
            $FilAbrev      = isset($param["FilAbrev"]) ? $param["FilAbrev"] : "";
            $FilSigla      = isset($param["FilSigla"]) ? $param["FilSigla"] : "";
            $PaisId        = isset($param["PaisId"]) ? $param["PaisId"] : "";
            $DptoId        = isset($param["DptoId"]) ? $param["DptoId"] : "";
            $PrvnId        = isset($param["PrvnId"]) ? $param["PrvnId"] : "";
            $DsttId        = isset($param["DsttId"]) ? $param["DsttId"] : "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";

        }
        $_records = \DB::select('exec grl.[filiales_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($FilId,$FilKey,$UniEjeId,$FilPrincipal,$FilNombre,$FilAbrev,$FilSigla,$PaisId,$DptoId,$PrvnId,$DsttId,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}