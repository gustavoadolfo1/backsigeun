<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class tre_adeudos_cabController extends Controller{
    public function tre_adeudos_cab_select(Request $data){
        $AdeuCabId        = $data->get("AdeuCabId");
        $AdeuCabKey       = $data->get("AdeuCabKey");
        $AdeuCabFlga      = $data->get("AdeuCabFlga");
        $UniEjeId         = $data->get("UniEjeId");
        $FilId            = $data->get("FilId");
        $DocId            = $data->get("DocId");
        $DocNro           = $data->get("DocNro");
        $DocFecha         = $data->get("DocFecha");
        $TipAdeuId        = $data->get("TipAdeuId");
        $PersId           = $data->get("PersId");
        $Tablex           = $data->get("Tablex");
        $TablexId         = $data->get("TablexId");
        $Tabley           = $data->get("Tabley");
        $TableyId         = $data->get("TableyId");
        $AdeuCabEstado    = $data->get("AdeuCabEstado");
        $PersApeNom       = $data->get("PersApeNom");
        $TablexCode       = $data->get("TablexCode");
        $SessKey          = $data->get("SessKey");
        $MenuId           = $data->get("MenuId");
        $TypeRecord       = $data->get("TypeRecord"); 
        $TypeQuery        = $data->get("TypeQuery"); 
        $OrderBy          = $data->get("OrderBy");
        $RecordLimit      = $data->get("RecordLimit");
        $RecordStart      = $data->get("RecordStart");

        $_records = \DB::select('exec tre.[adeudos_cab_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($AdeuCabId,$AdeuCabKey,$AdeuCabFlga,$UniEjeId,$FilId,$DocId,$DocNro,$DocFecha,$TipAdeuId,$PersId,$Tablex,$TablexId,$Tabley,$TableyId,$AdeuCabEstado,$PersApeNom,$TablexCode,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }

    public function tre_adeudos_cab_update(Request $data){
        /*$TypeEdit       = 1; //$data->get("TypeEdit");
        $ngId          = 0; //$data->get("IngId");
        $ngKey         = $data->get("IngKey");
        $UniEjeId       = 1230; //$data->get("UniEjeId");
        $FilId          = 0; //$data->get("FilId");
        $DocId          = $data->get("DocId");
        $DocSerie       = $data->get("DocSerie");
        $DocNro         = 0; //$data->get("DocNro");
        $DocFecha       = $data->get("DocFecha");
        $PersId         = $data->get("PersId");
        $EstudId        = $data->get("EstudId");
        $ngImpt        = $data->get("IngImpt");
        $ngObserv      = $data->get("IngObserv");
        $data           = $data->get("data");
        $SessKey        = NULL;
        $redDepenId    = 110003; //$data->get("CredDepenId");
        $MenuId         = 0; //$data->get("MenuId"); */

        //$_id = \DB::select('exec tre.[adeudos_update] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($TypeEdit,$IngId,$IngKey,1230,$FilId,$DocId,$DocSerie,$DocNro,$DocFecha,$PersId,$EstudId,$IngImpt,$IngObserv,($data),$SessKey,$redDepenId,$MenuId));
        //return $_id;
    }
}