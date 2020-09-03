<?php

namespace App\Http\Controllers\Generales;

use App\UraEstudiante;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ArchivosController extends Controller
{
    public static function cargarArchivos(Request $request){
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');

            $nuevoNombre = ($request->prefijo??'') . (str_Replace('.'.$archivo->getClientOriginalExtension(), '', $archivo->getClientOriginalName())) .'-'.time() . ($request->sufijo ? '-' . $request->sufijo : '') . '.' . $archivo->getClientOriginalExtension();

            return response()->json('storage/'.$archivo->storePubliclyAs($request->carpeta, $nuevoNombre));
        } else {
            abort(503, 'No se adjuntaron archivos');
        }
        return response()->json($request->toArray());
        $archivo = $request->file('archivo');
        $archivo->storePublicly('_str');
        $archivo->storeAs('aaass', $archivo->getClientOriginalName());
        return response()->json(['a' => $archivo->storePubliclyAs('memore','asse.pdf')]);
        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $path = $request->photo->store('imageaaaas');
            return response()->json(['exito' => true]);
        }
        return response()->json($request->toArray());
        $archivo = $request->file('archivo');
  /*      $name=$archivo->getClientOriginalName();
        $archivo->move(public_path().'/imagenesss/', $name);

*/
        // dd($archivo);
        return response()->json(['a' => $archivo->getClientOriginalExtension()]);
        dd($request);
    }

    public function eliminarTemporales(Request $request) {
        if (isset($request->path)) {
            $pos = strpos($request->path, 'storage/temp/');
            if ($pos === false && !isset($request->elimOtros)) {
                abort(503, 'No se encontro el archivo en Temporales');
            }
            else {
                if (isset($request->elimOtros)) {
                    File::delete('storage/' . $request->path);
                }
                else {
                    File::delete($request->path);
                    $this->RemoveEmptySubFolders(public_path('storage/temp/'));
                }

                $jsonResponse = [
                    'error' => false,
                    'msg' => 'Se elimino Correctamente',
                ];
                return response()->json($jsonResponse);
            }
        }
        else {
            abort(503, 'No hay archivo');
        }
        return false;
    }

    function RemoveEmptySubFolders($path) {
        $empty=true;
        foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file) {
            if (is_dir($file)) {
                if (!$this->RemoveEmptySubFolders($file)) $empty=false;
            }
            else {
                $empty=false;
            }
        }
        if ($empty) rmdir($path);
        return $empty;
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
