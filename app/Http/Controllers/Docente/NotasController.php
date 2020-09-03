<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tram\TramitesController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NotasController extends Controller
{
    public function insertarNotasUnidad(Request $notas)
    {

        $this->validate(
            $notas,
            [
                'iControlCicloAcad' => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'iCurricId'         => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'iDocenteId'        => 'required',
                'iNumeroUnidad'     => 'required',
                'alumnos'           => 'required',
            ],
            [
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'iCurricId.required'         => 'ID de currícula requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'iDocenteId.required'        => 'ID del docente requerido',
                'iNumeroUnidad.required'     => 'Número de Unidad requerido',
                'alumnos.required'           => 'Datos de las notas (JSON) requerido',
            ]
        );
        $ip = $notas->getClientIp();
        $parametros = [

            $notas->iControlCicloAcad ?? NULL,
            $notas->iFilId            ?? NULL,
            $notas->iCarreraId        ?? NULL,
            $notas->iCurricId         ?? NULL,
            $notas->cCurricCursoCod   ?? NULL,
            $notas->iSeccionId        ?? NULL,
            $notas->iDocenteId        ?? NULL,
            $notas->iNumeroUnidad     ?? NULL,
            $notas->alumnos           ?? NULL,
            //$notas->pro ?? NULL,
            //$notas->con ?? NULL,
            //$notas->act ?? NULL,
            //$notas->ct  ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];

        $revision = json_decode($parametros[8], true);

        $falta = false;
        foreach ($revision as $key => $value) {
            $cod = $value['Codigo_Estudiante'];
            if ($value['NotaConceptual']) {
            }
            # code...
            if ($value['NotaConceptual'] < 0 || $value['NotaConceptual'] > 20) {
                $falta = true;
            }
            if ($value['NotaProcedimental'] < 0 || $value['NotaProcedimental'] > 20) {
                $falta = true;
            }
            if ($value['NotaActitudinal'] < 0 || $value['NotaActitudinal'] > 20) {
                $falta = true;
            }
            if ($value['NotaPromedio'] < 0 || $value['NotaPromedio'] > 20) {
                $falta = true;
            }
            if ($falta == true) {
                $mensaje_error = 'Hay errores de nota, asegúrese de ingresar notas entre 0 y 20. del Estudiante de Código::' . $cod;
                //echo '<p>Hay errores de nota?: '.$falta.'</p>';
                $response = ['validated' => true, 'mensaje' => $mensaje_error];
                return response()->json($response, 500);
            }
        }
        //return $notas;
        try {
            $notas = DB::SELECT('EXEC [ura].[Sp_DOCE_INS_Notas_InsertarNotasXUnidad] ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Las notas se guardaron correctamente.', 'result' => $notas];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
        //return response()->json(['notas' => $notas, 'res' => $response]);

    }


    public function listNotasEstudianteEdit(
        Request $edit
    ) {
        $this->validate(
            $edit,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'iNumeroUnidad'     => 'required',
            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'iNumeroUnidad.required'     => 'Número de Unidad requerido',

            ]
        );
        $ip = $edit->getClientIp();
        $parametros = [

            $edit->iDocenteId        ?? NULL,
            $edit->iControlCicloAcad ?? NULL,
            $edit->iCurricId         ?? NULL,
            $edit->iFilId            ?? NULL,
            $edit->iCarreraId        ?? NULL,
            $edit->cCurricCursoCod   ?? NULL,
            $edit->iSeccionId        ?? NULL,
            $edit->iNumeroUnidad     ?? NULL,
            //$notas->pro ?? NULL,
            //$notas->con ?? NULL,
            //$notas->act ?? NULL,
            //$notas->ct  ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];

        try {
            $edit = DB::SELECT('EXEC [ura].[Sp_DOCE_SEL_Notas_Muestra_ListadoParaEditar] ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Las notas se guardaron listNotasEstudianteEdit correctamente.', 'result' => $edit];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $edit = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['edit' => $edit, 'res' => $response]);
    }

    public function conteoEstudianteARunidad(
        $iControlCicloAcad,
        $iFilId,
        $iCarreraId,
        $iCurricId,
        $cCurricCursoCod,
        $iSeccionId,
        $iDocenteId

    ) {
        try {
            $conteo = DB::SELECT(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_MuestraConteo_AprobadosXReprobados] ?,?,?,?,?,?,?',
                [
                    $iControlCicloAcad,
                    $iFilId,
                    $iCarreraId,
                    $iCurricId,
                    $cCurricCursoCod,
                    $iSeccionId,
                    $iDocenteId
                ]
            );
            $response = ['validated' => true, 'mensaje' => '.', 'result' => $conteo];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $conteo = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['conteo' => $conteo, 'res' => $response]);
    }

    public function updateNotasEstudiante(Request $edit)
    {
        //header("Access-Control-Allow-Origin: *");
        $this->validate(
            $edit,
            [
                'ifilId'            => 'required',
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCarreraId'        => 'required',
                'iCurricId'         => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'cEstudCodUniv'     => 'required',
                'unidad'            => 'required',
                'TipoNota'          => 'required',
                //'nota'              => 'required|numeric|min:0|max:20',
                //'promedio'          => 'required',
            ],
            [
                'ifilId.required'            => 'ID de filial requerido.',
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'iCurricId.required'         => 'ID de currícula requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'cEstudCodUniv.required'     => 'Número de Unidad requerido',
                'unidad.required'            => 'Número de Unidad requerido',
                'TipoNota.required'          => 'Tipo de Nota requerido',
                'nota.required'              => 'La Nota es requerida',
                'nota.numeric'               => 'La Nota es requerida',
                'nota.min'                   => 'La Nota es requerida',
                'nota.max'                   => 'La Nota es requerida',

                //'promedio.required'          => 'Promedio es requerido',
            ]
        );
        $ip = $edit->getClientIp();
        $falta = false;

        /*  foreach ($revision as $key => $value) {

            $cod = $value['Codigo_Estudiante'];
            switch ($edit['TipoNota']) {
                case 'nc':
                    # code...
                    break;
                case 'np':
                    # code...
                    break;

                case 'na':
                    # code...
                    break;

                default:
                    # code...
                    break;
            }


            if($falta == true)
            {
                $mensaje_error = 'Hay errores de nota, asegúrese de ingresar notas entre 0 y 20. del Estudiante de Código::' . $cod;
                //echo '<p>Hay errores de nota?: '.$falta.'</p>';
                $response = ['validated' => true, 'mensaje' => $mensaje_error];
                return response()->json($response, 500);
            }
        } */

        $parametros = [

            $edit->ifilId            ?? NULL,
            $edit->iDocenteId        ?? NULL,
            $edit->iControlCicloAcad ?? NULL,
            $edit->iCarreraId        ?? NULL,
            $edit->iCurricId         ?? NULL,
            $edit->cCurricCursoCod   ?? NULL,
            $edit->iSeccionId        ?? NULL,
            $edit->cEstudCodUniv     ?? NULL,
            $edit->unidad            ?? NULL,
            $edit->TipoNota          ?? NULL,
            intval($edit->nota)      ?? NULL,
            NULL, //$edit->promedio          ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        $revision = json_decode($parametros[8], true);

        $falta = false;
        /*
        foreach ($revision as $key => $value) {
             $cod = $value['Codigo_Estudiante'];


            # code...
            if(is_numeric($value['NotaConceptual']) || $value['NotaConceptual'] < 0 || $value['NotaConceptual'] > 20)
                {
                    $falta = true;
                }
            if(is_numeric($value['NotaProcedimental']) || $value['NotaProcedimental'] < 0 || $value['NotaProcedimental'] > 20)
                {
                    $falta = true;
                }
            if(is_numeric($value['NotaActitudinal']) || $value['NotaActitudinal'] < 0 || $value['NotaActitudinal'] > 20)
                {
                    $falta = true;
                }
            if(is_numeric($value['NotaPromedio']) || $value['NotaPromedio'] < 0 || $value['NotaPromedio'] > 20)
                {
                    $falta = true;
                }
            if($falta == true)
            {
                $mensaje_error = 'Hay errores de nota, asegúrese de ingresar notas entre 0 y 20. del Estudiante de Código::' . $cod;
                //echo '<p>Hay errores de nota?: '.$falta.'</p>';
                $response = ['validated' => true, 'mensaje' => $mensaje_error];
                return response()->json($response, 500);
            }
        }*/

        //return $notas;
        try {
            $edit = DB::select('EXEC [ura].[Sp_DOCE_UPD_Notas_Actualiza_NotasXEstudiante] ?,
            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            //return $edit;

            if ($edit[0]->iResult > 0) {
                $response = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
            } else {
                $response = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
            }

            /*     $response = ['validated' => true, 'mensaje' => 'La nota se actualizo correctamente.', 'result' => $edit];
            $codeResponse = 200; */
            //return $edit;

        } catch (\Exception $e) {
            $edit = "ERROR";
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['edit' => $edit, 'res' => $response]);
    }
    public function notasCerrarIngreso(Request $ncerrar)
    {

        $this->validate(
            $ncerrar,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'iNumeroUnidad'     => 'required',
            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'iNumeroUnidad.required'     => 'Número de Unidad requerido',
            ]
        );

        $ip = $ncerrar->getClientIp();

        $parametros = [

            $ncerrar->iDocenteId        ?? NULL,
            $ncerrar->iControlCicloAcad ?? NULL,
            $ncerrar->iCurricId         ?? NULL,
            $ncerrar->iFilId            ?? NULL,
            $ncerrar->iCarreraId        ?? NULL,
            $ncerrar->cCurricCursoCod   ?? NULL,
            $ncerrar->iSeccionId        ?? NULL,
            $ncerrar->iNumeroUnidad     ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        $unid = $ncerrar->iNumeroUnidad;
        try {
            $ncerrar = DB::SELECT('EXEC [ura].[Sp_DOCE_UPD_Notas_CerrarIngresoNotasXUnidad] ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            // $ncerrar;
            if ($ncerrar[0]->iResult > 0) {
                $response = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
            } else {
                $response = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
            }

            /* $response = ['validated' => true, 'mensaje' => 'el cierre de notas de la Unidad N°'. $unid .' fue exitoso.', 'result' => $ncerrar];
            $codeResponse = 200; */
        } catch (\Exception $e) {
            $ncerrar = "error";
            $response = [
                'validated' => true,
                'mensaje'   => substr($e->errorInfo[2] ?? '', 54),
                'exception' => $e->getCode()
            ];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['ncerrar' => $ncerrar, 'res' => $response]);
    }
    public function listSustitutorioEstudiantes(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        try {
            $susti = DB::SELECT(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_Muestra_ListadoSustitutorio] ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId
                ]
            );
            $response = ['validated' => true, 'mensaje' => '.', 'result' => $susti];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $susti = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['susti' => $susti, 'res' => $response]);
    }

    public function updateActualizaFechaExamen(Request $fecha)
    {
        $this->validate(
            $fecha,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'iNumeroUnidad'     => 'required',
                'dFechaExamen'      => 'required',
            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'iNumeroUnidad.required'     => 'Número de Unidad requerido',
                'dFechaExamen.required'      => 'La Fecha es requerida',
            ]
        );
        $ip = $fecha->getClientIp();
        $parametros = [

            $fecha->iDocenteId        ?? NULL,
            $fecha->iControlCicloAcad ?? NULL,
            $fecha->iCurricId         ?? NULL,
            $fecha->iFilId            ?? NULL,
            $fecha->iCarreraId        ?? NULL,
            $fecha->cCurricCursoCod   ?? NULL,
            $fecha->iSeccionId        ?? NULL,
            $fecha->iNumeroUnidad     ?? NULL,
            $fecha->dFechaExamen      ?? NULL,
            //$notas->pro ?? NULL,
            //$notas->con ?? NULL,
            //$notas->act ?? NULL,
            //$notas->ct  ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        try {
            $fecha = DB::SELECT('EXEC [ura].[[Sp_DOCE_UPD_Notas_Actualiza_FechaExamenUnidad] ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Las notas se guardaron correctamente.', 'result' => $fecha];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['fecha' => $fecha, 'res' => $response]);
    }

    public function registroEvaluacionEstudiantes(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        try {
            $registro = DB::SELECT(
                'EXEC [ura].[Sp_DOCE_Sel_Muestra_RegistroDeEvaluacion] ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId
                ]
            );
            $response = ['validated' => true, 'mensaje' => '.', 'result' => $registro];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $registro = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['registro' => $registro, 'res' => $response]);
    }


    public function muestraUnidadesXCursoConFecha(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        try {
            $und = DB::SELECT(
                'EXEC [ura].Sp_DOCE_SEL_Notas_MuestraUnidadesXCursoConFecha ?,?,?,?,?,?,?',
                [$iDocenteId, $iControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId]
            );
            $response = ['validated' => true, 'mensaje' => '.', 'result' => $und];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $und = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['und' => $und, 'res' => $response]);
    }
    public function porcentajeAsistencia($iDocenteId, $iControlCicloAcad)
    {
        try {
            $porcentaje = DB::SELECT(
                'EXEC [ura].[Sp_DOCE_SEL_Asistencia_MuestraPorcetaje_AsistentesXFaltantes] ?,?',
                [$iDocenteId, $iControlCicloAcad]
            );
            $response = ['validated' => true, 'mensaje' => '.', 'result' => $porcentaje];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $porcentaje = 0;
            $response = ['validated' => false, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['porcentaje' => $porcentaje, 'res' => $response]);
    }
    public function notasUnidadEliminar(Request $delete)
    {
        $this->validate(
            $delete,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'iNumeroUnidad'     => 'required',
            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'iNumeroUnidad.required'     => 'Número de Unidad requerido',
            ]
        );
        $ip = $delete->getClientIp();
        $parametros = [

            $delete->iDocenteId        ?? NULL,
            $delete->iControlCicloAcad ?? NULL,
            $delete->iCurricId         ?? NULL,
            $delete->iFilId            ?? NULL,
            $delete->iCarreraId        ?? NULL,
            $delete->cCurricCursoCod   ?? NULL,
            $delete->iSeccionId        ?? NULL,
            $delete->iNumeroUnidad     ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];

        try {
            $delete = DB::SELECT('EXEC [ura].[Sp_DOCE_UPD_Notas_Unidades_Eliminar] ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            /*  if ($delete[0]->Curso_Con_Dos_Unidades_Unidad_1 > 0){

                $response = ['validated' => true, 'mensaje' => 'Curso con dos unidades mínimas'];
                $codeResponse = 200;
            }
            else { */
            $response = ['validated' => true, 'mensaje' => 'La unidad se elimino correctamente.', 'result' => $delete];
            $codeResponse = 200;
            //}

        } catch (\Exception $e) {
            $delete = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['delete' => $delete, 'res' => $response]);
    }
    public function notasListadoUnidad(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId,
        $iNumeroUnidad
    ) {
        try {
            $und = DB::SELECT(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_Muestra_ListadoXUnidad] ?,?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId,
                    $iNumeroUnidad
                ]
            );
            $response = ['validated' => true, 'mensaje' => 'Sp_DOCE_SEL_Notas_Muestra_ListadoXUnidad.', 'result' => $und];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $und = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['und' => $und, 'res' => $response]);
    }



    public function notasListadosusti(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        try {
            $sus = DB::select(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_Muestra_ListadoSustitutorio] ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId,
                ]
            );
            /* if ($sus[0]->Unidad_NO_Cerrada == "Unidad 1"){
                $response = ['validated' => true, 'mensaje' => 'La unidad n° 1 no ha sido  cerrada, para continuar haga click en la pestaña (ingreso o edición de notas) para poder cerrarla.'];
                $codeResponse = 200;
            }
            if ($sus[0]->Unidad_NO_Cerrada == "Unidad 2") {
                 $response = ['validated' => true, 'mensaje' => 'La unidad n° 2 no ha sido  cerrada, para continuar haga click en la pestaña (ingreso o edición de notas) para poder cerrarla.'];
                $codeResponse = 200;
            }
            if ($sus[0]->Unidad_NO_Cerrada == "Unidad 3") {
                 $response = ['validated' => true, 'mensaje' => 'La unidad n° 3 no ha sido  cerrada, para continuar haga click en la pestaña (ingreso o edición de notas) para poder cerrarla.'];
                $codeResponse = 200;
            }
            if ($sus[0]->Unidad_NO_Cerrada == "Unidad 4") {
                 $response = ['validated' => true, 'mensaje' => 'La unidad n° 4 no ha sido  cerrada, para continuar haga click en la pestaña (ingreso o edición de notas) para poder cerrarla.'];
                $codeResponse = 200;
            }
            if ($codeResponse == 200) {
                # code...
            } else { */
            $response = ['validated' => true, 'mensaje' => 'notasListadosusti', 'result' => $sus];
            $codeResponse = 200;
            //}

        } catch (\Exception $e) {
            $sus = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['susti' => $sus, 'res' => $response]);
    }

    public function notasListadoPromedioFinal(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        //return $iDocenteId;
        try {
            $final = DB::select(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_Muestra_ListadoXPromedioFinal] ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId
                ]
            );

            $response = ['validated' => true, 'mensaje' => 'Sp_DOCE_SEL_Notas_Muestra_ListadoXPromedioFinal.', 'result' => $final];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $final = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['final' => $final, 'res' => $response]);
    }

    public function notasGeneraListsusti(Request $susti)
    {


        $this->validate(
            $susti,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',

            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',

            ]
        );
        $ip = $susti->getClientIp();
        $parametros = [

            $susti->iDocenteId        ?? NULL,
            $susti->iControlCicloAcad ?? NULL,
            $susti->iCurricId         ?? NULL,
            $susti->iFilId            ?? NULL,
            $susti->iCarreraId        ?? NULL,
            $susti->cCurricCursoCod   ?? NULL,
            $susti->iSeccionId        ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip,
            'N',
            'mac'
        ];

        try {
            $susti = DB::SELECT('EXEC [ura].[Sp_DOCE_INS_Notas_Genera_ListadoSustitutorio] ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => '', 'result' => $susti];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $susti = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['sustitutorio' => $susti, 'res' => $response]);
    }

    public function activaBtnPromedio(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        try {
            $btn = DB::select(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_ActivaBoton_PromedioFinal] ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId
                ]
            );

            $response = ['validated' => true, 'mensaje' => 'Sp_DOCE_SEL_Notas_ActivaBoton_PromedioFinal.', 'result' => $btn];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $btn = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['btn' => $btn, 'res' => $response]);
    }


    public function notasInsertSusti(Request $notasusti)
    {


        $this->validate(
            $notasusti,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'cEstudCodUniv'     => 'required',
                'nota'              => 'required|numeric|min:0|max:20',

            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'cEstudCodUniv.required'     => 'Código del estudiante querido',
                'nota.required'              => 'Nota es requerida',

            ]
        );
        $ip = $notasusti->getClientIp();
        $parametros = [

            $notasusti->iDocenteId        ?? NULL,
            $notasusti->iControlCicloAcad ?? NULL,
            $notasusti->iCurricId         ?? NULL,
            $notasusti->iFilId            ?? NULL,
            $notasusti->iCarreraId        ?? NULL,
            $notasusti->cCurricCursoCod   ?? NULL,
            $notasusti->iSeccionId        ?? NULL,
            $notasusti->cEstudCodUniv     ?? NULL,
            $notasusti->nota              ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip,
            'N',
            'mac'
        ];

        try {
            $notasusti = DB::SELECT('EXEC [ura].[Sp_DOCE_UPD_Notas_Actualiza_NotaSustitutoria] ?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $response = ['validated' => true, 'mensaje' => '', 'result' => $notasusti];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $notasusti = 0;
            $response = ['validated' => false, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['nsusti' => $notasusti, 'res' => $response]);
    }

    public function notasCerrarSusti(Request $cerrarsusti)
    {


        $this->validate(
            $cerrarsusti,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',

            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',


            ]
        );
        $ip = $cerrarsusti->getClientIp();
        $parametros = [

            $cerrarsusti->iDocenteId        ?? NULL,
            $cerrarsusti->iControlCicloAcad ?? NULL,
            $cerrarsusti->iCurricId         ?? NULL,
            $cerrarsusti->iFilId            ?? NULL,
            $cerrarsusti->iCarreraId        ?? NULL,
            $cerrarsusti->cCurricCursoCod   ?? NULL,
            $cerrarsusti->iSeccionId        ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip,
            'N',
            'mac'
        ];

        try {
            $cerrarsusti = DB::SELECT('EXEC [ura].[Sp_DOCE_UPD_Notas_Cierra_UnidadSustitutoria] ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            $cerrardasa = DB::SELECT('EXEC [ura].[Sp_DOCE_UPD_Notas_CierreFinal_DASA] ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($cerrarsusti[0]->iResult > 0) {
                $response = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
            } else {
                $response = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
            }

            if ($cerrardasa[0]->iResult > 0) {
                $response = ['error' => false, 'mensaje' => 'El registro se guardo correctamente. dasa'];
            } else {
                $response = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
            }
            /* $response = ['validated' => true, 'mensaje' => '', 'result' => $cerrarsusti];
            $codeResponse = 200; */
        } catch (\Exception $e) {
            $cerrarsusti = 0;
            $response = ['validated' => false, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['csusti' => $cerrarsusti, 'res' => $response]);
    }

    public function notasCerrarCurso(Request $cerrar)
    {


        $this->validate(
            $cerrar,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',

            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',


            ]
        );
        $ip = $cerrar->getClientIp();
        $parametros = [

            $cerrar->iDocenteId        ?? NULL,
            $cerrar->iControlCicloAcad ?? NULL,
            $cerrar->iCurricId         ?? NULL,
            $cerrar->iFilId            ?? NULL,
            $cerrar->iCarreraId        ?? NULL,
            $cerrar->cCurricCursoCod   ?? NULL,
            $cerrar->iSeccionId        ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip,
            'N',
            'mac'
        ];

        try {


            $cerrarCurso = DB::SELECT('EXEC [ura].[Sp_DOCE_UPD_Notas_CierreFinal_DASA] ?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($cerrarCurso[0]->iResult > 0) {
                $response = ['error' => false, 'mensaje' => 'Se cerro el curso correctamente.'];
            } else {
                $response = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
            }
            /* $response = ['validated' => true, 'mensaje' => '', 'result' => $cerrarsusti];
            $codeResponse = 200; */
        } catch (\Exception $e) {
            $cerrarCurso = 0;
            $response = ['validated' => false, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['cerrarcurso' => $cerrarCurso, 'res' => $response]);
    }

    public function finalPromedio(
        $iDocenteId,
        $iControlCicloAcad,
        $iCurricId,
        $iFilId,
        $iCarreraId,
        $cCurricCursoCod,
        $iSeccionId
    ) {
        try {
            $final = DB::SELECT(
                'EXEC [ura].[Sp_DOCE_SEL_Notas_Muestra_PromedioFinal] ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId
                ]
            );
            $response = ['validated' => true, 'mensaje' => '.', 'result' => $susti];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $final = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        //return response()->json($response, $codeResponse);
        return response()->json(['final' => $final, 'res' => $response]);
    }


    public function importListEstudiates(Request $request)
    {

        $request->validate([
            'import_file' => 'required'
        ]);

        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();

        if ($data->count()) {
            foreach ($data as $key => $value) {
                $arr[] = ['title' => $value->title, 'description' => $value->description];
            }

            if (!empty($arr)) {
                Item::insert($arr);
            }
        }

        return back()->with('success', 'Insert Record successfully.');

        //$file = $request->file('file');

        //Excel::import(new EstudiantesImport,$file);

        //return back();
    }

    public function Guardar_NotaSustitutoria(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();

        $errorJson = new TramitesController();

        $result = null;
        //return $data;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el ingreso del campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iDocenteId'        => 'required',
                    'iControlCicloAcad' => 'required',
                    'iCurricId'         => 'required',
                    'iFilId'            => 'required',
                    'iCarreraId'        => 'required',
                    'cCurricCursoCod'   => 'required',
                    'iSeccionId'        => 'required',
                    'cEstudCodUniv'     => 'required',
                    'nota'              => 'required',

                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }
                if (is_null(empty((intval($data['nota']))))) {
                    $responseJson = ['error' => true, 'mensaje' => 'nota null o vacia.'];
                    return response()->json($responseJson);
                }

                try {
                    $result = DB::select('EXEC ura.Sp_DOCE_INS_Notas_Guardar_NotaSustitutoria ?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $data['iDocenteId'],
                        $data['iControlCicloAcad'],
                        $data['iCurricId'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],
                        $data['cEstudCodUniv'],
                        $data['nota'],

                        auth()->user()->cCredUsuario, //'user',
                        //date
                        'equipo',
                        $request->getClientIp(),
                        'N',
                        'mac'
                    ]);

                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar la nota.'];
                    }

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;
        }
        return response()->json($responseJson);
    }


    public function ver_NotaSusti_Pro(Request $request)
    {
        $method = $request->method();
        $data   = $request->all();

        $errorJson = new TramitesController();

        $result = null;
        //return $data;

        switch ($method) {

            case 'POST':
                DB::beginTransaction();

                $messages = [
                    'required' => 'Se requiere el ingreso del campo :attribute.',
                ];

                $validator = Validator::make($request->all(), [
                    'iDocenteId'        => 'required',
                    'iControlCicloAcad' => 'required',
                    'iCurricId'         => 'required',
                    'iFilId'            => 'required',
                    'iCarreraId'        => 'required',
                    'cCurricCursoCod'   => 'required',
                    'iSeccionId'        => 'required',
                    'cEstudCodUniv'     => 'required',


                ], $messages);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }


                try {
                    $responseJson = DB::select('EXEC ura.Sp_DOCE_SEL_Notas_Muestra_NotaSustitutoria_Estudiante ?,?,?,?,?,?,?,?', [
                        $data['iDocenteId'],
                        $data['iControlCicloAcad'],
                        $data['iCurricId'],
                        $data['iFilId'],
                        $data['iCarreraId'],
                        $data['cCurricCursoCod'],
                        $data['iSeccionId'],
                        $data['cEstudCodUniv'],

                    ]);

                    /* if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar la nota.'];
                    } */

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    report($e);
                    $responseJson = $errorJson->returnError($e);
                }

                break;
        }
        return response()->json($responseJson);
    }
}
