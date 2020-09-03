<?php

namespace App\Http\Controllers\Tesoreria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComprobantesPagoController extends Controller
{

    public function selComprobantesPago(Request $request)
    {
        $parameters = [];
        switch($request->iOpcionFiltro){
            case '0':
                $parameters = [$request->iEntId,$request->iTipoComprobantePagoId,'','','','','','','','',1,$request->iPageNumber,$request->iPageSize];
            break;
            case '1':
                $parameters = [$request->iEntId,$request->iTipoComprobantePagoId,date_format(date_create($request->cFecha),'Ymd'),'','','','','','','',0,$request->iPageNumber,$request->iPageSize];
            break;
            case '2':
                $parameters = [$request->iEntId,$request->iTipoComprobantePagoId,'',$request->iYear,$request->iMonth,'','','','','',0,$request->iPageNumber,$request->iPageSize];
            break;
            case '3':
                $parameters = [$request->iEntId,$request->iTipoComprobantePagoId,'','','',date_format(date_create($request->cFechaDesde),'Ymd'),date_format(date_create($request->cFechaHasta),'Ymd'),'','','',0,$request->iPageNumber,$request->iPageSize];
            break;
            case '4':
                $parameters = [$request->iEntId,$request->iTipoComprobantePagoId,'','','','','',$request->iYearCriterio,$request->iCritId,$request->cCritVariable,0,$request->iPageNumber,$request->iPageSize];
            break;
        }
        $dataResult = DB::select('EXEC tre.Sp_SEL_comprobantes_pagoXiEntIdXiTipoComprobantePagoIdXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }
    public function savComprobantesPagoMultiple(Request $request){
        $dataResult = [];
        $parameters = [
            $request->iEntId,
            $request->iCredEmisorId,
            $request->iTipoChequeraId,
            $request->Ano_eje,
            date_format(date_create($request->dtTramFechaDocumento),'Ymd h:m:s'),
            $request->iCantidad_Comprobantes_Pago,
            $request->cTramContenido,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'            
        ];
        $dataResult = DB::select('EXEC tre.Sp_Generar_Comprobante_Pago_Multiple ?,?,?,?,?,?,?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }

    public function savComprobantesPago(Request $request)
    {
        $dataResult = [];
        if(!$request->iTramId)
        {
            $parameters = [
                $request->iEntId,
                $request->iCredEmisorId,
                $request->iTipoChequeraId,
                $request->iIdPersona,
                Carbon::parse($request->dtTramFechaDocumento)->format('Y-d-m H:i:s.v'),
                //date_format(date_create($request->dtTramFechaDocumento),'Y-d-m H:i:s.v'),
                isset($request->cNumeroComprobantePago) ? $request->cNumeroComprobantePago : "",
                $request->cTramAsunto,
                $request->cTramContenido,
                $request->cTramObservaciones,
                $request->nTramMonto,
                $request->cTramNumeroCheque,
                $request->Sec_ejec,
                $request->Ano_eje,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
                $request->Tipo_operacion,
                $request->Origen,
                $request->Fuente_financ,
                $request->Tipo_recurso,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_Comprobante_Pago ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iTramId,
                $request->iIdPersona,//
                Carbon::parse($request->dtTramFechaDocumento)->format('Y-d-m H:i:s.v'),
                $request->cTramAsunto,
                $request->cTramContenido,
                $request->cTramObservaciones,
                $request->nTramMonto,
                $request->cTramNumeroCheque,

                $request->Sec_ejec,
                $request->Ano_eje,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
                $request->Tipo_operacion,
                $request->Origen,
                $request->Fuente_financ,
                $request->Tipo_recurso,                

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_Comprobante_Pago ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }
    public function datComprobantesPago($iTramId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_comprobantes_pagoXiTramId ?',[$iTramId]);
        return response()->json($dataResult);
    }  

    public function importDatosSIAFComprobantesPago(Request $request)
    {
        $parameters = [
            $request->Sec_ejec,
            $request->Ano_eje,
            str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
        ];
        $dataResult = DB::select('EXEC tre.Sp_SEL_Expediente_Generar_Comprobante_PagoXSec_ejecXAno_ejeXExpediente ?,?,?',$parameters);
        return response()->json($dataResult);
    }      
    public function delComprobantesPago(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_Comprobante_Pago ?',[$request->iTramId]);
        return response()->json($dataResult);
    }


    public function selTiposComprobantes()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_comprobantes_pago');
        return response()->json($dataResult);
    }
    public function selPeriodosCP()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_years');
        return response()->json($dataResult);
    }

    public function selMesesCP()
    {
        $dataResult = DB::select('EXEC grl.Sp_SEL_months');
        return response()->json($dataResult);
    }

    public function selCriterioCP()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_Criterio_Busqueda_ComprobantePago');
        return response()->json($dataResult);
    }

    public function selTiposChequeras()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_chequeras');
        return response()->json($dataResult);
    }

    public function savTiposChequeras(Request $request)
    {
        $dataResult = [];
        if(!$request->iTipoChequeraId)//si es nuevo registro
        {
            $parameters = [
                $request->cTipoChequeraNombre,
                $request->cTipoChequeraSigla,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_tipos_chequeras ?,?,?,?,?,?',$parameters);
        }
        else{
                $parameters = [
                $request->iTipoChequeraId,
                $request->cTipoChequeraNombre,
                $request->cTipoChequeraSigla,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_tipos_chequeras ?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }
    public function datTiposChequeras($iTipoChequeraId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_chequerasXiTipoChequeraId ?',[$iTipoChequeraId]);
        return response()->json($dataResult);
    }  
    public function delTiposChequeras(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_tipos_chequeras ?',[$request->iTipoChequeraId]);
        return response()->json($dataResult);
    }

}
