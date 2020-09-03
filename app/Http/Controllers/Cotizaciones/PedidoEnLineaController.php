<?php

namespace App\Http\Controllers\Cotizaciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

class PedidoEnLineaController extends Controller
{
    public function getDetallesPedidosSIGA($secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido)
    {
        $data = \DB::select("EXEC log.Sp_SEL_pedidos_enlineaXiIdPedidos ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido ]);

        return response()->json( $data );
    }

    public function insertarPedido(Request $request)
    {
        $parametros = [
            auth()->user()->iCredId,
            date('Y-m-d'),
            $request->secEjec,
            $request->anioEjec,
            $request->tipoBien, 
            $request->tipoPedido, 
            $request->nroPedido,
            $request->archivoTDR,
            $request->observacion,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.[Sp_INS_pedidos_enlinea] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            foreach ($request->detalles as $detalle) {
                $this->insertarActualizarDetallePedido($queryResult[0]->iPedEnLId, $request, $detalle);
            }

            $response = ['validated' => true, 'mensaje' => 'Se guardó el pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function insertarActualizarDetallePedido($pedidoId, Request $request, $detalle)
    {
        $parametros = [
            $pedidoId,
            $request->secEjec,
            $request->anioEjec,
            $request->tipoBien, 
            $request->tipoPedido, 
            $request->nroPedido,
            $detalle['SECUENCIA'],
            $detalle['CANT_SOLICITADA'],
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        $queryResult = \DB::select('exec log.Sp_INS_UPD_pedidos_enlinea_detalles ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
    }

    public function getPedidosPublicados($page, $pageSize)
    {
        $data = \DB::select("EXEC log.Sp_SEL_pedidos_enlinea_publicados ?, ?", [ $page, $pageSize ]);

        $ids = [];
        for ($i=0; $i < count($data); $i++) { 
            $ids[] = $data[$i]->iPedEnLId;
        }

        $cotizaciones = \DB::table('log.cotizaciones')->select('iCotizaId', 'iPedEnLId', 'iEstCotizId')->where('iCredProveedorId', auth()->user()->iCredId)->whereIn('iPedEnLId', $ids)->get();

        foreach ($cotizaciones as $cotizacion) {
            for ($i=0; $i < count($data); $i++) { 
                if ($cotizacion->iPedEnLId == $data[$i]->iPedEnLId) {
                    $data[$i]->cotizacion = $cotizacion;
                    break;
                }
            }
        }

        return response()->json( $data );
    }

    public function actualizarPedido(Request $request)
    {
        $parametros = [
            $request->pedidoId,
            auth()->user()->iCredId,
            date('Y-m-d'),
            $request->archivoTDR,
            $request->observacion,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec [log].[Sp_UPD_pedidos_enlinea] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            foreach ($request->detalles as $detalle) {
                $this->insertarActualizarDetallePedido($queryResult[0]->iPedEnLId, $request, $detalle);
            }

            $response = ['validated' => true, 'mensaje' => 'Se actualizó el pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function actualizarPedidoPublicar(Request $request)
    {
        if ($request->oldFile && $request->hasFile('archivoTDR')) {
            Storage::delete($request->oldFile);
        }

        if ($request->hasFile('archivoTDR')) {
            $archivoTDR = $request->archivoTDR->store('TDRS');
        }
        elseif ($request->oldFile) {
            $archivoTDR = $request->oldFile;
        }
        

        $ini = str_replace("T", " ", $request->fechaInicio);
        $ini = str_replace("-", "", $ini);
        $end = str_replace("T", " ", $request->fechaFin);
        $end = str_replace("-", "",$end);

        $parametros = [
            $request->pedidoId,
            auth()->user()->iCredId,
            $ini ?? NULL,
            $end ?? NULL,
            $archivoTDR ?? NULL,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.Sp_UPD_pedidos_enlinea_publicar ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se actualizó el pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\sException $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function insertarPublicarPedidoSecCuadro(Request $request)
    {
        $archivoTDR = $request->archivoTDR->store('TDRS');

        $secEjec = config('constantes.sec_ejec');

        $ini = str_replace("T", " ", $request->fechaInicio);
        $ini = str_replace("-", "", $ini);
        $end = str_replace("T", " ", $request->fechaFin);
        $end = str_replace("-", "",$end);

        $parametros = [
            auth()->user()->iCredId,
            $secEjec,
            $request->anioEjec,
            $request->tipoBien, 
            $request->secCuadro,

            $ini,
            $end,

            $archivoTDR,
            $request->observacion,

            $request->garantia,
            $request->plazo,
            $request->almacen,

            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.Sp_INS_pedidos_enlinea_PublicarXSEC_CUADRO ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se guardó y publicó el pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\aException $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getPedidosCotizador($cotizadorId, $page, $pageSize)
    {
        $data = \DB::select("EXEC log.Sp_SEL_pedidos_enlineaXiCredCotizadorId ?, ?, ?", [ $cotizadorId, $page, $pageSize ]);

        return response()->json( $data );
    }

    public function eliminarDetallePedido($detalleId)
    {
        try {
            $queryResult = \DB::select('exec log.Sp_DEL_pedidos_enlinea_detallesXiPedEnLDetId ?', [ $detalleId ] );

            $response = ['validated' => true, 'mensaje' => 'Se eliminó el detalle de pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getCriteriosBusqueda()
    {
        $secEjec = config('constantes.sec_ejec');
        $criterios = \DB::select("EXEC siga.Sp_SEL_criterio_busqueda_cuadro_adquisiciones");
        
        $anios = \DB::select("EXEC siga.Sp_SEL_ANO_EJE_SIG_PEDIDOS ?", [ $secEjec ]);

        return response()->json( [ 'criterios' => $criterios, 'anios' => $anios ] );
    }

    public function getAnexosDetalle($anioEjec, $tipoBien, $tipoPedido, $nroPedido, $secuencia)
    {
        $secEjec = config('constantes.sec_ejec');
        $data = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_anexosXiIdPedidos_Detalles ?, ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido, $secuencia ]);

        return response()->json( $data );
    }

    public function getDetallesPedidoSecCuadro($anioEjec, $tipoBien, $secCuadro)
    {
        $secEjec = config('constantes.sec_ejec');
        $data = \DB::select("EXEC siga.Sp_SEL_pedidos_detallesXSEC_CUADRO ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $secCuadro ]);

        foreach ($data as $i => $detalle) {
            $detalle->anexos = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_anexosXiIdPedidos_Detalles ?, ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $detalle->TIPO_BIEN, $detalle->TIPO_PEDIDO, $detalle->NRO_PEDIDO, $detalle->SECUENCIA ]);
        }

        return response()->json( $data );
    }

    public function getCuadroAdquisiciones($secEjec, $anioEjec, $critId, $critVariable, $page, $pageSize)
    {
        if($critVariable == null || $critVariable == 'null' || $critVariable == '' ){
            $critVariable = NULL;
        }
        $loadData = [
            $secEjec, 
            $anioEjec, 
            $critId, 
            $critVariable, 
            $page, 
            $pageSize 
        ];
        // return response()->json( $loadData );
        $data = \DB::select("EXEC siga.Sp_SEL_cuadro_adquisicionesXcConsultaVariablesCampos ?, ?, ?, ?, ?, ?",$loadData );

        return response()->json( $data );
    }

    public function getDetallesPedido($pedidoId)
    {
        $data = \DB::select("EXEC log.Sp_SEL_pedidos_enlinea_detallesXiPedEnLId ?", [ $pedidoId ]);

        foreach ($data as $i => $detalle) {
            $detalle->anexos = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_anexosXiIdPedidos_Detalles ?, ?, ?, ?, ?, ?", [ $detalle->SEC_EJEC, $detalle->ANO_EJE, $detalle->TIPO_BIEN, $detalle->TIPO_PEDIDO, $detalle->NRO_PEDIDO, $detalle->SECUENCIA ]);
        }

        return response()->json( $data );
    }

    public function cerrarPedido(Request $request)
    {
        $parametros = [
            $request->pedidoId,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select('exec log.Sp_UPD_pedidos_enlinea_cerrar ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se cerró el pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getPedidoAndDetalles($pedidoId)
    {
        $pedido = \DB::select("EXEC [log].[Sp_SEL_pedidos_enlineaXiPedEnLId] ?", [ $pedidoId ]);

        $pedido[0]->detalles = $this->getDetallesPedido($pedido[0]->iPedEnLId);

        return response()->json( $pedido[0] );
    }
    public function updPedidoEnLinea(Request $request)
    {
        $data = \DB::table('users')
                ->where('id', $request->pedidoId)
                ->update([
                    'dPedEnLFechaInicio' => $request->dPedEnLFechaInicio,
                    'dPedEnLFechaFin' => $request->dPedEnLFechaFin,

                    'iAlmacenId' => $request->iAlmacenId,
                    'iPedEnLGarantia' => $request->iPedEnLGarantia,
                    'iPedEnLPlazoEntregaEjecucionServicio' => $request->iPedEnLPlazoEntregaEjecucionServicio,
                    
                    ]);
    }

    public function eliminarPedido($pedidoId)
    {
        try {
            $queryResult = \DB::select('exec log.Sp_DEL_pedidos_enlineaXiPedEnLId ?', [ $pedidoId ] );

            $response = ['validated' => true, 'mensaje' => 'Se eliminó el pedido en línea.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
}
