<?php

namespace App\Http\Controllers\Ura;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Generales\GrlPersonasController;

use App\UraCurricula;
use App\UraControlCicloAcademico;

class GeneralController extends Controller
{
    /**
     * Busca entre docentes y estudiantes
     */
    public function buscarEstudiantesDocentes($parametro, $carreraId = 0, $filialId = 0)
    {
        
        $docentes = \DB::select('exec ura.[Sp_GRAL_SEL_docentesSistema] ?, ?',array($parametro, $carreraId));
        $estudiantes = \DB::select('exec ura.[Sp_GRAL_SEL_estudiantes] ?, ?, ?',array($parametro, $carreraId, $filialId));

        $data = [ 'docentes' => $docentes, 'estudiantes' => $estudiantes];

        return response()->json( $data );

    }

    /**
     * Obtener datos del estudiante
     */
    public function obtenerDatosEstudiante($codigo)
    {
        $estudiante = \DB::select('exec [ura].[Sp_GRAL_SEL_estudiantesXcEstudCodUniv] ?',array($codigo));

        $gpc = new GrlPersonasController();

        $request = new \Illuminate\Http\Request();
        $request->replace(['code' => $estudiante[0]->iPersId]);

        $estudiante[0]->fotoReniec = $gpc->getFotoReniec($request, true);
        
        return response()->json( $estudiante[0] );
    }

    /**
     * Obtener datos del estudiante
     */
    public function obtenerDatosDocente($id)
    {
        $docente = \DB::select('exec [ura].[Sp_GRAL_SEL_docente_x_id] ?',array($id));

        $docenteCabecera = [];
        if (count($docente) > 0) {
            $docenteCabecera = $docente[0];
            $docenteCabecera->contacto = [];
        }
        $contacto = [];
        foreach ($docente as $docente_contacto) {
            if ($docente_contacto->iPersTipoConId != null) {
                $count = array_key_exists($docente_contacto->cTipoConDescripcion, $contacto) ? count($contacto[$docente_contacto->cTipoConDescripcion]) : 0;
                $contacto[$docente_contacto->cTipoConDescripcion][$count] = [];
                $contacto[$docente_contacto->cTipoConDescripcion][$count]['iPersTipoConId'] = $docente_contacto->iPersTipoConId;
                $contacto[$docente_contacto->cTipoConDescripcion][$count]['cPersTipoConDescripcion'] = $docente_contacto->cPersTipoConDescripcion;
                $contacto[$docente_contacto->cTipoConDescripcion][$count]['bPersTipoConPrincipal'] = $docente_contacto->bPersTipoConPrincipal;
            }
        }
        $docenteCabecera->contacto = $contacto;

        $gpc = new GrlPersonasController();

        $request = new \Illuminate\Http\Request();
        $request->replace(['code' => $docenteCabecera->iPersId]);

        $docenteCabecera->fotoReniec = $gpc->getFotoReniec($request, true);
        
        return response()->json( $docenteCabecera );
    }

    /**
     * obtener el horario de un estudiante 
     */
    public function obtenerHorarioEstudiante($codigo, $cicloAcad)
    {
        $horario = \DB::select('exec [ura].[Sp_ESTUD_SEL_horario_estudiante] ?, ?',array($codigo, $cicloAcad));
        
        return response()->json( $horario );
    }

    public function reporteMatriculados($carreraId)
    {
        $data = \DB::connection('mysql')->table('encuesta_reg')->where('CODI_UNIV', '2012204049')->where('CICL_ACAD', '20192')->count();
// \DB::connection('mysql')->getDatabaseName()
        return response()->json( $data );

        $estudiantes = \DB::select('exec [ura].[SP_SEL_reporte_matriculados] ?',array($carreraId));

        return view('reporteMatriculados', [ 'estudiantes' => $estudiantes ]);
    }

    public function obtenerFilialesCarreras()
    {
        $filialesCarreras = \DB::select('exec [ura].[Sp_GRAL_SEL_carrerasFiliales]');

        return response()->json( $filialesCarreras );
    }

    public function obtenerPlanesCarrera($carreraFilialId)
    {
        $planes = \DB::select('exec [ura].[Sp_GRAL_SEL_curriculasXiCarreraId] ?', array($carreraFilialId));

        return response()->json( $planes );
    }

    public function obtenerFilialesCarrerasPlanesCiclos()
    {
        $filialesCarreras = \DB::select('exec [ura].[Sp_GRAL_SEL_carrerasFiliales]');

        $planes = UraCurricula::all();
        $ciclosAcademicos = UraControlCicloAcademico::orderBy('iControlCicloAcad', 'desc')->get();

        $secciones = \DB::select('exec [ura].[Sp_SEL_secciones]');

        $tipoAperturaCursos = \DB::select('exec [ura].[Sp_GRAL_SEL_tiposAperturasCursos]');

        $data = [ 'planes' => $planes, 'ciclosAcademicos' => $ciclosAcademicos, 'filialesCarreras' => $filialesCarreras, 'secciones' => $secciones, 'tipoAperturaCursos' => $tipoAperturaCursos ];

        return response()->json($data);
    }

    public function getCarrerasSemestres()
    {
        $ciclosAcademicos = UraControlCicloAcademico::orderBy('iControlCicloAcad', 'desc')->get();
        $carreras = \DB::select('exec ura.Sp_GRAL_SEL_RegistrosXcShemaXcNombreTabla ?, ?',array('ura', 'carreras'));

        return response()->json( [ 'semestres' => $ciclosAcademicos, 'carreras' => $carreras ] );
    }

    public function getPlanesCiclosCarrera($carreraId)
    {
        $planesCiclos = \DB::select('exec ura.Sp_DASA_SEL_ciclosXiCarreraId ?',array($carreraId));

        return response()->json( $planesCiclos );
    }

    public function getLinkCapacitacion($moduloCod)
    {
        $link = \DB::select('exec ura.Sp_SEL_rutaVideoconferencia', [ $moduloCod ]);

        return response()->json( $link );
    }

    public function getLinksModulos($moduloId)
    {
        $links = \DB::table('seg.modulos_enlaces_web')->where('iModuloId', $moduloId)->get();

        return response()->json( $links );
    }

    public function ingresarConferencia($conferenciaId, $codEstud)
    {
        try {
            $queryResult = \DB::select('exec ura.Sp_INS_asistenciaVideoconferencia ?, ?', [ $conferenciaId, $codEstud ]);

            $response = ['validated' => true, 'mensaje' => 'Se guardó la el registro de asistencia.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
