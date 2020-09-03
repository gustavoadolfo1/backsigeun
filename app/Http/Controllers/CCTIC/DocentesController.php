<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Docente\Dedicacion;
use App\Http\Resources\CCTIC\DedicacionDocenteResource;
use App\Model\cctic\Docente;
use App\GrlPersona;
use App\Http\Resources\CCTIC\DocenteResource;
use Illuminate\Support\Facades\DB;


class DocentesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate(
            $request,
            [
                'iFilId'  => 'required',
                'bDocenteActivo' => 'required'
            ]
        );

        $parameters = [
            $request->iFilId,
            $request->bDocenteActivo,
        ];

        try {
            $docente = DB::select('exec [acad].[Sp_CCTIC_SEL_Docente_mostrarDocenteXSede] ?, ?', $parameters);

            $response = ['validated' => true, 'message' => 'datos obtenidos correctamente', 'data' => $docente];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener docentes', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function activarDocente(Request $request)
    {
        $this->validate(
            $request,
            [
                "docenteId"        => "required|integer",
                "active"        => "required",
            ]
        );
        $data = [
            'bDocenteActivo' => $request->active,
        ];

        $da = Docente::where('iDocenteId', $request->docenteId)
            ->update($data);

        return $request->docenteId;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get cv file url
        // $cvPath = 'cctic/docentes/qAmFeOzeDAPcQWODBBIkOtBR0Yy2bxVRWKbgl41N.pdf';
        // $cvUrl = Storage::url($cvPath);
        // return response()->json(['cv_url' => $cvUrl], 201);
        // end get cv file url

        $filePathName = null;
        if ($request->hasFile("cDocentecvPath")) {
            $filePathName = $request->file("cDocentecvPath")->store('cctic/docentes');
        }

        $this->validate(
            $request,
            [
                'iPersId'            => 'required',
                'iFilId'             => 'required',
                'iGradoAcadId'       => 'required',
                'cDescGradoAcad'     => 'required',
                'cDocenteDoc'        => 'required',
                'cDocenteCel'        => 'required',
                'cDocenteCorreoElec' => 'required',
                'cDocenteDirec'      => 'required',
                'dDocenteFechNac'    => 'required',
                'bDocentePide'       => 'required',
                //'cDocenteRuc'        => 'required',
                //   'cDocentecvPath'     => 'required'
            ],
            [
                // 'iTipoApertura.required' => 'Hubo un problema al obtener información del select.',
            ]
        );

        $parameters = [
            $request->iPersId,
            3,
            $request->iFilId,
            $request->iGradoAcadId,
            $request->cDescGradoAcad,
            $request->cDocenteDoc,
            $request->cDocenteCel,
            $request->cDocenteCorreoElec,
            $request->cDocenteTel,
            $request->cDocenteDirec,
            $request->dDocenteFechNac,
            $request->bDocentePide,
            $request->cDocenteRuc,
            $filePathName,
            $request->cDocenUsuarioSis,
            gethostname(),
            gethostname(),
            $request->getClientIp()
        ];

        $person = [
            1,
            $request->iTipoIdentId,
            $request->cDocenteDoc,
            /*Persona Natural*/
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPerSexo, //cPersSexo,
            $request->dDocenteFechNac,
            /*Persona Juridica*/
            null,
            null,
            null,
            null,
            /*Campos de autoria*/
            null,
            gethostname(),
            $request->getClientIp(),
            'getmac'
        ];

        try {
            $docente = DB::select('exec [acad].[Sp_CCTIC_INS_Docentes_GeneraDocente] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            if (!$request->iPersId) {
                $persona = DB::select('exec [grl].[Sp_INS_personas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $person);
            }

            $response = ['validated' => true, 'message' => 'Docente registrado correctamente', 'data' => $docente];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo registrar el docente', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function getTiposDedicacion()
    {
        $dedicacion = Dedicacion::all();
        return DedicacionDocenteResource::collection($dedicacion);
    }

    public function byDNI($dni)
    {
        try {
            $docente = DB::select('exec [acad].[Sp_CCTIC_SEL_Panel_Muestra_InfoDocente] ?', [$dni]);

            if (count($docente) == 0) {
                return response()->json(['validated' => true, 'message' => 'No se encontro un docente con este DNI', 'data' => new stdClass()], 200);
            }

            $docente = $docente[0];

            $response = ['validated' => true, 'message' => 'Docente obtenido correctamente', 'data' => $docente];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener el docente', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function asignarFilialPrograma(Request $request)
    {
        $this->validate(
            $request,
            [
                'iDocenteId' => 'required',
                'iFilId'  => 'required'
                // 'iProgramaAcadId' => 'required'
            ]
        );

        $parameters = [
            $request->iDocenteId,
            $request->iFilId,
            3
        ];

        try {
            $docente = DB::select('exec [acad].[Sp_CCTIC_SEL_General_Docentes_Asignar_FilialProgramaAcad] ?, ?, ?', $parameters);

            $response = ['validated' => true, 'message' => 'Docente obtenido correctamente', 'data' => $docente];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener el docente', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function obtenerFilialDocentexDocumento(Request $request)
    {
        $this->validate(
            $request,
            [
                'cDocenteDoc' => 'required'
            ]
        );

        $parameters = [
            $request->cDocenteDoc
        ];

        try {
            $docente = DB::select('exec [acad].[Sp_CCTIC_SEL_Docentes_mostrarDocenteXDocumento] ?', $parameters);

            $response = ['validated' => true, 'message' => 'Docente obtenido correctamente', 'data' => $docente];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'No se puedo obtener el docente', 'error' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
}
