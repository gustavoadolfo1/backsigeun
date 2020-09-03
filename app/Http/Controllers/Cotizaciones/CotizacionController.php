<?php

namespace App\Http\Controllers\Cotizaciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Swift_Mailer;
use Swift_MailTransport;
use Swift_Message;
use Swift_Attachment;

class CotizacionController extends Controller
{
    public function getBusquedaCriterios()
    {
        $data = \DB::select("EXEC siga.Sp_SEL_criterio_busqueda_pedidos");

        return response()->json( $data );
    }

    public function getPedidosSIGA($secEjec, $anioEjec, $tipoBien, $critId, $critVariable, $size, $page)
    {
        if ($critVariable == 'null') {
            $critVariable = '';
        }


        $data = \DB::select("EXEC siga.Sp_SEL_pedidosXcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $critId, $critVariable, $page, $size ]);

        return response()->json( $data );
    }

    public function getDetallesPedidosSIGA($secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido)
    {
        $data = \DB::select("EXEC siga.Sp_SEL_pedidos_detallesXiIdPedidos ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido ]);

        $data1 = \DB::select("EXEC log.Sp_SEL_pedidos_enlineaXiIdPedidos ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido ]);

        foreach ($data1 as $pedido) {
            $pedido->detalles = \DB::select("exec log.Sp_SEL_pedidos_enlinea_detallesXiPedEnLId ?", [ $pedido->iPedEnLId  ]);
        }

        return response()->json( [ 'detallesPedido' => $data, 'pedidosEnLinea' => $data1 ] );
    }

    public function getCotizacionesPedido($pedidoId)
    {
        $data = \DB::select("EXEC log.Sp_SEL_cotizacionesXiPedEnLId ?", [ $pedidoId ]);

        return response()->json( $data );
    }

    public function guardarCotizacion(Request $request)
    {
        $parametros = [
            auth()->user()->iCredId,
            $request->pedidoId,
            date('Y-m-d'),
            $request->archivo1 ?? NULL,
            $request->archivo2 ?? NULL,
            $request->observaciones ?? NULL,
            NULL,NULL,NULL,NULL,NULL,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.Sp_INS_cotizaciones ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Se guardó la cotización', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\sException $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getDetallesCotizacion($cotizaId)
    {
        $data = \DB::select("EXEC log.Sp_SEL_cotizaciones_detallesXiCotizaId ?", [ $cotizaId ]);

        foreach ($data as $i => $detalle) {
            $detalle->anexos = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_anexosXiIdPedidos_Detalles ?, ?, ?, ?, ?, ?", [ $detalle->SEC_EJEC, $detalle->ANO_EJE, $detalle->TIPO_BIEN, $detalle->TIPO_PEDIDO, $detalle->NRO_PEDIDO, $detalle->SECUENCIA ]);
        }

        // $data = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_anexosXiIdPedidos_Detalles ?, ?, ?, ?, ?, ?", [ $secEjec, $anioEjec, $tipoBien, $tipoPedido, $nroPedido, $secuencia ]);

        $cotizacion = \DB::select("EXEC log.Sp_SEL_cotizacionesXiCotizaId ?", [ $cotizaId ]);

        //$anexos = [];

        // if (count($data) > 0) {
        //     $anexos = \DB::select("EXEC siga.Sp_SEL_pedidos_detalles_ansexoXSEC_CUADRO ?, ?, ?, ?", [ $data[0]->SEC_EJEC, $data[0]->ANO_EJE, $data[0]->TIPO_BIEN, $data[0]->NRO_CUADRO ]);

        //     foreach ($data as $i => $detalle) {
        //         $data[$i]->anexos = [];
        //         foreach ($anexos as $j => $anexo) {
        //             if ($detalle->SECUENCIA == $anexo->SECUENCIA) {
        //                 $data[$i]->anexos[] = $anexo;
        //                 unset($anexos[$j]);
        //                 break;
        //             }
        //         }
        //     }
        // }

        return response()->json( [ 'detalles' => $data, 'cotizacion' => $cotizacion[0]] );
    }

    public function actualizarDetallesCotizacion(Request $request)
    {
        try {
            foreach ($request->detalles as $detalle) {

                if ((float)$detalle['nCotizaDetPrecioUnitario'] != 0) {
                    $parametros = [
                        $detalle['iCotizaDetId'],
                        $detalle['nCotizaDetPrecioUnitario'],
                        $detalle['cCotizaDetMarca'],
                        $detalle['cCotizaDetModelo'],
                        auth()->user()->iCredId,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac'
                    ];
                    $queryResult = \DB::select("EXEC log.Sp_UPD_cotizaciones_detalles ?, ?, ?, ?, ?, ?, ?, ?", $parametros );
                }
            }

            //$queryResult = $this->enviarCotizacion($request->cotizaId, $request->server->get('REMOTE_ADDR'));

            $response = ['validated' => true, 'mensaje' => 'Se actualizaron los detalles de la cotización' ];

            $codeResponse = 200;

        } catch (\Eaxception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function enviarCotizacion($cotizaId, Request $request)
    {
        $parametros = [
            $cotizaId,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select("EXEC log.Sp_UPD_cotizaciones_Enviar ?, ?, ?, ?, ?", $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se envió la cotización', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function actualizarCotizacion(Request $request)
    {
        $archivo1 = NULL;
        if ($request->hasFile('archivo1')) {
            $archivo1 = $request->archivo1->store('cotizaciones');
        } else {
            $archivo1 = $request->archivo1;
        }
        $archivo2 = NULL;
        if ($request->hasFile('archivo2')) {
            $archivo2 = $request->archivo2->store('cotizaciones');
        } else if ($request->archivo2) {
            $archivo2 = $request->archivo2;
        }

        $cotizacion = \DB::table('log.cotizaciones')->where('iCotizaId', $request->cotizaId)->first();

        $parametros = [
            $request->cotizaId,
            $archivo1,
            $archivo2,
            $request->obs ?? NULL,
            $request->formaPago,
            $request->garantia,
            $request->plazo,
            $request->tipoMoneda,
            $request->validez,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.Sp_UPD_cotizaciones ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );

            Storage::delete($cotizacion->cCotizaArchivo1);

            $response = ['validated' => true, 'mensaje' => 'Se actualizó la cotización', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getCotizacionPDF(Request $request)
    {
        try {
            return Storage::download($request->file);
        } catch (Exception $e) {
            $response = ['exception' => $e];
            $codeResponse = 500;

            return response()->json( report($e), $codeResponse );
        }
    }

    public function asignarBuenaPro(Request $request)
    {
        $parametros = [
            $request->cotizaDetId,
            $request->cotizaBuenaPro,
            $request->motivo,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('EXEC log.Sp_UPD_bCotizaDetBuenaPro_cotizaciones_detalles ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => 'Asignada buena Pro.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function getCotizacionesCuadroComparativo($cuadroId)
    {
        $data = \DB::select("EXEC log.Sp_SEL_cotizacionesXiCuadroCompaId ?", [ $cuadroId ]);

        foreach ($data as $cotizacion) {
            $cotizacion->detalles =  \DB::select("EXEC log.Sp_SEL_cotizaciones_detallesXiCotizaId ?", [  $cotizacion->iCotizaId ]);
        }

        return response()->json( $data );

    }

    public function getCotizacionesBuenaProPorCotizador($anio, $cotizadorId, $page, $pageSize )
    {
        $secEjec = config('constantes.sec_ejec');

        $data = \DB::select("EXEC log.Sp_SEL_cotizaciones_BuenaProXiCredCotizadorId ?, ?, ?, ?, ?", [ $secEjec, $anio, $cotizadorId, $page, $pageSize ]);

        foreach ($data as $cotizacion) {
            $cotizacion->detalles = \DB::select("EXEC log.Sp_SEL_cotizaciones_detalles_BuenaProXiCotizaId ?", [ $cotizacion->iCotizaId ]);
        }

        return response()->json( $data );
    }

    public function updCotizacionesNotificacion(Request $request)
    {
        $ordenArchivo = NULL;
        if ($request->hasFile('ordenArchivo')) {
            $ordenArchivo = $request->ordenArchivo->store('ordenesCompra');
        } else {
            $ordenArchivo = $request->ordenArchivo;
        }
        
        $parametros = [
            $request->cotizaId,
            $ordenArchivo,
            $request->obs,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('EXEC log.Sp_SEL_cotizadoresXSEC_EJECXANO_EJE ?, ?, ?, ?, ?, ?, ?', $parametros );

            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

    public function selCotizadoresYear($year)
    {
        $secEjec = config('constantes.sec_ejec');

        $data = \DB::select("EXEC log.Sp_SEL_cotizadoresXSEC_EJECXANO_EJE ?, ?", [ $secEjec, $year ]);

        return response()->json( $data );
    }

    public function notificar(Request $request){

   
        $ordenArchivo = NULL;
        $file = $request->ordenArchivo;
        if ($request->hasFile('ordenArchivo')) {
            $ordenArchivo = $request->ordenArchivo->store('ordenesCompra');
        } else {
            $ordenArchivo = $request->ordenArchivo;
        }
        
        $parametros = [
            $request->cotizaId,
            $ordenArchivo,
            $request->obs,
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.Sp_UPD_cotizaciones_notificacion ?, ?, ?, ?, ?, ?, ?', $parametros );

            $backup = Mail::getSwiftMailer();
            $transport = new \Swift_SmtpTransport('smtp.office365.com', 587, 'TLS');
            $transport->setUsername('adquisiciones@unam.edu.pe');
            $transport->setPassword('Moquegua2019*');
            $gmail =new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);

            $path = Storage::disk('public')->path($ordenArchivo);

            Mail::send('cotizaciones/mail', [ 'data'=>$request ] , function($message) use ($request,$path) {
                $message->to($request->correo, ucwords(strtolower('Proveedor')))->subject('SIGEUN - NOTIFICACIÓN DE ORDEN DE '.$request->tipo. ' N°'.$request->NRO_ORDEN. ' SIAF N° '.$request->NRO_SIAF .' - '.$request->cNombre_Cotizador.'(cotizador)' );
                $message->from('adquisiciones@unam.edu.pe','U.N.A.M. - Logística');
                
                $message->attach($path, array(
                    'as' => 'coti.pdf') 
                );
            });

            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\qException $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    public function renotificar(Request $request){
        // return response()->json( $request );
        try {
            $backup = Mail::getSwiftMailer();
            $transport = new \Swift_SmtpTransport('smtp.office365.com', 587, 'TLS');
            $transport->setUsername('adquisiciones@unam.edu.pe');
            $transport->setPassword('Moquegua2019*');
            $gmail =new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);

            // $file2 = Storage::get($request->cCotizaNotificacionOrdenArchivo);
            // return response()->json( $file2 ); 
            $path = Storage::disk('public')->path($request->cCotizaNotificacionOrdenArchivo);
            Mail::send('cotizaciones/mail', [ 'data'=>$request ], function($message) use ($request,$path) {
                $message->to($request->cCorreo_Proveedor, ucwords(strtolower('Proveedor')))->subject('SIGEUN - NOTIFICACIÓN DE ORDEN DE '.$request->tipo. ' N°'.$request->NRO_ORDEN. ' SIAF N° '.$request->NRO_SIAF .' - '.$request->cNombre_Cotizador.'(cotizador)');
                $message->from('adquisiciones@unam.edu.pe','U.N.A.M. - Logística');
                $message->attach($path, array(
                    'as' => 'coti.pdf') 
                );
            });
            
            
        } catch (\qException $e) {
            
            return response()->json( $e );    
        }
        
    }
    public function getAlmacenesCotizacion (){
        $data = \DB::select('EXEC log.Sp_SEL_almacenes');
        return response()->json( $data );
    }
    public function updPedidoLinea(Request $request){
        

        $ini = str_replace("T", " ", $request->dPedEnLFechaInicio);
        $ini = str_replace("-", "", $ini);
        $end = str_replace("T", " ", $request->dPedEnLFechaFin);
        $end = str_replace("-", "",$end);

        $parametros = [
            $request->iPedEnLId,

            $ini,
            $end,

            $request->cPedEnLArchivoTDR,
            $request->cPedEnLObs,
            
            $request->iPedEnLGarantia,
            $request->iPedEnLPlazoEntregaEjecucionServicio,
            $request->iAlmacenId,

            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        try {
            $queryResult = \DB::select('exec log.Sp_UPD_pedidos_enlinea_PublicarXiPedEnLId ?, ?, ?, ?, ?, ?, ?,?    ,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\qException $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }

}
