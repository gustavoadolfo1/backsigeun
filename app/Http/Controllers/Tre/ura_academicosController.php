<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ura_academicosController extends Controller{
    public function ura_academicos_select(Request $data){
        $PersId        = $data->get("PersId");
        $UniejeId      = $data->get("UniejeId");
        $FilId         = $data->get("FilId");
        $PersDocumento = $data->get("PersDocumento");
        $PersApeNom    = $data->get("PersApeNom");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec ura.[academicos_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?',array($PersId,$UniejeId,$FilId,$PersDocumento,$PersApeNom,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }
}