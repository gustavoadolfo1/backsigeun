<?php

namespace App\Http\Controllers\Cepre;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use PDF;

class CarneController extends Controller
{
    public function loadFicha($ficha){
        $data = \DB::select("exec [cepre].[SP_SEL_fichaEstudianteXiEstudServId] ".$ficha);
        $data[0]->deudas = json_decode($data[0]->json_estado_cta_cte);
        for ($i=0; $i <  count($data[0]->deudas); $i++) { 
            switch($data[0]->deudas[$i]->iNumCuota){
                case '0':
                    $data[0]->matricula =  $data[0]->deudas[$i]->cEstado ;
                    $data[0]->matriculad =   number_format($data[0]->deudas[$i]->nCuota, 2, '.', '');
                break;
                case '1':
                    $data[0]->pago1 =  $data[0]->deudas[$i]->cEstado;
                    $data[0]->pago1d =   number_format($data[0]->deudas[$i]->nCuota, 2, '.', '');
                break;
                case '2':
                    $data[0]->pago2 =  $data[0]->deudas[$i]->cEstado;
                    $data[0]->pago2d =   number_format($data[0]->deudas[$i]->nCuota, 2, '.', '');
                break;
            }
        }
        // dd($data[0]);
        $pdf = PDF::loadView('cepre.ficha', [ 'data' => $data[0] ])->setPaper('A4');
        return $pdf->stream();
    }
}
