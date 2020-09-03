<?php

namespace App\Http\Controllers\Cotizaciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;
// use Barryvdh\DomPDF\PDF;

use Illuminate\Support\Facades\Storage;

class ProveedorController extends Controller
{
    public function getAnios()
    {
        $data = \DB::table('log.cotizaciones')->where('iCredProveedorId', auth()->user()->iCredId )->select(\DB::raw('YEAR ( dCotizaFecha ) as year'))->distinct()->orderBy('year', 'desc')->get();

        return response()->json( $data );
    }

    public function getCotizacionesProveedor($anioEjec, $page, $pageSize)
    {
        $secEjec = config('constantes.sec_ejec');

        $data = \DB::select("EXEC log.Sp_SEL_cotizacionesXiCredProveedorId ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, auth()->user()->iCredId, $page, $pageSize  ]);

        return response()->json( $data );
    }

    public function docGenerado($cotizaId){
        $headData = \DB::select("EXEC log.Sp_SEL_cotizacionesXiCotizaId ?", [ $cotizaId ]);
        $data = \DB::select("EXEC log.Sp_SEL_cotizaciones_detallesXiCotizaId ?", [ $cotizaId ]);
        $sume = 0;
        $head = $headData[0];
        // dd($head);
        foreach ($data as $i => $detalle) {
            $sume += (float)$detalle->nCotizaDetSubTotal;
            $detalle->anexos = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_anexosXiIdPedidos_Detalles ?, ?, ?, ?, ?, ?", [ $detalle->SEC_EJEC, $detalle->ANO_EJE, $detalle->TIPO_BIEN, $detalle->TIPO_PEDIDO, $detalle->NRO_PEDIDO, $detalle->SECUENCIA ]);
        }
        $pdf = PDF::setOptions(['isPhpEnabled' => true])->loadView('cotizaciones.cotizacionGenerada', compact(['head','data','sume']))->setPaper('A4');
        return $pdf->stream();
    }

    public function actualizarInfoProveedor(Request $request)
    {
        if ($request->hasFile('requisitosFile')) {
            Storage::delete($request->oldRequisitosFile);
            $requisitosFile = $request->requisitosFile->store('proveedores');
        } else {
            $requisitosFile = $request->requisitosFile ?? NULL;
        }

        /*if ($request->hasFile('firmaSelloFile')) {
            if ($request->oldFirmaSelloFile != null) {
                Storage::delete($request->oldFirmaSelloFile);
            }
            $firmaSelloFile = $request->firmaSelloFile->store('proveedores');
        } else {
            $firmaSelloFile = $request->firmaSelloFile ?? NULL;
        }*/

        $parametros = [
            auth()->user()->iPersId,
            $request->razonSocial,
            $request->tipoDocRepLegal,
            $request->repLegalDoc ?? NULL,
            $request->repLegal ?? NULL,
            $request->repLegalInscrito ?? NULL,
            //$firmaSelloFile,
            $requisitosFile,
            $request->direccion,
            $request->phone,
            $request->email,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select('exec grl.Sp_UPD_personas_proveedor ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se actualizó su información exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\sException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function generarAnexo7()
    {
        // setlocale(LC_TIME, 'Spanish');
        $data = \DB::select("EXEC log.Sp_SEL_DeclaracionJuradaProveedor_Anexo_7XiPersId ?", [ auth()->user()->iPersId ]);
        $dt = Carbon::now();
        $date = $dt->isoFormat('D [de] MMMM [del] YYYY');
        $pdf = PDF::loadView('cotizaciones.anexoSiete', [ 'data' => $data[0], 'date' => $date ])->setPaper('A4');
        return $pdf->stream();
    }

    public function docGeneradoCuadro($cuadroId){
        $data = \DB::select("EXEC log.Sp_SEL_cotizacionesXiCuadroCompaId ?", [ $cuadroId ]);

        foreach ($data as $key=>$cotizacion) {
            $cotizacion->npro = 'PROVEEDOR-'. (intval($key) + 1);
            $cotizacion->detalles =  \DB::select("EXEC log.Sp_SEL_cotizaciones_detallesXiCotizaId ?", [  $cotizacion->iCotizaId ]);
        }
        $dt = Carbon::now();
        $date = $dt->isoFormat('D [de] MMMM [del] YYYY');
        /* DATA XXX PRUEBA*/
            // $data = array_merge($data, $data, $data,$data,$data,$data);
            // foreach ($data as $key=>$cotizacion) {
            //     $cotizacion->npro = 'PROVEEDOR-'. (intval($key) + 1);
            // }
        /* CLOSE DATA XXX PRUEBA*/
        $final = array_chunk($data, 4);
        $pdf = PDF::loadView('cotizaciones.cuadro', [ 'res' => $final, 'date' => $date ])->setPaper('A4','landscape');
        return $pdf->stream();
    }

    public function registrarNuevoProveedor(Request $request)
    {
        $this->validate(
            $request,
            [
                'requisitosFile' => 'required|mimes:pdf',

            ],
            [
                'requisitosFile.required' => 'Es necesario que adjunte su ficha RUC',
                'requisitosFile.mimes' => 'El archivo debe ser formato PDF',
            ]
        );

        $requisitosFile = NULL;
        $oldRequisitosFile = null;

        if ($request->hasFile('requisitosFile')) {
            $requisitosFile = $request->requisitosFile->store('proveedores');
        }

        $data = \DB::select("EXEC grl.Sp_SEL_personas_proveedorXcNroRUC ?", [ $request->ruc ]);

        //return response()->json($data);

        if (count($data) > 0) {
            $oldRequisitosFile = $data[0]->cPersRequisitosProvArchivo;
            $sp = 'exec grl.Sp_UPD_personas_proveedor ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';

            if ($data[0]->iCredId != null) {
                if ($requisitosFile != null)
                    Storage::delete($requisitosFile);
                return response()->json( ['validated' => true, 'mensaje' => 'Este proveedor ya se encuentra registrado.'], 500 );
            }

            $parametros = [
                $data[0]->iPersId,
                $request->razonSocial,
                $request->tipoDocRepLegal,
                $request->repLegalDoc ?? NULL,
                $request->repLegal ?? NULL,
                $request->repLegalInscrito ?? NULL,
                $requisitosFile,
                $request->direccion,
                $request->phone,
                $request->email,

                NULL,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
        }
        else {
            $sp = 'exec grl.Sp_INS_personas_proveedor ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?';

            $parametros = [
                $request->ruc,
                $request->razonSocial,
                $request->tipoDocRepLegal,
                $request->repLegalDoc ?? NULL,
                $request->repLegal ?? NULL,
                $request->repLegalInscrito ?? NULL,
                $requisitosFile,
                $request->direccion,
                $request->phone,
                $request->email,

                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            ];
        }

        try {
            $queryResult = \DB::select( $sp, $parametros );

            if ($request->sunatActivo == 1) {
                \DB::select('grl.Sp_INS_UPD_personas_sunatXiPersId ?, ?, ?', [ $queryResult[0]->iPersId, $request->sunatActivo, $request->sunatHabido] );
            }
            

            $response = ['validated' => true, 'mensaje' => 'Se envió el formulario con éxito.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            if ($requisitosFile != null)
                Storage::delete($requisitosFile);
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        if ($oldRequisitosFile != null) {
            Storage::delete($oldRequisitosFile);
        }

        return response()->json( $response, $codeResponse );
    }

    public function getInfoProveedor()
    {
        $data = \DB::select("exec grl.Sp_SEL_personas_proveedorXiPersId ?", [ auth()->user()->iPersId ]);

        return response()->json( $data[0] );
    }

    public function updateInfoPIDE(Request $request)
    {
        if ($request->activo != null) {
            \DB::select('grl.Sp_INS_UPD_personas_sunatXiPersId ?, ?, ?', [ auth()->user()->iPersId, $request->activo, $request->habido] );
        }
        if ($request->vigente != null) {
            \DB::select('grl.Sp_INS_UPD_personas_osceXiPersId ?, ?, ?', [ auth()->user()->iPersId, $request->vigente, $request->sancionado] );
        }
        return response()->json( 'Guardado con éxito' );
    }

    public function updateCotizacionYRequisitos(Request $request)
    {
        $this->validate(
            $request,
            [
                'archivo' => 'required|mimes:pdf',

            ],
            [
                'archivo.required' => 'Es necesario que adjunte el archivo de requisitos.',
                'archivo.mimes' => 'El archivo debe ser formato PDF',
            ]
        );

        if ($request->hasFile('archivo')) {
            $archivo = $request->archivo->store('proveedores');
        }

        $parametros = [
            $archivo,
            $request->cotizaId,
            $request->obs,

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select('exec log.Sp_UPD_proveedorRequisitos_cotizaObs ?, ?, ?, ?, ?, ?, ?', $parametros );
            Storage::delete($queryResult[0]->cPersRequisitosProvArchivo);

            $response = ['validated' => true, 'mensaje' => 'Se actualizó su información exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            Storage::delete($archivo);
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getDashboardCotizacionesOnline()
    {
        $cotizacionesEnviadas = \DB::table('log.cotizaciones')
                                    ->where('iCredProveedorId', auth()->user()->iCredId )
                                    ->where('iEstCotizId', 2 )
                                    ->count();
        $pedidosDisponibles = \DB::table('log.pedidos_enlinea')
                                    ->where([
                                        ['iEstPedEnLId', 2], 
                                        ['dPedEnLFechaInicio', '<=', date('Y-m-d\TH:i:s')], 
                                        ['dPedEnLFechaFin', '>=', date('Y-m-d\TH:i:s')], 
                                    ])
                                    ->count();

        // $ids = [];
        // for ($i=0; $i < count($pedidosDisponibles); $i++) { 
        //     $ids[] = $pedidosDisponibles[$i]->iPedEnLId;
        // }

        // $cotizaciones = \DB::table('log.cotizaciones')->select('iCotizaId', 'iPedEnLId', 'iEstCotizId')->where('iCredProveedorId', auth()->user()->iCredId)->whereIn('iPedEnLId', $ids)->get();

        // foreach ($cotizaciones as $cotizacion) {
        //     for ($i=0; $i < count($pedidosDisponibles); $i++) { 
        //         if ($cotizacion->iPedEnLId == $pedidosDisponibles[$i]->iPedEnLId) {
        //             unset($pedidosDisponibles[$i]);
        //             break;
        //         }
        //     }
        // }

        return response()->json( [ 'cotizacionesEnviadas' => $cotizacionesEnviadas, 'pedidosDisponibles' => $pedidosDisponibles ] );
    }
}
