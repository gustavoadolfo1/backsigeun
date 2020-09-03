<?php

namespace App\Http\Controllers\AulaVirtual;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GlosarioController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set("America/Lima");
    }
    public function getGlosario($activ){
        $data = \DB::table('aula.glosarios_terminos')->where('iActividadesId', $activ )->get();
        return response()->json( $data );
    }
    public function saveGlosario(Request $request){
        if($request->iGlosariosTermId){
            $data = \DB::table('aula.glosarios_terminos')->where('iGlosariosTermId', $request->iGlosariosTermId)->update( [
                'iActividadesId' => $request->iActividadesId, 
                'cGlosariosTermTitulo' => $request->concepto, 
                'cGlosariosTermDsc' => $request->contenido, 
                'cGlosariosTermTags' => $request->tags, 

                'cGlosariosTermUsuarioSis' =>auth()->user()->cCredUsuario,
                'cGlosariosTermIpSis' =>'equipo',
                'cGlosariosTermEquipoSis' =>'mac'
            ]);
        }else{
            $data = \DB::table('aula.glosarios_terminos')->insert( [
                'iActividadesId' => $request->iActividadesId, 
                'cGlosariosTermTitulo' => $request->concepto, 
                'cGlosariosTermDsc' => $request->contenido, 
                'cGlosariosTermTags' => $request->tags, 

                'cGlosariosTermUsuarioSis' =>auth()->user()->cCredUsuario,
                'cGlosariosTermIpSis' =>'equipo',
                'cGlosariosTermEquipoSis' =>'mac'
            ]);
        }
        return response()->json( $data );
    }
    public function deleteGlosario($id){
        $data = \DB::table('aula.glosarios_terminos')->where('iGlosariosTermId', $id )->delete();
        return response()->json( $data );
    }
}
