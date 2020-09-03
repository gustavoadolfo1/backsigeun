<?php

namespace App\Http\Controllers;

use App\GrlConfiguracionGeneral;
use Illuminate\Http\Request;

class ConfiguracionesGeneralesController extends Controller
{
    //
    public function configuracion(){
        $conf = GrlConfiguracionGeneral::first();
        return response()->json($conf);
    }

    public function conf() {
        $conf = GrlConfiguracionGeneral::all();
        $object = new \stdClass;
        foreach ($conf as $c) {
            $object->{$c->cConfigGrlesNombre} = $c->cConfigGrlesValor;
            //$varRet[] = [$c->cConfigGrlesNombre => $c->cConfigGrlesValor];
        }
        // return response()->json($varRet);
        return response()->json($object);
    }
}
