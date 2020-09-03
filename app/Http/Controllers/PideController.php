<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PideController extends Controller
{
    public static function consultar(Request $request, $tipo, $persona_id=null, $local = false)
    {
        $servActivos = ['reniec', 'seguro', 'sms', 'sunat', 'osce', 'sunedu'];

        if (in_array($tipo, $servActivos)){
            $persona_id = ($persona_id)?'/'.$persona_id:'';
            $urlPide = 'http://200.48.160.218:8081/api/pide/' . $tipo . $persona_id;
            $ch = curl_init($urlPide);
            $payload = json_encode($request->toArray());
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);

            if(curl_errno($ch)){
                $jsonResponse = [
                    'error' => true,
                    'msg' => curl_error($ch),
                    'timeout' => true,
                ];

                curl_close($ch);

                if ($local) {
                    return $jsonResponse;
                }
                else {
                    return response()->json($jsonResponse);
                }
            }

            curl_close($ch);
            $data = json_decode($result);

            if ($data == null){
                $jsonResponse = [
                    'error' => true,
                    'msg' => 'Error desconocido',
                    'data' => $data
                ];
            }
            else {
                if (isset($data->error) && ($data->error) && isset($data->msg) ){
                    $jsonResponse = [
                        'error' => $data->error,
                        'msg' => $data->msg,
                        'data' => $data->data
                    ];
                }
                else {
                    if ($tipo == 'reniec') {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => '',
                            'data' => $data->data
                        ];
                    }
                    else {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => '',
                            'data' => $data
                        ];
                    }
                }
            }
        }
        else{
            $jsonResponse = [
                'error' => true,
                'msg' => 'El servicio no está activo o no existe',
                'data' => null
            ];
        }

        if ($local) {
            return $jsonResponse;
        }
        else {
            return response()->json($jsonResponse);
        }
    }

    public function checkIfHasPIDEReniec($dni)
    {
        $hasPide = DB::table('grl.reniec')->where('cReniecDni', $dni)->exists();

        return response()->json([ 'hasPide' => $hasPide ]);
    }
}
