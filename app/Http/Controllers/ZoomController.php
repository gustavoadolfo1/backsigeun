<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Curl\Curl;

class ZoomController extends Controller
{
    public function __construct($cSegTokensJwtTtoken)
    {
        $this->headers = [ 
            'authorization' => "Bearer " . $cSegTokensJwtTtoken,
            'content-type' => "application/json"
        ];

        $this->userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
    }

    public function getAsistentesMeeting($cMeetingId, $iPageSize = null, $cNextPageToken = null)
    {   
        $response = null;

        $curl = new Curl();
        $curl->setUserAgent($this->userAgent);
        $curl->setHeaders($this->headers);

        $curl->get('https://api.zoom.us/v2/report/meetings/' . $cMeetingId . '/participants');

        if ($curl->error) {
            $mensaje = 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
        } else {
            $mensaje = 'Ok';
            $response = $curl->response;
        }

        return ['mensaje' => $mensaje, 'response' => $response];
    }
}
