<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Peru\Http\ContextClient;
use Peru\Sunat\{HtmlParser, Ruc, RucParser};
use Peru\Jne\{Dni, DniParser};

class grl_personasController extends Controller{
    public function grl_personas_searchAPI(Request $data){
        if ( $data->get("Len") == 11 ) {
            $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));
        } else {
            $cs = new Dni(new ContextClient(), new DniParser());
        }

        if ($cs){
            $_record = $cs->get($data->get("PersDocumento"));
            if (!$_record){
                $_record = array("dni"=>"", "ruc"=>"");
            }
        } else {
            $_record = array("dni"=>"", "ruc"=>"");
        }
        return response()->json($_record);
    }

    public function grl_personas_select(Request $data, $param = ""){
        if ( $param == "" ) {
            $PersId                = $data->get("iPersId");
            $TipoPersId            = $data->get("TipoPersId");
            $TipoIdentId           = $data->get("TipoIdentId");
            $PersDocumento         = $data->get("PersDocumento");
            $PersPaterno           = $data->get("PersPaterno");
            $PersMaterno           = $data->get("PersMaterno");
            $PersNombre            = $data->get("PersNombre");
            $PersNacimiento        = $data->get("PersNacimiento");
            $TipSexId              = $data->get("TipSexId");
            $TipEstaCivId          = $data->get("TipEstaCivId");
            $PersRazonSocialNombre = $data->get("PersRazonSocialNombre");
            $PersRazonSocialCorto  = $data->get("PersRazonSocialCorto");
            $PersApeNom            = $data->get("PersApeNom");
            $SessKey     = $data->get("SessKey");
            $MenuId      = $data->get("MenuId");
            $TypeRecord  = $data->get("TypeRecord"); 
            $TypeQuery   = $data->get("TypeQuery"); 
            $OrderBy     = $data->get("OrderBy");
            $RecordLimit = $data->get("RecordLimit");
            $RecordStart = $data->get("RecordStart");
        } else {
            $PersId                = isset($param["PersId"]) ? $param["PersId"] : "";
            $TipoPersId            = isset($param["TipPersId"]) ? $param["TipPersId"] : "";
            $TipoIdentId           = isset($param["TipoIdentId"]) ? $param["TipoIdentId"] : "";
            $PersDocumento         = isset($param["PersDocumento"]) ? $param["PersDocumento"] : "";
            $PersPaterno           = isset($param["PersPaterno"]) ? $param["PersPaterno"] : "";
            $PersMaterno           = isset($param["PersMaterno"]) ? $param["PersMaterno"] : "";
            $PersNombre            = isset($param["PersNombre"]) ? $param["PersNombre"] : "";
            $PersNacimiento        = isset($param["PersNacimiento"]) ? $param["PersNacimiento"] : "";
            $TipSexId              = isset($param["TipSexId"]) ? $param["TipSexId"] : "";
            $TipEstaCivId          = isset($param["TipExtaCivId"]) ? $param["TipExtaCivId"] : "";
            $PersRazonSocialNombre = isset($param["PersRazonSocialNombre"]) ? $param["PersRazonSocialNombre"] : "";
            $PersRazonSocialCorto  = isset($param["PersRazonSocialCorto"]) ? $param["PersRazonSocialCorto"] : "";
            $PersApeNom            = isset($param["PersApeNom"]) ? $param["PersApeNom"] : "";
            $SessKey     = isset($param["SessKey"]) ? $param["SessKey"] : "";
            $MenuId      = isset($param["MenuId"]) ? $param["MenuId"] : "";
            $TypeRecord  = isset($param["TypeRecord"]) ? $param["TypeRecord"] : "";
            $TypeQuery   = isset($param["TypeQuery"]) ? $param["TypeQuery"] : "";
            $OrderBy     = isset($param["OrderBy"]) ? $param["OrderBy"] : "";
            $RecordLimit = isset($param["RecordLimit"]) ? $param["RecordLimit"] : "";
            $RecordStart = isset($param["RecordStart"]) ? $param["RecordStart"] : "";
        }
        $_records = \DB::select('exec grl.[personas_sp_select] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($PersId,$TipoPersId,$TipoIdentId,$PersDocumento,$PersPaterno,$PersMaterno,$PersNombre,$PersNacimiento,$TipSexId,$TipEstaCivId,$PersRazonSocialNombre,$PersRazonSocialCorto,$PersApeNom,$SessKey,$MenuId,$TypeRecord,$TypeQuery,$OrderBy,$RecordLimit,$RecordStart));
        return response()->json($_records);
    }

    public function grl_personas_update(Request $data){
        $TypeEdit            = ($data->get("PersId")*1 > 0 ? 2 : 1);
        $PersId              = $data->get("PersId");
        $PersKey             = $data->get("PersKey");
        $TipoPersId          = $data->get("TipoPersId");
        $TipoIdentId         = $data->get("TipoIdentId");
        $PersDocumento       = $data->get("PersDocumento");
        $PersPaterno         = $data->get("PersPaterno");
        $PersMaterno         = $data->get("PersMaterno");
        $PersNombre          = $data->get("PersNombre");
        $TipSexCode          = $data->get("TipSexCode");
        $TipEstaCivId        = $data->get("TipEstaCivId");
        $PersFechaNac        = $data->get("PersFechaNac");
        $PaisId              = $data->get("PaisId");
        $DptoId              = $data->get("DptoId");
        $PrvnId              = $data->get("PrvnId");
        $DsttId              = $data->get("DsttId");
        $PersDomicilio       = $data->get("PersDomicilio");
        $ReniecFotografia    = $data->get("ReniecFotografia");
        $ReniecUbigeo        = $data->get("ReniecUbigeo");
        $ReniecDireccion     = $data->get("ReniecDireccion");
        $ReniecEsta_civi     = $data->get("ReniecEsta_civi");
        $ReniecRestricciones = $data->get("ReniecRestricciones");
        $SessKey = $data->get("SessKey");
        $MenuId  = $data->get("MenuId");

        $_id = \DB::select('exec grl.[personas_sp_update] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($TypeEdit,$PersId,$PersKey,$TipoPersId,$TipoIdentId,$PersDocumento,$PersPaterno,$PersMaterno,$PersNombre,$TipSexCode,$TipEstaCivId,$PersFechaNac,$PaisId,$DptoId,$PrvnId,$DsttId,$PersDomicilio,$ReniecFotografia,$ReniecUbigeo,$ReniecDireccion,$ReniecEsta_civi,$ReniecRestricciones,$SessKey,$MenuId));
        return $_id;
    }
}