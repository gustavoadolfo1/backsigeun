<?php

namespace App\Http\Controllers\Tesoreria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecibosSaldosController extends Controller
{
    public function selTiposRecibos(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_recibos');
        return response()->json($dataResult);
    }

    public function selTiposRecibosRS(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_recibos');
        return response()->json($dataResult);
    }

    public function selCriterioRS(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_Criterio_Busqueda_Recibo_Saldos');
        return response()->json($dataResult);
    }

    public function selConceptoReciboRS(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_conceptos_recibosXiTipoRecId 2');
        return response()->json($dataResult);
    }    

    public function selRecibosSaldos(Request $request)
    {
        $parameters = [];
        switch($request->iOpcionFiltro){
            case '0':
                $parameters = [$request->iEntId,$request->iTipoRecId,'','','','','','','','',1,$request->iPageNumber,$request->iPageSize];
            break;
            case '1':
                $parameters = [$request->iEntId,$request->iTipoRecId,date_format(date_create($request->cFecha),'Ymd'),'','','','','','','',0,$request->iPageNumber,$request->iPageSize];
            break;
            case '2':
                $parameters = [$request->iEntId,$request->iTipoRecId,'',$request->iYear,$request->iMonth,'','','','','',0,$request->iPageNumber,$request->iPageSize];
            break;
            case '3':
                $parameters = [$request->iEntId,$request->iTipoRecId,'','','',date_format(date_create($request->cFechaDesde),'Ymd'),date_format(date_create($request->cFechaHasta),'Ymd'),'','','',0,$request->iPageNumber,$request->iPageSize];
            break;
            case '4':
                $parameters = [$request->iEntId,$request->iTipoRecId,'','','','','',$request->iYearCriterio,$request->iCritId,$request->cCritVariable,0,$request->iPageNumber,$request->iPageSize];
            break;
        }
        $dataResult = DB::select('EXEC tre.Sp_SEL_recibos_saldosXiEntIdXiTipoRecIdXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        //return response()->json($dataResult);

        // $data = $request->data;
        // $dataResult = DB::select('EXEC grl.Sp_SEL_conceptosXiEntIdXcCodigo_cNombre 1, ?', $data);
        $dataResult = collect($dataResult);
        foreach ($dataResult as $recibo) {
            $datReq = DB::select('EXEC tre.Sp_SEL_recibos_detXiRecId ?',[$recibo->iRecId]);
            $recibo->listaRecibosSaldosDetalle = collect($datReq)->sortBy('iRecId');
        }
        return response()->json($dataResult);
    }

    public function savRecibosSaldos(Request $request)
    {
        $dataResult = [];
        if(!$request->iRecId)
        {
            $parameters = [
                $request->iEntId,
                Carbon::parse($request->dRecFecha)->format('Ymd'),
                $request->iPersReciboId,
                $request->iConcepRecId,
                $request->cRecObs,
                $request->Ano_eje,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
                $request->cRecNroComprobantePago,
                $request->cRecNroCheque,
                $request->iTipoPagoId,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_recibos_saldos ?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
               
                $request->iRecId,
                Carbon::parse($request->dRecFecha)->format('Ymd'),
                $request->iPersReciboId,
                $request->iConcepRecId,
                $request->cRecObs,
                $request->Ano_eje,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),                
                $request->cRecNroComprobantePago,
                $request->cRecNroCheque,
                $request->iTipoPagoId,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_recibos_saldos ?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }

    public function datRecibosSaldos($iRecId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_recibosXiRecId ?',[$iRecId]);
        return response()->json($dataResult);
    }  
    public function delRecibosSaldos(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_recibos ?',[$request->iRecId]);
        return response()->json($dataResult);
    }

    public function selConceptosRecibos(){
        $dataResult = DB::select('tre.Sp_SEL_conceptos_recibos');
        return response()->json($dataResult);
    }
    public function savConceptosRecibos(Request $request){
        $dataResult = [];
        if(!$request->iConcepRecId)
        {
            $parameters = [
                $request->iTipoRecId,
                $request->cConcepRecNombre,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_conceptos_recibos ?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iConcepRecId,
                $request->iTipoRecId,
                $request->cConcepRecNombre,         

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_conceptos_recibos ?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);   
    }
    public function datConceptosRecibos($iConcepRecId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_conceptos_recibosXiConcepRecId ?',[$iConcepRecId]);
        return response()->json($dataResult);
    }
    public function delConceptosRecibos(Request $request){
        $dataResult = DB::select('EXEC tre.Sp_DEL_conceptos_recibos ?',[$request->iConcepRecId]);
        return response()->json($dataResult);
    }


    

    public function selRecibosSaldosDetalle($iRecId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_recibos_detXiRecId ?',[$iRecId]);
        return response()->json($dataResult);
    }   
    public function savRecibosSaldosDetalle(Request $request)
    {
        $dataResult = [];
        if(!$request->iRecDetId)
        {
            $parameters = [
                $request->iRecId,
                $request->iEspeDetId,
                $request->nRecDetSubMonto,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_recibos_det ?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
               
                $request->iRecDetId,
                $request->iEspeDetId,
                $request->nRecDetSubMonto,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_recibos_det ?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }
    public function datRecibosSaldosDetalle ($iRecDetId){
        $dataResult = DB::select('EXEC tre.Sp_SEL_recibos_detXiRecDetId ?',[$iRecDetId]);
        return response()->json($dataResult);
    }
    public function delRecibosSaldosDetalle (Request $request){
        $dataResult = DB::select('EXEC tre.Sp_DEL_recibos_det ?',[$request->iRecDetId]);
        return response()->json($dataResult);
    }


}
