<?php

namespace App\Http\Controllers\Generales;

use App\GrlConfiguracionGeneral;
use App\GrlPersona;
use App\GrlReniec;
use App\Http\Controllers\PideController;
use App\UraEstudiante;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class GrlPersonasController extends Controller
{
    //
    public function getFotografia(Request $request) {
        if (($request->code) && is_numeric($request->code) ) {

            $configGrl = GrlConfiguracionGeneral::get();


            $persona = GrlPersona::where('iPersId', $request->code)->first();

            $jsonRespuesta = [];
            if ($persona) {
                if ($persona->cPersFotografia) {
                    $configGrlFotoPath = $configGrl->where('cConfigGrlesNombre', 'rutaPersonas')->first();
                    $jsonRespuesta = [
                        'src' => $configGrlFotoPath->cConfigGrlesValor . $persona->cPersFotografia
                    ];
                }
                else {
                    $reniecP = GrlReniec::where('iPersId', $request->code)->first();
                    $configGrlFotoPath = $configGrl->where('cConfigGrlesNombre', 'rutaReniec')->first();
                    if (isset($reniecP->cReniecFotografia)) {
                        $jsonRespuesta = [
                            'src' => $configGrlFotoPath->cConfigGrlesValor . $reniecP->cReniecFotografia
                        ];
                    }
                    else {
                        $retApi = PideController::consultar($request, 'reniec', $request->code, true);
                        if (!$retApi['error']) {
                            $jsonRespuesta = [
                                'src' => $configGrlFotoPath->cConfigGrlesValor . $retApi['data']->cReniecFotografia
                            ];
                        }
                    }
                }
            }
        }
        return response()->json($jsonRespuesta);
    }

    public function getFotoArchivo($tipo, $iPersId) {

        if (is_numeric($iPersId)) {

            $keyCache = 'fotografia_' . $iPersId;
            $tiempoCache = 60; //En Minutos

            $urlImg = null;
            $contenFotografia = null;

            switch ($tipo) {
                case 'persona':
                    $urlImg = GrlConfiguracionGeneral::where('cConfigGrlesNombre', 'rutaPersonas')->first();
                    $contenFotografia = GrlPersona::where('iPersId', $iPersId)->first();
                    if ($contenFotografia) {
                        $contenFotografia = $contenFotografia->cPersFotografia;
                    }
                    break;
                case 'reniec':
                    $urlImg = GrlConfiguracionGeneral::where('cConfigGrlesNombre', 'rutaReniec')->first();
                    $contenFotografia = GrlReniec::where('iPersId', $iPersId)->first();
                    if ($contenFotografia) {
                        $contenFotografia = $contenFotografia->cReniecFotografia;
                    }
                    break;

            }

            //dd($contenFotografia);

            if ($contenFotografia != null) {
                $rutaFotografia = $urlImg->cConfigGrlesValor . $contenFotografia;
                if (file_exists(public_path($rutaFotografia))) {
                    $file = File::get($rutaFotografia);
                    // $contenido = Cache::remember($keyCache, now()->addMinute(30), $file);
                    return Cache::remember($keyCache, now()->addMinute($tiempoCache), function () use ($file) {
                        return $file;
                    });
                }
                else {
                    $client = new \GuzzleHttp\Client();

                    try {
                        $backendProduccion = $client->request('GET', 'http://sigeun.unam.edu.pe/' . $rutaFotografia, [
                            'Content-Type' => 'image/jpeg'
                        ]);
                        $result = $backendProduccion->getBody()->getContents();
                        // Storage::disk('public')->put(str_replace('storage/', '', $rutaFotografia), $result);
                        return Cache::remember($keyCache, now()->addMinute($tiempoCache), function () use ($result) {
                            return $result;
                        });
                    }
                    catch (\GuzzleHttp\Exception\BadResponseException $exception) {
                        try {
                            $backendPide = $client->request('GET', 'http://200.48.160.218:8081/' . $rutaFotografia, [
                                'Content-Type' => 'image/jpeg'
                            ]);
                            $result = $backendPide->getBody()->getContents();
                            Storage::disk('public')->put(str_replace('storage/', '', $rutaFotografia), $result);
                            return Cache::remember($keyCache, now()->addMinute($tiempoCache), function () use ($result) {
                                return $result;
                            });                        }
                        catch (\GuzzleHttp\Exception\BadResponseException $exception) {

                        }
                    }


                    /*
                    $urlFoto = 'http://200.48.160.218:8081/' . $rutaFotografia;
                    try {
                        $response2 = $client->request('GET', $urlFoto, ['Content-Type' => 'image/jpeg']);
                        $result = $response2->getBody()->getContents();
                        Storage::disk('public')->put(str_replace('storage/', '', $rutaFotografia), $result);

                        //$contenido = Cache::remember($keyCache, now()->addMinute(30), $result);

                        return Cache::remember($keyCache, now()->addMinute(30), function () use ($result) {
                            return $result;
                        });
                    }
                    catch (\GuzzleHttp\Exception\BadResponseException $exception) {
                    }
                    */
                }
            }
        }
        return false;
    }

    public function getFotografiaExistente($iPersId, $reniec = false, $persona = null){
        if (is_numeric($iPersId)){

            $keyCache = 'fotografia_' . $iPersId;
            if (Cache::has($keyCache) && !$reniec){
                //$response = Cache::get($keyCache);
                $response = response()->make(Cache::get($keyCache), 200);
            }
            else {

                $contenFotoPersona = $this->getFotoArchivo('persona', $iPersId);
                if ($contenFotoPersona){
                    $response = response()->make($contenFotoPersona, 200);
                }
                else {
                    $contenFotoReniec = $this->getFotoArchivo('reniec', $iPersId);
                    if ($contenFotoReniec){
                        $response = response()->make($contenFotoReniec, 200);
                    }
                    else {
                        $path = public_path('img/falta.jpg');
                        $file = File::get($path);
                        $response = response()->make($file, 200);
                    }
                }
            }
        }
        else {
            $path = public_path('img/img-404.png');
            $file = File::get($path);
            $response = response()->make($file, 200);
        }

        $response->header("Content-Type", 'image/jpg');
        return $response;
    }

    public function getFotoReniec(Request $request, $local = false)
    {
        if (($request->code) && is_numeric($request->code) ) {

            $configGrl = GrlConfiguracionGeneral::get();

            $reniecP = GrlReniec::where('iPersId', $request->code)->first();
            $configGrlFotoPath = $configGrl->where('cConfigGrlesNombre', 'rutaReniec')->first();

            $jsonRespuesta = [ 'src' => null ];

            if (isset($reniecP->cReniecFotografia)) {
                $jsonRespuesta = [
                    'src' => $configGrlFotoPath->cConfigGrlesValor . $reniecP->cReniecFotografia
                ];
            }
            else {
                $retApi = PideController::consultar($request, 'reniec', $request->code, true);
                if (!$retApi['error']) {
                    $jsonRespuesta = [
                        'src' => $configGrlFotoPath->cConfigGrlesValor . $retApi['data']->cReniecFotografia
                    ];
                }
                else {
                    $jsonRespuesta = [ 'src' => null, 'error' => $retApi['error'], 'msg' => $retApi['msg'] ];
                }
            }
        }

        if ($local) {
            return $jsonRespuesta;
        }
        else {
            return response()->json($jsonRespuesta);
        }
    }

    public function getIdentificacionesTipos()
    {
        $documents = DB::table('grl.tipo_Identificaciones')->where('iTipoIdentId', '<>', 2)->get();

        return response()->json( $documents );
    }

    public function buscarPersona($dni, $tipoDoc)
    {
        $persona = DB::table('grl.personas')->where('cPersDocumento', $dni)->where('iTipoIdentId', $tipoDoc)->first();

        return response()->json( $persona );
    }


















    public function procImagenes(){

        set_time_limit(1000);
        //$est = UraEstudiante::all();

        //dd($est->toArray());

        $imgs = Storage::files('estudiantes');

        //$imgs = ['estudiantes/2010204030.jpg', 'estudiantes/2012102054.jpg', 'estudiantes/2013101015.jpg', 'estudiantes/2013102047.jpg', 'estudiantes/2013103076.jpg', 'estudiantes/2014103033.jpg', 'estudiantes/2014103050.jpg', 'estudiantes/2015103005.jpg', 'estudiantes/2015204042.jpg', 'estudiantes/2015206001.jpg', 'estudiantes/2015402031.jpg', 'estudiantes/2016101080.jpg', 'estudiantes/2016204005.jpg', 'estudiantes/2016205050.jpg', 'estudiantes/2016205076..jpg', 'estudiantes/2016303002..jpg', 'estudiantes/2016303005..jpg', 'estudiantes/2017303002..jpg', 'estudiantes/2017303008..jpg', 'estudiantes/2017303009..jpg', 'estudiantes/2017303011..jpg', 'estudiantes/2017303012..jpg', 'estudiantes/2017303013..jpg', 'estudiantes/2017303014..jpg', 'estudiantes/2017303015..jpg', 'estudiantes/2017303016..jpg', 'estudiantes/2017303017..jpg', 'estudiantes/2017303019..jpg', 'estudiantes/2017303021..jpg', 'estudiantes/2017303024..jpg', 'estudiantes/2017303025..jpg', 'estudiantes/2018102038.jpg', 'estudiantes/2018103001.jpg', 'estudiantes/2018205043.JPG', 'estudiantes/2019102002.jpg', 'estudiantes/2019204028.jpg', 'estudiantes/2019205046.jpg', 'estudiantes/falta.jpg',];

        $docNo = '';
        $arrayErrores = [];
        $erroresIMGSave = [];

        foreach ($imgs as $img){
            // dd($img);
            $img = str_replace('..', '.', $img);

            $info = pathinfo($img);
            $file_name =  basename($img,'.'.$info['extension']);

            $estSelec = UraEstudiante::where('cEstudCodUniv', $file_name)->with('persona')->first();

            if ($estSelec) {
                $nombreFoto = Hash::make($estSelec->iPersId) . '.' . $info['extension'];


                // dd($estSelec->toArray());
                try{
                    Storage::copy($img, 'fotos/personas/'. $nombreFoto);
                    // $estSelec->cEstudFoto = $nombreFoto . '.' . $info['extension'];
                    $estSelec->persona->cPersFotografia = $nombreFoto;
                    $estSelec->push();
                    // $estSelec->save();
                } catch(\Exception $e){
                    $erroresIMGSave[] = [
                        'imgOrig' => $img,
                        'imgFin' => Hash::make($file_name) . '.' . $info['extension'],
                        'error' => $e->getMessage()
                    ];
                    break;
                }

            }
            else {
                $docNo .= "'$img', ";
                $arrayErrores[] = $img;
            }
            //dd();

            //dd($file_name);
        }

        dump($docNo);
        dump($erroresIMGSave);
        dd($arrayErrores);


        dd($imgs);
    }
}
