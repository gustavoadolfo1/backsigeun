<?php
Route::group(['middleware' => 'auth.role:ADMIN,BIBLIOTECARIO,AUXILIAR', 'prefix' => 'biblioteca'], function () {
    Route::any('tp/{id?}', 'Biblioteca\MantenimientoController@tipoprestamo');
    Route::any('tb/{id?}', 'Biblioteca\MantenimientoController@tipobien');
    Route::any('ep/{id?}', 'Biblioteca\MantenimientoController@estadoprestamo');
    Route::any('eb/{id?}', 'Biblioteca\MantenimientoController@estadobien');
    Route::any('autores/{id?}', 'Biblioteca\MantenimientoController@autores');
    Route::any('bienes/{id?}', 'Biblioteca\MantenimientoController@bienes');
    Route::any('reserva/{id?}', 'Biblioteca\BibliotecaController@reserva');
    Route::any('devolver/{id?}', 'Biblioteca\BibliotecaController@devolver');
    Route::any('detalles/{id?}', 'Biblioteca\BibliotecaController@detalles');
    Route::any('aceptarsolo/{id?}', 'Biblioteca\BibliotecaController@aceptarSolo');
    Route::any('prestamo/{id?}', 'Biblioteca\BibliotecaController@prestamo');
    Route::any('apoyo/{id?}', 'Biblioteca\BibliotecaController@apoyo');
    Route::any('apoyodevolver/{id?}', 'Biblioteca\BibliotecaController@apoyoDevolver');
    Route::any('info/{id?}', 'Biblioteca\DatosController@info');
    Route::any('sanciones/{id?}', 'Biblioteca\BibliotecaController@sanciones');
    Route::any('renovacion/{id?}', 'Biblioteca\BibliotecaController@renovacion');
    Route::any('cambiar/{id?}', 'Biblioteca\BibliotecaController@cambiarTP');
    Route::any('dp/{id?}', 'Biblioteca\MantenimientoController@diasprestamo');
    Route::any('localex/{id?}', 'Biblioteca\MantenimientoController@locales');
    Route::any('materiales/{id?}', 'Biblioteca\MantenimientoController@material');
    Route::any('matdetalle/{id?}', 'Biblioteca\MantenimientoController@materialDetalle');
    Route::any('editoriales/{id?}', 'Biblioteca\MantenimientoController@editoriales');

    Route::any('ubicacion/{id?}', 'Biblioteca\MantenimientoController@ubicacion');

    Route::any(
        'infohistory/{id?}',
        'Biblioteca\BibliotecaController@infoHistory'
    );

    Route::any('historylocal/{id?}', 'Biblioteca\BibliotecaController@Historylocal');
    Route::any('rpthistory/{id?}', 'Biblioteca\BibliotecaController@Historyuser');

    Route::any('estante/{id?}', 'Biblioteca\BibliotecaController@ubicaestante');
    Route::any('lado/{id?}', 'Biblioteca\BibliotecaController@ubicalado');
    Route::any('fila/{id?}', 'Biblioteca\BibliotecaController@ubicafilas');
    Route::any('col/{id?}', 'Biblioteca\BibliotecaController@bienUbica');
});

Route::group(['middleware' => 'auth.role:ESTANDAR',  'prefix' => 'biblioteca'], function () {
    Route::any('eprestamo/{id?}', 'Biblioteca\EstandarController@seleccionar');
    Route::any('cesta/{id?}', 'Biblioteca\EstandarController@cesta');
    Route::any('historial/{id?}', 'Biblioteca\EstandarController@historial');
    Route::any('estado/{id?}', 'Biblioteca\EstandarController@estado');
    Route::any('sancionuser/{id?}', 'Biblioteca\EstandarController@muestraSanciones');
    Route::any('laptop/{id?}', 'Biblioteca\EstandarController@apoyo');
});

Route::group(['middleware' => 'auth.role:ESTANDAR,ADMIN,BIBLIOTECARIO,AUXILIAR',  'prefix' => 'biblioteca'], function () {
    Route::any('user/{id?}', 'Biblioteca\DatosController@userdata');
    Route::any('solo/{id?}', 'Biblioteca\DatosController@datasolicitante');

    Route::any('resumen/{id?}', 'Biblioteca\BibliotecaController@resumen');
    Route::any('solicitud/{id?}', 'Biblioteca\BibliotecaController@solicitudes');
    Route::any('cancelar/{id?}', 'Biblioteca\EstandarController@cancelarReserva');
    Route::any('lep/{id?}', 'Biblioteca\BibliotecaController@listep');

    Route::any('infohistory/{id?}', 'Biblioteca\BibliotecaController@infoHistory');
    //Route::any('solo/{id?}', 'Biblioteca\BibliotecaController@reserva');

    /*  Route::any('prestamo/{id?}', function ($id = '0') {
        return $id;
    },'Biblioteca\BibliotecaController@prestamo');
    */
    // Route::any('muestra/{id?}', 'Biblioteca\BibliotecaController@muestra');

});

Route::group(['prefix' => 'biblioteca'], function () {
    Route::any('catalogos/{id?}', 'Biblioteca\MainController@catalogo');
    Route::any('catalogohome/{id?}', 'Biblioteca\MainController@catalogohome');

    Route::any('ranking/{id?}', 'Biblioteca\MainController@ranking');
    Route::any('local/{id?}', 'Biblioteca\MainController@localidades');
    Route::any('locales/{id?}', 'Biblioteca\MainController@selectLocal');
    Route::any('search/{id?}', 'Biblioteca\MainController@busqueda');
    Route::any('fil/{id?}', 'Biblioteca\MainController@listFilial');
    Route::any('ep/{id?}', 'Biblioteca\MainController@listEscuelas');
    Route::any('ma/{id?}', 'Biblioteca\MainController@listMaterial');
    Route::any('config/{id?}', 'Biblioteca\MantenimientoController@config');

    Route::post('upload', 'Biblioteca\UploadController@image');



    //Route::any('muestra/{id?}', 'Biblioteca\BibliotecaController@muestra');
    //Route::any('cesta/{id?}', 'Biblioteca\MainController@seleciona');

});
