<?php
Route::group([ 'prefix' => 'tre' ], function ($router) {
    Route::post('bud_especificas_det_select', 'Tre\bud_especificas_detController@bud_especificas_det_select');

    Route::post('grl_conceptos_select', 'Tre\grl_conceptosController@grl_conceptos_select');
    Route::post('grl_conceptos_update', 'Tre\grl_conceptosController@grl_conceptos_update');
    Route::post('grl_conceptos_importes_select', 'Tre\grl_conceptos_importesController@grl_conceptos_importes_select');
    Route::post('grl_conceptos_importes_update', 'Tre\grl_conceptos_importesController@grl_conceptos_importes_update');
    Route::post('grl_conceptos_requisitos_select', 'Tre\grl_conceptos_requisitosController@grl_conceptos_requisitos_select');
    Route::post('grl_conceptos_requisitos_update', 'Tre\grl_conceptos_requisitosController@grl_conceptos_requisitos_update');
    //Route::post('getReporteRequisito/{funcion}', 'Reporte\ReporteController@getReporteRequisito'); /* pdf para conceptos requisitos*/
    Route::post('grl_conceptos_importes_select', 'Tre\grl_conceptos_importesController@grl_conceptos_importes_select');
    Route::post('grl_departamentos_select', 'Tre\grl_departamentosController@grl_departamentos_select');
    Route::post('grl_dependencias_select', 'Tre\grl_dependenciasController@grl_dependencias_select');
    Route::post('grl_distritos_select', 'Tre\grl_distritosController@grl_distritos_select');
    Route::post('grl_documentos_gestion_select', 'Tre\grl_documentos_gestionController@grl_documentos_gestion_select');
    Route::post('grl_documentos_series_select', 'Tre\grl_documentos_seriesController@grl_documentos_series_select');
    Route::post('grl_documentos_series_update', 'Tre\grl_documentos_seriesController@grl_documentos_series_update');
    Route::post('grl_filiales_select', 'Tre\grl_filialesController@grl_filiales_select');
    Route::post('grl_paises_select', 'Tre\grl_paisesController@grl_paises_select');
    Route::post('grl_personas_searchAPI', 'Tre\grl_personasController@grl_personas_searchAPI');
    Route::post('grl_personas_select', 'Tre\grl_personasController@grl_personas_select');
    Route::post('grl_personas_update', 'Tre\grl_personasController@grl_personas_update');
    Route::post('grl_provincias_select', 'Tre\grl_provinciasController@grl_provincias_select');
    Route::post('grl_reportes_select', 'Tre\grl_reportesController@grl_reportes_select');
    Route::post('grl_tablas_mixtas_select', 'Tre\grl_tablas_mixtasController@grl_tablas_mixtas_select');
    Route::post('grl_tablas_mixtas_update', 'Tre\grl_tablas_mixtasController@grl_tablas_mixtas_update');

    Route::post('seg_credenciales_dependencias_select', 'Tre\seg_credenciales_dependenciasController@seg_credenciales_dependencias_select');
    Route::post('seg_credenciales_dependencias_update', 'Tre\seg_credenciales_dependenciasController@seg_credenciales_dependencias_update');
    Route::post('seg_personas_sessions_update', 'Tre\seg_personas_sessionsController@seg_personas_sessions_update');
    Route::post('seg_personas_sessions_validate', 'Tre\seg_personas_sessionsController@seg_personas_sessions_validate');
    Route::post('seg_sessions_update', 'Tre\seg_sessionsController@seg_sessions_update');
    Route::post('seg_sessions_validate', 'Tre\seg_sessionsController@seg_sessions_validate');

    Route::post('siga_bien_serv_select', 'Tre\siga_bien_servController@siga_bien_serv_select');
    Route::post('siga_pedidos_select', 'Tre\siga_pedidosController@siga_pedidos_select');
    Route::post('siga_pedidos_det_select', 'Tre\siga_pedidos_detController@siga_pedidos_det_select');
    Route::post('siga_ordenes_select', 'Tre\siga_ordenesController@siga_ordenes_select');
    Route::post('siga_ordenes_selectprov', 'Tre\siga_ordenesController@siga_ordenes_selectprov');
    Route::post('siga_ordenes_det_select', 'Tre\siga_ordenes_detController@siga_ordenes_det_select');

    Route::post('tre_adeudos_select', 'Tre\tre_adeudosController@tre_adeudos_select');
    Route::post('tre_adeudos_cab_select', 'Tre\tre_adeudos_cabController@tre_adeudos_cab_select');
    Route::post('tre_adeudos_cab_delete', 'Tre\tre_adeudos_cabController@tre_adeudos_cab_select');
    Route::post('tre_conceptos_enlaces_select', 'Tre\tre_conceptos_enlacesController@tre_conceptos_enlaces_select');
    Route::post('tre_cuentas_bancarias_select', 'Tre\tre_cuentas_bancariasController@tre_cuentas_bancarias_select');
    Route::post('tre_ingresos_annular', 'Tre\tre_ingresosController@tre_ingresos_annular');
    Route::post('tre_ingresos_delete', 'Tre\tre_ingresosController@tre_ingresos_delete');
    Route::post('tre_ingresos_select', 'Tre\tre_ingresosController@tre_ingresos_select');
    Route::post('tre_ingresos_update', 'Tre\tre_ingresosController@tre_ingresos_update');
    Route::post('tre_ingresos_det_select', 'Tre\tre_ingresos_detController@tre_ingresos_det_select');
    Route::post('tre_ingresos_especificas_det_select', 'Tre\tre_ingresos_especificas_detController@tre_ingresos_especificas_det_select');
    Route::post('tre_ingresos_especificas_det_update', 'Tre\tre_ingresos_especificas_detController@tre_ingresos_especificas_det_update');
    Route::post('tre_operaciones_select', 'Tre\tre_operacionesController@tre_operaciones_select');
    Route::post('tre_operaciones_update', 'Tre\tre_operacionesController@tre_operaciones_update');

    Route::post('ura_academicos_select', 'Tre\ura_academicosController@ura_academicos_select');
    Route::post('ura_estudiantes_select', 'Tre\ura_estudiantesController@ura_estudiantes_select');
});

Route::group([ 'prefix' => 'tre/reports' ], function ($router) {
    Route::post('grl_conceptos_requisitos_report', 'Tre\Report\grl_conceptos_requisitos_reportController@report');
    Route::post('grl_conceptos_requisitos_report_reg', 'Tre\Report\grl_conceptos_requisitos_reportREGController@report');
    Route::post('grl_conceptos_requisitos_report_regdepen', 'Tre\Report\grl_conceptos_requisitos_reportREGDEPENController@report');
    Route::post('grl_conceptos_requisitos_report_regespedet', 'Tre\Report\grl_conceptos_requisitos_reportREGESPEDETController@report');

    Route::post('tre_ingresos_report_reg', 'Tre\Report\tre_ingresos_reportREGController@report');
    Route::post('tre_ingresos_report_regconcep', 'Tre\Report\tre_ingresos_reportREGCONCEPController@report');
    Route::post('tre_ingresos_report_regconcepimpt', 'Tre\Report\tre_ingresos_reportREGCONCEPIMPTController@report');
    Route::post('tre_ingresos_report_regdet', 'Tre\Report\tre_ingresos_reportREGDETController@report');
    Route::post('tre_ingresos_report_regoper', 'Tre\Report\tre_ingresos_reportREGOPERController@report');
    Route::post('tre_ingresos_report_regpers', 'Tre\Report\tre_ingresos_reportREGPERSController@report');
    Route::post('tre_ingresos_report_regprint', 'Tre\Report\tre_ingresos_reportREGPRINTController@report');
    Route::post('tre_ingresos_report_resespedet', 'Tre\Report\tre_ingresos_reportRESESPEDETController@report');
    Route::post('tre_ingresos_report_resespedett1', 'Tre\Report\tre_ingresos_reportRESESPEDETT1Controller@report');
    Route::post('tre_ingresos_report_conconcepreq', 'Tre\Report\tre_ingresos_reportCONCONCEPREQController@report');
    Route::post('tre_ingresos_report_condepen', 'Tre\Report\tre_ingresos_reportCONDEPENController@report');
    Route::post('tre_ingresos_report_conespedet', 'Tre\Report\tre_ingresos_reportCONESPEDETController@report');
    Route::post('tre_ingresos_report_conespedetf01', 'Tre\Report\tre_ingresos_reportCONESPEDETF01Controller@report');
    Route::post('tre_ingresos_report_conespedetf02', 'Tre\Report\tre_ingresos_reportCONESPEDETF02Controller@report');
    Route::post('tre_ingresos_report_conespedetf03', 'Tre\Report\tre_ingresos_reportCONESPEDETF03Controller@report');
    
    Route::post('tre_ingresos_report_id', 'Tre\Report\tre_ingresos_reportIDController@report');
    Route::post('tre_ingresos_report_rrd', 'Tre\Report\tre_ingresos_reportRRDController@report');
    Route::post('tre_ingresos_report_rrdc', 'Tre\Report\tre_ingresos_reportRRDCController@report');
    Route::post('tre_ingresos_report_rrccr', 'Tre\Report\tre_ingresos_reportRRCCRController@report');
});