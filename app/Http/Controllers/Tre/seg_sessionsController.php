<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class seg_sessionsController extends Controller{
    /*public function __construct(){
        $this->middleware('auth:api', ['except' => ['login']]);
    }*/

    public function seg_sessions_update(Request $data){
        $CredUsuario  = $data->get("CredUsuario");
        $CredDepenKey = $data->get("CredDepenKey");
        $Resumen      = $data->get("Resumen");

        $_records = \DB::select('exec seg.[sessions_sp_update] ?,?,?', array($CredUsuario,$CredDepenKey,$Resumen));
        //return response()->json($_records);
        return $_records;
    }

    public function seg_sessions_validate(Request $data){
        $SessKey = $data->get("SessKey");

        $_id = \DB::select('exec seg.[sessions_sp_validate] ?', array($SessKey));
        return response()->json($_id);
    }
}