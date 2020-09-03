<?php

namespace App\Http\Controllers\Tesoreria;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositosAbonoController extends Controller
{

    public function selTiposCuenta()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_motivos_cuentas');
        return response()->json($dataResult);
    }
    public function selEstadosDeposito()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_estados_depositos_personas');
        return response()->json($dataResult);
    }

    public function selDepositosAbono(Request $request)
    {
        $parameters = [$request->iEntId,$request->iYearId,$request->iMonthId,$request->iPageNumber,$request->iPageSize];
        $dataResult = DB::select('EXEC tre.Sp_SEL_depositos_personasXiEntIdXiYearIdXiMonthId ?,?,?,?,?',$parameters);
        return response()->json($dataResult);
    }

    public function savDepositosAbono(Request $request)
    {
        $dataResult = [];
        if(!$request->iDepPersId)
        {
            $parameters = [
                $request->iEntId,
                $request->dDepPersFecha,
                $request->iYearId,
                $request->iMonthId,
                $request->iBancoId,
                $request->iMotCuentaId,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
                $request->cDepPersNombre,
                $request->cDepPersObs,                

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_depositos_personas ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [               
                $request->iDepPersId,
                $request->dDepPersFecha,
                $request->iMonthId,
                $request->iEstDepPersId,
                str_pad($request->Expediente, 10, "0", STR_PAD_LEFT),
                $request->cDepPersNombre,
                $request->cDepPersObs,                

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_depositos_personas ?,?,?,?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }

    public function datDepositosAbono($iDepPersId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_depositos_personasXiDepPersId ?',[$iDepPersId]);
        return response()->json($dataResult);
    }  
    public function delDepositosAbono(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_depositos_personas ?',[$request->iDepPersId]);
        return response()->json($dataResult);
    }    
  
    
    public function selCuentasDA(Request $request)
    {
        //$parameters = [$request->iEntId,$request->iBancoId,$request->iPageNumber,$request->iPageSize];
        switch($request->iOpcionFiltro){
            case '0':
                $parameters = [$request->iEntId,$request->iBancoId,'','',1,$request->iPageNumber,$request->iPageSize];
            break;
            case '4':
                $parameters = [$request->iEntId,$request->iBancoId,$request->iCritId,$request->cCritVariable,0,$request->iPageNumber,$request->iPageSize];
            break;
        }        
        //$dataResult = DB::select('EXEC tre.Sp_SEL_personas_cuentasXiEntId ?,?,?,?',$parameters);
        $dataResult = DB::select('EXEC tre.Sp_SEL_personas_cuentasXiEntIdXcConsultaVariablesCampos ?,?,?,?,?,?,?',$parameters);
        
        return response()->json($dataResult);
    }    

    public function savCuentasDA(Request $request)
    {
        $dataResult = [];
        if(!$request->iPersCuentaId)
        {
            $parameters = [
                $request->iEntId,
                $request->iPersId,
                $request->iMotCuentaId,
                $request->iBancoId,
                $request->cPersCuentaNumero,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_personas_cuentas ?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iPersCuentaId,
                $request->iMotCuentaId,
                $request->cPersCuentaNumero,
                $request->iPersCuentaEstado,                

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_personas_cuentas ?,?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }

    public function datCuentasDA($iPersCuentaId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_personas_cuentasXiPersCuentaId ?',[$iPersCuentaId]);
        return response()->json($dataResult);
    }  
    public function delCuentasDA(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_personas_cuentas ?',[$request->iPersCuentaId]);
        return response()->json($dataResult);
    }
    
    public function selbancosDA($iEntId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_bancos_personas_cuentasXiEntId ?',[$iEntId]);
        return response()->json($dataResult);
    }    
    
    public function selMotivosCuentaDA()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_motivos_cuentas');
        return response()->json($dataResult);
    } 
    
    public function selCriterioBusquedaDA()
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_Criterio_Busqueda_personas_cuentas');
        return response()->json($dataResult);
    }     
     
    public function selDepositosAbonoDetalles(Request $request)
    {
        $parameters = [$request->iDepPersId,$request->iPageNumber,$request->iPageSize];
        $dataResult = DB::select('EXEC tre.Sp_SEL_depositos_personas_abonosXiDepPersId ?,?,?',$parameters);
        return response()->json($dataResult);
    }   
    public function savDepositosAbonoDetalles(Request $request)
    {
        $dataResult = [];
        if(!$request->iDepPersAbonoId)
        {
            $parameters = [
                $request->iDepPersId,
                $request->iPersId,
                $request->cDepPersAbonoNumeroCuenta,
                $request->nDepPersAbonoImporteDeposito,
                $request->cDepPersAbonoObs,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_INS_depositos_personas_abonos ?,?,?,?,?,?,?,?,?',$parameters);
        }
        else{
            $parameters = [
                $request->iDepPersAbonoId,
                $request->nDepPersAbonoImporteDeposito,
                $request->cDepPersAbonoObs,

                auth()->user()->iCredId,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
            $dataResult = DB::select('EXEC tre.Sp_UPD_depositos_personas_abonos ?,?,?,?,?,?,?',$parameters);
        }        
        return response()->json($dataResult);              
    }

    public function datDepositosAbonoDetalles($iDepPersAbonoId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_depositos_personas_abonosXiDepPersAbonoId ?',[$iDepPersAbonoId]);
        return response()->json($dataResult);
    }  
    public function delDepositosAbonoDetalles(Request $request)
    {
        $dataResult = DB::select('EXEC tre.Sp_DEL_depositos_personas_abonos ?',[$request->iDepPersAbonoId]);
        return response()->json($dataResult);
    }   
    public function importCuentaTrabajador(Request $request)
    {
        $dataResult = DB::select('EXEC tre.personas_cuentasXiDepPersIdXiPersId ?,?',[$request->iDepPersId,$request->iPersId]);
        return response()->json($dataResult);
    }     
    
    public function generateTXTDeposito($iDepPersId)
    {
        $dataResult = DB::select('EXEC tre.Sp_SEL_TXT_BANCO_depositos_personas_abonos_presentacionXiDepPersId ?',[$iDepPersId]);
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
            echo 'Hubo un error al generar el Archivo TXT (verifique que la planilla de pago tenga registro de depositos)';
        }

    } 


    public function generateTXTAction($dataResult){        
        if (!empty($dataResult)) {
            $nombreArchivo = $dataResult[0]->cEstructuraNombreArchivo;
            $extension = $dataResult[0]->cEstructuraExtensionArchivo;
            $archivo =  storage_path('app/public/DEPOSITOS_ABONOS/TXT/') . $nombreArchivo . "." . $extension;
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
                    'ruta'=> "DEPOSITOS_ABONOS/TXT/".$nombreArchivo.".".$extension,
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