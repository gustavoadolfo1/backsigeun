<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;


//use App\Imports\UsersImport;
use App\Imports\Tesoreria\CuentasDetraccionImport;
//use App\Users2;
class TablasMaestrasController extends Controller
{

    public function importCuentasDetraccion(Request $request) 
    {

        $ruta  = $request->archivo->store('TABLAS_GENERALES/CUENTAS_DETRACCION');
        //$rut = 'http://localhost:8089/storage/'.$ruta;
        $rut =  storage_path('app/public/'.$ruta);
        //echo $rut;
        Excel::import(new CuentasDetraccionImport, $rut);
    }


    public function selModalidadesContratos()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_modalidades_contratos');
        return response()->json($dataResult);
    }
    public function datModalidadesContratos($iModalidadContratoId)
    {@
        $dataResult = DB::select('EXEC tre.Sp_SEL_modalidades_contratosXiModalidadContratoId ?', [ $iModalidadContratoId ]);
        return response()->json($dataResult);
    }
    public function savModalidadesContratos(Request $request)
    {
        if(!$request->iModalidadContratoId)
        {
            $parameters = [
                $request->cModalidadContratoNombre,
    
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            $dataResult = DB::select('EXEC tre.Sp_INS_modalidades_contratos ?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }else{
            $parameters = [
                $request->iModalidadContratoId,
                $request->cModalidadContratoNombre,
    
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            $dataResult = DB::select('EXEC tre.Sp_UPD_modalidades_contratos ?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }
    }
    public function delModalidadesContratos(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_modalidades_contratos ?',[$request->iModalidadContratoId]);
        return response()->json($dataResult);
    }    
    public function selBancos()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_bancos');
        return response()->json($dataResult);
    }
    public function datBancos($iBancoId)
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_bancosXiBancoId ?', [ $iBancoId ]);
        return response()->json($dataResult);
    }
    public function savBancos(Request $request)
    {
        if(!$request->iBancoId)
        {
            $parameters = [
                $request->cBancoNombre,
                $request->cBancoCodigoSunat,
                $request->cBancoCodigoAirshp,
    
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            $dataResult = DB::select('EXEC grl.Sp_INS_Bancos ?,?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }else{
            $parameters = [
                $request->iBancoId,
                $request->cBancoNombre,
                $request->cBancoCodigoSunat,
                $request->cBancoCodigoAirshp,
    
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            $dataResult = DB::select('EXEC grl.Sp_UPD_Bancos ?,?,?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }
    }
    public function delBancos(Request $request)
    {
        $dataResult = DB::select('EXEC grl.Sp_DEL_Bancos ?',[$request->iBancoId]);
        return response()->json($dataResult);
    }  


    public function selCuentasDetraccion(Request $request)
    {
        $dataResult = array();
        switch($request->iOpcionFiltro)
        {
            case '1'://todo
                $parameters = [
                    $request->cTipo_id,
                    $request->iPageNumber,
                    $request->iPageSize
                ];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_PersonaXcTipo_id ?,?,?',$parameters);
                return response()->json($dataResult);                
            break;
            case '2'://solo cuentas detraccion
                $parameters = [
                    $request->cTipo_id,
                    $request->iPageNumber,
                    $request->iPageSize
                ];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Persona_CtaCteDetraccionXcTipo_id ?,?,?',$parameters);
                return response()->json($dataResult);                
            break;
            case '3'://solo cci
                $parameters = [
                    $request->cTipo_id,
                    $request->iPageNumber,
                    $request->iPageSize
                ];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Persona_CCIXcTipo_id ?,?,?',$parameters);
                return response()->json($dataResult);                 
            break;
            case '4':// busqueda por ruc o nombre
                $parameters = [
                    $request->cTipo_id,
                    $request->cCampoVariable,
                    $request->iPageNumber,
                    $request->iPageSize
                ];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_PersonaXcTipo_idXcCampoVariable ?,?,?,?',$parameters);
                
                
            break;
            default:
                $dataResult = array();
            break;
        }
        return response()->json($dataResult);                 
    }  

    public function datCuentasDetraccion($iIdPersona)
    {
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_PersonaXiIdPersona ?', [ $iIdPersona ]);
        return response()->json($dataResult);
    } 
    
    public function savCuentasDetraccion(Request $request){
        $dataResult = "";
        if(!$request->iIdPersona)
        {
            $parameters = [
                $request->cTipo_id,
                $request->Ruc,
                $request->Nombre,
                $request->Direccion,
                $request->cCtaCteDetraccion
            ]; 
            $dataResult = DB::select('EXEC Siaf.Sp_Siaf_INS_Persona ?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }else{
            $parameters = [
                $request->iIdPersona,
                $request->cTipo_id,
                $request->Ruc,
                $request->Nombre,
                $request->Direccion,
                $request->cCtaCteDetraccion
            ]; 
            $dataResult = DB::select('EXEC Siaf.Sp_Siaf_UPD_Persona ?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }
    }
    public function delCuentasDetraccion(Request $request)
    {
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_DEL_PersonaXiIdPersona ?',[$request->iIdPersona]);
        return response()->json($dataResult);
    }  



}
