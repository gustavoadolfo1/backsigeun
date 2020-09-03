<?php

Route::group(['prefix' => 'cotizaciones', 'middleware' => 'auth:api'], function () {
    Route::get('getBusquedaCriterios', 'Cotizaciones\CotizacionController@getBusquedaCriterios');

    Route::post('updPedidoLinea','Cotizaciones\CotizacionController@updPedidoLinea');
    
    Route::get('getPedidosSIGA/{secEjec}/{anioEjec}/{tipoBien}/{critId}/{critVariable}/{size}/{page}', 'Cotizaciones\CotizacionController@getPedidosSIGA');
    Route::get('getDetallesPedidosSIGA/{secEjec}/{anioEjec}/{tipoBien}/{tipoPedido}/{nroPedido}', 'Cotizaciones\CotizacionController@getDetallesPedidosSIGA');

    Route::get('getDetallesCotizacion/{cotizaId}', 'Cotizaciones\CotizacionController@getDetallesCotizacion');

    Route::get('getAlmacenes', 'Cotizaciones\CotizacionController@getAlmacenesCotizacion');

    Route::get('getCotizacionesPedido/{pedidoId}', 'Cotizaciones\CotizacionController@getCotizacionesPedido');

    Route::post('getCotizacionPDF', 'Cotizaciones\CotizacionController@getCotizacionPDF');
    Route::get('getCotizacionesCuadroComparativo/{cuadroId}', 'Cotizaciones\CotizacionController@getCotizacionesCuadroComparativo');

    Route::get('getCotizacionesBuenaProPorCotizador/{anio}/{cotizadorId}/{page}/{pageSize}', 'Cotizaciones\CotizacionController@getCotizacionesBuenaProPorCotizador');

    Route::get('selCotizadoresYear/{year}', 'Cotizaciones\CotizacionController@selCotizadoresYear');

    Route::post('updCotizacionesNotificacion', 'Cotizaciones\CotizacionController@updCotizacionesNotificacion');

    Route::post('asignarBuenaPro', 'Cotizaciones\CotizacionController@asignarBuenaPro');

    Route::group(['prefix' => 'pedidosEnLinea', 'middleware' => 'auth:api'], function () {
        Route::post('insertarPedido', 'Cotizaciones\PedidoEnLineaController@insertarPedido');
        Route::post('actualizarPedido', 'Cotizaciones\PedidoEnLineaController@actualizarPedido');
        Route::get('getPedidosPublicados/{page}/{pagesize}', 'Cotizaciones\PedidoEnLineaController@getPedidosPublicados');
        Route::post('actualizarPedidoPublicar', 'Cotizaciones\PedidoEnLineaController@actualizarPedidoPublicar');

        Route::post('insertarPublicarPedidoSecCuadro', 'Cotizaciones\PedidoEnLineaController@insertarPublicarPedidoSecCuadro');

        Route::get('getPedidosCotizador/{cotizadorId}/{page}/{pagesize}', 'Cotizaciones\PedidoEnLineaController@getPedidosCotizador');

        Route::delete('eliminarDetallePedido/{detalleId}', 'Cotizaciones\PedidoEnLineaController@eliminarDetallePedido');

        Route::get('getCriteriosBusqueda', 'Cotizaciones\PedidoEnLineaController@getCriteriosBusqueda');
        Route::get('getCuadroAdquisiciones/{secEjec}/{anioEjec}/{critId}/{critVariable}/{page}/{pageSize}', 'Cotizaciones\PedidoEnLineaController@getCuadroAdquisiciones');
        Route::get('getAnexosDetalle/{anioEjec}/{tipoBien}/{tipoPedido}/{nroPedido}/{secuencia}', 'Cotizaciones\PedidoEnLineaController@getAnexosDetalle');
        Route::get('getDetallesPedidoSecCuadro/{anioEjec}/{tipoBien}/{secCuadro}', 'Cotizaciones\PedidoEnLineaController@getDetallesPedidoSecCuadro');
        Route::get('getDetallesPedido/{pedidoId}', 'Cotizaciones\PedidoEnLineaController@getDetallesPedido');

        Route::post('cerrarPedido', 'Cotizaciones\PedidoEnLineaController@cerrarPedido');

        Route::get('getPedidoAndDetalles/{pedidoId}', 'Cotizaciones\PedidoEnLineaController@getPedidoAndDetalles');

        Route::post('updPedidoEnLinea', 'Cotizaciones\PedidoEnLineaController@updPedidoEnLinea');

        Route::delete('eliminarPedido/{pedidoId}', 'Cotizaciones\PedidoEnLineaController@eliminarPedido');
    });

    Route::group(['prefix' => 'proveedor', 'middleware' => 'auth:api'], function () {
        Route::get('getAnios', 'Cotizaciones\ProveedorController@getAnios');
        Route::post('guardarCotizacion', 'Cotizaciones\CotizacionController@guardarCotizacion');
        Route::post('actualizarDetallesCotizacion', 'Cotizaciones\CotizacionController@actualizarDetallesCotizacion');
        Route::get('enviarCotizacion/{cotizaId}', 'Cotizaciones\CotizacionController@enviarCotizacion');

        Route::post('actualizarCotizacion', 'Cotizaciones\CotizacionController@actualizarCotizacion');

        // Route::get('getCotizacionesProveedor/{anioEjec}/{page}/{pageSize}', 'Cotizaciones\CotizacionController@getCotizacionesPedido');
        // Route::get('pdfGenerado', 'Cotizaciones\ProveedorController@docGenerado');

        Route::get('getCotizacionesProveedor/{anioEjec}/{page}/{pageSize}', 'Cotizaciones\ProveedorController@getCotizacionesProveedor');

        Route::post('actualizarInfoProveedor', 'Cotizaciones\ProveedorController@actualizarInfoProveedor');

        Route::get('generarAnexo7', 'Cotizaciones\ProveedorController@generarAnexo7');

        Route::get('getInfoProveedor', 'Cotizaciones\ProveedorController@getInfoProveedor');
        Route::post('updateInfoPIDE', 'Cotizaciones\ProveedorController@updateInfoPIDE');

        Route::post('updateCotizacionYRequisitos', 'Cotizaciones\ProveedorController@updateCotizacionYRequisitos');

        Route::get('getDashboardCotizacionesOnline', 'Cotizaciones\ProveedorController@getDashboardCotizacionesOnline');
        
    });

    Route::group(['prefix' => 'cuadroComparativo', 'middleware' => 'auth:api'], function () {
        Route::post('insertarCuadroComparativo', 'Cotizaciones\CuadroComparativoController@insertarCuadroComparativo');
    });
});
Route::get('cotizaciones/pdfGenerado/{cotizaId}', 'Cotizaciones\ProveedorController@docGenerado');
Route::get('cotizaciones/pdfGeneradoCuadro/{cuadroId}', 'Cotizaciones\ProveedorController@docGeneradoCuadro');

Route::post('cotizaciones/proveedor/registrarNuevoProveedor', 'Cotizaciones\ProveedorController@registrarNuevoProveedor');
Route::post('sendMailNotify', 'Cotizaciones\CotizacionController@notificar');
Route::post('reSendMailNotify', 'Cotizaciones\CotizacionController@renotificar');

Route::post('cotizaciones/getArchivo', 'Cotizaciones\CotizacionController@getCotizacionPDF');