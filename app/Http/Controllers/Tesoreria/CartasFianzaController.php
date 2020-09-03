<?php

namespace App\Http\Controllers\Tesoreria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class CartasFianzaController extends Controller
{

    /**carta fianza */
    public function selCartasFianza(Request $request)
    {
        switch($request->iOpcionFiltro){
            case "0":
                $parameters = [$request->iEntId,'','','','','','','',$request->iPageNumber,$request->iPageSize];                
            break;
            case "1":
                $parameters = [$request->iEntId,date_format(date_create($request->cFecha),'Ymd'),'','','','','','',$request->iPageNumber,$request->iPageSize];
            break;
            case "2":
                $parameters = [$request->iEntId,'',$request->iYear,$request->iMonth,'','','','',$request->iPageNumber,$request->iPageSize];
            break;
            case "3":
                $parameters = [$request->iEntId,'','','',date_format(date_create($request->cFechaDesde),'Ymd'),date_format(date_create($request->cFechaHasta),'Ymd'),'','',$request->iPageNumber,$request->iPageSize];
            break;
            case "4":
                $parameters = [$request->iEntId,'','','','','',$request->iCritId,$request->cCritVariable,$request->iPageNumber,$request->iPageSize];
            break;
            case "5":
                $parameters = [$request->iEntId,$request->iDias_enquevenceran];
            break;
            default:
                $parameters = [$request->iEntId,'','','','','','','',$request->iPageNumber,$request->iPageSize];
            break;
        }
        if($request->iOpcionFiltro == "5")
        {
            $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzas_que_venceranXiEntIdXiDias_enquevenceran ?,?',$parameters);
        }else{
            $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzasXiEntIdXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        return response()->json($dataResult);
    }

 
    public function datCartasFianza($iCartaFianzaId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzasXiCartaFianzaId ?', [ $iCartaFianzaId ]);
        return response()->json($dataResult);
    }  
    
    public function savCartasFianza(Request $request)
    {
        if(!$request->iCartaFianzaId)//si es nuevo registro
        {
            if($request->hasFile('cCartaFianzaAdjuntarArchivo'))
            {
                $cCartaFianzaAdjuntarArchivo = $request->cCartaFianzaAdjuntarArchivo->store('CARTAS_FIANZA');
            }else{
                $cCartaFianzaAdjuntarArchivo='';
            }
        }else{//si se esta editando un registro
            if($request->cAdjunto && $request->hasFile('cCartaFianzaAdjuntarArchivo'))
            {
                Storage::delete($request->cAdjunto);
                $cCartaFianzaAdjuntarArchivo = $request->cCartaFianzaAdjuntarArchivo->store('CARTAS_FIANZA');
            }else if($request->cAdjunto=='' && $request->hasFile('cCartaFianzaAdjuntarArchivo')){
                $cCartaFianzaAdjuntarArchivo=$request->cCartaFianzaAdjuntarArchivo->store('CARTAS_FIANZA');
            }    
            else if($request->cAdjunto && !$request->hasFile('cCartaFianzaAdjuntarArchivo'))
            {
                $cCartaFianzaAdjuntarArchivo = $request->cAdjunto;
            }
            else
            {
                $cCartaFianzaAdjuntarArchivo='';
            }
        }
        
        if(!$request->iCartaFianzaId)
        {
            
            $parameters = [
                $request->iEntId,
                $request->dCartaFianzaCustodia,
                $request->cCartaFianzaNumero,
                $request->iIdPersona,
                $request->bCartaFianzaEsConsorcio,
                $request->cCartaFianzaNombreConsorcio,
                $request->iModalidadContratoId,
                $request->cCartaFianzaNumeroModalidadContrato,
                $request->iTipoCartaFianzaId,
                $request->iClaseCartaFianzaId,
                $request->iEstadoCartaFianzaId,
                $request->dCartaFianzaFechaEstado,
                $request->iBancoId,
                $request->cCartaFianzaObs,
                //$request->cCartaFianzaAdjuntarArchivo,
                $cCartaFianzaAdjuntarArchivo,
                // $request->dCartaFianzaDevueltoFecha,
                $request->iPersDevueltoId,//new
                $request->cCartaFianzaDevueltoObs,
                // $request->dCartaFianzaEjecutadoFecha,
                $request->cCartaFianzaEjecutadoMonto,//new
                $request->cCartaFianzaEjecutadoCausal,
                $request->cCartaFianzaEjecutadoObs,
                $request->Sec_ejec,
                $request->Id_contrato,
                $request->Id_proceso,
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'



            ]; 
            //print_r($parameters);
            $dataResult = DB::select('EXEC tre.Sp_INS_cartas_fianzas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }else{
            $parameters = [
                $request->iCartaFianzaId,
                $request->dCartaFianzaCustodia,
                $request->cCartaFianzaNumero,
                $request->iIdPersona,
                $request->bCartaFianzaEsConsorcio,
                $request->cCartaFianzaNombreConsorcio == "null" ? NULL : $request->cCartaFianzaNombreConsorcio,
                $request->iModalidadContratoId,
                $request->cCartaFianzaNumeroModalidadContrato,
                $request->iTipoCartaFianzaId,
                $request->iClaseCartaFianzaId,
                $request->iEstadoCartaFianzaId,
                $request->dCartaFianzaFechaEstado,
                $request->iBancoId,
                $request->cCartaFianzaObs == "null" ? NULL : $request->cCartaFianzaObs,
                //$request->cCartaFianzaAdjuntarArchivo,
                $cCartaFianzaAdjuntarArchivo,
                // $request->dCartaFianzaDevueltoFecha,
                $request->iPersDevueltoId,//new
                $request->cCartaFianzaDevueltoObs,
                // $request->dCartaFianzaEjecutadoFecha,
                $request->cCartaFianzaEjecutadoMonto,//new
                $request->cCartaFianzaEjecutadoCausal,
                $request->cCartaFianzaEjecutadoObs,

                $request->Sec_ejec == "null" ? NULL : $request->Sec_ejec,
                $request->Id_contrato == "null" ? NULL : $request->Id_contrato,
                $request->Id_proceso == "null" ? NULL : $request->Id_proceso,

                auth()->user()->iCredId,
                @gethostbyaddr($_SERVER["REMOTE_ADDR"]),
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            //print_r($parameters);
            $dataResult = DB::select('EXEC tre.Sp_UPD_cartas_fianzas ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }
    }
    public function delCartasFianza(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_cartas_fianzas ?',[$request->iCartaFianzaId]);
        return response()->json($dataResult);
    }  


    public function dowCartasFianza(Request $request)
    {
        //echo $request->file;
        return Storage::download($request->file);
    }


    public function selPeriodoConvocatoriaPS($cSec_ejec){
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Ano_convocatoria_Contrato_PsXcSec_ejec ?', [ $cSec_ejec ]);
        return response()->json($dataResult);        
    }
    public function selCriterioBusquedaContratoPS(){
        $dataResult = DB::select('EXEC Siaf.Sp_SEL_Criterio_Busqueda_Contrato_Ps');
        return response()->json($dataResult);   
    }
    public function selContratoPS(Request $request){
        switch($request->iOpcionFiltro){
            case "0":
                $parameters = [$request->cSec_ejec,$request->cAno_convocatoria,'','',$request->iPageNumber,$request->iPageSize];
            break;
            case "1":
                $parameters = [$request->cSec_ejec,$request->cAno_convocatoria,$request->iCritId,isset($request->cCritVariable) ? $request->cCritVariable : "",$request->iPageNumber,$request->iPageSize];
            break;
            default:
                $parameters = [$request->cSec_ejec,'','','',$request->iPageNumber,$request->iPageSize];
            break;
        }
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_Contrato_PsXcSec_ejecXcAno_convocatoriaXcConsultaVariablesCampos ?,?,?,?,?,?', $parameters);
        return response()->json($dataResult);   
    }

    public function selPersonaProveedor(Request $request){

        $parameters = [$request->cTipo_id,isset($request->cCampoVariable) ? $request->cCampoVariable : "",$request->iPageNumber,$request->iPageSize];
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_PersonaXcTipo_idXcCampoVariable ?,?,?,?', $parameters);
        return response()->json($dataResult);   
    }

    

    /**fin carta fianza */

    /**Detalle carta fianza */

    public function selDetalleCartasFianza($iCartaFianzaId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzas_detallesXiCartaFianzaId ?',[$iCartaFianzaId]);
        return response()->json($dataResult);
    }   
    public function datDetalleCartasFianza($iCartaFianzaDetId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzas_detallesXiCartaFianzaDetId ?', [ $iCartaFianzaDetId ]);
        return response()->json($dataResult);
    }  
    public function delDetalleCartasFianza(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_cartas_fianzas_detalles ?',[$request->iCartaFianzaDetId]);
        return response()->json($dataResult);
    }  
    public function savDetalleCartasFianza(Request $request)
    {

        if(!$request->iCartaFianzaDetId)//si es nuevo registro
        {
            if($request->hasFile('cCartaFianzaDetAdjuntarArchivo'))
            {
                $cCartaFianzaDetAdjuntarArchivo = $request->cCartaFianzaDetAdjuntarArchivo->store('CARTAS_FIANZA/DETALLES');
            }else{
                $cCartaFianzaDetAdjuntarArchivo='';
            }
        }else{//si se esta editando un registro
            if($request->cAdjunto && $request->hasFile('cCartaFianzaDetAdjuntarArchivo'))
            {
                Storage::delete($request->cAdjunto);
                $cCartaFianzaDetAdjuntarArchivo = $request->cCartaFianzaDetAdjuntarArchivo->store('CARTAS_FIANZA/DETALLES');
            }else if($request->cAdjunto=='' && $request->hasFile('cCartaFianzaDetAdjuntarArchivo')){
                $cCartaFianzaDetAdjuntarArchivo=$request->cCartaFianzaDetAdjuntarArchivo->store('CARTAS_FIANZA/DETALLES');
            }    
            else if($request->cAdjunto && !$request->hasFile('cCartaFianzaDetAdjuntarArchivo'))
            {
                $cCartaFianzaDetAdjuntarArchivo = $request->cAdjunto;
            }
            else
            {
                $cCartaFianzaDetAdjuntarArchivo='';
            }
        }

        if(!$request->iCartaFianzaDetId)
        {
            $parameters = [
                $request->iCartaFianzaId,
                $request->cCartaFianzaNumero,
                $request->iFaseCartaFianzaId,
                $request->dCartaFianzaDetEmision,
                $request->dCartaFianzaDetVencimiento,
                $request->nCartaFianzaDetMonto,
                $request->cCartaFianzaDetObs,
                $cCartaFianzaDetAdjuntarArchivo,
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            $dataResult = DB::select('EXEC tre.Sp_INS_cartas_fianzas_detalles ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }else{
            $parameters = [
                $request->iCartaFianzaDetId,
                $request->cCartaFianzaNumero,
                $request->iFaseCartaFianzaId,
                $request->dCartaFianzaDetEmision,
                $request->dCartaFianzaDetVencimiento,
                $request->nCartaFianzaDetMonto,
                $request->cCartaFianzaDetObs,
                $cCartaFianzaDetAdjuntarArchivo,
                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ]; 
            $dataResult = DB::select('EXEC tre.Sp_UPD_cartas_fianzas_detalles ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
            return response()->json($dataResult);
        }
    }


    /**fin detalle carta fianza */
    public function selPeriodos($iEntId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_fianzas_iCartaFianzaCustodiaYearXiEntId ?',[ $iEntId ]);
        return response()->json($dataResult);
    }
    public function selMeses($iEntId,$iCartaFianzaCustodiaYear){
        $dataResult = DB::select('EXEC tre.Sp_SEL_cartas_iCartaFianzaCustodiaMonthXiEntIdXiCartaFianzaCustodiaYear ?,?',[$iEntId,$iCartaFianzaCustodiaYear]);
        return response()->json($dataResult);
    }
    public function selCriterioBusqueda(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_Criterio_Busqueda_cartas_fianzas');
        return response()->json($dataResult);
    }

    public function selTipos(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_cartas_fianzas');
        return response()->json($dataResult);
    }
    public function selTiposCartaFianzaId($iTipoCartaFianzaId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_cartas_fianzasXiTipoCartaFianzaId ?',[$iTipoCartaFianzaId] );
        return response()->json($dataResult);
    }
    public function selClases(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_clases_cartas_fianzas');
        return response()->json($dataResult);
    }
    public function selClasesCartaFianzaId($iClaseCartaFianzaId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_clases_cartas_fianzasXiClaseCartaFianzaId',[$iClaseCartaFianzaId]);
        return response()->json($dataResult);
    }
    public function selClasesTipoCartaFianzaId($iTipoCartaFianzaId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_clases_cartas_fianzasXiTipoCartaFianzaId',[$iTipoCartaFianzaId]);
        return response()->json($dataResult);
    }
    public function selEstados(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_estados_cartas_fianzas');
        return response()->json($dataResult);
    }
    public function selEstadosCartaFianzaId($iEstadoCartaFianzaId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_estados_cartas_fianzasXiEstadoCartaFianzaId',[$iEstadoCartaFianzaId]);
        return response()->json($dataResult);
    }
    public function selFases(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_fases_cartas_fianzas');
        return response()->json($dataResult);
    }
    public function selFasesCartaFianzaId($iFaseCartaFianzaId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_fases_cartas_fianzasXiFaseCartaFianzaId',[$iFaseCartaFianzaId]);
        return response()->json($dataResult);
    }
    public function updFechaVencimientoCartaFianza(request $request){
        $parameters = [
            $request->iEntId,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];         
        $dataResult = DB::select('EXEC tre.Sp_UPD_cartas_fianzas_iEstadoCartaFianzaIdXFecha_Servidor ?,?,?,?,?',$parameters);
        return response()->json($dataResult);

    }
    public function selExpedienteCartasFianza(Request $request){
        $parameters = [
            $request->cSec_ejec,
            $request->cId_proceso,
            $request->cId_contrato,
        ];        
        $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_ExpedienteXcSec_ejecXcId_procesoXcId_contrato ?,?,?',$parameters);
        return response()->json($dataResult);
    }


}
