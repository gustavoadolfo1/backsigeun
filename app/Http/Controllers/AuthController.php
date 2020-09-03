<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Tram\TramitesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'forgot', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = ['cCredUsuario' => $request->usuario, 'password' => $request->password];

        if ($request->modulo == 'BIBLIOTECA') {

            $cBusqueda = collect(DB::select('EXEC bib.Sp_SEL_credenciales_rolXcBusqueda ?', [$request->usuario]));
            //return $cBusqueda;
            $cRol = Trim($cBusqueda[0]->cRol);
        }

        //return $cRol;
        $idsNoVerificar = [
            12, //TRAMITE
        ];
        if ($request->modulo && !in_array($request->modulo, $idsNoVerificar)) {
            $value = $this->verificarAccesoModulo($request->usuario, $request->modulo);
            if ($value == 0) {
                return response()->json(['error' => 'No cuenta con permisos para ingresar a este módulo.'], 403);
            }
            if ($value == -1) {
                return response()->json(['error' => 'El usuario no existe en nuestros registros.'], 403);
            }
        }
        //verifica usuario con y sin dependencia
        /*
        if ($request->conDependencia == 'no') {
            if (! $this->verificarAccesoConSinDependencia($request->usuario, $request->tipoUsuario)) {
                return response()->json(['error' => 'UnauthorizedTipoUsuario'], 401);
            }
        }
        */

        if (!$token = auth()->claims(['role' => $cRol ?? ''])->attempt($credentials)) {
            return response()->json(['error' => 'Verifica tu usuario y contraseña'], 401);
        }

        $samePass = $this->checkIfSamePassword(auth()->user());

        $isSuccess = $this->guardarLogInicioSesion($request, 'login');

        if (!$isSuccess) {
            auth()->logout();
            return response()->json(['error' => 'Hubo un problema en el servidor, inténtelo nuevamente.'], 500);
        }

        return $this->respondWithToken($token, $samePass);
    }

    public function verificarAccesoConSinDependencia($credencial, $tipoUsuario)
    {
        switch ($tipoUsuario) {
            case 1: //integrante de proyecto
                $data = \DB::select('exec [inv].[Sp_SEL_credencialesMiembroProyectoXcCampoBusqueda] ?', array($credencial));
                break;
            case 2: //par evaluador
                $data = \DB::select('exec [inv].[Sp_SEL_credencialesParEvaluadorProyectoXcCampoBusqueda] ?', array($credencial));
                break;
        }
        $access = false;
        foreach ($data as $reg) {
            if ($reg->numProyectos > 0) {
                $access = true;
                break;
            }
        }
        return $access;
    }

    public function verificarAccesoModulo($credencial, $moduloCod)
    {
        $modulos = \DB::select('exec [seg].[Sp_SEL_modulos_credencial] ?', array($credencial));

        if (count($modulos) == 0) {
            $access = -1;
            return $access;
        }

        $access = 0;
        foreach ($modulos as $modulo) {
            if ($modulo->cModuloCodigo == $moduloCod || $modulo->iModuloId == $moduloCod) {
                $access = 1;
                break;
            }
        }
        return $access;
    }

    public function forgot(Request $request)
    {

        $dataDocumento = collect(DB::select('EXEC seg.Sp_SEL_credencialesXcCredUsuario ?', [$request->dni]));
        if ($dataDocumento->count() > 0) {
            $datCredencial = $dataDocumento->first();
            // dd($request->toArray());

            if ($request->token) {

                $this->validate(
                    $request,
                    [
                        'token' => 'required',
                        'new_password' => 'required|min:6',
                        're_new_password' => 'required|min:6|same:new_password'
                    ],
                    [
                        'token.required' => 'Debe ingresar el token proporcionado.',
                        'new_password.required' => 'Nueva contraseña es obligatorio',
                        'new_password.min' => 'La Nueva contraseña debe ser de al menos :min caracteres',
                        're_new_password.required' => 'Debe volver a escribir la nueva contraseña',
                        're_new_password.min' => 'Las contraseñas no coinciden.',
                        're_new_password.same' => 'Las contraseñas no coinciden.',
                    ]
                );

                if (($request->token == $datCredencial->cCredToken)) {
                    $dataGuardar = [
                        $datCredencial->cCredUsuario,
                        $datCredencial->iCredId,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac',
                        $request->new_password
                    ];

                    $retCambio = collect(DB::select('EXEC ura.Sp_GRAL_UPD_cambioContrasenia ?, ?, ?, ?, ?, ?', $dataGuardar));
                    if (isset($retCambio->first()->iResult)) {
                        $dataGuardar = [
                            $datCredencial->iCredId,
                            NULL,
                            NULL,
                            $datCredencial->iCredId,
                            'equipo',
                            $request->server->get('REMOTE_ADDR'),
                            'mac',
                        ];
                        $retCambio = collect(DB::select('EXEC seg.Sp_UPD_cCredToken_iCredIntentos_credencialesXiCredId ?, ?, ?, ?, ?, ?, ?', $dataGuardar));

                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Contraseña Actualizada correctamente.',
                            'data' => ['retCambio' => true]
                        ];
                    } else {
                        abort(503, 'Hubo un error en el sistema, por favor comuniquelo al administrador.');
                    }
                } else {
                    $dataGuardar = [
                        $datCredencial->iCredId,
                        $datCredencial->iCredIntentos ?? NULL,
                        ($datCredencial->iCredIntentos ?? 0) + 1,

                        $datCredencial->iCredId,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac',
                    ];
                    // $retCambio = collect(DB::select('EXEC seg.Sp_UPD_cCredToken_iCredIntentos_credencialesXiCredId ?, ?, ?,      ?, ?, ?, ?', $dataGuardar));
                    try {
                        $retCambio = collect(DB::select('EXEC seg.Sp_UPD_cCredToken_iCredIntentos_credencialesXiCredId ?, ?, ?,      ?, ?, ?, ?', $dataGuardar));
                    } catch (\Exception $e) {
                        $jsonResponse = TramitesController::returnError($e);
                        abort(500, $jsonResponse['msg']);
                    }
                    abort(503, 'El token ingresado es invalido para este usuario (intentos ' . $datCredencial->iCredIntentos . ' de un maximo de 3');
                }
            } else {
                if ($datCredencial->iCredIntentos <= 3) {

                    $dataContacto = collect(DB::select('EXEC grl.Sp_SEL_persona_tipo_contactosXiPersId ?', [$datCredencial->iPersId]));

                    $numRand = rand(10000, 99999);
                    $request->request->add([
                        'celular' => $dataContacto->first()->cTelefonoMovil,
                        'mensaje' => $numRand . ' es el token de validacion, tambien puedes restaurar tu contraseña en http://sigeun.unam.edu.pe/modulos/modtramite/sesion/olvidado/' . $request->dni . '/' . $numRand,
                    ]);
                    //$dPideSMS = PideController::consultar($request, 'sms', null, true);

                    $url = $request->server->get('HTTP_REFERER');

                    Mail::send('auth/forgot_password_email', ['dataUsuario' => $datCredencial, 'datAuth' => $request->dni . '/' . $numRand, 'token' => $numRand, 'url' => $url], function ($message) use ($dataContacto, $datCredencial) {
                        // Set the receiver and subject of the mail.
                        $message->to($dataContacto->first()->cCorreoElectronico, ucwords(strtolower($datCredencial->cPersNombre)))->subject('SIGEUN / Accion: Cambio de Contraseña');
                        // Set the sender
                        $message->from('soporte.sigeun@unam.edu.pe', 'Soporte SIGEUN');
                    });


                    // dd($dPideSMS);
                    $dataGuardar = [
                        $datCredencial->iCredId,
                        $numRand,
                        0,

                        $datCredencial->iCredId,
                        'equipo',
                        $request->server->get('REMOTE_ADDR'),
                        'mac',
                    ];

                    $retCambio = collect(DB::select('EXEC seg.Sp_UPD_cCredToken_iCredIntentos_credencialesXiCredId ?, ?, ?,      ?, ?, ?, ?', $dataGuardar));
                    if (($retCambio->count() > 0) && ($retCambio->first()->iResult == 1)) {
                        $mail = explode('@', $dataContacto->first()->cCorreoElectronico);
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Token de recuperación enviado al correo electrónico: ' . substr_replace($mail[0], '*******', 2, -2) . '@' . $mail[1],
                            'data' => [
                                'token' => true
                            ]
                        ];
                    } else {
                        abort(503, 'Error de Sistema - Comuníquese con el administrador');
                    }


                    // 953677714

                } else {
                    abort(503, 'Se superó el numero máximo de intentos (3), reintente en 10 minutos aprox');
                }
            }
        } else {
            abort(503, 'No existe el usuario especificado');
        }
        return response()->json($jsonResponse);
    }


    /**
     * Establece el atributo cCredUsuario como usuario
     *
     * @return string
     */
    public function username()
    {
        return 'cCredUsuario';
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout(true);

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $samePass = null)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'passSameToUser' => $samePass
        ]);
    }

    protected function checkIfSamePassword($user)
    {
        if ($user->password == hash('sha1', $user->cCredUsuario)) {
            return true;
        } else {
            return false;
        }
    }

    public function guardarLogInicioSesion(Request $request, $iniciador)
    {
        $parametros = [
            auth()->user()->iCredId,
            $request->modulo,
            $request->server->get('REMOTE_ADDR'),

            $request->dependencia ?? NULL,
        ];

        try {
            $data = \DB::select('EXEC [seg].[Sp_INS_accesoLogSistemas] ?, ?, ?, ?', $parametros);
            $isSuccess = true;

            $response = ['mensaje' => 'Acceso guardado', 'response' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $isSuccess = true;
            $codeResponse = 500;
            $response = ['mensaje' => 'Acceso no guardado'];
        }

        if ($iniciador == 'login') {
            return $isSuccess;
        }

        return response()->json( $response, $codeResponse );
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/api/register",
     *     tags={"Users"},
     *     summary="Create new User",
     *     @SWG\Parameter(
     * 			name="body",
     * 			in="body",
     * 			required=true,
     * 			@SWG\Schema(ref="#/definitions/User"),
     * 			description="Json format",
     * 		),
     *     @SWG\Response(
     *          response=201,
     *          description="Success: A Newly Created User",
     *          @SWG\Schema(ref="#/definitions/User")
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="Success: operation Successfully"
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Refused: Unauthenticated"
     *     ),
     *     @SWG\Response(
     *          response="422",
     *          description="Missing mandatory field"
     *     ),
     *     @SWG\Response(
     *          response="404",
     *          description="Not Found"
     *   )
     * ),
     */
    public function register(Request $request)
    {
        $messages = [
            'required' => 'Se requiere el campo :attribute.',
        ];
        $table = DB::connection('sqlsrv');
        //return $table;
        $validator = Validator::make($request->all(), [
            'cCredUsuario' => 'required',
            'password'=> 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'iTipoCredId' => 2,
            'cCredIpSis' => $request->getClientIp(),
            'cCredUsuarioSis' => $request->cCredUsuario,
            'cRol' => 'EXTERNO',
            //'dtCredRegistro' => date("Y-m-d H:i:s"),
            'cCredUsuario' => $request->cCredUsuario,
            'password' => Hash::make($request->password), //Hash::make  bcrypt
        ]);

        if ($request->modulo == 'BIBLIOTECA') {
            $cBusqueda = collect(DB::select('EXEC bib.Sp_SEL_credenciales_rolXcBusqueda ?', [$request->usuario]));

            $cRol = Trim($cBusqueda[0]->cRol);
        }

        $token = auth()->claims(['role' => $cRol ?? ''])->login($user); //auth()->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 201);
    }
}
