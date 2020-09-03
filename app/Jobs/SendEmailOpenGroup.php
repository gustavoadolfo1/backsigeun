<?php

namespace App\Jobs;

use App\Mail\EmailOpenGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\EmailForQueuing;
use Illuminate\Support\Facades\Mail;


class SendEmailOpenGroup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected  $data;
    public $tries = 5;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $email = new EmailOpenGroup($this->data);
        $backup = Mail::getSwiftMailer();
        $transport = new \Swift_SmtpTransport('smtp.office365.com', 587, 'TLS');
        $transport->setUsername('vapazaa@unam.edu.pe');
        $transport->setPassword('Sigeun2020*');
        $gmail = new \Swift_Mailer($transport);
        Mail::setSwiftMailer($gmail);

        Mail::to($this->data->cPreinscripcionEmail)->send($email);
    }
}
