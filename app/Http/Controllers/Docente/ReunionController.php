<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;

class ReunionController extends Controller
{
    public function reunion(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();
        $id     = $request->id;
        $errorJson = new TramitesController();

        $result = null;

        switch ($method) {
            case 'POST':

                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [

                    'iControlCicloAcad' => 'required',
                    'iFilId' => 'required',
                    'iCarreraId' => 'required',
                    'iCurricId' => 'required',
                    'cCurricCursoCod' => 'required',
                    'iSeccionId' => 'required',
                    'iDocenteId' => 'required',
                    'cSignatureEndpoint' => 'required',
                    'cMeetingNumber' => 'required',
                    'iRole' => 'required',
                    'cLeaveUrl' => 'required',
                    'cUserName' => 'required',
                    'cUserEmail' => 'required',
                    'cPassWord' => 'required',

                    'cTema' => 'required',
                    'dInicio' => 'required',
                    'tInicio' => 'required',
                    'iDuracionHoras' => 'required',
                    'iDuracionMin' => 'required',
                    'iEstado' => 'required',
                    'cCreated_at' => 'required',
                    'iDuration' => 'required',
                    'cHost_id' => 'required',
                    'iId' => 'required',
                    'cJoin_url' => 'required',
                    'cSettings' => 'required',
                    'cStart_time' => 'required',
                    'cStart_url' => 'required',
                    'cStatus' => 'required',
                    'cTimezone' => 'required',
                    'cTopic' => 'required',
                    'iType' => 'required',
                    'cUuid' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                try {
                    $responseJson = DB::select('EXEC ura.Sp_DOCE_INS_UPD_Reunion ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,', [

                        $data['iReunionCtaId'],

                        $data['iControlCicloAcad'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['iCurricId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],
                        $data['iDocenteId'],
                        $data['cSignatureEndpoint'],
                        $data['cMeetingNumber'],
                        $data['iRole'],
                        $data['cLeaveUrl'],
                        $data['cUserName'],
                        $data['cUserEmail'],
                        $data['cPassWord'],

                        $data['cTema'],
                        $data['dInicio'],
                        $data['tInicio'],
                        $data['iDuracionHoras'],
                        $data['iDuracionMin'],
                        $data['iEstado'],
                        $data['cCreated_at'],
                        $data['iDuration'],
                        $data['cHost_id'],
                        $data['iId'],
                        $data['cJoin_url'],
                        $data['cSettings'],
                        $data['cStart_time'],
                        $data['cStart_url'],
                        $data['cStatus'],
                        $data['cTimezone'],
                        $data['cTopic'],
                        $data['iType'],
                        $data['cUuid'],

                        auth()->user()->iCredId,
                        gethostname(),
                        $request->getClientIp(),
                        null,

                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamene.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }
                break;
        }

        return response()->json($responseJson);
    }
}
