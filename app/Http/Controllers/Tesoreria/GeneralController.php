<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{

    public function selFiliales()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_filialesXiEntId 1');
        return response()->json($dataResult);
    }

    public function selTipoIdentificacion()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_tipo_Identificaciones');
        return response()->json($dataResult);
    }

    public function selPeriodos()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_years');
        return response()->json($dataResult);
    }

    public function selMeses()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_months');
        return response()->json($dataResult);
    }

    public function selTipoIdentificadores()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_tipo_Identificaciones');
        return response()->json($dataResult);
    }
    public function selTipoMoneda()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_tipos_monedas');
        return response()->json($dataResult);
    }

    public function selPeriodoEjecucionSIAF($cSec_ejec)
    {
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Ano_ejeXcSec_ejec ?',[$cSec_ejec]);
        return response()->json($dataResult);
    }  

    public function selSecuenciaDocumento(Request $request){
        $dataResult = "";
        switch($request->iOpcionFiltro){
            case '1':
                $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente ];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Secuencia_Documento_FaseXcCodigoExpediente ?,?,?', $parameters);
            break;
            case '2':
                $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente,$request->cFase  ];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Secuencia_Documento_FaseXcCodigoFase ?,?,?,?', $parameters);
            break;
        }
        return response()->json($dataResult);        
    }
    public function selFaseSIAF(Request $request){
        $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente ];
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_FaseXcCodigoExpediente ?,?,?',$parameters);
        return response()->json($dataResult);        
    }  
    public function selSumCiclo(Request $request){
        $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente ];
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SUM_ExpedienteCicloFaseXcCodigoExpediente ?,?,?',$parameters);
        return response()->json($dataResult);        
    }     
    public function selExpedienteClasificador(Request $request){
        $dataResult="";
        switch($request->cCiclo)
        {
            case "G":
                $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente,$request->cCiclo,$request->cFase,$request->cSecuencia,$request->cCorrelativo];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Expediente_ClasificadorXcCodigoCorrelativo ?,?,?,?,?,?,?',$parameters);
            break;
            case "I":
                $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente,$request->cCiclo,$request->cFase,$request->cSecuencia,$request->cCorrelativo];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Expediente_Ingreso_ClasificadorXcCodigoCorrelativo ?,?,?,?,?,?,?',$parameters);
            break;
            default:
                $dataResult = "";
            break;
        }
        return response()->json($dataResult);
    }
    public function selExpedienteClasificadorDatos(Request $request){
        $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente,$request->cCiclo,$request->cFase,$request->cSecuencia,$request->cCorrelativo];
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Expediente_Ingreso_ClasificadorXcCodigoCorrelativo ?,?,?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }

    public function selExpedienteSecuenciaFuncional(Request $request){
        $parameters = [$request->cSec_ejec,$request->cAno_eje,$request->cExpediente,$request->cCiclo,$request->cFase,$request->cSecuencia,$request->cCorrelativo,$request->Categ_gasto,$request->Grupo_gasto,$request->Modalidad_gasto,$request->Elemento_gasto,$request->Id_clasificador];
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Expediente_Sec_FuncXcCodigoClasificador ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }


    public function selTiposPagos()
    {
        $dataResult = DB::select('EXEC rhh.Sp_SEL_tipos_pagos');
        return response()->json($dataResult);
    }

    public function selPersonaEspecificasGI(Request $request){
        $parameters = [$request->cTipTransCode,isset($request->cCampoBusqueda) ? $request->cCampoBusqueda : "",$request->iPageNumber,$request->iPageSize];
        $dataResult = DB::select('EXEC tre.Sp_SEL_Especificas_Ingreso_GastoXcTipTransCodeXcCampoBusqueda ?,?,?,?', $parameters);
        return response()->json($dataResult);   
    }

    public function selPersonaProveedorGeneral(Request $request){
        $parameters = [$request->cTipo_id,isset($request->cCampoVariable) ? $request->cCampoVariable : "",$request->iPageNumber,$request->iPageSize];
        $dataResult = DB::select('EXEC grl.Sp_SEL_personasXiTipoPersIdXcDocumento_cNombre ?,?,?,?', $parameters);
        return response()->json($dataResult);   
    }

    public function savPersonaProveedor(Request $request)
    {
        $dataResult = [];
        if(!$request->iPersId)
        {
            $parameters = [
                $request->iTipoPersId,
                $request->iTipoIdentId,
                $request->cPersDocumento,
                $request->cPersPaterno,
                $request->cPersMaterno,
                $request->cPersNombre,
                $request->cPersSexo,
                $request->dPersNacimiento,
                $request->cPersRazonSocialNombre,
                $request->cPersRazonSocialCorto,
                $request->cPersRazonSocialSigla,
                $request->cPersRepresentateLegal,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC grl.Sp_INS_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iPersId,
                $request->iTipoIdentId,
                $request->cPersDocumento,
                $request->cPersPaterno,
                $request->cPersMaterno,
                $request->cPersNombre,
                $request->cPersSexo,
                $request->dPersNacimiento,
                $request->cPersRazonSocialNombre,
                $request->cPersRazonSocialCorto,
                $request->cPersRazonSocialSigla,
                $request->cPersRepresentateLegal,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC grl.Sp_UPD_personas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }
    public function datPersonaProveedor($iPersId)
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_personasXiPersId ?',[$iPersId]);
        return response()->json($dataResult);
    }  
    public function delPersonaProveedor(Request $request)
    {
        $dataResult = DB::select('EXEC grl.Sp_DEL_personas ?',[$request->iPersId]);
        return response()->json($dataResult);
    }

    public static function consultar(Request $request, $persona_id=null, $local = false)
    {
        $servActivos = ['reniec', 'seguro', 'sms', 'sunat', 'osce', 'sunedu'];

        if (in_array($request->tipo, $servActivos)){
            $persona_id = ($persona_id)?'/'.$persona_id:'';
            $urlPide = 'http://200.48.160.218:8081/api/pide/' . $request->tipo . $persona_id;
            $ch = curl_init($urlPide);
            $payload = json_encode($request->toArray());
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);

            if(curl_errno($ch)){
                $jsonResponse = [
                    'error' => true,
                    'msg' => curl_error($ch),
                    'timeout' => true,
                ];

                curl_close($ch);

                if ($local) {
                    return $jsonResponse;
                }
                else {
                    return response()->json($jsonResponse);
                }
            }

            curl_close($ch);
            $data = json_decode($result);

            if ($data == null){
                $jsonResponse = [
                    'error' => true,
                    'msg' => 'Error desconocido',
                    'data' => $data
                ];
            }
            else {
                if (isset($data->error) && ($data->error) && isset($data->msg) ){
                    $jsonResponse = [
                        'error' => $data->error,
                        'msg' => $data->msg,
                        'data' => $data->data
                    ];
                }
                else {
                    if ($request->tipo == 'reniec') {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => '',
                            'data' => $data->data
                        ];
                    }
                    else {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => '',
                            'data' => $data
                        ];
                    }
                }
            }
        }
        else{
            $jsonResponse = [
                'error' => true,
                'msg' => 'El servicio no está activo o no existe',
                'data' => null
            ];
        }

        if ($local) {
            return $jsonResponse;
        }
        else {
            return response()->json($jsonResponse);
        }
    }

    public function checkIfHasPIDEReniec($dni)
    {
        $hasPide = DB::table('grl.reniec')->where('cReniecDni', $dni)->exists();

        return response()->json([ 'hasPide' => $hasPide ]);
    }


}
