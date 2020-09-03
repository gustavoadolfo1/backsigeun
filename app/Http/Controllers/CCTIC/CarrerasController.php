<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\cctic\CarrerasRepository;
use App\Http\Controllers\CCTIC\PreInscripcionController;


class CarrerasController extends Controller
{
    public function __construct(CarrerasRepository $carreras)
    {
        $this->carreras = $carreras;
    }

    public
    function obtenerCarerras($proAcadId)
    {
        try {
            $carreras = $this->carreras->obtenerCarerras($proAcadId);
            $response = ['validated' => true, 'message' => 'datos obtenidos correctamente', 'data' => $carreras];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerCarrerasXprogAcadFilialActiva($proAcadId)
    {
        try {
            $carreras = $this->carreras->obtenerCarerras($proAcadId);
            foreach ($carreras as $key => $value) {
                $carreras[$key]->filiales = \DB::select('[acad].[SP_SEL_Filial_activa] ?', [$value->iCarreraId]);
            }
            $response = ['validated' => true, 'message' => 'datos obtenidos correctamente', 'data' => $carreras];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }


    public
    function ObtenerCarreraByID($carreaId)
    {
        try {
            $carrera = $this->carreras->obtenerCarreraByID($carreaId);
            $modulos = $this->obtenerModulosByCarrera($carreaId, $carrera[0]->iProgramasAcadId);
            $carrera[0]->modulos = $modulos;
            $response = ['validated' => true, 'data' => $carrera[0]];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => null, 'message' => $e->getMessage()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);

    }

    public
    function obtenerModulosByCarrera($carrera_id, $programAcad_id)
    {
        try {
            $modulos = \DB::select('[acad].[Sp_SEL_modulos_programasXiCarreraIdXiProgramasAcadId] ?, ?',
                [$carrera_id, $programAcad_id]);
            foreach ($modulos as $key => $value) {
                $modulos[$key]->cursos = $this->obtenerCursosByModulo($value->iCarreraId, $value->cCiclosId);
            }
        } catch (\Exception $e) {
            $modulos = [];
        }

        return $modulos;

    }

    public
    function obtenerCursosByModulo($modulo_id, $curricDetCicloCurso)
    {
        try {
            $cursos = \DB::select('exec [acad].[Sp_SEL_cursosModulosXiCarreraIdXiModProgId] ?, ?', [$modulo_id, $curricDetCicloCurso]);

        } catch (\Exception $e) {
            $cursos = [];
        }

        return $cursos;
    }

    public function obtenerCarrerasModulos()
    {

        // todo refactorizar el obtener las carreras, modulos y carreras
        $preins = new PreInscripcionController();
        try {
            $carreras = $this->carreras->obtenerCarerras();

            foreach ($carreras as $key => $value) {
                // obtener modulos por carrera/taller
                $carreras[$key]->modulos = $preins->obtenerModulosByCarrera($value->iCarreraId, 3);
            }
            return response()->json(['data' => $carreras]);
        } catch (\Exception $e) {
            $cursos = [];
        }
    }
}
