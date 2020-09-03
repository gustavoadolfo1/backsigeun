<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_ingresos_especificas_detController extends Controller{
    public function tre_ingresos_especificas_det_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $IngEspeDetId   = $data->get("IngEspeDetId");
            $IngEspeDetKey  = $data->get("IngEspeDetKey");
            $UniEjeId       = $data->get("UniEjeId");
            $EspeDetId      = $data->get("EspeDetId");
            $EspeDetNombre  = $data->get("EspeDetNombre");
            $EspeDetCodigo  = $data->get("EspeDetCodigo");
            $EspeDetSiaf    = $data->get("EspeDetSiaf");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord");
            $TypeQuery   = $data->get("TypeQuery");
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit");
            $RecordStart = $data->get("RecordStart");
        } else {
            $IngEspeDetId   = isset($param["IngEspeDetId"]) ? $param["IngEspeDetId"] : "";
            $IngEspeDetKey  = isset($param["IngEspeDetKey"]) ? $param["IngEspeDetKey"] : "";
            $UniEjeId       = "";
            $EspeDetId      = "";
            $EspeDetNombre  = "";
            $EspeDetCodigo  = "";
            $EspeDetSiaf    = "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";
    }

        $_records = \DB::select('exec tre.[ingresos_especificas_det_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($IngEspeDetId,$IngEspeDetKey,$UniEjeId,$EspeDetId,$EspeDetNombre,$EspeDetCodigo,$EspeDetSiaf,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json( $_records );
    }
}