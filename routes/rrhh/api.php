<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'rrhh', 'namespace' => 'RecursosHumanos'], function () {
    $subModulos = ['Remuneraciones', 'Contratos'];

    foreach ($subModulos as $subModulo) {
        Route::group(['prefix' => strtolower($subModulo)], function () use ($subModulo) {
            Route::any('dataexistenteAnonimo/{tipo}', $subModulo . 'Controller@anonimo');
            Route::group(['middleware' => 'jwt.auth'], function () use ($subModulo) {
                Route::any('dataexistente/{tipo}/{subtipo?}', $subModulo . 'Controller@getData');
                Route::any('guardar/{tipo}/{subtipo?}', $subModulo . 'Controller@setData');
            });
        });
    }
    /*

    Route::group(['prefix' => 'remuneraciones'], function () {
        Route::any('dataexistenteAnonimo/{tipo}', 'RemuneracionesController@anonimo');

        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::any('dataexistente/{tipo}/{subtipo?}', 'RemuneracionesController@getData');
            Route::any('guardar/{tipo}/{subtipo?}', 'RemuneracionesController@setData');
        });
    });

    Route::group(['prefix' => 'contratos'], function () {
        Route::any('dataexistenteAnonimo/{tipo}', 'RecursosHumanos\ContratosController@anonimo');

        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::any('dataexistente/{tipo}/{subtipo?}', 'RecursosHumanos\ContratosController@getData');
            Route::any('guardar/{tipo}/{subtipo?}', 'RecursosHumanos\ContratosController@setData');
        });
    });
    */
});


Route::any('scraping', function () {

    $client = new GuzzleHttp\Client();
    $jar = new \GuzzleHttp\Cookie\CookieJar();
    $response =$client->request('GET', 'https://www.sbs.gob.pe/app/spp/empleadores/comisiones_spp/Paginas/comision_prima.aspx', ['cookies' => $jar]);

    //dd($response->getBody()->getContents());
    $Dt = new DiDom\Document($response->getBody()->getContents());
    $elem = $Dt->first("#__VIEWSTATE");
    dump($elem->getAttribute('value'));
    $elem2 = $Dt->first("#__VIEWSTATEGENERATOR");
    dump($elem2->getAttribute('value'));
    $elem3 = $Dt->first("#__EVENTVALIDATION");
    dump($elem3->getAttribute('value'));

    // $perido = cboPeriodo
    // btn =

    $response2 = $client->request('POST', 'https://www.sbs.gob.pe/app/spp/empleadores/comisiones_spp/Paginas/comision_prima.aspx', [
        'form_params' => [
            '__VIEWSTATE' => $elem->getAttribute('value'),
            '__VIEWSTATEGENERATOR' => $elem2->getAttribute('value'),
            '__EVENTVALIDATION' => $elem3->getAttribute('value'),
            'cboPeriodo' => '2008-01',
            'btnConsultar' => 'Buscar+Datos'
        ]
    ]);
    //dd($response2->getBody()->getContents());
    $dataDiDom2 = new DiDom\Document($response2->getBody()->getContents());
    $elementos = $dataDiDom2->find('.JER_filaContenido');

    $resultados = collect();

    foreach ($elementos as $elem) {
        $afp = (object)[];
        $celdas = $elem->find('td');
        // dump(count($celdas));
        $afp->nombre = trim($celdas[0]->text());
        $afp->comision_fija = trim($celdas[1]->text());
        $afp->comision_flujo = trim($celdas[2]->text());
        if (count($celdas) == 6){
            $afp->comision_mixta_flujo = null;
            $afp->comision_mixta_anual_saldo = null;

            $afp->prima_seguros = trim($celdas[3]->text());
            $afp->obligatorio = trim($celdas[4]->text());
            $afp->max_asegurable = trim($celdas[5]->text());
        }
        if (count($celdas) == 8){
            $afp->comision_mixta_flujo = trim($celdas[3]->text());
            $afp->comision_mixta_anual_saldo = trim($celdas[4]->text());
            $afp->prima_seguros = trim($celdas[5]->text());
            $afp->obligatorio = trim($celdas[6]->text());
            $afp->max_asegurable = trim($celdas[7]->text());
        }
        $resultados->push($afp);
    }
    dd($resultados);
});
