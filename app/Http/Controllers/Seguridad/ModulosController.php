<?php

namespace App\Http\Controllers\Seguridad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ModulosController extends Controller
{
    public function obtenerModulosCategorias(Request $request)
    {
        try {
            $categorias = DB::select('exec [seg].[Sp_SEL_modulos_categorias]');
            $response = ['validated' => true, 'data' => $categorias,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function obtenerModulos(Request $request)
    {
        $parameters = [
            (int)$request->iModulosCategId
        ];
        // return $parameters;
        try {
            $modulos = DB::select('exec [seg].[Sp_SEL_modulosWebXiModulosCategId] ?', $parameters);

            $collection = collect($modulos)->each(function ($item, $key) {

                //$item->cModuloRutaImagen = Storage::url($item->cModuloRutaImagen);
                $item->cModuloRutaImagen = URL::asset('assets/'.$item->cModuloRutaImagen);
            });
            $response = ['validated' => true, 'data' => $collection,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
}
