<?php
namespace App\Http\Controllers\Tre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class seg_personas_sessionsController extends Controller{
    /*public function __construct(){
        $this->middleware('auth:api', ['except' => ['login']]);
    }*/

    public function seg_personas_sessions_update(Request $data){
        $PersDocumento = $data->get("PersDocumento");
        $PersCredLogin = $data->get("PersCredLogin");
        $PersCredPsw1  = $data->get("PersCredPsw1");

        $_r = \DB::select('exec seg.[personas_sessions_sp_update] ?,?,?', array($PersDocumento,$PersCredLogin,$PersCredPsw1));
        //return response()->json($_records);
        return $_r;
    }

    public function seg_personas_sessions_validate(Request $data){
        $PersSessKey = $data->get("PersSessKey");

        $_id = \DB::select('exec seg.[personas_sessions_sp_validate] ?', array($PersSessKey));
        return response()->json($_id);
    }
}