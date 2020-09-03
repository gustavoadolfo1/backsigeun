<?php

namespace App\Http\Controllers\cctic;

use App\Jobs\SendEmailOpenGroup;
use App\Mail\EmailOpenGroup;
use App\Model\cctic\PreInscripcion;
use App\Notifications\OpenGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use Swift_Mailer;
use Swift_MailTransport;
use Swift_Message;
use Swift_Attachment;

class emailController extends Controller
{
    public function enviarCorreoAPreinscrito(Request $request)
    {
        $parameters = [
            $request->iPreinscripcionId,
        ];

        $data = DB::select('[acad].[Sp_CCTIC_SEL_Preinscripciones_MostrarPreinscripcionXId] ?', $parameters)[0];

        $data->horario = json_decode($data->horario ? $data->horario : '[]');
        $data->cPreinscripcionEmail = $request->cPreinscripcionEmail;
        $data->subject = $request->preinscripcionCreated ? '😃 Felicidades ' . $data->cPersNombre . ', tu preinscripción recibida' : '😃 Felicidades ' . $data->cPersNombre . ', tu preinscripción actualizada';
        try {
            $backup = Mail::getSwiftMailer();
            $transport = new \Swift_SmtpTransport('smtp.office365.com', 587, 'TLS');
            $transport->setUsername('vapazaa@unam.edu.pe');
            $transport->setPassword('Sigeun2020*');
            $gmail = new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);
            //    $data->cPreInscripEmail
            Mail::send('cctic/email', ['data' => $data], function ($message) use ($data) {
                $message->to($data->cPreinscripcionEmail, ucwords(strtolower('Preinscrito')))->subject($data->subject);
                $message->from('vapazaa@unam.edu.pe', 'U.N.A.M. - CCTIC');
            });

            $response  = ['validated' => true, 'message' => 'email enviado correctamente'];
            $responseCode = 200;
            //            dd($gmail);

        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => $data, 'message' => $e];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }
    public function sendEmailPreinscrito(Request $request)
    {
        $parameters = [
            $request->iPreInscripId,
            $iProgramasAcadId = 3
        ];

        $data = \DB::select('[acad].[SP_SEL_emailDataXiPreInscripId] ?, ?', $parameters)[0];

        try {
            $backup = Mail::getSwiftMailer();
            $transport = new \Swift_SmtpTransport('smtp.office365.com', 587, 'TLS');
            $transport->setUsername('vapazaa@unam.edu.pe');
            $transport->setPassword('Sigeun2020*');
            $gmail = new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);
            //    $data->cPreInscripEmail
            Mail::send('cctic/email', ['data' => $data], function ($message) use ($data) {
                $message->to($data->cPreInscripEmail, ucwords(strtolower('Preinscrito')))->subject('😃 Notificacion, Grupo abierto');
                $message->from('vapazaa@unam.edu.pe', 'U.N.A.M. - CCTIC');
            });

            $response  = ['validated' => true, 'message' => 'email enviado correctamente'];
            $responseCode = 200;
            //            dd($gmail);

        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    public function sendEmailGrupo($grupoID)
    {

        try {
            $preinscritos = DB::select('exec [acad].[Sp_CCTIC_SEL_preinscritosByGrupoID] ?', [$grupoID]);
        } catch (\Exception $e) {
            $preinscritos = [];
        }


        foreach ($preinscritos as $preinscrito) {
            $preinscrito->horario = json_decode($preinscrito->horario);
            $preinscrito->publico_objetivo = json_decode($preinscrito->publico_objetivo);
            SendEmailOpenGroup::dispatch($preinscrito);
        }


        $response = ['message' => 'correros enviados correctamente'];
        $responseCode = 200;


        return response()->json($response, $responseCode);

    }
}
