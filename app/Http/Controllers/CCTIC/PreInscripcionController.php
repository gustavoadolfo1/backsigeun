<?php

namespace App\Http\Controllers\CCTIC;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\cctic\PreInscripcion;

use App\Http\Controllers\CCTIC\emailController;

class PreInscripcionController extends Controller
{

    public function registrarPreInscripcion(Request $request)
    {
        // si no hay personId lo registra en preinscripciones y en personas caso contrario solo preinscripciones
        $this->validate(
            $request,
            [
                'iPersId'                        => 'required', // integer o false
                'iFilId'                         => 'required',
                'iNacionId'                      => 'required',
                'iTipoIdentId'                   => 'required',
                'iPublicoObjetivoId'             => 'required',
                'iModalEstudId'                  => 'required',
                'cPersDocumento'                 => 'required',
                'cPersPaterno'                   => 'required',
                'cPersMaterno'                   => 'required',
                'cPersNombre'                    => 'required',
                'cPerSexo'                       => 'required',
                'dFechaNac'                      => 'required',
                'cPreinscripcionCelular'         => 'required',
                'cPreinscripcionEmail'           => 'required',
                'cPreinscripcionDireccion'       => 'required',
                'bPreinscripcionPideEstado'      => 'required',
                'iPreHorariosPublicacionId'      => 'required'
            ]
        );

        $parameters = [
            $request->iPersId,
            3,
            $request->iFilId,
            $request->iNacionId,
            $request->iTipoIdentId,
            $request->iPublicoObjetivoId,
            $request->iModalEstudId,
            // null,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPerSexo,
            $request->dFechaNac,
            $request->cPersFotografia,
            $request->cPreinscripcionTelefoto,
            $request->cPreinscripcionCelular,
            $request->cPreinscripcionEmail,
            $request->cPreinscripcionDireccion,
            $request->bPreinscripcionPideEstado,
            0,
            '',
            0,
            $request->cPreinscripcionSugHorario,
            $request->iPreHorariosPublicacionId,
            gethostname(),
            $request->getClientIp(),
            gethostname(),
            'mac' //31
        ];


        $parametersPersona = [
            1,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            /*Persona Natural*/
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPerSexo, //cPersSexo,
            $request->dFechaNac, //dPersNacimiento,
            /*Persona Juridica*/
            null,
            null,
            null,
            null,
            /*Campos de autoria*/
            null,
            gethostname(),
            $request->getClientIp(),
            strtok(exec('getmac'), ' ')
        ];

        $sendEmail = new emailController;

        try {
            if (!$request->iPersId) {
                $persona = DB::select('exec [grl].[Sp_INS_personas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametersPersona);
                $parameters[0] = $persona[0]->iPersId;
            }

            // insert preinscripcion
            $preinscripcion =  DB::select('exec [acad].[Sp_CCTIC_INS_PreIncripciones_Insertar] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            // send email
            $request->iPreinscripcionId = $preinscripcion[0]->iPreinscripcionId;
            $request->preinscripcionCreated = true;

            $sendEmail->enviarCorreoAPreinscrito($request);

            $response = ['validated' => true, 'data' => [], 'mensaje' => 'Se ha creado correctamente la pre-inscripcion'];
            $codeRespones = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeRespones = 500;
        }
        return response()->json($response, $codeRespones);
    }
    public function actualizarPreinscripcion(Request $request)
    {

        $this->validate(
            $request,
            [
                'iPreinscripcionId'              => 'required|integer',
                'iPersId'                        => 'required|integer',
                'iFilId'                         => 'required|integer',
                'iNacionId'                      => 'required|integer',
                'iTipoIdentId'                   => 'required|integer',
                'iPublicoObjetivoId'             => 'required|integer',
                'iModalEstudId'                  => 'required|integer',
                'cPersDocumento'                 => 'required',
                'cPersPaterno'                   => 'required',
                'cPersMaterno'                   => 'required',
                'cPersNombre'                    => 'required',
                'cPerSexo'                       => 'required',
                'dFechaNac'                      => 'required',
                'cPreinscripcionCelular'         => 'required',
                'cPreinscripcionEmail'           => 'required',
                'cPreinscripcionDireccion'       => 'required',
                'bPreinscripcionPideEstado'      => 'required',
                'iPreHorariosPublicacionId'      => 'required|integer'
            ]
        );

        $parameters = [
            $request->iPreinscripcionId,
            $request->iPersId,
            3,
            $request->iFilId,
            $request->iNacionId,
            $request->iTipoIdentId,
            $request->iPublicoObjetivoId,
            $request->iModalEstudId,
            // null,
            $request->cPersDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPerSexo,
            $request->dFechaNac,
            $request->cPersFotografia,
            $request->cPreinscripcionTelefoto,
            $request->cPreinscripcionCelular,
            $request->cPreinscripcionEmail,
            $request->cPreinscripcionDireccion,
            $request->bPreinscripcionPideEstado,
            0,
            '',
            0,
            $request->cPreinscripcionSugHorario,
            $request->iPreHorariosPublicacionId,
            gethostname(),
            $request->getClientIp(),
            gethostname(),
            'mac' //31
        ];

        $sendEmail = new emailController;

        try {

            // update preinscripcion
            $queryResult =  DB::select('exec [acad].[Sp_CCTIC_UPD_PreIncripciones_Actualizar] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            // send email
            $sendEmail->enviarCorreoAPreinscrito($request);

            $response = ['validated' => true, 'data' => [], 'mensaje' => 'Se ha actualizado correctamente la pre-inscripcion'];
            $codeRespones = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeRespones = 500;
        }
        return response()->json($response, $codeRespones);
    }

    public function obtenerPreinscripcionesPorDNI(Request $request)
    {
        $this->validate(
            $request,
            [
                'dni' => 'required',
            ]
        );

        $parameters = [
            $request->dni
        ];
        try {
            $preinscripciones = DB::select('exec [acad].[Sp_CCTIC_SEL_PreIncripciones_MostrarInfo] ?', $parameters);
            $response = ['validated' => true, 'data' => $preinscripciones,  'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    public function obtenerPreInscripcionesByModulo()
    {
        try {
            //            exec store procedure
            $response = ['validated' => true, 'mensaje' => 'Se ah sellecionado las pre inscripciones correctamente'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => 'No se pudo obtener las pre inscripciones'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function obtenerPreinscritos()
    {
        $parameters = [
            Input::get('carrera'),
            Input::get('fechaInicio'),
            Input::get('fechaFin'),
            Input::get('filial'),
            Input::get('programaAcad'),
            Input::get('iConfigDiasId'),
            Input::get('horaInicio'),
            Input::get('horaFin'),
        ];


        try {
            $preinscritos = \DB::select('exec [acad].[Sp_SEL_obtenerPreinscritos] ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $preinscritos];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => []];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerPreinscritosAdeudos()
    {

        $parameters = [
            Input::get('carrera'),
            Input::get('fechaInicio'),
            Input::get('fechaFin'),
            Input::get('filial'),
            Input::get('programaAcad'),
            Input::get('iConfigDiasId'),
            Input::get('horaInicio'),
            Input::get('horaFin'),
        ];
        try {
            $adeudos = \DB::select('exec [acad].[Sp_SEL_preinscritos_adeudos] ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $adeudos, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function getCantidadPreinsModulo()
    {
        $parameters = [
            Input::get('fechaInicio'),
            Input::get('fechaFin'),
            Input::get('filial'),
            Input::get('programaAcad'),
            Input::get('carrera'),
        ];
        try {
            $preInscripciones = \DB::select('[acad].[Sp_SEL_cant_preinscrip_ModuloXfechas_progAcad_filial] ?, ?, ?, ?, ?', $parameters);


            foreach ($preInscripciones as $key => $row) {

                $paramnsPreinscrios = [
                    Input::get('carrera'),
                    Input::get('fechaInicio'),
                    Input::get('fechaFin'),
                    Input::get('filial'),
                    Input::get('programaAcad'),
                    $row->iConfigDiasId,
                    $row->horaInicio,
                    $row->horaFin,
                ];
                $row->cantidadPreinscritos = \DB::select('exec [acad].[Sp_SEL_cantidad_preinscrip_carreraIdXfechasXhorasXturnoId] ?, ?, ?, ?, ?, ?, ?, ?', $paramnsPreinscrios)[0]->cantidadPreniscritos;
            }

            $adeudos = $this->obtenerCantidadPreinsAdeudo(
                Input::get('fechaInicio'),
                Input::get('fechaFin'),
                Input::get('filial'),
                Input::get('programaAcad'),
                Input::get('carrera')
            );

            $data = [
                'preinscripciones' => $preInscripciones,
                'adeudos' => $adeudos->original['data'],
            ];

            $response = ['success' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['success' => false, $e->getMessage()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }


    public function preInscripcionRecursos()
    {
        $data = [];
        try {
            $modalidades = \DB::select('exec [acad].[Sp_SEL_modalidades_estudios]');
            $data['modalidades'] = $modalidades;

            $programAcad = \Db::select('exec[acad].[Sp_SEL_programas_academicos]');
            $data['prgamAcad'] = $programAcad;

            $tipoIngres = \DB::select('exec  [acad].[Sp_SEL_tipos_ingresantes_servicios]');
            $data['tipoIngresantes'] = $tipoIngres;

            $talleres = \DB::select('exec [acad].[Sp_SEL_carrerasXiProgramasAcadId] ?', [3]);

            foreach ($talleres as $key => $value) {
                // obtener modulos por carrera/taller
                $talleres[$key]->modulos = $this->obtenerModulosByCarrera($value->iCarreraId, 3);
            }

            $data['talleres'] = $talleres;

            $response = ['validated' => true, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => $e->getMessage()];
            $codeResponse = 500;
        }


        return response()->json($response, $codeResponse);
    }

    public function obtenerModulosByCarrera($carrera_id, $programAcad_id)
    {
        try {
            $modulos = \DB::select(
                '[acad].[Sp_SEL_modulos_programasXiCarreraIdXiProgramasAcadId] ?, ?',
                [$carrera_id, $programAcad_id]
            );
            foreach ($modulos as $key => $value) {
                $modulos[$key]->cursos = $this->obtenerCursosByModulo($value->iCarreraId, $value->cCiclosId);
            }
        } catch (\Exception $e) {
            $modulos = [];
        }

        return $modulos;
    }


    public function obtenerCursosByModulo($modulo_id, $curricDetCicloCurso)
    {
        try {
            $cursos = \DB::select('exec [acad].[Sp_SEL_cursosModulosXiCarreraIdXiModProgId] ?, ?', [$modulo_id, $curricDetCicloCurso]);
        } catch (\Exception $e) {
            $cursos = [];
        }

        return $cursos;
    }


    public function obtenerPreInscripcion($id)
    {
        try {
            //            exec store procedure
            $response = ['validated' => true, 'mensaje' => 'Se obtuvo la pre inscripcion cerrectamente'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => 'No se pudo obtener la inscripcion'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }


    public function validarPreInscripcion(Request $request)
    {
        try {
            $response = ['validated' => true, 'mensaje' => 'Se valido la pre inscripcion correctament'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => 'No se pudo validar la inscipcion'];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function tipoPersona($dni)
    {

        //        1 = estudiante
        //        2 = docente
        //        3 = no encontrado

        $tipePersona = ['tipoPersona' => [
            'estudiante' => false,
            'docente' => false,
            'noEncontrado' => false
        ], 'tipoPersonaDesc' => ''];

        try {
            $data = \DB::select('[acad].[Sp_SEL_estudiante_carreraXdni] ?', [$dni]);
            if (!empty($data)) {
                $tipePersona['tipoPersona']['estudiante'] = true;
                $tipePersona['tipoPersonaDesc'] = 'Estudiante';
                $response = ['validate' => true, 'mensaje' => '', 'persona' => $tipePersona, 'data' => $data];
                $codeResponse = 200;
                return response()->json($response, $codeResponse);
            }

            $data = \DB::select('[acad].[Sp_SEL_docenteXdni] ?', [$dni]);
            if (!empty($data)) {
                $tipePersona['tipoPersona']['docente'] = true;
                $tipePersona['tipoPersonaDesc'] = 'Docente';
                $response = ['validate' => true, 'mensaje' => '', 'persona' => $tipePersona, 'data' => $data];
                $codeResponse = 200;
                return response()->json($response, $codeResponse);
            }

            $tipePersona['tipoPersona']['noEncontrado'] = true;
            $tipePersona['tipoPersonaDesc'] = 'No encontrado';
            $response = ['validate' => true, 'mensaje' => '', 'persona' => $tipePersona, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'mensaje' => 'Error al buscar tipo de persona'];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }

    public function byDNI($dni)
    {

        try {
            $preinscrito = \DB::select('[acad].[SP_SEL_preinscritoXdni] ?', [$dni]);
            return response()->json(['data' => $preinscrito]);
            $response = ['validate' => true, 'mensaje' => 'obtenido correctamente', 'persona' => $tipePersona, 'data' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
        }
    }

    public function obtenerCantidadPreinsAdeudo($fechaInicio, $fechaFin, $filial, $programAcad, $carrera)
    {
        $parameters = [
            $fechaInicio, $fechaFin, $filial, $programAcad, $carrera,
            'CCTIC'
        ];

        try {

            $preadeudos = \DB::select('exec [acad].[Sp_SEL_cant_preinscritos_adeduos] ?, ?, ?, ?, ?, ?', $parameters);

            foreach ($preadeudos as $key => $row) {

                $paramnsPreinscrios = [
                    $carrera,
                    $fechaInicio,
                    $fechaFin,
                    $filial,
                    $programAcad,
                    $row->iConfigDiasId,
                    $row->horaInicio,
                    $row->horaFin,
                ];
                $row->cantidadPreinscritos = \DB::select('exec [acad].[Sp_SEL_cantidad_preinscrip_adeudo] ?, ?, ?, ?, ?, ?, ?, ?', $paramnsPreinscrios)[0]->cantidadPreniscritos;
            }

            $response = ['validated' => true, 'data' => $preadeudos];

            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    // public function test()
    // {
    //     try {

    //         $pdf =  \PDF::loadView('cctic.test');
    //         $pdf->setPaper('A4');
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    //     return $pdf->stream('test.pdf');
    // }


    public function crearPreinscripcion(Request $request)
    {
        $parameters = [
            $request->iPersId,
            $request->iProgaramAcadId,
            $request->iNacionId,
            $request->iTipoIdentId,
            $request->iPublicacionId,
            $request->iTiposIngServId,
            $request->iModalEstudId,
            $request->iPreHorarioId,
            $request->iCurriculaModulo,
            $request->cPursDocumento,
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPerSexo,
            $request->dFechaNac,
            $request->cPersFotografia,
            $request->cPreinscripcionTelefoto,
            $request->cPreinscripcionCelular,
            $request->cPreinscripcionEmail,
            $request->cPreinscripcionDireccion,
            $request->cPreinscripcionDireccionActual,
            $request->bPreinscripcionPideEstado,
            $request->bPreinscripcionLlamadaEstado,
            $request->bPreinscripcionVistaEstado,
            $request->cPursDocumento,
            $request->getClientIp(),
            'N',
            'mac',
        ];

        DB::beginTransaction();
        try {
            $result = DB::select('EXEC acad.Sp_INS_preinscripciones ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parameters);

            $response = ['validated' => true, 'data' => $result, 'message' => 'preinscirpcion creada correctamente'];
            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['validated' => false, 'data' => [], $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }




    public function obtenerPreinscritosByPublicacionHoario(Request $request)
    {
        $iPreHorariosPublicacionId = $request->input('iPreHorariosPublicacionId');

        try {
            $preinscritos = PreInscripcion::where('iPreHorariosPublicacionId', '=', $iPreHorariosPublicacionId)
                ->get();


            $response = ['validated' => true, 'data' => $preinscritos, 'message' => 'preinscritos seleccionados correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function updateObservacionesPreinscrito(Request $request)
    {
        DB::beginTransaction();
        try {

            $preinscricpion = DB::table('acad.preinscripciones')
                ->where('iPreinscripcionId', '=', $request->iPreinscripcionId)
                ->update(['cPreinscripcionDetalleLlamada' => $request->cPreinscripcionDetalleLlamada, 'bPreinscripcionLlamadaEstado' => 1]);

            $response = ['validated' => true, 'data ' => $preinscricpion, 'message' => 'Preinscripcion acutualizada correctament'];
            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => 0, $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function byPersona(Request $request)
    {
        $params = [
            $request->dni,
            $request->programAcad,
            $request->filial
        ];
        try {

            $preinscripciones = DB::select('exec [acad].[Sp_CCTIC_SEL_Panel_Muestra_PreInscripciones_DNI] ?, ?, ?', $params);

            foreach ($preinscripciones as $preinscripcion) {
                $preinscripcion->Horarios = json_decode($preinscripcion->Horarios);
            }


            $response = ['validated' => true, 'data' => $preinscripciones, 'message' => 'Preinscripciones obtenidas correctamente'];
            $responseCode = 200;

        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'error' => $e->getMessage(), 'message' => 'No se pudo obtener las preinscripciones'];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function listaPreinscrtiosPDF(Request $request)
    {
        $filtro =  $request->filtro;
        $preinscritos =  $request->preinscritos;
        $curso =  $request->curso;

        $pdf = \PDF::loadView('cctic.preinscritos', compact(['filtro', 'preinscritos', 'curso']))->setPaper('A4');
//        return $pdf->download("cctic.pdf");
        return $pdf->stream();
    }


    public function eliminarPreinscripcionByID($id)
    {
        DB::beginTransaction();

        try {
            Db::table('acad.preinscripciones')
                ->where('iPreinscripcionId', '=', $id)
                ->delete();

            $response = ['validated' => true, 'data' => [], 'message' => 'Preinscripcion eliminada correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'error' => $e->getMessage(), 'message' => 'No se pudo eliminar la preinscripcion'];
            $responseCode = 500;
        }

        DB::commit();

        return response()->json($response, $responseCode);
    }
}
