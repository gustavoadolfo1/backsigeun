<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/intranet', function () {
    return view('intranet');
});
Route::get('seguimiento', function () {
    return redirect('modulos/modtramite/e/seguimiento');
});

Route::get('reporteMatriculados/{carreraId}', 'Ura\GeneralController@reporteMatriculados');

Route::get('ast', 'docente\NotificationController@preunion');

//Route::get('descargaestudiante/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@exportlistEstudiantesXls');

//Route::get('descargahorario/{a}/{b}', 'Docente\DocenteController@exportHorarioDocenteXls');

Route::get('descargaAsistenciaExcel/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@exportlistAsistenciaExcel');

Route::get('descargaAsistenciaPdf/{a}/{b}/{c}/{d}/{e}/{f}/{g}', 'Docente\DocenteController@descargaestudiante');

Route::get('storage/{carpeta}/{archivo}', function ($carpeta, $archivo) {
    $path = storage_path("app/{$carpeta}/{$archivo}");

    //return $path;
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::get('storage/{carpeta}/{carpeta1}/{archivo}', function ($carpeta, $carpeta1, $archivo) {
    $path = storage_path("app/public/{$carpeta}/{$carpeta1}/{$archivo}");

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});;

Route::get('storage/fotos/{tipo}/{hash}', function ($tipo, $hash) {
    $urlFoto = 'http://200.48.160.218:8081/storage/fotos/' . $tipo . '/' . $hash;
    $ch = curl_init($urlFoto);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:image/jpeg'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    // dd(curl_error($ch));
    curl_close($ch);

    // dd(gettype($result));

    $primerosCaracteres = substr($result, 0, 3);

    if ($primerosCaracteres != '<!D') {

        // $file = File::get($result);

        $response = response()->make($result, 200);
    } else {
        $path = public_path('img/falta.jpg');
        // dd($path);

        // $path = storage_public('images/' . $filename);
        $file = File::get($path);
        // $type = File::mimeType($path);

        $response = response()->make($file, 200);
        // $response->header("Content-Type", $type);
    }
    $response->header("Content-Type", 'image/jpg');

    return $response;
});
Route::any('fotografia/{iPersId}', 'Generales\GrlPersonasController@getFotografiaExistente');

Route::any('genDocs/{iDocIdEncoded}', 'Tram\ReportePdfController@getPdfFromUrl')->name('tramites.pdfPublico');

// Route::any('test', 'Generales\GrlPersonasController@procImagenes');




Route::get('test/view', function () {
    return view('cctic.certificado', ['name' => 'James']);
});
Route::get('email/view', function () {
    return view('cctic.emailGrupo');
});

// Route::get('test/acta', 'CCTIC\CertificadosController@imprimirActaNotasExamen');
