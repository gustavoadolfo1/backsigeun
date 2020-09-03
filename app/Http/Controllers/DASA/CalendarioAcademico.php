<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CalendarioAcademico extends Controller
{
    public function insCalAcademico()
    {
        $academico = ['1','1','calendario','2019','titulo','subtitulo','null','null','null','null','null'];
        DB::insert('EXEC ura.Sp_DASA_INS_calendariosAcademicos ?,?,?,?,?,?,?,?,?,?', $academico);
        echo 'insert calendario academico';
    }
}
