<?php

namespace App\Http\Controllers\tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class DetraccionesMasivasController extends Controller
{
    public function selDetraccionesMasivas(Request $request)
    {
        $parameters = [
            $request->iEntId,
            $request->iYearId,
            $request->iMonthId,
            $request->iPageNumber,
            $request->iPageSize,
        ];
        $dataResult = DB::select('EXEC tre.Sp_SEL_detracciones_adquirientesXiEntIdXiYearIdXiMonthId ?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }
    
    public function savDetraccionesMasivas(Request $request)
    {
        $dataResult = [];
        if(!$request->iDetraccAdquirId)//si es nuevo registro
        {
            $parameters = [
                $request->iEntId,
                $request->iYearId,
                $request->iMonthId,
                $request->dDetraccAdquirEmision,
                $request->iEstadoDetraccId,
                $request->cDetraccAdquirObs,
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_detracciones_adquirientes ?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{

                $parameters = [
                $request->iDetraccAdquirId,
                $request->iMonthId,
                $request->dDetraccAdquirEmision,
                $request->iEstadoDetraccId,
                $request->cDetraccAdquirObs,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_detracciones_adquirientes ?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }

    public function savDetraccionesMasivasDetalles(Request $request)
    {
        $dataResult = [];
        if(!$request->iDetraccProveedId)//si es nuevo registro
        {
            $parameters = [
                $request->iDetraccAdquirId,
                $request->iIdPersona,
                $request->cDetraccProveedCtaCte,
                $request->iBienServSujDetraccId,
                $request->iTipoOperacSujDetraccId,
                $request->nDetraccProveedImporte,
                $request->dDetraccProveedEmision,
                $request->cDetraccProveedCheque,
                $request->iTipoDocId,
                $request->cDetraccProveedSerie,
                $request->cDetraccProveedNumero,
                $request->Ano_eje,
                $request->Expediente,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_detracciones_proveedores ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iDetraccProveedId,
                $request->iIdPersona,
                $request->cDetraccProveedCtaCte,
                $request->iBienServSujDetraccId,
                $request->iTipoOperacSujDetraccId,
                $request->nDetraccProveedImporte,
                $request->dDetraccProveedEmision,
                $request->cDetraccProveedCheque,
                $request->iTipoDocId,
                $request->cDetraccProveedSerie,
                $request->cDetraccProveedNumero,
                $request->Ano_eje,
                $request->Expediente,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_detracciones_proveedores ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }

    public function datDetraccionesMasivas($iDetraccAdquirId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_detracciones_adquirientesXiDetraccAdquirId ?',[$iDetraccAdquirId]);
        return response()->json($dataResult);
    }  
    public function datDetraccionesMasivasDetalles($iDetraccProveedId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_detracciones_proveedoresXiDetraccProveedId ?',[$iDetraccProveedId]);
        return response()->json($dataResult);
    }

    public function delDetraccionesMasivas(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_detracciones_adquirientes ?',[$request->iDetraccAdquirId]);
        return response()->json($dataResult);
    }      

    public function delDetraccionesMasivasDetalles(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_detracciones_proveedores ?',[$request->iDetraccProveedId]);
        return response()->json($dataResult);
    }   

    public function selDetraccionesMasivasDetalles(Request $request)
    {
        $parameters = [
            $request->iDetraccAdquirId,
            $request->iPageNumber,
            $request->iPageSize,
        ];
        $dataResult = DB::select('EXEC tre.Sp_SEL_detracciones_proveedoresXiDetraccAdquirId ?,?,?',$parameters);
        return response()->json($dataResult);
    }  

    public function selPeriodosDetracciones()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_years');
        return response()->json($dataResult);
    }  
    public function selMesesDetracciones()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_months');
        return response()->json($dataResult);
    }  
    public function selEstadoDetracciones()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_estados_detracciones');
        return response()->json($dataResult);
    }      
    public function selServSujDetracciones()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_bienes_servicios_sujetos_detracciones');
        return response()->json($dataResult);
    }   
    public function selOperSujDetracciones()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_operaciones_sujetos_detracciones');
        return response()->json($dataResult);
    }       
    public function selTipoDocDetracciones()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipo_documentos_detracciones');
        return response()->json($dataResult);
    }   
    
    public function selPeriodoEjecucionSIAF($cSec_ejec)
    {
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Ano_ejeXcSec_ejec ?',[$cSec_ejec]);
        return response()->json($dataResult);
    }  
    
    public function selExpedientesFasesDetraccion(Request $request)
    {
        $parameters = [
            $request->cSec_ejec,
            $request->cAno_eje,
            str_pad($request->cExpediente, 10, "0", STR_PAD_LEFT),
            $request->iYear_Detraccion,
            $request->iMonth_Detraccion,
            ];
        $dataResult = DB::select('EXEC tre.Sp_SEL_Secuencia_Documento_Fase_DetraccionesXcSec_ejecXcAno_ejeXcExpediente ?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }  
    public function savExpedientesFasesDetraccion(Request $request)
    {
        $parameters = [
            $request->iDetraccAdquirId,
            $request->cSec_ejec,
            $request->cAno_eje,
            $request->cExpediente,
            $request->cSecuencia,
            $request->cSecuencia_anterior,
            $request->cCorrelativo,
            $request->cCiclo,
            $request->cFase,

            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
            ];
        $dataResult = DB::select('EXEC tre.Sp_INS_detracciones_proveedoresXiDetraccAdquirIdXcSec_ejecXcAno_ejeXcExpediente ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }  
    
    public function datEntidad($iEntId)
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_entidadesXiEntId ?',[$iEntId]);
        return response()->json($dataResult);
    } 

    public function generateTXTSunatDepositoMasivo(Request $request)
    {
        $parameters = [
            $request->iDetraccAdquirId,
            $request->iOpcionDeposito,
        ];
        $dataResult = DB::select('EXEC tre.Sp_SEL_TXT_SUNAT_DepositoMasivoDetraccion_PorInternet_BancoNacionXiDetraccAdquirIdXiOpcionDeposito ?,?',$parameters);
        // return response()->json($dataResult);
        $res = $this->generateTXTAction($dataResult);
        if($res != 0)
        {
            if($res['status'] ==1){
                return response()->json($res);
                // return Storage::download($res['ruta']);
            }
            else{
                echo 'Hubo un error al generar el Archivo TXT';
            }
        }else{
            echo 'Hubo un error al generar el Archivo TXT';
        }

    } 


    public function generateTXTAction($dataResult){        
        if (!empty($dataResult)) {
            $nombreArchivo = $dataResult[0]->cEstructuraNombreArchivo;
            $extension = $dataResult[0]->cEstructuraExtensionArchivo;
            $archivo =  storage_path('app/public/DETRACCIONES_MASIVAS/TXT/') . $nombreArchivo . "." . $extension;
            /** contenido */
            $lista = $dataResult[0]->cEstructuraCampoArchivo;
            $det = fopen( $archivo , "w+");
            foreach ($dataResult as $list_det_value) {
                fwrite($det, "$list_det_value->cEstructuraCampoArchivo\r\n");
            }
            fclose($det);
            $exists = file_exists( $archivo );
            if($exists == true){
                //return response()->download($archivo);
                $respuesta = array(
                    'status'=> 1,
                    'ruta'=> "DETRACCIONES_MASIVAS/TXT/".$nombreArchivo.".".$extension,
                    'nombreArchivo'=> $nombreArchivo.".".$extension,
                );
                return $respuesta;
            }else{
                return 0;
            }
        }
        return 0;
    }

    public function dowTXTSunatDepositoMasivo(Request $request){
            return Storage::download($request->ruta);
    }
}
