<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComentarioController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }
    public function verComentarios($id)
    {
        $data = \DB::select('exec [aula].[Sp_SEL_ComentariosPublicosXcComentariosPubId] ?', [ $id ]);

        return response()->json( $data );
    }

    public function insertar(Request $request)
    {
        $parametros = [
            $request->comentarioPubId,
            $request->estudId,
            $request->persId,
            $request->comentarioDsc,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $data = \DB::select('exec [aula].[Sp_INS_ComentariosPublicos] ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Hecho', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500; 
        }

        return response()->json($response, $codeResponse);
    }

    public function responderComentario(Request $request)
    {
        $parametros = [
            $request->comentarioPubId,
            $request->estudId,
            $request->persId,
            $request->comentarioDsc,
            $request->comentarioPubDetId,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $data = \DB::select('exec [aula].[Sp_INS_ComentariosPublicos_Respuesta] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Hecho', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500; 
        }

        return response()->json($response, $codeResponse);
    }
    public function insertarComentarioPrivado(Request $request)
    {
        $parametros = [
            $request->actividadId,
            $request->estudId,
            $request->persId,
            $request->comentarioDsc,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $data = \DB::select('exec [aula].[Sp_INS_ActividadesRespuestas_ComentariosPrivados] ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Hecho', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500; 
        }

        return response()->json($response, $codeResponse);
    }

    public function responderComentarioPrivado(Request $request)
    {
        $parametros = [
            $request->actividadId,
            $request->estudId,
            $request->persId,
            $request->comentarioPrivId,
            $request->comentarioDsc,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $data = \DB::select('exec [aula].[Sp_INS_ActividadesRespuestas_ComentariosPrivados_Respuesta_a_Comentario] ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Hecho', 'data' => $data];
            $codeResponse = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $codeResponse = 500; 
        }

        return response()->json($response, $codeResponse);
    }
    public function eliminarComentario($id){
       
        $data =  \DB::table('aula.comentarios_publicos ')->where('iComentariosPubId', $id)->delete();
        $response = ['validated' => true, 'mensaje' =>  $data];

    }
    public function editarComentario(Request $request){
        $data = \DB::table('aula.comentarios_publicos')
              ->where('iComentariosPubId',  $request->iComentariosPubId)
              ->update([
                  'cComentariosPubDsc' => $request->cComentariosPubDsc, 
                  'iEstudId'=> 1,
                  'dtComentariosPubFechaSis'=> date("Y-m-d\TH:i:s")
                ]);
        $response = ['validated' => true, 'mensaje' =>  $data];

    }
}
