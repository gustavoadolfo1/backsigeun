<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;
use Hashids\Hashids;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->hashids = new Hashids('SIGEUN UNAM', 15);
    }

    public function sendPushNotification($to = '', $title, $msg)
    {

        $apiKey = 'AAAAqrWVsQ8:APA91bEVFB0zN22-SOjA62qKpriAZLn4u9X7degxJa0W-YeIaAMymdRgcFrploECp0wTqpxajtoXz6inxxoL_xIuHrjqWweN6TsAH9DAquejTOfiC62O80Y6iLu_ZvJ_vfMfJW35-TAs';

        $data = array(
            'title' => $title,
            'body'  => $msg,
            'icon'  => 'https://backsigeun.test/img/btn_3.png'
        );

        $fields = array('to' => $to, 'notification' => $data);

        $headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');

        $url = 'https://fcm.googleapis.com/fcm/send';
        $ch = \curl_init();
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_setopt($ch, CURLOPT_POST, true);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, \json_encode($fields));

        $result = \curl_exec($ch);
        \curl_close($ch);

        return \json_decode($result, true);
    }

    public function createuser(Request $request)
    {

        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $uid = "DtnHFr-_TKmEC6M7vnAakQ";
        $curl = curl_init();

        $create = array(
            'action'     => $data['action'],
            'user_info'  => $data['user_info']
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/users",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => \json_encode($create),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6ImNPUDFCR2lxVEMyMUtkOXZIRGtXYlEiLCJleHAiOjE1ODk0MTIyNjEsImlhdCI6MTU4ODgwNzQ2Mn0.6j8lsunPNpXVANBKZA2tbIYWsFpYP9a9-cYP46ggos8",
                "content-type: application/json"
            ),
        ));

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = $result;
        }

        return \json_decode($response, true);
    }
    public function listUsers()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/users?page_number=1&page_size=300&status=active",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6ImNPUDFCR2lxVEMyMUtkOXZIRGtXYlEiLCJleHAiOjE1ODk0MTIyNjEsImlhdCI6MTU4ODgwNzQ2Mn0.6j8lsunPNpXVANBKZA2tbIYWsFpYP9a9-cYP46ggos8"
            ),
        ));

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = $result;
          /*  $lista = [];
            $b=json_decode($response);
            //return ($b->{'users'});
        
            $i=0;
            while($b->{'users'}[$i]){
 

                $data = \DB::update('exec aula.[Sp_AULA_INS_UPD_LISTUSER] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
                    '1',
                    '1',
                    $b->{'users'}[$i]->{'created_at'},
                    '',
                    $b->{'users'}[$i]->{'email'},
                    $b->{'users'}[$i]->{'first_name'},
                    $b->{'users'}[$i]->{'id'},
                    $b->{'users'}[$i]->{'language'},
                    $b->{'users'}[$i]->{'last_login_time'},
                    $b->{'users'}[$i]->{'last_name'},
                    '',
                    $b->{'users'}[$i]->{'pmi'},
                    $b->{'users'}[$i]->{'status'},
                    $b->{'users'}[$i]->{'timezone'},
                    $b->{'users'}[$i]->{'type'},
                    $b->{'users'}[$i]->{'verified'},


                    'user',
                    gethostname(),
                    '-',
                    '-',
                    '-',
                    
                 )); 

                 $i++;
             }


       */  } 

        return \json_decode($response, true);
    }

    public function prgreunion(Request $request)
    {
        $ids = $this->hashids->decode($request->hashedId);
        //return $request->all();
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
      
        $errorJson = new TramitesController();

        $ConsultaCarreraId = \DB::table('ura.curriculas_cursos')
        ->where('iCurricCursoId',  $ids[0])
        ->get();
    
        $dataUser = \DB::select('exec [aula].[Sp_SEL_cuentaLibreZoomXiCarreraIdXiFilIdXiControlCicloAcad] ?, ?, ?, ?', array(
            $ConsultaCarreraId[0]->iCarreraId,
            $ids[2],
            $ids[4],
            $data['iReunionProgId']
        ));

        $ConsultaRP = \DB::table('aula.reunion_programacion')
            ->where('iReunionProgId',  $data['iReunionProgId'])
            ->get();
        
        $ConsultaFechaInicio = \DB::table('aula.actividades')
            ->where('iActividadesId', $ConsultaRP[0]->iActividadesId)
            ->get();

        $obtenerLink = '';
        $responseJson = '';
        $mensaje = '';
        $soporte='';

        switch( $dataUser[0]->iErrorCode){
            case 0:
                //GENERAR NUEVA REUNION
                $uid = $dataUser[0]->cUserId;

                $dataZoom = \DB::select('exec [aula].[Sp_SEL_seguridadTokenZoom] ');
                //return $dataZoom;
                $curl = curl_init();
                $hora = explode(":",  $ConsultaRP[0]->tInicio);
                $meeting = array(
                    'topic' => substr($ConsultaFechaInicio[0]->cActividadesTitulo, 0, 199),
                    'type' => $data['type'],
                    'start_time' => $ConsultaRP[0]->dInicio.'T'.$hora[0].':'. $hora[1].':00',
                    'duration' => $ConsultaRP[0]->iDuration,
                    'schedule_for' => $data['schedule_for'],
                    'timezone' => $data['timezone'],
                    'password' => '1234',
                    'agenda' => $ConsultaFechaInicio[0]->cActividadesDsc,
                    'settings' => $data['settings']
                );

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/users/" . $uid . "/meetings",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => \json_encode($meeting),
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer ".$dataZoom[0]->cSegTokensJwtTtoken,
                        "content-type: application/json"
                    ),
        
                ));


                $result = curl_exec($curl);
                $err = curl_error($curl);
                //return $result;
                curl_close($curl);
        
                if ($err) {
                    $responseJson = "cURL Error #:" . $err;
                } else {
                
                    $b=json_decode($result);
                    //return response()->json($b);
                    
                    $actualizar = \DB::table('aula.reunion_programacion')
                    ->where('iReunionProgId', $ConsultaRP[0]->iReunionProgId)
                    ->update(
                        array(
                            'cStart_url' => $b->{'start_url'},
                            'cJoin_url' => $b->{'join_url'},
                            'iId' => $b->{'id'} ?? NULL,
                            'cUuid' => $b->{'uuid'} ?? NULL,
                        )
                    );

                    $obtenerLink  =  \DB::table('aula.reunion_programacion')
                        ->where('iReunionProgId', $ConsultaRP[0]->iReunionProgId)
                        ->get();

                    $mensaje=$dataUser[0]->cMensaje;
                        
                        
                }
                break;
            case 1:
                //FALLO
                $mensaje=$dataUser[0]->cMensaje;
                break;
            case 2:
                //CUENTA NO DISPONIBLE
                $mensaje=$dataUser[0]->cMensaje;
                break;
            case 3:
                //REUTILIZO CUENTA ZOOM
                $obtenerLink  = \DB::table('aula.reunion_programacion')
                ->where('iReunionProgId', $ConsultaRP[0]->iReunionProgId)
                ->get();

                $mensaje=$dataUser[0]->cMensaje;
                break;
            default:break;

        }

        return Response::json(['obtenerLink' => $obtenerLink, 'responseJson' => $responseJson, 'mensaje' => $mensaje, 'soporte' => $soporte]);
    }

    public function listmeetings(Request $request)
    {

        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();
        $uid = "VpCsvLeTShOCJlkaSHtLqA"; //"3louftPGTk-OUDOY5eSofA";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/users/" . $uid . "/meetings",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6ImNPUDFCR2lxVEMyMUtkOXZIRGtXYlEiLCJleHAiOjE1ODk0MTIyNjEsImlhdCI6MTU4ODgwNzQ2Mn0.6j8lsunPNpXVANBKZA2tbIYWsFpYP9a9-cYP46ggos8"
            ),
        ));

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = $result;
        }

        return \json_decode($response, true);
    }

    public function deletemeeting(Request $request)
    {

        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();
        $uid = $data;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/meetings/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6ImNPUDFCR2lxVEMyMUtkOXZIRGtXYlEiLCJleHAiOjE1ODk0MTIyNjEsImlhdCI6MTU4ODgwNzQ2Mn0.6j8lsunPNpXVANBKZA2tbIYWsFpYP9a9-cYP46ggos8"
            ),
        ));

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = $result;
        }

        return \json_decode($response, true);
    }

    public function getmeeting(Request $request)
    {


        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();
        $uid = $data;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/meetings/" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6ImNPUDFCR2lxVEMyMUtkOXZIRGtXYlEiLCJleHAiOjE1ODk0MTIyNjEsImlhdCI6MTU4ODgwNzQ2Mn0.6j8lsunPNpXVANBKZA2tbIYWsFpYP9a9-cYP46ggos8"
            ),
        ));

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = "cURL Error #:" . $err;
        } else {
            $response = $result;
        }

        return \json_decode($response, true);
    }
}
