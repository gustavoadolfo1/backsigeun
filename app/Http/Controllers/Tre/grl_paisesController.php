<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_paisesController extends Controller{
    public function grl_paises_select(Request $data){
        $PaisId        = $data->get("PaisId");
        $PaisNombre    = $data->get("PaisNombre");
        $PaisAbrev     = $data->get("PaisAbrev");
        $PaisCode      = $data->get("PaisCode");
        $PaisEstado    = $data->get("PaisEstado");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord");
        $TypeQuery   = $data->get("TypeQuery");
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec grl.[paises_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?', array($PaisId,$PaisNombre,$PaisAbrev,$PaisCode,$PaisEstado,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}