<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;

class FilesController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }
    public function getFilesUser(Request $request){
        $data = \DB::table('aula.archivos')->where('iPersId',$request->id)->where('bEstadoActivo',true)->where('cUbicacion',$request->url)->orderBy('dFechaSubida', 'desc')->get();
        return response()->json($data);
    }
    public function saveFilesUser(Request $request){

        $file = $request->archivos;
        if ($request->hasFile('archivos')) {

            // $filename = utf8_decode($request->file('archivos')->getClientOriginalName());
            $filenameOrigin = $request->file('archivos')->getClientOriginalName();

            $filePath = 'AulaVitual/Repositorio/'. $request->IdPerson;
            $archivo = $request->file('archivos');

            $nuevoNombreFile = time() . ($request->sufijo ? '-' . $request->sufijo : '') . '.' . $archivo->getClientOriginalExtension();

            $archivo->storePubliclyAs($filePath,$nuevoNombreFile);
            $resultData = \DB::table('aula.archivos')->insert([
                'cNombre' => $filenameOrigin, 
                'cTipo' => $request->file('archivos')->getClientOriginalExtension(), 
                'cPeso' => $request->file('archivos')->getSize() / 1000, 
                'address' => 'AulaVitual/Repositorio/'. $request->IdPerson.'/'. $nuevoNombreFile, 
                'cUbicacion'=> $request->url,
                'iCategoria' => 0,
                'dFechaSubida' => date("Y-m-d\TH:i:s"), 
                'iPersId' =>  $request->IdPerson, 
            ]);
            
            return response()->json( [ 'file' => $filePath . $filenameOrigin ] );
        } else {
            return response()->json( [ 'error' => true ], 500 );
        }

    }
    public function saveFilesUserExamen(Request $request){

        $file = $request->archivos;
        if ($request->hasFile('file')) {
            $filenameOrigin = $request->file('file')->getClientOriginalName();
            $filePath = 'AulaVitual/Repositorio/'. $request->iPersId . '/FilesExamenes';
            $archivo = $request->file('file');

            $nuevoNombreFile = time() . ($request->sufijo ? '-' . $request->sufijo : '') . '.' . $archivo->getClientOriginalExtension();

            $archivo->storePubliclyAs($filePath,$nuevoNombreFile);

            $resultData = \DB::table('aula.evaluaciones_respuestas_detalle')
              ->where('iEvalRptaDetId', $request->iEvalRptaDetId)
              ->update(['iEvalDetRespuesta' =>  $filePath .'/'. $nuevoNombreFile]);
            
            return response()->json( [ 'file' => $filePath .'/'. $nuevoNombreFile ] );
        } else {
            return response()->json( [ 'error' => true ], 500 );
        }

    }
    public function saveFolder(Request $request){
        $resultData = \DB::table('aula.archivos')->insert([
            'cNombre' => $request->cNombre, 
            'cTipo' => 'carpeta', 
            'cPeso' => '-', 
            'address' => 'AulaVitual/Repositorio/'. $request->IdPerson.'/'. $request->cUbicacion, 
            'dFechaSubida' => date("Y-m-d\TH:i:s"), 
            'cUbicacion'=> $request->cUbicacion,
            'iCategoria' => 1,
            'iPersId' =>  $request->idPerson, 
        ]);
        return response()->json( [ 'fileStatus' => $resultData ] );
    }
    public function deleteFilesUser($id){
        // $data = \DB::table('aula.archivos')->where('iArchivoId',$id)->delete(); aliminado fisico
        $data = DB::table('aula.archivos')->where('iArchivoId', $id)->update(['bEstadoActivo' => false]);
        return response()->json($data);

    }
}
