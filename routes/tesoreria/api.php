<?php
Route::group(['prefix' => 'tesoreria', 'middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'globales', 'middleware' => 'auth:api'], function () {
        Route::get('selPeriodos', 'Tesoreria\GeneralController@selPeriodos');
        Route::get('selMeses', 'Tesoreria\GeneralController@selMeses');

        Route::group(['prefix' => 'pide', /*'middleware' => 'auth:api'*/], function (){
            Route::get('checkIfHasPIDEReniec/{dni}', 'Tesoreria\GeneralController@checkIfHasPIDEReniec');
            Route::post('{persona_id?}', 'Tesoreria\GeneralController@consultar');
        });       
       
        Route::post('selSecuenciaDocumento', 'Tesoreria\GeneralController@selSecuenciaDocumento');
        Route::post('selFaseSIAF', 'Tesoreria\GeneralController@selFaseSIAF');
        Route::post('selSumCiclo', 'Tesoreria\GeneralController@selSumCiclo');
        Route::post('selExpedienteClasificador', 'Tesoreria\GeneralController@selExpedienteClasificador');
        Route::post('selExpedienteClasificadorDatos', 'Tesoreria\GeneralController@selExpedienteClasificadorDatos');
        Route::post('selExpedienteSecuenciaFuncional', 'Tesoreria\GeneralController@selExpedienteSecuenciaFuncional');

        Route::post('selPersonaEspecificasGI', 'Tesoreria\GeneralController@selPersonaEspecificasGI');            
        Route::post('selTipoIdentificadores', 'Tesoreria\GeneralController@selTipoIdentificadores');            
        Route::post('selTipoMoneda', 'Tesoreria\GeneralController@selTipoMoneda');            
        
        Route::group(['prefix' => 'persona', 'middleware' => 'auth:api'], function () {
            Route::post('selPersonaProveedorGeneral', 'Tesoreria\GeneralController@selPersonaProveedorGeneral');            
            Route::get('datPersonaProveedor/{iPersId}', 'Tesoreria\GeneralController@datPersonaProveedor');
            Route::post('savPersonaProveedor', 'Tesoreria\GeneralController@savPersonaProveedor');
            Route::post('delPersonaProveedor', 'Tesoreria\GeneralController@delPersonaProveedor');
            
            Route::get('selTipoIdentificacion', 'Tesoreria\GeneralController@selTipoIdentificacion');
        });
    });

    Route::group(['prefix' => 'tablasMaestras', 'middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'cuentasDetraccion', 'middleware' => 'auth:api'], function () {
            Route::post('selCuentasDetraccion', 'Tesoreria\TablasMaestrasController@selCuentasDetraccion');
            Route::get('datCuentasDetraccion/{iIdPersona}', 'Tesoreria\TablasMaestrasController@datCuentasDetraccion');
            Route::post('savCuentasDetraccion', 'Tesoreria\TablasMaestrasController@savCuentasDetraccion');
            Route::post('delCuentasDetraccion', 'Tesoreria\TablasMaestrasController@delCuentasDetraccion');
            Route::post('importCuentasDetraccion', 'Tesoreria\TablasMaestrasController@importCuentasDetraccion');
        }); 
        Route::group(['prefix' => 'modalidadesContrato', 'middleware' => 'auth:api'], function () {
            Route::get('selModalidadesContratos', 'Tesoreria\TablasMaestrasController@selModalidadesContratos');
            Route::post('savModalidadesContratos', 'Tesoreria\TablasMaestrasController@savModalidadesContratos');
            Route::post('delModalidadesContratos', 'Tesoreria\TablasMaestrasController@delModalidadesContratos');
            Route::get('datModalidadesContratos/{iModalidadContratoId}', 'Tesoreria\TablasMaestrasController@datModalidadesContratos');
        }); 
        Route::group(['prefix' => 'bancos', 'middleware' => 'auth:api'], function () {
            Route::get('selBancos', 'Tesoreria\TablasMaestrasController@selBancos');
            Route::get('datBancos/{iBancoId}', 'Tesoreria\TablasMaestrasController@datBancos');
            Route::post('savBancos', 'Tesoreria\TablasMaestrasController@savBancos');
            Route::post('delBancos', 'Tesoreria\TablasMaestrasController@delBancos');
        });         
    });    
    Route::group(['prefix' => 'cartasFianza', 'middleware' => 'auth:api'], function () {
        /* generales cartas fianza*/
        Route::get('selPeriodos/{iEntId}', 'Tesoreria\CartasFianzaController@selPeriodos');
        Route::get('selMeses/{iEntId}/{iCartaFianzaCustodiaYear}', 'Tesoreria\CartasFianzaController@selMeses');        
        Route::get('selCriterioBusqueda', 'Tesoreria\CartasFianzaController@selCriterioBusqueda');
        
        Route::get('selTipos', 'Tesoreria\CartasFianzaController@selTipos');
        Route::get('selTiposCartaFianzaId/{iTipoCartaFianzaId}', 'Tesoreria\CartasFianzaController@selTiposCartaFianzaId');

        Route::get('selClases', 'Tesoreria\CartasFianzaController@selClases');
        Route::get('selClasesCartaFianzaId/{iClaseCartaFianzaId}', 'Tesoreria\CartasFianzaController@selClasesCartaFianzaId');
        Route::get('selClasesTipoCartaFianzaId/{iTipoCartaFianzaId}', 'Tesoreria\CartasFianzaController@selClasesTipoCartaFianzaId');
        
        Route::get('selEstados', 'Tesoreria\CartasFianzaController@selEstados');
        Route::get('selEstadosCartaFianzaId/{iEstadoCartaFianzaId}', 'Tesoreria\CartasFianzaController@selEstadosCartaFianzaId');
        
        Route::get('selFases', 'Tesoreria\CartasFianzaController@selFases');
        Route::get('selFasesCartaFianzaId/{iFaseCartaFianzaId}', 'Tesoreria\CartasFianzaController@selFasesCartaFianzaId');

        Route::post('updFechaVencimientoCartaFianza', 'Tesoreria\CartasFianzaController@updFechaVencimientoCartaFianza');
        /* fin */
        Route::group(['prefix' => 'registro', 'middleware' => 'auth:api'], function () {
            Route::post('selCartasFianza', 'Tesoreria\CartasFianzaController@selCartasFianza');
            Route::get('datCartasFianza/{iCartaFianzaId}', 'Tesoreria\CartasFianzaController@datCartasFianza');
            Route::post('savCartasFianza', 'Tesoreria\CartasFianzaController@savCartasFianza');
            Route::post('delCartasFianza', 'Tesoreria\CartasFianzaController@delCartasFianza');
            
            Route::post('dowCartasFianza', 'Tesoreria\CartasFianzaController@dowCartasFianza');            
            
            Route::group(['prefix' => 'contratoPS', 'middleware' => 'auth:api'], function () {
                Route::get('selPeriodoConvocatoriaPS/{cSec_ejec}', 'Tesoreria\CartasFianzaController@selPeriodoConvocatoriaPS');            
                Route::get('selCriterioBusquedaContratoPS', 'Tesoreria\CartasFianzaController@selCriterioBusquedaContratoPS');            
                Route::post('selContratoPS', 'Tesoreria\CartasFianzaController@selContratoPS');            
            }); 
            Route::group(['prefix' => 'personaProveedor', 'middleware' => 'auth:api'], function () {
                Route::post('selPersonaProveedor', 'Tesoreria\CartasFianzaController@selPersonaProveedor');            
            });             

        });  
        Route::group(['prefix' => 'detalle', 'middleware' => 'auth:api'], function () {
            Route::get('selDetalleCartasFianza/{iCartaFianzaId}', 'Tesoreria\CartasFianzaController@selDetalleCartasFianza');
            Route::get('datDetalleCartasFianza/{iCartaFianzaId}', 'Tesoreria\CartasFianzaController@datDetalleCartasFianza');
            Route::post('savDetalleCartasFianza', 'Tesoreria\CartasFianzaController@savDetalleCartasFianza');
            Route::post('delDetalleCartasFianza', 'Tesoreria\CartasFianzaController@delDetalleCartasFianza');
        });     
        Route::group(['prefix' => 'expediente', 'middleware' => 'auth:api'], function () {
            Route::post('selExpedienteCartasFianza', 'Tesoreria\CartasFianzaController@selExpedienteCartasFianza');
        });         
    });

    Route::group(['prefix' => 'detraccionesMasivas', 'middleware' => 'auth:api'], function () {
        Route::get('datEntidad/{iEntId}', 'Tesoreria\DetraccionesMasivasController@datEntidad');
        Route::get('selPeriodosDetracciones', 'Tesoreria\DetraccionesMasivasController@selPeriodosDetracciones');
        Route::get('selMesesDetracciones', 'Tesoreria\DetraccionesMasivasController@selMesesDetracciones');
        Route::get('selEstadoDetracciones', 'Tesoreria\DetraccionesMasivasController@selEstadoDetracciones');

        Route::get('selServSujDetracciones', 'Tesoreria\DetraccionesMasivasController@selServSujDetracciones');
        Route::get('selOperSujDetracciones', 'Tesoreria\DetraccionesMasivasController@selOperSujDetracciones');
        
        Route::get('selTipoDocDetracciones', 'Tesoreria\DetraccionesMasivasController@selTipoDocDetracciones');

        Route::group(['prefix' => 'registro', 'middleware' => 'auth:api'], function () {
            Route::post('selDetraccionesMasivas', 'Tesoreria\DetraccionesMasivasController@selDetraccionesMasivas');
            Route::post('savDetraccionesMasivas', 'Tesoreria\DetraccionesMasivasController@savDetraccionesMasivas');
            Route::get('datDetraccionesMasivas/{iDetraccAdquirId}', 'Tesoreria\DetraccionesMasivasController@datDetraccionesMasivas');
            Route::post('delDetraccionesMasivas', 'Tesoreria\DetraccionesMasivasController@delDetraccionesMasivas');
        });          

        Route::group(['prefix' => 'detalle', 'middleware' => 'auth:api'], function () {
            Route::post('selDetraccionesMasivasDetalles', 'Tesoreria\DetraccionesMasivasController@selDetraccionesMasivasDetalles');
            Route::post('savDetraccionesMasivasDetalles', 'Tesoreria\DetraccionesMasivasController@savDetraccionesMasivasDetalles');
            Route::get('datDetraccionesMasivasDetalles/{iDetraccProveedId}', 'Tesoreria\DetraccionesMasivasController@datDetraccionesMasivasDetalles');
            Route::post('delDetraccionesMasivasDetalles', 'Tesoreria\DetraccionesMasivasController@delDetraccionesMasivasDetalles');
        }); 
        
        Route::group(['prefix' => 'importacionSIAF', 'middleware' => 'auth:api'], function () {
            Route::get('selPeriodoEjecucionSIAF/{cSec_ejec}', 'Tesoreria\DetraccionesMasivasController@selPeriodoEjecucionSIAF');            
            Route::post('selExpedientesFasesDetraccion', 'Tesoreria\DetraccionesMasivasController@selExpedientesFasesDetraccion');            
            Route::post('savExpedientesFasesDetraccion', 'Tesoreria\DetraccionesMasivasController@savExpedientesFasesDetraccion');            
        });  
        
        Route::group(['prefix' => 'generacionTXT', 'middleware' => 'auth:api'], function () {
            Route::post('generateTXTSunatDepositoMasivo', 'Tesoreria\DetraccionesMasivasController@generateTXTSunatDepositoMasivo');            
            Route::post('dowTXTSunatDepositoMasivo', 'Tesoreria\DetraccionesMasivasController@dowTXTSunatDepositoMasivo');            
        });         
    });

    Route::group(['prefix' => 'comprobantesPago', 'middleware' => 'auth:api'], function () {
        Route::get('selTiposComprobantes', 'Tesoreria\ComprobantesPagoController@selTiposComprobantes');
        Route::get('selPeriodosCP', 'Tesoreria\ComprobantesPagoController@selPeriodosCP');
        Route::get('selMesesCP', 'Tesoreria\ComprobantesPagoController@selMesesCP');
        Route::get('selCriterioCP', 'Tesoreria\ComprobantesPagoController@selCriterioCP');
        

        Route::group(['prefix' => 'registroCP', 'middleware' => 'auth:api'], function () {
            Route::post('selComprobantesPago', 'Tesoreria\ComprobantesPagoController@selComprobantesPago');
            Route::get('datComprobantesPago/{iTramId}', 'Tesoreria\ComprobantesPagoController@datComprobantesPago');
            Route::post('savComprobantesPago', 'Tesoreria\ComprobantesPagoController@savComprobantesPago');
            Route::post('savComprobantesPagoMultiple', 'Tesoreria\ComprobantesPagoController@savComprobantesPagoMultiple');
            Route::post('importDatosSIAFComprobantesPago', 'Tesoreria\ComprobantesPagoController@importDatosSIAFComprobantesPago');
            Route::post('delComprobantesPago', 'Tesoreria\ComprobantesPagoController@delComprobantesPago');    
        });          

        Route::group(['prefix' => 'tiposChequeras', 'middleware' => 'auth:api'], function () {
            Route::get('selTiposChequeras', 'Tesoreria\ComprobantesPagoController@selTiposChequeras');
            Route::post('savTiposChequeras', 'Tesoreria\ComprobantesPagoController@savTiposChequeras');
            Route::get('datTiposChequeras/{iTipoChequeraId}', 'Tesoreria\ComprobantesPagoController@datTiposChequeras');
            Route::post('delTiposChequeras', 'Tesoreria\ComprobantesPagoController@delTiposChequeras');
        });
    });

    Route::group(['prefix' => 'recibosSaldos', 'middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'conceptosRecibos', 'middleware' => 'auth:api'], function () {
            Route::get('selConceptosRecibos', 'Tesoreria\RecibosSaldosController@selConceptosRecibos');
            Route::post('savConceptosRecibos', 'Tesoreria\RecibosSaldosController@savConceptosRecibos');
            Route::get('datConceptosRecibos/{iConcepRecId}', 'Tesoreria\RecibosSaldosController@datConceptosRecibos');
            Route::post('delConceptosRecibos', 'Tesoreria\RecibosSaldosController@delConceptosRecibos');
            
            Route::get('selTiposRecibos', 'Tesoreria\RecibosSaldosController@selTiposRecibos');
        });        
        Route::group(['prefix' => 'registroRS', 'middleware' => 'auth:api'], function () {
            Route::post('selRecibosSaldos', 'Tesoreria\RecibosSaldosController@selRecibosSaldos');

            Route::post('savRecibosSaldos', 'Tesoreria\RecibosSaldosController@savRecibosSaldos');
            Route::get('datRecibosSaldos/{iRecId}', 'Tesoreria\RecibosSaldosController@datRecibosSaldos');
            Route::post('delRecibosSaldos', 'Tesoreria\RecibosSaldosController@delRecibosSaldos');

            Route::get('selTiposRecibosRS', 'Tesoreria\RecibosSaldosController@selTiposRecibosRS');
            Route::get('selCriterioRS', 'Tesoreria\RecibosSaldosController@selCriterioRS');
            Route::get('selPeriodosRS', 'Tesoreria\GeneralController@selPeriodos');
            Route::get('selMesesRS', 'Tesoreria\GeneralController@selMeses');            
            Route::get('selTiposPagosRS', 'Tesoreria\GeneralController@selTiposPagos');            
            Route::get('selConceptoReciboRS', 'Tesoreria\RecibosSaldosController@selConceptoReciboRS');            
        });
        Route::group(['prefix' => 'detalleRS', 'middleware' => 'auth:api'], function () {
            Route::get('selRecibosSaldosDetalle/{iRecId}', 'Tesoreria\RecibosSaldosController@selRecibosSaldosDetalle');
            Route::post('savRecibosSaldosDetalle', 'Tesoreria\RecibosSaldosController@savRecibosSaldosDetalle');
            Route::get('datRecibosSaldosDetalle/{iRecDetId}', 'Tesoreria\RecibosSaldosController@datRecibosSaldosDetalle');
            Route::post('delRecibosSaldosDetalle', 'Tesoreria\RecibosSaldosController@delRecibosSaldosDetalle');
        });
    });
    Route::group(['prefix' => 'ingresosConsolidados', 'middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'registroIC', 'middleware' => 'auth:api'], function () {
            Route::post('selIngresosConsolidados', 'Tesoreria\IngresosConsolidadosController@selIngresosConsolidados');

            Route::post('savIngresosConsolidados', 'Tesoreria\IngresosConsolidadosController@savIngresosConsolidados');
            Route::get('datIngresosConsolidados/{iRecId}', 'Tesoreria\IngresosConsolidadosController@datIngresosConsolidados');
            Route::post('delIngresosConsolidados', 'Tesoreria\IngresosConsolidadosController@delIngresosConsolidados');

            Route::get('selTiposRecibosIC', 'Tesoreria\IngresosConsolidadosController@selTiposRecibosIC');
            Route::get('selCriterioIC', 'Tesoreria\IngresosConsolidadosController@selCriterioIC');
            Route::get('selPeriodosIC', 'Tesoreria\GeneralController@selPeriodos');
            Route::get('selMesesIC', 'Tesoreria\GeneralController@selMeses');

            Route::get('selTiposDocumentoRecibo', 'Tesoreria\IngresosConsolidadosController@selTiposDocumentoRecibo');
            Route::get('selFilialesIC', 'Tesoreria\GeneralController@selFiliales');
            Route::get('selPeriodoEjecucionSIAFIC/{cSec_ejec}', 'Tesoreria\GeneralController@selPeriodoEjecucionSIAF');            
            
        });        
    });
    Route::group(['prefix' => 'depositosAbono', 'middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'registroDA', 'middleware' => 'auth:api'], function () {
            Route::post('selDepositosAbono', 'Tesoreria\DepositosAbonoController@selDepositosAbono');
            Route::post('savDepositosAbono', 'Tesoreria\DepositosAbonoController@savDepositosAbono');
            Route::get('datDepositosAbono/{iPersCuentaId}', 'Tesoreria\DepositosAbonoController@datDepositosAbono');
            Route::post('delDepositosAbono', 'Tesoreria\DepositosAbonoController@delDepositosAbono');

            Route::get('selTiposCuenta', 'Tesoreria\DepositosAbonoController@selTiposCuenta');
            Route::get('selEstadosDeposito', 'Tesoreria\DepositosAbonoController@selEstadosDeposito');
            Route::get('generateTXTDepositos/{iDepPersId}', 'Tesoreria\DepositosAbonoController@generateTXTDeposito');            

        }); 
        Route::group(['prefix' => 'detallesDA', 'middleware' => 'auth:api'], function () {
            Route::post('selDepositosAbonoDetalles', 'Tesoreria\DepositosAbonoController@selDepositosAbonoDetalles');
            Route::post('savDepositosAbonoDetalles', 'Tesoreria\DepositosAbonoController@savDepositosAbonoDetalles');
            Route::get('datDepositosAbonoDetalles/{iDepPersAbonoId}', 'Tesoreria\DepositosAbonoController@datDepositosAbonoDetalles');
            Route::post('delDepositosAbonoDetalles', 'Tesoreria\DepositosAbonoController@delDepositosAbonoDetalles');

            Route::post('importCuentaTrabajador', 'Tesoreria\DepositosAbonoController@importCuentaTrabajador');
        }); 

        Route::group(['prefix' => 'cuentasDA', 'middleware' => 'auth:api'], function () {
            Route::post('selCuentasDA', 'Tesoreria\DepositosAbonoController@selCuentasDA');
            Route::get('selbancosDA/{iEntId}', 'Tesoreria\DepositosAbonoController@selbancosDA');
            Route::get('selMotivosCuentaDA', 'Tesoreria\DepositosAbonoController@selMotivosCuentaDA');
            Route::get('selCriterioBusquedaDA', 'Tesoreria\DepositosAbonoController@selCriterioBusquedaDA');
            
            Route::post('savCuentasDA', 'Tesoreria\DepositosAbonoController@savCuentasDA');
            Route::get('datCuentasDA/{iPersCuentaId}', 'Tesoreria\DepositosAbonoController@datCuentasDA');
            Route::post('delCuentasDA', 'Tesoreria\DepositosAbonoController@delCuentasDA');            
        });
    });
});

Route::get('rptPDF/{nameReport}/{paper}/{id?}/{ids?}', 'Tesoreria\ReportesController@rptPDF');//dommpdf
// Route::get('rptEXCEL/comprobantesPago/{nameReport}/{ids?}', 'Tesoreria\ReportesController@excelComprobantesPago');
Route::get('rptEXCEL/{nameReport}/{ids?}', 'Tesoreria\ReportesController@rptEXCEL');//laravel excel
Route::post('importEXCEL', 'Tesoreria\ReportesController@importEXCEL');

?>