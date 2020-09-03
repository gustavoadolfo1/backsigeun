<?php

namespace App\Http\Controllers\ceid;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_MailTransport;
use Swift_Message;
use Swift_Attachment;

class emailController extends Controller
{
    public function sendEmailPreinscrito($data)
    {

        try {
            $backup = Mail::getSwiftMailer();
            $transport = new \Swift_SmtpTransport('smtp.office365.com', 587, 'TLS');
            $transport->setUsername('vapazaa@unam.edu.pe');
            $transport->setPassword('Sigeun2020*');
            $gmail =new \Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);

            Mail::send('ceid/mail', ['data' => $data], function($message) use($data) {
                $message->to( $data->cPreInscripEmail, ucwords(strtolower('Preinscrito')))->subject('😃 Notificacion, Preinscricion Creada');
                $message->from('vapazaa@unam.edu.pe','U.N.A.M. - CEID');
            });

            $response  = ['validated' => true, 'message' => 'email enviado correctamente'];
            $responseCode = 200;
//            dd($gmail);

        } catch (\qException $e) {
            $response = ['validated' => false, 'message' => $e];
            $responseCode = 500;
        }
        return response()->json( $response, $responseCode);
    }
}
