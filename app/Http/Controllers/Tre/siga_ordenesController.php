<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class siga_ordenesController extends Controller{
    public function siga_ordenes_select(Request $data){
        $YearId      = $data->get("YearId");
        $UniEjeId    = $data->get("UniEjeId");
        $OrdenNro    = $data->get("OrdenNro");
        $BstCode     = $data->get("BstCode");
        $CenCosCode  = $data->get("CenCosCode");
        $ProvId      = $data->get("ProvId");
        $FechaIni    = $data->get("FechaIni");
        $FechaFin    = $data->get("FechaFin");
        $SecFuncCode = $data->get("SecFuncCode");
        $FueFinCode  = $data->get("FueFinCode");
        $ExpeNro     = $data->get("ExpeNro");
        $ActProyCode = $data->get("ActProyCode");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec siga.[ordenes_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array(
            $YearId,$UniEjeId,$OrdenNro,$BstCode,$CenCosCode,$ProvId,$FechaIni,$FechaFin,$SecFuncCode,$FueFinCode,$ExpeNro,$ActProyCode,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }

    public function siga_ordenes_selectprov(Request $data){
        $YearId      = $data->get("YearId");
        $UniEjeId    = $data->get("UniEjeId");
        $BstCode     = $data->get("BstCode");
        $OrdenNro    = $data->get("OrdenNro");
        $CenCosCode  = $data->get("CenCosCode");
        $FechaIni    = $data->get("FechaIni");
        $FechaFin    = $data->get("FechaFin");
        $SecFuncCode = $data->get("SecFuncCode");
        $FueFinCode  = $data->get("FueFinCode");
        $ExpeNro     = $data->get("ExpeNro");
        $ActProyCode = $data->get("ActProyCode");
        $PersSessKey = $data->get("PersSessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec siga.[ordenes_sp_selectprov] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array(
            $YearId,$UniEjeId,$BstCode,$OrdenNro,$CenCosCode,$FechaIni,$FechaFin,$SecFuncCode,$FueFinCode,$ExpeNro,$ActProyCode,$PersSessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}