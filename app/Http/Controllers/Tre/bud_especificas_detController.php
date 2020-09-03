<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class bud_especificas_detController extends Controller{
    public function bud_especificas_det_select(Request $data){
        $EspeDetId       = $data->get("EspeDetId");
        $EspeDetKey      = $data->get("EspeDetKey");
        $TipTransCode    = $data->get("TipTransCode");
        $GeneCode        = $data->get("GeneCode");
        $SubGeneCode     = $data->get("SubGeneCode");
        $SubGeneDetCode  = $data->get("SubGeneDetCode");
        $EspeCode        = $data->get("EspeCode");
        $EspeDetCode     = $data->get("EspeDetCode");
        $EspeDetNombre   = $data->get("EspeDetNombre");
        $EspeDetAbrev    = $data->get("EspeDetAbrev");
        $EspeDetSiaf     = $data->get("EspeDetSiaf");
        $EspeDetEstado   = $data->get("EspeDetEstado");
        $EspeDetCodigo   = $data->get("EspeDetCodigo");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec bud.[especificas_det_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array($EspeDetId,$EspeDetKey,$TipTransCode,$GeneCode,$SubGeneCode,$SubGeneDetCode,$EspeCode,$EspeDetCode,$EspeDetNombre,$EspeDetAbrev,$EspeDetSiaf,$EspeDetEstado,$EspeDetCodigo,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}