<?php

namespace App\Http\Controllers\cctic;

use App\Model\cctic\CurriculaModulo;
use App\Model\cctic\CurriculaModuloCosto;
use App\Model\cctic\Modalidad;
use App\model\cctic\PlanTrabajo;
use App\Model\cctic\PublicoObjetivo;
use App\Model\cctic\TipoCurso;
use App\Model\cctic\Unidad;
use App\Model\cctic\PreInscripcion;
use App\Repositories\cctic\CursoRepository;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Model\cctic\Curso;
use App\Model\cctic\Modulo;
use App\Model\cctic\Publicacion;
use Illuminate\Support\Facades\File;
use App\Repositories\cctic\PlanTrabajoRepository;
use App\Repositories\cctic\GeneralRepository;
use phpDocumentor\Reflection\Types\Array_;



class GestionCursosController extends Controller
{
    public function __construct(CursoRepository $curso, PlanTrabajoRepository $curricula, GeneralRepository $general)
    {
        $this->curso = $curso;
        $this->planTrabajo = $curricula;
        $this->general = $general;
    }

    public function obtenerCursos(Request $request)
    {

        $filial = $request->input('iFilId');
        $programAcad = $request->input('iProgramasAcadId');
        $planAcad = $request->input('iCurricId');
        $estado = $request->input('estado');

        try {

            $cursos = $this->curso->getCursos($planAcad, $filial, $programAcad, $estado);


            $response = ['validated' => true, 'data' => $cursos, 'message' => 'cursos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }


    public function cursoById($id)
    {
        try {

            $curso = $this->curso->CursoById($id);

            $response = ['validated' => true, 'data' => $curso, 'message' => 'curso obtenido correctamente.'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function crearCurso(Request $request)
    {

        $modulos = json_decode($request->modulos);
        $programAcad = $request->programAcad;
        $iCurricId = $request->iCurricId;
        $filial = $request->filial;

        DB::beginTransaction();

        try {
            $cursoId = DB::table('acad.cursos')->insertGetId([
                'iTipoCursoId' => $request->iTipoCursoId,
                'cCursoNombre' => $request->cCursoNombre,
                'bCursoEstado' => 1,
            ]);


            foreach ($modulos as $key => $row) {


                if ($request->hasFile($key)) {
                    $filePathName = $request->file($key)->store('cctic/silabus');
                }

                // create modulo
                $moduloId = DB::table('acad.modulos')->insertGetId([
                    'iCursoId' => $cursoId,
                    'cModuloNombre' => $row->cModuloNombre,
                    'iTotalHorasCertificado' => $row->iTotalHorasCertificado,
                    'bModuloEstado' => 1,
                    'cPerfilProfesional' => $row->cPerfilProfesional,
                    'cModuloDescripcion' => $row->cModuloDescripcion,
                    'cSilabusPath ' => $filePathName,
                    'fModuloPrecioMatricula' => $row->fModuloPrecioMatricula,
                    'fModuloPrecioMensualidad' => $row->fModuloPrecioMensualidad
                ]);

                foreach ($row->unidades as $key => $rowUnidad) {
                    DB::table('acad.unidades')->insert([
                        'iModuloId' => $moduloId,
                        'cUnidadDsc' => $rowUnidad->cUnidadDsc,
                        'iCantidadHorasAcadpresenciales' => $rowUnidad->iCantidadHorasAcadPresenciales,
                        'iCantidadHorasAcadVirtuales' => $rowUnidad->iCantidadHorasAcadVirtuales,
                        'bUnidadEstado' => 1
                    ]);
                }

                DB::table('acad.plan_trabajo_modulos')->insert([
                    'iPlanTrabajoId' => $iCurricId,
                    'iModuloId' => $moduloId,
//                    'bCurriculaModuloEstado' => 1,
                    'iFilId' => $filial,
                    'iProgramasAcadId' => $programAcad
                ]);


                foreach ($row->publico_objetivo as $pKey => $publico) {
                    DB::table('acad.modulo_publico_objetivo')->insert([
                        'iPublicoObjetivoId' => $publico->iPublicoObjetivoId,
                        'iModuloId' => $moduloId,
                        'fMensualidad' => $publico->fMensualidad,
                        'fTotal' => $publico->fTotal
                    ]);
                }
            }

            $response = ['validated' => true, 'data' => [], 'message' => 'curso creado correctamente'];
            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }


        return response()->json($response, $responseCode);
    }

    public function cambiarEstadoCurso(Request $request)
    {
        $curso = $request->all();

        DB::beginTransaction();
        try {
            DB::table('acad.cursos')
                ->where('iCursoId', '=', $curso['iCursoId'])
                ->update(['bCursoEstado' => $curso['bCursoEstado']]);

            DB::table('acad.modulos')
                ->where('iCursoId', '=', $curso['iCursoId'])
                ->update(['bModuloEstado' => $curso['bCursoEstado']]);

            $modulos = $curso['modulos'];
            if (count($modulos) > 0) {
                foreach ($modulos as $key => $modulo) {
                    DB::table('acad.unidades')
                        ->where('iModuloId', '=', $modulo['iModuloId'])
                        ->update(['bUnidadEstado' => $curso['bCursoEstado']]);

//                    DB::table('acad.plan_trabajo')
//                        ->where('iModuloId', '=', $modulo['iModuloId'])
//                        ->update(['bCurriculaModuloEstado' => $curso['bCursoEstado']]);
                }

            }

            $response = ['validated' => true, 'data' => [], 'message' => 'actualizado'];
            $responseCode = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        DB::commit();

        return response()->json($response, $responseCode);
    }

    public function editarCurso(Request $request)
    {

        $modulos = json_decode($request->modulos);


        DB::beginTransaction();
        $filepaths = array();
        $oldFilePathNames = array();
        try {


            $curso = DB::table('acad.cursos')->updateOrInsert(
                [
                    'iCursoId' => $request->iCursoId,
                ],
                [
                    'iTipoCursoId' => $request->iTipoCursoId,
                    'cCursoNombre' => $request->cCursoNombre,
                    'bCursoEstado' => $request->bCursoEstado,
                    'iPrerequisitoId' => $request->iPrerequisitoId,
                    'dCursosUpdatedAt' => date("Y-d-m H:i:s.v")
                ]
            );

            foreach ($modulos as $keym => $modulo) {
                $filePathName = null;
                $oldFilePathName = null;


                if ($request->hasFile($keym)) {
                    $filePathName = $request->file($keym)->store('cctic/silabus');
                    array_push($filepaths, $filePathName);
                }

                if ($modulo->iModuloId == 0) {
                    $modulo->iModuloId = DB::table('acad.modulos')->insertGetId([
                        'iCursoId' => $request->iCursoId,
                        'cModuloNombre' => $modulo->cModuloNombre,
                        'iTotalHorasCertificado' => $modulo->iTotalHorasCertificado,
                        'bModuloEstado' => 1,
                        'cPerfilProfesional' => $modulo->cPerfilProfesional,
                        'cModuloDescripcion' => $modulo->cModuloDescripcion,
                        'cSilabusPath' => $filePathName,
                        'fModuloPrecioMatricula' => $modulo->fModuloPrecioMatricula,
                        'fModuloPrecioMensualidad' => $modulo->fModuloPrecioMensualidad
                    ]);
                } else {
                    $moduloFind = Modulo::find($modulo->iModuloId);
                    array_push($oldFilePathNames, $moduloFind->cSilabusPath);

                    DB::table('acad.modulos')
                        ->where('iModuloId', '=', $modulo->iModuloId)
                        ->update([
                            'cModuloNombre' => $modulo->cModuloNombre,
                            'iTotalHorasCertificado' => $modulo->iTotalHorasCertificado,
                            'bModuloEstado' => $modulo->bModuloEstado,
                            'cPerfilProfesional' => $modulo->cPerfilProfesional,
                            'cModuloDescripcion' => $modulo->cModuloDescripcion,
                            'cSilabusPath' => $filePathName,
                            'fModuloPrecioMatricula' => $modulo->fModuloPrecioMatricula,
                            'fModuloPrecioMensualidad' => $modulo->fModuloPrecioMensualidad
                        ]);

                }
                foreach ($modulo->unidades as $keyu => $unidad) {

                    if (is_null($unidad->iUnidadId)) {
                        DB::table('acad.unidades')->insert(
                            [
                                'iModuloId' => $modulo->iModuloId,
                                'cUnidadDsc' => $unidad->cUnidadDsc,
                                'iCantidadHorasAcadPresenciales' => $unidad->iCantidadHorasAcadPresenciales,
                                'iCantidadHorasAcadVirtuales' => $unidad->iCantidadHorasAcadPresenciales,
                                'bUnidadEstado' => $unidad->bUnidadEstado
                            ]
                        );
                    } else {
                        DB::table('acad.unidades')->where('iUnidadId', '=', $unidad->iUnidadId)
                            ->update(
                                [
                                    'iModuloId' => $modulo->iModuloId,
                                    'cUnidadDsc' => $unidad->cUnidadDsc,
                                    'iCantidadHorasAcadPresenciales' => $unidad->iCantidadHorasAcadPresenciales,
                                    'iCantidadHorasAcadVirtuales' => $unidad->iCantidadHorasAcadPresenciales,
                                    'bUnidadEstado' => $unidad->bUnidadEstado
                                ]
                            );
                    }

                }

//                DB::enableQueryLog();

                DB::table('acad.plan_trabajo_modulos')->updateOrInsert(
                    [
                        'iModuloId' => $modulo->iModuloId,
                    ],
                    [
                        'iPlanTrabajoId' => $request->iCurricId,
                        'iModuloId' => $modulo->iModuloId,
//                        'bCurriculaModuloEstado' => 1,
                        'iFilId' => $request->filial,
                        'iProgramasAcadId' => $request->programAcad
                    ]
                );


//              publico objetivo
                DB::table('acad.modulo_publico_objetivo')
                    ->where('iModuloId', '=', $modulo->iModuloId)
                    ->delete();
                foreach ($modulo->publico_objetivo as $keypub => $publico) {
                    DB::table('acad.modulo_publico_objetivo')->updateOrInsert(
                        [
                            'iModuloId' => $modulo->iModuloId,
                            'iPublicoObjetivoId' => $publico->iPublicoObjetivoId,
                        ],
                        [
                            'iModuloId' => $modulo->iModuloId,
                            'iPublicoObjetivoId' => $publico->iPublicoObjetivoId,
                            'fMensualidad' => $publico->fMensualidad,
                            'fTotal' => $publico->fTotal
                        ]
                    );
                }
            }


            $response = ['validated' => true, 'data' => $curso, 'message' => 'actualizado cerrectamente'];
            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            foreach ($filepaths as $filepath) {
                Storage::delete($filepath);
            }
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        foreach ($oldFilePathNames as $filepathOld) {
            Storage::delete($filepathOld);
        }

        return response()->json($response, $responseCode);

    }

    public function recursosCrearCurso(Request $request)
    {
        $filial = $request->filial;
        $programaAcad = $request->programaAcad;

        try {
            $planTrabajo = $this->planTrabajo->obtenerPlanesTrabajo($filial, $programaAcad);

            if (count($planTrabajo) === 0) {
                return response()->json(['validated' => true, 'data' => [], 'no se encontraon curriculas disponibles'], 404);
            }
            $prerequisitos = $this->curso->Prerequisitos($planTrabajo[0]->iPlanTrabajoId, $filial, $programaAcad);

            $tipoCurso = TipoCurso::get();
            $publicoObjetivo = PublicoObjetivo::select('iPublicoObjetivoId', 'cPublicoObjetivoDsc')
                ->get();
            $data = [
                'prerequisitos' => $prerequisitos,
                'planesTrabajo' => $planTrabajo,
                'tipoCurso' => $tipoCurso,
                'publicoObjetivo' => $publicoObjetivo
            ];
            $response = ['validated' => true, 'data' => $data, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validatet' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        if ($request->has('cursoId')) {
            return $data;
        }
        return response()->json($response, $responseCode);
    }

    public function recursosEditarCurso(Request $request)
    {

        try {
            $recursos = $this->recursosCrearCurso($request);
            $curso = $this->curso->CursoAllById($request->cursoId);

            $data = [
                'recursos' => $recursos,
                'curso' => $curso,
            ];

            $response = ['validated' => true, 'data' => $data, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {

            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }


        return response()->json($response, $responseCode);
    }

    public function obtenerCursoById($cursoId)
    {
        //        $this->curso->CursoById()
    }

    public function downloadSilabus(Request $request)
    {

        $path = $request->cSilabusPath;

        try {
            return Storage::download($path);
        } catch (\Exception $e) {
            return response()->json(['data' => [], $e->getMessage()]);
        }
    }

    public function recursosCrearPublicacion($iCursoId)
    {
        try {

            $curso = DB::select('exec [acad].[SP_SEL_cursoById] @iCursoId = ?', [$iCursoId]);

            foreach ($curso as $c) {
                $c->modulos = json_decode($c->modulos, true);
            }


            if (count($curso) == 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'No se encontro el ID']);
            }

            $curso = $curso[0];

            $planTrabajo = DB::table('acad.plan_trabajo_modulos as plantm')
                ->join('acad.plan_trabajo as plant', 'plantm.iPlanTrabajoId', '=', 'plant.iPlanTrabajoId')
                ->where('plantm.iModuloId', '=', $curso->modulos[0]['iModuloId'])
                ->select('plant.iPlanTrabajoId')->first();

            if ($planTrabajo) {
                $curso->iPlanTrabajo = $planTrabajo->iPlanTrabajoId;
            }

//            dd(DB::getQueryLog());

            $publicoObjetivo = PublicoObjetivo::get();
            $modalidadEstudio = Modalidad::select('iModalEstudId', 'cModalEstudDsc')
                ->get();
            $tipoDuracion = $this->general->publicacionDuracion();

            $data = [
                'curso' => $curso,
                'publicoObjetivo' => $publicoObjetivo,
                'modalidades' => $modalidadEstudio,
                'tipoDuracion' => $tipoDuracion,
            ];

            $response = ['validated' => true, 'data' => $data, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;

        } catch (\Exception $e) {
            $response = ['validated' => false, 'data ' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
            report($e);
        }

        return response()->json($response, $responseCode);
    }


    public function crearPublicaicon(Request $request)
    {

        $horarios = json_decode($request->horarios);
        $iModuloId = $request->iModuloId;
        $planTrabajo = $request->iPlanTrabajo;


//        return response()->json($horarios[0]->detalles);

        if ($request->hasFile('imagenPublicacion')) {
            $filePathImage = $request->file('imagenPublicacion')->store('cctic/publicaciones/images');
        }

        if ($request->hasFile('file')) {
            $filePathPDF = $request->file('file')->store('cctic/publicaciones/pdf');
        }



        DB::beginTransaction();
        try {
            $planTrabajoModulo = DB::table('acad.plan_trabajo_modulos')
                ->where('iModuloId', '=', $iModuloId)
                ->where('iPlanTrabajoId', '=', $planTrabajo)
                ->first();



            $moduloPlanTrabajoCostoId = DB::table('acad.modulo_plan_trabajo_costo')->insertGetId(
                [
                    'iPlanTrabajoModuloId' => $planTrabajoModulo->iPlanTrabajoModuloId,
                    'fMatricula' => $request->costoMatricula,
                    'fMensualidad' => $request->costoMensualidad,
                    'iDescuentoId' =>$request->iDescuentoId
                ]
            );

            $publicacionId = DB::table('acad.publicaciones')->insertGetId(
                [
                    'iModuloPlanTrabajoCostoId' => $moduloPlanTrabajoCostoId,
                    'iModalEstudId' => $request->iModalEstudId,
                    'iProgramasAcadId' => $request->iProgramAcadId,
                    'iFilId' => $request->iFilId,
                    'cPublicacionNombre' => '',
                    'cPublicacionDescripcion' => '',
                    'iMinCantMatricula' => $request->iMinCantMatricula,
                    'iMaxCantMatricula' => $request->iMaxCantMatricula,
                    'iMinCantPreinscritos' => $request->iMinCantPreinscritos,
                    'dPublicacionFechaInicio' => $request->dPublicacionFechaInicio,
                    'cPublicacionImagen' => $filePathImage,
                    'bPublicacionEstado' => 1,
                    'iTipoDuracionId' => $request->iTipoDuracionId,
                    'iPublicacionCantidadDuracion' => $request->iPublicacionCantidadDuracion,
                    'cPublicacionFile' => $filePathPDF
                ]
            );


            $dataHorario = [
                'iProgramaAcadId' => $request->iProgramAcadId,
                'iFilId' => $request->iFilId,
                'cUsuarioSis' => auth()->user()->cCredUsuario,
                'cIpSis' => $request->server->get('REMOTE_ADDR'),
            ];


            foreach ($horarios as $horario) {
                $horarioId = DB::table('acad.pre_horarios')->insertGetId(
                    $dataHorario
                );

                foreach ($horario->detalles as $detalle) {
                    DB::table('acad.pre_horarios_detalle')->insert(
                        [
                            'iPreHorariosId' => $horarioId,
                            'iDia' => $detalle->iDia,
                            'tHoraInicio' => $detalle->tHoraInicio,
                            'tHoraFin' => $detalle->tHoraFin,
                            'nHorasAcademicas' => $detalle->nHorasAcademicas,
                        ]
                    );
                }


                DB::table('acad.pre_horario_publicaciones')
                    ->insert(
                        [
                            'iPreHorariosId' => $horarioId,
                            'iPublicacionId' => $publicacionId
                        ]
                );

            }







            $response = ['validated' => true, 'data' => [], 'message' => 'publiacion creada correctamente'];
            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'message_publicacion' => $e->getMessage()];
            $responseCode = 500;
        }

        return response($response, $responseCode);
    }



    public function obtenerGrupoHorariosByPublicacionId($publicacionId)
    {
        $parameters = [
            $publicacionId
        ];
        try {
            // SP_SEL_agruparHorariosXpublicacionId
            $horarios =  DB::select('exec acad.[SP_SEL_obtenerPreinscritosXHorario] ?', $parameters);

            $response = ['validated' => true, 'data' => $horarios, 'message' => 'Horarios obtenidos con éxito'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);;
    }

    public function obtenerPublicaciones(Request $request)
    {

        $parameters = [
            $request->input('iProgramasAcadId'),
            $request->input('iFilId')
        ];


        try {
            $publicaicon = DB::select('exec acad.[Sp_Sel_obtener_publicaciones] ?, ?', $parameters);

            $filiales = DB::table('grl.filiales')->get(['iFilId', 'bFilPrincipal', 'cFilDescripcion', 'cFilAbrev', 'cFilSigla']);

            $data = [
                'publicaciones' => $publicaicon,
                'filiales' => $filiales
            ];

            $response = ['validated' => true, 'data' => $data, 'message' => 'publicaciones obtenidas correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
//            DB::table('acad . pre_horarios')->updateOrInsert(
//                [
//                  'tHoraInicio' => $request->,
//                  'tHoraFin' =>  $request->,
//                ],
//                [
//                    'tHorainicio' =>,
//                    'tHoraFin' =>,
//                    'bActivo' => 1,
//                    'bDiaLunes' => ,
//                    'bDiaMartes' =>,
//                    'bDiaMiercoles' =>,
//                    'bDiaJueves' =>,
//                    'bDiaViernes' => ,
//                    'bDiaSabado' =>,
//                    'bDiaDomingo' =>,
//                    'cPreHorarioDiasDsc' =>
//                ],
//            );



    public function obtenerPUblacicanesCantidadPreinscritos(Request $request)
    {
        $parameters = [
            $request->input('iProgramasAcadId'),
            $request->input('iFilId'),
            $request->input('estado')
        ];


        try {
            $publicaciones = DB::select('exec [acad].[Sp_Sel_publacionesActivoInactivo] ?, ?, ?', $parameters);

            foreach ($publicaciones as $i => $publacion) {

                $publacion->cantidad = DB::table('acad.pre_horario_publicaciones as php')
                    ->join('acad.preinscripciones as p', 'p.iPreHorariosPublicacionId', '=', 'php.iPreHorariosPublicacionId')
                    ->where('iPublicacionId', '=', $publacion->iPublicacionId)
                    ->count('p.iPreHorariosPublicacionId');
            }

            $response = ['validated' => true, 'data' => $publicaciones, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function ActualizarEstadoPublicacion(Request $request)
    {
        $iPublicacionId = $request->iPublicacionId;
        $bPublicacionEstado = $request->bPublicacionEstado;


        DB::beginTransaction();
        try {
            $update = DB::table('acad.publicaciones')
                ->where('iPublicacionId', '=', $iPublicacionId)
                ->update(['bPublicacionEstado' => $bPublicacionEstado]);

            $response = ['validated' => true, 'data' => $update, 'message' => 'actualizado correctamente'];
            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function testImage(Request $request)
    {
        $filename = $request->file('filename');
        $extension = $filename->getClientOriginalExtension();

        $fName = $filename->getFilename() . '.' . $extension;
        Storage::disk('public')->put($fName, File::get($filename));

        return response()->json($fName);
    }

    public function crearPlanTrabajo(Request $request)
    {

        $filial = $request->iFilId;
        $programAcad = $request->iProgramAcad;
        $cursos = $request->cursos;
        $planAcad = $request->planAcad;

        $planesTrabajo = $this->planTrabajo->obtenerPlanesTrabajo($filial, $programAcad);

        DB::beginTransaction();

        $planTrabajo = $this->crearPlanTrabajoPath($planesTrabajo);


        try {
            $lastPlanTrabajo = DB::table('acad.plan_trabajo_modulos')->insertGetId(
                [
                    'iPlanTrabajoId' => $planTrabajo,
                    'iProgramasAcadId' => $programAcad,
                    'iFilId' => $filial
                ]
            );

            foreach ($cursos as $curso) {

                $cursoInserted = DB::table('acad.cursos')
                    ->insertGetId(
                        [
                            'cCursoNombre' => $curso['cCursoNombre'],
                            'iTipoCursoId' => $curso['iTipoCursoId'],
                            'bCursoEstado' => $curso['bCursoEstado'],
                        ]
                    );

                $modulos = $curso['modulos'];
                foreach ($modulos as $modulo) {
                    $moduloInserted = DB::table('acad.modulos')
                        ->insertGetId(
                            [
                                'iCursoId' => $cursoInserted,
                                'cModuloNombre' => $modulo['cModuloNombre'],
                                'cModuloCodigo' => $modulo['cModuloCodigo'],
                                'cModuloSigla' => $modulo['cModuloSigla'],
                                'bModuloEstado' => $modulo['bModuloEstado'],
                                'iTotalHorasCertificado' => $modulo['iTotalHorasCertificado'],
                                'cPerfilProfesional' => $modulo['cPerfilProfesional'],
                                'cModuloDescripcion' => $modulo['cModuloDescripcion'],
                                'fModuloPrecioMatricula' =>$modulo['fModuloPrecioMatricula'],
                                'fModuloPrecioMensualidad' => $modulo['fModuloPrecioMensualidad']
                            ]
                        );




                    DB::table('acad.plan_trabajo_modulos')
                        ->insert(
                            [
                                'iPlanTrabajoId' => $planTrabajo,
                                'iModuloId' => $moduloInserted,
                                'iProgramasAcadId' => $programAcad,
                                'iFilId' => $filial,
                                'iPlanTrabajoAnteriorId' => $planAcad,
                            ]
                        );

                    $unidades = $modulo['unidades'];
                    foreach ($unidades as $unidad) {
                        DB::table('acad.unidades')
                            ->insert(
                                [
                                    'iModuloId' => $moduloInserted,
                                    'cUnidadDsc' => $unidad['cUnidadDsc'],
                                    'iCantidadHorasAcadPresenciales' => $unidad['iCantidadHorasAcadPresenciales'],
                                    'iCantidadHorasAcadVirtuales' => $unidad['iCantidadHorasAcadVirtuales'],
                                    'bUnidadEstado' => $unidad['bUnidadEstado'],
                                ]
                            );
                    }

                    $publicoObjetivo = $modulo['publicoObjetivo'];
                    foreach ($publicoObjetivo as $pub) {
                        DB::table('acad.modulo_publico_objetivo')
                            ->insert(
                                [
                                    'iPublicoObjetivoId' => $pub['iPublicoObjetivoId'],
                                    'iModuloId' => $moduloInserted
                                ]
                            );
                    }
                }
            }


            $response = ['validate' => true, 'data' => [], 'message' => 'plan creacion correctamante'];

            $responseCode = 200;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => true, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);


    }

    public function crearPlanTrabajoPath($planesTrabajo)
    {
        $newPath = null;
        $planTrabajo = null;
        if (count($planesTrabajo) == 0) {
            $newPath = date('Y') . '-1';
            $planTrabajo = PlanTrabajo::where('cPlanTrabajoPath', '=', $newPath)
                ->first('iPlanTrabajoId');

            if (!is_null($planTrabajo)) {
                $planTrabajo = $planTrabajo->iPlanTrabajoId;
            }
            if ($planTrabajo == null) {
                $planTrabajo = DB::table('acad.plan_trabajo')->insertGetId(
                    ['cPlanTrabajoDsc' => $newPath, 'cPlanTrabajoPath' => $newPath]
                );
            }
            return $planTrabajo;
        }

        if (count($planesTrabajo) > 0) {
            $lastPlanTrabajo = $planesTrabajo[0];


            list($date, $serial) = explode('-', $lastPlanTrabajo->cPlanTrabajoPath);

            if (intval($date) < intval(date('Y'))) {
                $newPath = date('Y', '-1');
                $planTrabajo = PlanTrabajo::where('cPlanTrabajoPath', '=', $newPath)
                    ->first('iPlanTrabajoId');

                if (!is_null($planTrabajo)) {
                    $planTrabajo = $planTrabajo->iPlanTrabajoId;
                }

                if ($planTrabajo == null) {
                    $planTrabajo = DB::table('acad.plan_trabajo')->insertGetId(
                        ['cPlanTrabajoDsc' => $newPath, 'cPlanTrabajoPath' => $newPath]
                    );
                }
                return $planTrabajo;
            }

            if (intval($date) >= intval(date('Y'))) {
                $number = strval(intVal($serial) + 1);
                $newPath = date("Y") . '-' . $number;

                $planTrabajo = PlanTrabajo::where('cPlanTrabajoPath', '=', $newPath)
                    ->first('iPlanTrabajoId');
                if (!is_null($planTrabajo)) {
                    $planTrabajo = $planTrabajo->iPlanTrabajoId;
                }

                if (is_null($planTrabajo)) {
                    $planTrabajo = DB::table('acad.plan_trabajo')->insertGetId(
                        ['cPlanTrabajoDsc' => $newPath, 'cPlanTrabajoPath' => $newPath]
                    );
                }
                return $planTrabajo;

            }
        }


        return null;
    }

    public function recursosCrearPlanTrabajo(Request $request)
    {

        $filial = $request->iFilId;
        $programAcad = $request->iProgramAcad;

        try {


            $planesTrabajo = $this->planTrabajo->obtenerPlanesTrabajo($filial, $programAcad);

            if (count($planesTrabajo) == 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'no se encontraron planes de trabajo'], 404);
            }

            $planTrabajo = $planesTrabajo[0];


            $cursos = $this->curso->getCursos($planTrabajo->iPlanTrabajoId, $filial, $programAcad);


            $data = [
                'planesTrabajo' => $planesTrabajo,
                'cursos' => $cursos
            ];


            $response = ['validated' => true, 'data' => $data, 'message' => 'datos obtenidos correctamente'];

            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function listaPlanTrabajo(Request $request)
    {
        $filial = $request->iFilId;
        $programAcad = $request->iProgramAcad;

        try {
            $planesTrabajo = $this->planTrabajo->obtenerPlanesTrabajo($filial, $programAcad);

            foreach ($planesTrabajo as $planTrabajo) {
                $planTrabajo->cursos = $this->curso->getCursos($planTrabajo->iPlanTrabajoId, $filial, $programAcad);
            }

            $response = ['validated' => true, 'data' => $planesTrabajo, 'message' => 'planes da trabajo obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);


    }


    public function EliminarCursosModulos(Request $request)
    {
        $ids = [];
        if ($request->code == 'CURSO') {
            $modulos = DB::table('acad.modulos as m')
                ->join('acad.plan_trabajo_modulos as plantm', 'm.iModuloId', '=', 'plantm.iModuloId')
                ->where('iCursoId', '=', $request->iCursoId)
                ->get();

            foreach ($modulos as $modulo) {
                $id = ['id' => $modulo->iPlanTrabajoModuloId];
                array_push($ids, $id);
            }
        }
        try {

            $jsonIds = json_encode($ids);



            $resp = DB::select('[acad].[Sp_CCTIC_DEL_Cursos_Elimina_CursosModulos] ?', [$jsonIds]);

            $response = ['validated' => true, 'data' => $resp, 'message' => 'El curso se elimino correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'error' => $e->getMessage(), 'message' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);

    }
}
