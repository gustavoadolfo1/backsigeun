<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class grl_documentos_gestionController extends Controller{
    public function grl_documentos_gestion_select(Request $data){
        $DocGestId      = $data->get("DocGestId");
        $DocGestKey     = $data->get("DocGestKey");
        $UniEjeId       = $data->get("UniEjeId");
        $TipDocGestId   = $data->get("TipDocGestId");
        $YearId         = $data->get("YearId");
        $DocId          = $data->get("DocId");
        $DocNro         = $data->get("DocNro");
        $DocYear        = $data->get("DocYear");
        $DocGestEstado  = $data->get("DocGestEstado");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord");
        $TypeQuery   = $data->get("TypeQuery");
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");
    
        $_records = \DB::select('exec grl.[documentos_gestion_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',
        array($DocGestId,$DocGestKey,$UniEjeId,$TipDocGestId,$YearId,$DocId,$DocNro,$DocYear,$DocGestEstado,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json( $_records );
    }
}