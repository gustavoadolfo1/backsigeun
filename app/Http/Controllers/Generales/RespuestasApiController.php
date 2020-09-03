<?php

namespace App\Http\Controllers\Generales;

use App\SegModulo;
use App\SegPerfil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RespuestasApiController extends Controller
{
    //
    public function anonimo(Request $request, $tipo) {
        // return response()->json(['t' => $tipo, 'r' => $request->all()]);
        $data =  $request->get('data');

        $respuesta = null;
        switch ($tipo) {
            case 'credenciales':
                if (isset($request->dni)){
                    $data = [$request->dni];
                }

                // return response()->json(['base' => env('APP_NAME')]);
                // $respuesta = collect( DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', $data) );
                $respuesta = collect( DB::select('EXEC seg.Sp_SEL_credencialesXcCredUsuario ?', $data) );

                if ($respuesta->count() > 0) {

                    $perfilesModulo = [];
                    $credencial = $respuesta->first();
                    $modulos = SegModulo::where('cModuloCodigo', $request->modulo)->with('perfiles')->first();
                    if ($modulos) {
                        foreach ($modulos->perfiles as $perfilModulo){
                            $perfilesModulo[] = $perfilModulo->iPerfilId;
                        }
                    }

                    $colectRetorno = collect();
                    // return response()->json($modulos);

                    $respuestaPerfiles = collect( DB::select('seg.Sp_SEL_credenciales_perfilesXiCredId ?', [$credencial->iCredId]) );

                    foreach ($respuestaPerfiles as $perfile){
                        if (in_array($perfile->iPerfilId, $perfilesModulo)) {
                            $perfile->nombre = $modulos->cModuloCodigo . ' - ' .  $perfile->cPerfilNombre;
                            $colectRetorno->add($perfile);
                        }
                    }

                    $oficinas = collect( DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", [$credencial->iCredId]) );

                    $credencial->perfiles = $colectRetorno;
                    $credencial->modulo = $modulos;
                    $credencial->oficinas = $oficinas;
                    $credencial->ip = $request->ips();

                    $respuesta = $credencial;
                }
                else {
                    abort(400, "No existe la credencial ingresada.");
                }
                break;
            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;
            case 'consulta_existencia':
                /**
                 * Buscar si existe un valor en una tabla y campo especifico
                 *
                 * $data = array('tabla', 'campo', 'valor');
                 */
                $respuesta = DB::select('EXECUTE grl.Sp_SEL_Verificar_Existe_Campo ?, ?, ?', $data);

                if ($respuesta[0]->iResult == 0){
                    $respuesta = ['error' => false, 'msg' => ''];
                } else {
                    $respuesta = ['error' => true, 'msg' => 'Ya se encuentra registrado'];
                }

                // dd($respuesta[0]->iResult);
                break;
        }
        return response()->json($respuesta);
    }

    public function getData(Request $request, $tipo, $interno=false)
    {
        // return response()->json(['t' => $tipo, 'r' => $request->all()]);
        $data = $request->get('data');
        $dataObj = json_decode(json_encode($data));

        $respuesta = null;
        switch ($tipo) {
            case 'buscar_credenciales':
                if (isset(request()->id) || isset($dataObj->id)){
                    $respuesta = collect(DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', [request()->id??$dataObj->id]));
                }
                else {
                    $respuesta = collect(DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', [request()->txtBuscar??$dataObj->txtBuscar]));
                }

                break;
            case 'credenciales':

                $perfilesModulo = [];
                $credencial = auth()->user();

                if (isset($request->modulo)) {
                    $modulos = SegModulo::where('cModuloCodigo', $request->modulo)->with('perfiles')->first();
                    foreach ($modulos->perfiles as $perfilModulo){
                        $perfilesModulo[] = $perfilModulo->iPerfilId;
                    }
                }

                $colectRetorno = collect();
                // return response()->json($modulos);

                $respuestaPerfiles = collect( DB::select('seg.Sp_SEL_credenciales_perfilesXiCredId ?', [$credencial->iCredId]) );

                foreach ($respuestaPerfiles as $perfile){
                    if (in_array($perfile->iPerfilId, $perfilesModulo)) {
                        $colectRetorno->add($perfile);
                    }
                }

                $oficinas = collect( DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", [$credencial->iCredId]) );

                $credencial->perfiles = $colectRetorno;
                $credencial->modulo = $modulos;
                $credencial->oficinas = $oficinas;
                $credencial->ip = $request->ips();
                $credencial->ips = $request->toArray();
                $credencial->ipss = $request->getClientIp();
                $credencial->ipsss = $request->getClientIps();

                $respuesta = $credencial;

                break;
            case 'contactos_persona':
                $respuesta = DB::select('EXEC grl.Sp_SEL_persona_tipo_contactosXiPersId ?', [auth()->user()->iPersId]);
                break;
            case 'data_credencial':
                if (!isset($data[0])) {
                    $data[0] = auth()->user()->iCredId;
                }
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', $data);
                break;
            case 'data_oficinas_usuario':
                if (isset($request->idCredencial)){
                    $data = [$request->idCredencial];
                }
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;
            case 'data_persona':
                // return response()->json($request->all());
                if (isset($dataObj->idPersona)) {
                    $respuesta = collect(DB::select("EXEC grl.Sp_SEL_personasXiPersId ?", [$dataObj->idPersona]))->first();
                }
                elseif (isset($dataObj->idTipo)) {
                    $respuesta = DB::select("EXEC grl.Sp_SEL_personasXiTipoPersIdXcDocumento_cDescripcion ? ,?", [$dataObj->idTipo, $dataObj->txtBuscar??'%%']);
                }
                else {
                    $respuesta = DB::select("EXEC grl.Sp_SEL_personasXcDocumento_cDescripcion ?", [$dataObj->txtBuscar??'%%']);
                }
                break;
            case 'data_dependencias':
                $respuesta = collect(DB::select('EXEC grl.Sp_SEL_dependenciasXiEntIdXcDepenNombre 1, ?', [$dataObj->txtBuscar??'%%']));
                break;
            case 'bancos':
                if (isset($dataObj->id)){
                    $respuesta = collect(DB::select('grl.Sp_SEL_bancosXiBancoId ?', [$dataObj->id]));
                }
                else {
                    $respuesta = collect(DB::select('grl.Sp_SEL_bancos'));
                }
                // $respuesta = $this->consultasSimples($dataObj, 'rhh.Sp_SEL_bancosXiBancoId ?', 'rhh.Sp_SEL_bancos');
                break;

        }

        if ($interno){
            return $respuesta;
        }
        return response()->json($respuesta);
    }

    public function setData(Request $request, $tipo) {
        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));

        $jsonResponse = [];
        DB::beginTransaction();
        try {
            switch ($tipo) {
                case 'actualizarDatosInicial':

                    $dataContacto = [
                        auth()->user()->iPersId,
                        $dataObj->celular,
                        $dataObj->email,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null,
                    ];
                    $rptaContactos = collect(DB::select('EXEC grl.Sp_INS_UPD_TelefonoMovilCorreoElectronicoXiPersId ?, ?, ?        , ?, ?, ?, ? ', $dataContacto ))->first();

                    if ($rptaContactos->iResult == 1){

                        $dataGuardar = [
                            auth()->user()->cCredUsuario,

                            auth()->user()->iCredId,
                            null,
                            $request->getClientIp(),
                            null,

                            $dataObj->new_password
                        ];

                        $respuesta = collect(DB::select('EXEC ura.Sp_GRAL_UPD_cambioContrasenia ?, ?,           ?, ?, ?, ? ', $dataGuardar ))->first();

                        if ($respuesta->iResult == 1){
                            $jsonResponse = [
                                'error' => false,
                                'msg' => 'Se guardo Correctamente',
                                'data' => ['cambiar_pwd' => $respuesta, 'actualizar_contacto' => $rptaContactos]
                            ];
                        }
                        else {
                            abort(503, 'Error: La contraseña no se actualizó.');
                        }
                    }
                    else {
                        abort(503, 'Error: No se pudieron guardar los datos de contacto y contraseña.');
                    }
                    break;
            }
            DB::commit();
        }
        catch (\Exception $e) {
            $this->returnError($e);
            DB::rollback();
        }
        return $this->retornoJson($jsonResponse);
    }



    private function retornoJson($data){
        return response()->json($data);
    }
    public static function returnError(\Exception $e){
        $msgResuelto = '';
        if (isset($e->errorInfo)){
            $msgResuelto = substr($e->errorInfo[2], 54); //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
        }

        abort(503, ($msgResuelto != '')?$msgResuelto:$e->getMessage());
    }
}
