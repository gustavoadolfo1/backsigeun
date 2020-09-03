<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ura_estudiantesController extends Controller{
    public function ura_estudiantes_select(Request $data){
        $EstudId       = $data->get("EstudId");
        $EstudCodUniv  = $data->get("EstudCodUniv");
        $PersId        = $data->get("PersId");
        $CarreraId     = $data->get("CarreraId");
        $CurricId      = $data->get("CurricId");
        $ClasificId    = $data->get("ClasificId");
        $FilId         = $data->get("FilId");
        $FilIngresoId  = $data->get("FilIngresoId");
        $ModalidadCod  = $data->get("ModalidadCod");
        $PersDocumento = $data->get("PersDocumento");
        $PersApenom    = $data->get("PersApeNom");
        $SessKey     = $data->get("SessKey");
        $MenuId      = $data->get("MenuId");
        $TypeRecord  = $data->get("TypeRecord"); 
        $TypeQuery   = $data->get("TypeQuery"); 
        $OrderBy     = $data->get("OrderBy");
        $RecordLimit = $data->get("RecordLimit");
        $RecordStart = $data->get("RecordStart");

        $_records = \DB::select('exec ura.[estudiantes_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($EstudId,$EstudCodUniv,$PersId,$CarreraId,$CurricId,$ClasificId,$FilId,$FilIngresoId,$ModalidadCod,$PersDocumento,$PersApenom,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json( $_records );
    }
}