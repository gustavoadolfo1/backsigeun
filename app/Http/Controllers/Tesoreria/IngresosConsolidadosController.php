<?php

namespace App\Http\Controllers\Tesoreria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IngresosConsolidadosController extends Controller
{
    public function selTiposRecibosIC(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipos_recibos');
        return response()->json($dataResult);
    }
    public function selTiposDocumentoRecibo(){
        $dataResult = DB::select('EXEC tre.Sp_SEL_tipo_documentos_recibos');
        return response()->json($dataResult);
    }


    public function selCriterioIC()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_Criterio_Busqueda_Recibo_Ingreso');
        return response()->json($dataResult);
    }
    public function selIngresosConsolidados(Request $request)
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
        $dataResult = DB::select('EXEC tre.Sp_SEL_recibos_ingresosXiEntIdXiTipoRecIdXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }
    public function savIngresosConsolidados(Request $request)
    {
        $dataResult = [];
        if(!$request->iRecId)
        {
            $parameters = [
                $request->iEntId,
                $request->Ano_eje,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
                Carbon::parse($request->dRecFecha)->format('Ymd'),
                $request->iTipoDocId,
                $request->cRecNroDoc,
                $request->iFilId,
                $request->cRecObs,
                $request->nRecMonto,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_recibos_ingresos ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iRecId,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),                
                Carbon::parse($request->dRecFecha)->format('Ymd'),
                $request->iTipoDocId,
                $request->cRecNroDoc,
                $request->iFilId,
                $request->cRecObs,
                $request->nRecMonto,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_recibos_ingresos ?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }
    public function datIngresosConsolidados($iRecId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_recibosXiRecId ?',[$iRecId]);
        return response()->json($dataResult);
    }  
    public function delIngresosConsolidados(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_recibos ?',[$request->iRecId]);
        return response()->json($dataResult);
    }
}