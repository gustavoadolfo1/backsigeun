<?php
use Illuminate\Http\Request;
//Route::middleware('api')->get('locales', 'Patrimonio\Locales@getResult');
Route::group(['middleware' => 'api','prefix' => 'pat'],function($router){//$skip,$top,$inlinecount,$format
         Route::post('locales/combo', 'Patrimonio\Locales@getCombo');   
         Route::post('locales/result', 'Patrimonio\Locales@getResult');
         Route::post('locales', 'Patrimonio\Locales@guardarLocal');
         Route::put('locales/{id}', 'Patrimonio\Locales@modificarLocal');
         Route::delete('locales/{id}', 'Patrimonio\Locales@eliminarLocal');

         Route::post('areas/result', 'Patrimonio\Areas@getResult');
         Route::post('areas/combo', 'Patrimonio\Areas@getCombo');
         Route::post('areas', 'Patrimonio\Areas@guardar');
         Route::put('areas/{id}', 'Patrimonio\Areas@modificar');
         Route::delete('areas/{id}', 'Patrimonio\Areas@eliminar');



         Route::post('oficinas/result', 'Patrimonio\Oficinas@getResult');
         Route::post('oficinas', 'Patrimonio\Oficinas@guardar');
         Route::put('oficinas/{id}', 'Patrimonio\Oficinas@modificar');
         Route::delete('oficinas/{id}', 'Patrimonio\Oficinas@eliminar');


         //-----CATALOGO SPN
         Route::post('grupos/result', 'Patrimonio\GruposGenericoSBN@getResult');
          Route::post('grupos/combo', 'Patrimonio\GruposGenericoSBN@getCombo');
         Route::post('grupos', 'Patrimonio\GruposGenericoSBN@guardar');
         Route::put('grupos/{id}', 'Patrimonio\GruposGenericoSBN@modificar');
         Route::delete('grupos/{id}', 'Patrimonio\GruposGenericoSBN@eliminar');


         Route::post('clases/result', 'Patrimonio\ClasesGenericoSBN@getResult');
         Route::post('clases/combo', 'Patrimonio\ClasesGenericoSBN@getCombo');
         Route::post('clases', 'Patrimonio\ClasesGenericoSBN@guardar');
         Route::put('clases/{id}', 'Patrimonio\ClasesGenericoSBN@modificar');
         Route::delete('clases/{id}', 'Patrimonio\ClasesGenericoSBN@eliminar');


         Route::post('grupos_clases/result', 'Patrimonio\GruposClasesGenericoSBN@getResult');
          Route::post('grupos_clases/combo', 'Patrimonio\GruposClasesGenericoSBN@getCombo');
         Route::post('grupos_clases', 'Patrimonio\GruposClasesGenericoSBN@guardar');
         Route::put('grupos_clases/{id}', 'Patrimonio\GruposClasesGenericoSBN@modificar');
         Route::delete('grupos_clases/{id}', 'Patrimonio\GruposClasesGenericoSBN@eliminar');



         Route::post('catalogoSBN/result', 'Patrimonio\CatalogoSBN@getResult');
         Route::post('catalogoSBN', 'Patrimonio\CatalogoSBN@guardar');
         Route::put('catalogoSBN/{id}', 'Patrimonio\CatalogoSBN@modificar');
         Route::delete('catalogoSBN/{id}', 'Patrimonio\CatalogoSBN@eliminar');


         //tablas generales


    
         Route::post('marcas/result', 'Patrimonio\Marcas@getResult');
         Route::post('marcas/combo', 'Patrimonio\Marcas@getCombo');
         Route::post('marcas', 'Patrimonio\Marcas@guardar');
         Route::put('marcas/{id}', 'Patrimonio\Marcas@modificar');
         Route::delete('marcas/{id}', 'Patrimonio\Marcas@eliminar');

         Route::post('modelos/result', 'Patrimonio\Modelos@getResult');
         Route::post('modelos/combo', 'Patrimonio\Modelos@getCombo');
         Route::post('modelos', 'Patrimonio\Modelos@guardar');
         Route::put('modelos/{id}', 'Patrimonio\Modelos@modificar');
         Route::delete('modelos/{id}', 'Patrimonio\Modelos@eliminar');


         Route::post('tipos/result', 'Patrimonio\Tipos@getResult');
         Route::post('tipos/combo', 'Patrimonio\Tipos@getCombo');
         Route::post('tipos', 'Patrimonio\Tipos@guardar');
         Route::put('tipos/{id}', 'Patrimonio\Tipos@modificar');
         Route::delete('tipos/{id}', 'Patrimonio\Tipos@eliminar');





         Route::post('documentos/result', 'Patrimonio\Documentos@getResult');
         Route::post('documentos/combo', 'Patrimonio\Documentos@getCombo');
         Route::post('documentos', 'Patrimonio\Documentos@guardar');
         Route::put('documentos/{id}', 'Patrimonio\Documentos@modificar');
         Route::delete('documentos/{id}', 'Patrimonio\Documentos@eliminar');
         Route::get('documentos/existe/{id}', 'Patrimonio\Documentos@ExisteOC');


         Route::post('calogosNoPatrimoniales/result', 'Patrimonio\CalogosNoPatrimoniales@getResult');
         Route::post('calogosNoPatrimoniales/combo', 'Patrimonio\CalogosNoPatrimoniales@getCombo');
         Route::post('calogosNoPatrimoniales', 'Patrimonio\CalogosNoPatrimoniales@guardar');
         Route::put('calogosNoPatrimoniales/{id}', 'Patrimonio\CalogosNoPatrimoniales@modificar');
         Route::delete('calogosNoPatrimoniales/{id}', 'Patrimonio\CalogosNoPatrimoniales@eliminar');


         Route::post('planes/result', 'Patrimonio\Planes@getResult');
         Route::get('planes/combo', 'Patrimonio\Planes@getCombo');
         Route::post('planes', 'Patrimonio\Planes@guardar');
         Route::put('planes/{id}', 'Patrimonio\Planes@modificar');
         Route::delete('planes/{id}', 'Patrimonio\Planes@eliminar');   

        // Route::post('subcuenta/result', 'Patrimonio\Planes@getResult');
         Route::get('subcuenta/combo', 'Patrimonio\SubCuenta@getCombo');
      //   Route::post('subcuenta', 'Patrimonio\Planes@guardar');
        // Route::put('subcuenta/{id}', 'Patrimonio\Planes@modificar');
       //  Route::delete('subcuenta/{id}', 'Patrimonio\Planes@eliminar');   


         Route::post('bienes/result', 'Patrimonio\Bienes@getResult');
         Route::post('bienes/combo', 'Patrimonio\Bienes@getCombo');
         Route::post('bienes', 'Patrimonio\Bienes@guardar');
         Route::put('bienes/{id}', 'Patrimonio\Bienes@modificar');
         Route::delete('bienes/{id}', 'Patrimonio\Bienes@eliminar');
         Route::put('bienes/baja/{id}', 'Patrimonio\Bienes@baja');  

         Route::post('situacion/combo', 'Patrimonio\SituacionBien@getCombo');
         Route::post('situacion', 'Patrimonio\SituacionBien@guardar');   




         Route::post('formas_adquisicion/combo', 'Patrimonio\Formas_adquisicion@getCombo'); 

         Route::post('estados_bien/combo', 'Patrimonio\Estados_bien@getCombo'); 
         Route::put('estados_bien/combo2', 'Patrimonio\Estados_bien@getCombo2'); 
         Route::post('colores/combo', 'Patrimonio\Colores@getCombo'); 




         Route::post('documentos_tramite/result', 'Patrimonio\DocumentosTramite@getResult');
         Route::post('oc/result', 'Patrimonio\Oc@getResult');
         Route::post('oc_item/result', 'Patrimonio\OcItem@getResult');
         Route::get('oc_item/comboCuentas/{_anio}/{clasificador}/{GRUPO_BIEN}/{CLASE_BIEN}/{FAMILIA_BIEN}', 'Patrimonio\OcItem@comboCuentas');

         Route::post('cargos/combo', 'Patrimonio\Cargos@getCombo');
         Route::get('anios/combo', 'Patrimonio\Anios@getCombo');

         Route::post('empleados/result', 'Patrimonio\Empleado@getResult');
         Route::post('empleados/combo', 'Patrimonio\Empleado@getCombo');
         Route::post('empleados', 'Patrimonio\Empleado@guardar');
         Route::put('empleados/{id}', 'Patrimonio\Empleado@modificar');
         Route::delete('empleados/{id}', 'Patrimonio\Empleado@eliminar');


         Route::post('centro_costo_pat/result', 'Patrimonio\CentroCostoPat@getResult');
         Route::post('centro_costo_pat/combo', 'Patrimonio\CentroCostoPat@getCombo');
        
         Route::post('centro_costo_pat', 'Patrimonio\CentroCostoPat@guardar');
         Route::put('centro_costo_pat/{id}', 'Patrimonio\CentroCostoPat@modificar');
         Route::delete('centro_costo_pat/{id}', 'Patrimonio\CentroCostoPat@eliminar');


           Route::post('centro_costo_empleado/result', 'Patrimonio\CentroCostoEmpleado@getResult');
         Route::post('centro_costo_empleado/combo', 'Patrimonio\CentroCostoEmpleado@getCombo');
         Route::post('centro_costo_empleado', 'Patrimonio\CentroCostoEmpleado@guardar');
         Route::put('centro_costo_empleado/{id}', 'Patrimonio\CentroCostoEmpleado@modificar');
         Route::delete('centro_costo_empleado/{id}', 'Patrimonio\CentroCostoEmpleado@eliminar');



        // Route::get('centro_costo_empleado/combo/{id}', 'Patrimonio\CentroCostoPat@getComboAndroid');



         Route::post('centro_costo/combo', 'Patrimonio\CentroCosto@getCombo');//corregir es get
       
         Route::post('tipo_desplazamiento/combo', 'Patrimonio\TipoDesplazamiento@getCombo'); 
         Route::put('desplazamiento/result', 'Patrimonio\DesplazamientoBienes@getResult');
         Route::post('desplazamiento/combo', 'Patrimonio\DesplazamientoBienes@getCombo');
         Route::post('desplazamiento', 'Patrimonio\DesplazamientoBienes@guardar');
         Route::put('desplazamiento/{id}', 'Patrimonio\DesplazamientoBienes@modificar');
         Route::delete('desplazamiento/{id}', 'Patrimonio\DesplazamientoBienes@eliminar');

        Route::post('desplazamiento/dataAsignacionBienes/{iDocAdqId}', 'Patrimonio\DesplazamientoBienes@dataAsignacionBienes');
        Route::get('desplazamiento/getResultRow/{iDocAdqId}', 'Patrimonio\DesplazamientoBienes@getResultRow');






         Route::post('empleado_bienes/result', 'Patrimonio\EmpleadoBienes@getResult');
         Route::post('empleado_bienes/data/{iCentroCostoEmpleadoId}', 'Patrimonio\EmpleadoBienes@getData');
         Route::post('empleado_bienes', 'Patrimonio\EmpleadoBienes@guardar');
         Route::put('empleado_bienes/{id}', 'Patrimonio\EmpleadoBienes@modificar');
         Route::delete('empleado_bienes/{id}', 'Patrimonio\EmpleadoBienes@eliminar');



         Route::post('doc_verificacion/result', 'Patrimonio\DocVerificacionBienes@getResult');
         Route::post('doc_verificacion/data/{iCentroCostoEmpleadoId}', 'Patrimonio\DocVerificacionBienes@getData');
         Route::post('doc_verificacion', 'Patrimonio\DocVerificacionBienes@guardar');
         Route::put('doc_verificacion/{id}', 'Patrimonio\DocVerificacionBienes@modificar');
         Route::delete('doc_verificacion/{id}', 'Patrimonio\DocVerificacionBienes@eliminar');
         Route::get('doc_verificacion/combo', 'Patrimonio\DocVerificacionBienes@getCombo');



         Route::post('android/login', 'Patrimonio\LoginAndroid@login');
         Route::get('subDependencia/combo/{id}', 'Patrimonio\CentroCostoPat@getComboAndroid');

         Route::get('centro_costo_empleado/combo_x_dependencia/{id}', 'Patrimonio\CentroCostoEmpleado@getCombo_x_Dependencia_Android');
         Route::get('centro_costo_empleado/combo_x_subdependencia/{id}/{idsub}', 'Patrimonio\CentroCostoEmpleado@getCombo_x_Subdependencia_Android');
         Route::get('dependencia/combo', 'Patrimonio\CentroCosto@getComboAndroid');

         Route::get('bienes/shear_x_codigo/{cBienCodigo}/{idCentroCostoEmpleado}', 'Patrimonio\Bienes@shear_x_codigoAndroid');
         Route::get('estados_bien/comboAndroid', 'Patrimonio\Estados_bien@getComboAndroid'); 



         Route::post('verificar/guardar', 'Patrimonio\Verificar@guardar'); 
         Route::get('verificar/resultAdroid/{iYearId}/{iDepenId}/{idCentroCostoEmpleado}', 'Patrimonio\Verificar@getResultAndroid');
         Route::get('verificar/resultBienesDesverificadosAndroid/{iYearId}/{iDepenId}/{idCentroCostoEmpleado}', 'Patrimonio\Verificar@getResultBienesDesverificadosAndroid');

         //por sub dependencia
          Route::get('verificar/result_x_sub_depemdemcoa_Adroid/{iYearId}/{iDepenId}/{iCentroCostoId}/{idCentroCostoEmpleado}', 'Patrimonio\Verificar@getResult_x_sub_depemdemcoa_Android');
          Route::get('verificar/result_x_sub_depemdemcoa_BienesDesverificadosAndroid/{iYearId}/{iDepenId}/{iCentroCostoId}/{idCentroCostoEmpleado}', 'Patrimonio\Verificar@getResult_x_sub_depemdemcoa_BienesDesverificadosAndroid');


         Route::post('verificar/result', 'Patrimonio\Verificar@getResult');
    /*********/
    
    Route::post('reportes/ubicacionPorDependencia/{iDepenId}', 'Patrimonio\Reportes@ubicacionPorDependencia');
    Route::post('reportes/ubicacionPorDepenSub/{iDepenId}/{iCentroCostoId}', 'Patrimonio\Reportes@ubicacionPorDepenSub');
    Route::get('reportes/ubicacionPorDepenSubEmp/{idCentroCostoEmpleado}   ', 'Patrimonio\Reportes@ubicacionPorDepenSubEmp');
    Route::post('reportes/getDataComboubicacionEmpleado/{iDepenId}/{iCentroCostoId}', 'Patrimonio\Reportes@getDataComboubicacionEmpleado');
	Route::get('reportes/ubicacionEmpleado', 'Patrimonio\Reportes@ubicacionEmpleado');
	Route::get('reportes/getDataCentroCosto/{iEmpleadoId}', 'Patrimonio\Reportes@getDataCentroCosto');
    Route::get('reportes/getBienNoDepreciable', 'Patrimonio\Reportes@getBienNoDepreciable');
    Route::get('reportes/getComboCuentaContable', 'Patrimonio\Reportes@getComboCuentaContable');
    Route::get('reportes/getComboCuentaMayor', 'Patrimonio\Reportes@getComboCuentaMayor');
    Route::get('reportes/getBienCuentaMayor/{iCuentaContable}', 'Patrimonio\Reportes@getBienCuentaMayor');


    /**********/


       //  Route::patch('calendarioacademico/{id}', 'Api\Ura\CalendarioAcademicoController@update')->name('api.calendario.update');

});
