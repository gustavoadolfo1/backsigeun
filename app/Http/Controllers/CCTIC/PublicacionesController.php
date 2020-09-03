<?php

namespace App\Http\Controllers\CCTIC;

use App\Model\cctic\Docente;
use App\Model\cctic\Grupo;
use App\Model\cctic\Modalidad;
use App\Model\cctic\Publicacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\cctic\PreInscripcion;
use App\Repositories\cctic\GeneralRepository;
use App\Model\cctic\PublicoObjetivo;
use Illuminate\Support\Facades\Storage;


class PublicacionesController extends Controller
{
    public function __construct(GeneralRepository $general)
    {
        $this->general = $general;
    }

    public function obtenerPublicaciones(Request $request)
    {

        $parameters = [
            3,
            $request->input('iFilId'),
            $request->input('iModalEstudId')
        ];

        try {
            $publicaciones = DB::select('exec acad.[Sp_Sel_obtener_publicaciones] ?, ?, ?', $parameters);
            foreach ($publicaciones as $i => $publicacion) {
                $publicaciones[$i]->cPublicacionImagen = Storage::url($publicacion->cPublicacionImagen);
                $publicaciones[$i]->cSilabusPath = Storage::url($publicacion->cSilabusPath);
            }


            $response = ['validated' => true, 'data' => $publicaciones, 'message' => 'publicaciones obtenidas correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerPublicacionById($id)
    {
        $parameters = [
            $id
        ];
        try {
            // Sp_Sel_publacionModuloById
            $publicacion = DB::select('exec acad.[SP_SEL_publicacionById] ?', $parameters);

            if (count($publicacion) === 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'no se encontro coincidencias'], 400);
            }

            $publicacion[0]->publicacionImagePath = Storage::url($publicacion[0]->cPublicacionImagen);
            $publicacion[0]->silabusPath = Storage::url($publicacion[0]->cSilabusPath);
            $publicacion[0]->cPublicacionFile = Storage::url($publicacion[0]->cPublicacionFile);

            $parameters = [$publicacion[0]->iModuloId];

            $publicacion[0]->unidades = DB::select('exec acad.[SP_SEL_unidadesByModuloId] ?', $parameters);
            $publicacion[0]->publicoObjetivo = DB::select('exec acad.[SP_SEL_publicoObjetivoByModuloId] ?', $parameters);

            $response = ['validated' => true, 'data' => $publicacion[0], 'message' => 'Publicacion obtenida correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerGrupoHorariosByPublicacionId($publicacionId)
    {
        $parameters = [
            $publicacionId
        ];
        try {
            $horarios =  DB::select('exec acad.[SP_SEL_obtenerPreinscritosXHorario] ?', $parameters);

            $response = ['validated' => true, 'data' => $horarios, 'message' => 'Horarios obtenidos con éxito'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);;
    }

    public function obtenerModalidadesEstudio()
    {
        try {
            $modalidades = Modalidad::select('iModalEstudId', 'cModalEstudDsc')
            ->get();
            $response = ['validated' => true, 'data' => $modalidades, 'message' => 'datos obtenidos correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }

    public function publicacionById(Request $request, $id)
    {

        $filial = $request->iFilId;
        $programaAcad = $request->programAacd;

        $paramateres = [
            $id
        ];
        try {

            $publicacion = DB::select('exec acad.[Sp_Sel_publacionModuloById] ?', $paramateres);


            if (count($publicacion) == 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'no se encontraron datos'], 400);
            }

            $docentes = DB::select('exec [acad].[Sp_CCTIC_SEL_obtenerDocentesByFilialProgramAcad] ?, ?', [$filial, $programaAcad]);

            $publicacion = $publicacion[0];
            $publicacion->unidades = json_decode($publicacion->unidades, true);

            $data = [
                'publicacion' => $publicacion,
                'docentes' => $docentes,
            ];

            $response = ['validated' => true, 'data' => $data, 'message' => 'publacion obtenida correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerFiltrosPreinscritos($publicacionId)
    {
        try {
            $filtros = DB::select('exec acad.SP_SEL_obtenerPreinscritosXHorario ?', [$publicacionId]);


            if (count($filtros) == 0) {
                return response()->json(['validated' => true, 'data' => [], 'message' => 'no se encontraron filtros'], 404);
            }


            foreach ($filtros as $filtro) {
                $filtro->detalles = json_decode($filtro->detallesPrehorario);
                $filtro->detallesGrupo = json_decode($filtro->detallesGrupoPrehorario);
            }


            $firstFilter = PreInscripcion::where('iPreHorariosPublicacionId', '=', $filtros[0]->iPreHorariosPublicacionId)
                ->get();

            $data = [
                'filtros' => $filtros,
                'firstFilter' => $firstFilter
            ];

            $response = ['validated' => true, 'data' => $data, 'message' => 'datos obtenidos correctamnte'];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function recursosEditarPublicacion($publicacionId)
    {

        try {
            $publicacion = DB::select('exec [acad].[SP_SEL_publicacionCursoHorarioByID] @iPublicacionId = ?', [$publicacionId]);

            $publicoObjetivo = PublicoObjetivo::get();
            $modalidadEstudio = Modalidad::select('iModalEstudId', 'cModalEstudDsc')
                ->get();
            $tipoDuracion = $this->general->publicacionDuracion();
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => $e->getMessage()];
            $responseCode = 500;
            return response()->json($response, $responseCode);
        }

        if (count($publicacion) == 0) {
            $response = ['validated' => true, 'message' => 'No se encontro la publicacion'];
            $responseCode = 404;
            return response()->json($response, $responseCode);
        }
        $publicacion = $publicacion[0];

        $publicacion->curso = json_decode($publicacion->curso, 1);
        $publicacion->horarios = json_decode($publicacion->horarios, 1);

        //        if (is_null($publicacion->horarios)) {
        //            $publicacion->horarios = [];
        //        }

        $data = [
            'publicoObjetivo' => $publicoObjetivo,
            'modalidades' => $modalidadEstudio,
            'tipoDuracion' => $tipoDuracion,
            'publicacion' => $publicacion,
        ];

        $response = ['validated' => true, 'data' => $data, 'message' => 'Datos obtenidos correctamente'];
        $responseCode = 200;
        return response()->json($response, $responseCode);
    }

    public function editarPublicacion(Request $request)
    {
        $horarios = json_decode($request->horarios);
        $newfilePathImage = null;
        $newfilePathPDF = null;
        if ($request->hasFile('imagenPublicacion')) {
            $newfilePathImage = $request->file('imagenPublicacion')->store('cctic/publicaciones/images');
        }

        if ($request->hasFile('file')) {
            $newfilePathPDF = $request->file('file')->store('cctic/publicaciones/pdf');
        }

        try {
            $publicacion = Publicacion::find($request->iPublicacionId);
            if (is_null($publicacion)) {
                $response = ['validated' => false, 'message' => 'Publicacion no encontrada'];
                $responseCode = 404;
            }
            return response()->json($response, $responseCode);
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'Error no se encontrto la pbulicacion'];
            $responseCode = 500;
        }

        $oldFilePathImage = $publicacion->cPublicacionImagen;
        $oldfilePathPDF = $publicacion->cPublicacionFile;

        $dataUpdate = [
            //            'iModuloPlanTrabajoCostoId' => $request->iModuloPlanTrabajoCostoId,
            'iModalEstudId' => $request->iModalEstudId,
            'iProgramasAcadId' => $request->iProgramAcadId,
            'iFilId' => $request->iFilId,
            'cPublicacionNombre' => '',
            'cPublicacionDescripcion' => '',
            'iMinCantMatricula' => $request->iMinCantMatricula,
            'iMaxCantMatricula' => $request->iMaxCantMatricula,
            'iMinCantPreinscritos' => $request->iMinCantPreinscritos,
            'dPublicacionFechaInicio' => $request->dPublicacionFechaInicio,
            'bPublicacionEstado' => 1,
            'iTipoDuracionId' => $request->iTipoDuracionId,
            'iPublicacionCantidadDuracion' => $request->iPublicacionCantidadDuracion,
        ];

        if (!is_null($newfilePathImage)) {
            $dataUpdate['cPublicacionImagen'] = $newfilePathImage;
        }

        if (!is_null($newfilePathPDF)) {
            $dataUpdate['cPublicacionFile'] = $newfilePathPDF;
        }

        DB::beginTransaction();


        $dataHorario = [
            'iProgramaAcadId' => $request->iProgramAcadId,
            'iFilId' => $request->iFilId,
            'cUsuarioSis' => auth()->user()->cCredUsuario,
            'cIpSis' => $request->server->get('REMOTE_ADDR'),
        ];

        foreach ($horarios as $hkey => $horario) {

            try {

                if (is_null($horario->iPreHorariosId)) {

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
                                'iPublicacionId' => $request->iPublicacionId
                            ]
                        );
                }

                if (!is_null($horario->iPreHorariosId)) {

                    foreach ($horario->detalles as $detalle) {

                        if (is_null($detalle->iPreHorariosDetalleId)) {
                            DB::table('acad.pre_horarios_detalle')->insert(
                                [
                                    'iPreHorariosId' => $horario->iPreHorariosId,
                                    'iDia' => $detalle->iDia,
                                    'tHoraInicio' => $detalle->tHoraInicio,
                                    'tHoraFin' => $detalle->tHoraFin,
                                    'nHorasAcademicas' => $detalle->nHorasAcademicas,
                                ]
                            );
                        }
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage(), 'message' => 'Error al actualizar el horario']);
            }
        }

        try {
            $planTrabajoModulo = DB::table('acad.modulo_plan_trabajo_costo')
                ->where('iModuloPlanTrabajoCostoId', '=', $request->iModuloPlanTrabajoCostoId)
                ->update([
                    'fMatricula' => $request->costoMatricula,
                    'fMensualidad' => $request->costoMensualidad,
                    'iDescuentoId' => $request->iDescuentoId
                ]);

            Publicacion::where('iPublicacionId', '=', $request->iPublicacionId)
                ->update($dataUpdate);

            $response = ['validated' => true, 'message' => 'Publicacion actualizada correctamente'];
            $responseCode = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'message' => 'Ne se pudo actualizar la publicacion', 'error' => $e->getMessage()];
            $responseCode = 500;

            Storage::delete($newfilePathPDF);
            Storage::delete($newfilePathImage);
        }

        DB::commit();

        if (!is_null($newfilePathImage)) {
            Storage::delete($oldFilePathImage);
        }

        if (!is_null($newfilePathPDF)) {
            Storage::delete($oldFilePathImage);
        }

        return  response()->json($response, $responseCode);
    }
}
