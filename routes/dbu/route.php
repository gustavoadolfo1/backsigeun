<?php
use Illuminate\Http\Request;

Route::group(['middleware' => 'api', 'prefix' => 'dbu' ], function ($router){
	Route::group(['prefix' => 'fichasocioeconomica'], function($router){
		Route::get('crearficha/{iEstudId}', 'DBU\DBUFichaController@CrearFichaSocioeconomica');
		Route::get('crearfamiliar/{iEstudId}/{iFamiliaId}', 'DBU\DBUFichaController@CrearFamiliarEstudiante');
		Route::get('leerfichasocioeconomica/{iEstudId}', 'DBU\DBUFichaController@LeerFichaSocioeconomica');
		Route::get('listafamiliaresestudiante/{iFamiliaId}', 'DBU\DBUFichaController@ListaFamiliaresEstudiante');
		Route::post('fichaeditardatosgenerales', 'DBU\DBUFichaController@FichaEditarDatosGenerales');
		Route::post('fichaeditaraspectofamiliar', 'DBU\DBUFichaController@FichaEditarAspectoFamiliar');
		Route::post('fichaeditaraspectoeconomico', 'DBU\DBUFichaController@FichaEditarAspectoEconomico');
		Route::post('fichaeditaraspectovivienda', 'DBU\DBUFichaController@FichaEditarAspectoVivienda');
		Route::post('fichaeditaralimentacion', 'DBU\DBUFichaController@FichaEditarAlimentacion');
		Route::post('fichaeditardiscapacidad', 'DBU\DBUFichaController@FichaEditarDiscapacidad');
		Route::post('fichaeditarsalud', 'DBU\DBUFichaController@FichaEditarSalud');
		Route::post('fichaeditarotros', 'DBU\DBUFichaController@FichaEditarOtros');
		Route::post('fichaeditarfamiliarestudiante', 'DBU\DBUFichaController@FichaEditarFamiliarEstudiante');
		Route::get('eliminarfamiliarlista/{iEstudId}/{iParienteId}/{iFamiliaId}', 'DBU\DBUFichaController@EliminarFamiliarListaFicha');
		Route::get('getid/{cEstudCodUniv}', 'DBU\DBUFichaController@ObtenerIDEstudiante');
		Route::get('getcarrera/{iCarreraId}', 'DBU\DBUFichaController@ObtenerNombreCarrera');
		Route::get('getpaises/', 'DBU\DBUFichaController@ListaPaises');
		Route::get('getdepartamentos/', 'DBU\DBUFichaController@ListaDepartamentosPeru');
		Route::get('getprovincias/{iDptoId}', 'DBU\DBUFichaController@ListaProvinciasxDepartamento');
		Route::get('getdistritos/{iPrvnId}', 'DBU\DBUFichaController@ListaDistritosxProvincia');
		Route::get('getficha','DBU\DBUFichaController@descargaFicha');
		Route::get('getfichadatos/{iEstudId}','DBU\DBUFichaController@descargaFichaDatos');
		Route::get('leerfichasocioeconomicapredata/{iEstudId}', 'DBU\DBUFichaController@LeerFichaSocioeconomicaPreData');
		Route::get('getsemestres','DBU\DBUFichaController@GetSemestres');
		Route::post('obternerDatosFS','DBU\DBUFichaController@obternerDatosFS');
		Route::post('getfichas','DBU\DBUFichaController@GetFichas');
		Route::get('leerfichasocioeconomicaxciclo/{iEstudId}/{iControlCicloAcad}', 'DBU\DBUFichaController@LeerFichaSocioeconomicaXciclo');
		Route::get('verficha/{iEstudId}', 'DBU\DBUFichaController@verficha');
		
	});
});

Route::group(['middleware' => 'api', 'prefix' => 'dbu' ], function ($router){
	Route::group(['prefix' => 'gestion'], function($router){
		Route::get('obtenerDatosEstudiante/{codigo}'	, 'DBU\ComedorUniversitarioController@obtenerDatosEstudiante');
		Route::post('agregarBecario'					, 'DBU\ComedorUniversitarioController@agregarBecario');
		Route::post('buscar'							, 'DBU\ComedorUniversitarioController@buscar');
		Route::get('buscarEstudiantesT'					, 'DBU\ComedorUniversitarioController@buscarEstudiantesT');
		Route::get('obtenerDatosFichasVI'				, 'DBU\ComedorUniversitarioController@obtenerDatosFichasVI');
		Route::get('obtenerTipoServicios'				, 'DBU\ComedorUniversitarioController@obtenerTipoServicios');
		Route::get('buscarHistorialEstudiante'			, 'DBU\ComedorUniversitarioController@buscar');
		Route::post('guardarfichaevaluacion'			, 'DBU\ComedorUniversitarioController@GuardarFichaEvaluacion');
		Route::post('revisarfichaevaluacion'			, 'DBU\ComedorUniversitarioController@ValidarFichaEvaluacion');
		Route::get('buscarEstudiantesSinBeca'			, 'DBU\ComedorUniversitarioController@buscarEstudiantesSinBeca');
		Route::post('guardarfichavisitadomicilio'		, 'DBU\ComedorUniversitarioController@RegistrarVisitaDomiciliaria');
		Route::get('leerfichaevaluacion/{iPersId}'		, 'DBU\ComedorUniversitarioController@LeerDatosFichaEvaluacion');
		Route::post('controlVerificacion/'				, 'DBU\ComedorUniversitarioController@controlVerificacion');
		Route::post('controlVerificacionDocente/'		, 'DBU\ComedorUniversitarioController@controlVerificacionDocente');
		Route::post('getreporte/'						, 'DBU\ComedorUniversitarioController@getreporte');
		Route::get('listareportes/'						, 'DBU\ComedorUniversitarioController@listareportes');
		Route::get('Atenciones'							, 'DBU\ComedorUniversitarioController@Atenciones');
		Route::post('Configuracion/'					, 'DBU\ComedorUniversitarioController@Configuracion');
		Route::post('Solicitud/'						, 'DBU\ComedorUniversitarioController@Solicitud');
		Route::get('SolicitudRanking'						, 'DBU\ComedorUniversitarioController@SolicitudRanking');
		Route::post('SolicitudBeca/'						, 'DBU\ComedorUniversitarioController@SolicitudBeca');
		
		Route::get('SolicitudBuscar/{iEstudId}'			, 'DBU\ComedorUniversitarioController@SolicitudBuscar');
		Route::get('getSemanasComedor/'					, 'DBU\ComedorUniversitarioController@getSemanasComedor');
		Route::get('getMenu/'							, 'DBU\ComedorUniversitarioController@getMenu');
		Route::get('crudComunicadoDbu'					, 'DBU\ComedorUniversitarioController@crudComunicadoDbu');
		Route::get('buscarPersona/{dni}', 'DBU\ComedorUniversitarioController@buscarPersona');
		Route::post('buscarPostulante'					, 'DBU\ComedorUniversitarioController@buscarPostulante');
		Route::get('obtenerSemestreComedor/'							, 'DBU\ComedorUniversitarioController@obtenerSemestreComedor');
		
	});

	Route::group(['prefix' => 'salud'], function($router){
		Route::get('Medicamento', 				'DBU\SaludController@Medicamento');
		Route::get('Presentacion', 				'DBU\SaludController@Presentacion');
		Route::get('CitasProgramadas', 			'DBU\SaludController@CitasProgramadas');
		Route::get('Medico', 					'DBU\SaludController@Medico');
		Route::get('Paciente', 					'DBU\SaludController@Paciente');
		Route::get('Test', 						'DBU\SaludController@Test');
		Route::get('Cita', 						'DBU\SaludController@Cita');
		Route::get('Resumen', 					'DBU\SaludController@Resumen');
		Route::get('Odontologo', 				'DBU\SaludController@Odontologo');
		Route::get('Psicologo', 				'DBU\SaludController@Psicologo');
		Route::get('Enfermedad',				'DBU\SaludController@Enfermedad');		
		Route::get('RecetaMedica',				'DBU\SaludController@RecetaMedica');		
		Route::get('MedicamentoPorNombre',		'DBU\SaludController@MedicamentoPorNombre');
		Route::get('mantenimiento_consultorio',	'DBU\SaludController@mantenimiento_consultorio');
		Route::get('horario',					'DBU\SaludController@horario');
		Route::post('horario_medico/',			'DBU\SaludController@horario_medico');	
		Route::get('Presentacion',				'DBU\SaludController@Presentacion');	
		Route::post('Campana/',					'DBU\SaludController@campana');		
	});



});

Route::group(['prefix' => 'dbu/gestion/descargas'], function($router){
	
	Route::get('descargaFichaEvaluacion/', 'DBU\ComedorUniversitarioController@ImprimirFichaE');
	Route::get('descargaAsistenciaPdf/{a}', 'DBU\ComedorUniversitarioController@descargaAsistenciaPdf');
	Route::get('ReporteFichaSocioeconomica/', 'DBU\ComedorUniversitarioController@ReporteFichaSocioeconomica');
	Route::get('descargaHCPdf/', 'DBU\SaludController@descargaHCPdf');
	Route::get('descargaPdfFecha/{a}/{b}', 'DBU\SaludController@descargaPdfFecha');
	Route::get('descargaRecetaPdf/', 'DBU\SaludController@descargaRecetaPdf');
	Route::get('ReportePostulante/', 'DBU\ComedorUniversitarioController@ReportePostulante');
	Route::get('DescargarReporteDetalladoExcel/', 'DBU\ComedorUniversitarioController@DescargarReporteDetalladoExcel');
	Route::get('DescargarFichaSocioeconomica/{a}', 'DBU\ComedorUniversitarioController@DescargarFichaSocioeconomica');
});

Route::group(['middleware' => 'api', 'prefix' => 'dbu' ], function ($router){
	Route::group(['prefix' => 'comedor'], function($router){
		Route::get('obtenerListaAsistenciaFecha/{fecha}', 'DBU\ComedorUniversitarioController@obtenerListaAsistenciaFecha');
		
	});
});
?>