<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class seg_credencialesController extends Controller{
    public function seg_credenciales_select(Request $data){
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
        $SessKey     = NULL;
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord");
        $TypeQuery   = $data->get("TypeQuery");
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit"); 
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec seg.[credenciales_dependencias_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
            $CredDepenId,$CredDepenKey,$UniEjeId,$CredId,$DepenId,$CargId,$CredDepenFirma,$FechaIni,$FechaFin,$CredDepenEstado,
            $CredKey,$PersId,$CredUsuario,$TipoCredId,$CredEstado,$UniEjeKey,$DepenKey,
            $SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}