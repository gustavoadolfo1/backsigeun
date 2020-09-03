<?php

namespace App\Http\Controllers\CCU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralController extends Controller
{
    public function obtenerSemestresAcademicos()
    {
        
        try {
            $semestresAcademicos = \DB::select('EXEC [ccu].[Sp_SEL_Combo_Semestre_Academico]');
            $response = ['validated'=> true, 'message' => 'se obtuvo los semestres correctamente', 'data' => $semestresAcademicos];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerSedes()
    {
        
        try {
            $sedes = \DB::select('EXEC [ccu].[Sp_SEL_Combo_Filiales]');
            $response = ['validated'=> true, 'message' => 'se obtuvo las sedes correctamente', 'data' => $sedes];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerHoras(Request $request)
    {

        $dataResult = [];
        $parameters = [
            $request->id,
            $request->horarioModulo,
            $request->turno   
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Combo_Horario_Horas] ?,?,?',$parameters);
       
        return response()->json($dataResult);
        
    }

    public function obtenerDias() // se necesita para el campo iDiaSemId, cDiaSemDsc
    {
        try {
            $dias = \DB::select('EXEC [ccu].[Sp_SEL_Combo_Dias_Semanas]');
            $response = ['validated'=> true, 'message' => 'se obtuvo los dias correctamente', 'data' => $dias];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function crearHorario(Request $request) //para crear el docente responsable en el plan de trabajo
    {
        $dataResult = [];
        
        $parameters = [

            $request->iFilId, //Id de la sede
            $request->cFilSigla, // Si es moquegua,ilo o ichuña(M.I.S) 
            $request->iControlCicloAcad, // Nombre del semestre academico 
            $request->cCodigoActividad, // cCodigoCurso o el nombre del codigo del curso
            $request->iCursoActividadId, // ID del curso(programa)
            $request->iDiaSemId, // ID del dia de la semana
            $request->cDiaSemDsc, // Nombre del dia de la semana
            $request->tHoraInicioM, // Hora inicio mañana
            $request->tHoraTerminoM, // Hora Termino mañana
            $request->tHoraInicioT, // Hora Inicio Tarde
            $request->tHoraTerminoT, // Hora Termino Tarde
            $request->iCarreraId, // Id carrera(null)
            $request->iSeccionId, // Id seccion(null)
            $request->iNumHoras, // Numero de horas del curso por semana(2)
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Horarios] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerDiaId($id)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_DiasSemanasId] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function obtenerSedeId($id)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_FilialId] ?',[$id]);
       
        return response()->json($dataResult);
    }
    public function eliminarHorario(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iHorariosId,
            auth()->user()->iCredId   
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Horarios] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerTipDoc()
    {
        
        try {
            $tipDocs = \DB::select('EXEC [grl].[Sp_SEL_tipo_Identificaciones]');
            $response = ['validated'=> true, 'message' => 'se obtuvo las sedes correctamente', 'data' => $tipDocs];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
    public function crearResponsable(Request $request)
    {
        $dataResult = [];
        
        $parameters = [
            $request->iTipoPersId, // Natural o Juridica : 1
            $request->iTipoIdentId, // DNI o RUC
            $request->cPersDocumento, // numero de documento
            $request->cPersPaterno, // ape paterno
            $request->cPersMaterno, // ape materno
            $request->cPersNombre, // nombres
            $request->cPersSexo, // sexo
            $request->dPersNacimiento, // fecha de nacimiento
            $request->cPersRazonSocialNombre, // null
            $request->cPersRazonSocialCorto, // null
            $request->cPersRazonSocialSigla, // null
            $request->cPersRepresentateLegal, // null
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        $dataResult = DB::select('EXEC [grl].[Sp_INS_personas] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function obtenerTipDocId($id)
    {
        $dataResult = DB::select('EXEC [grl].[Sp_SEL_tipo_IdentificacionesXiTipoIdentId] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function buscarPersona($texto)
    {
        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_personas] ?',[$texto]);
       
        return response()->json($dataResult);
    }
    public function crearCargasHorarias(Request $request) //segunda parte para crear el docente responsable del plan de trabajo
    {
        $dataResult = [];
        
        $parameters = [
            $request->iPersId, // PersonaId
            $request->iDocenteId, // Docente id
            $request->iControlCicloAcad, // control del ciclo
            $request->iFilId, // id de filial
            $request->cFilSigla, // sigla de filial
            $request->cDNIPersona, // 
            $request->iCarreraId, // sexo
            $request->iActividadId, // fecha de nacimiento
            $request->iCursoActividadId, // null
            $request->cCodigoCurso, // null
            $request->iSeccionId, // null
            $request->iHorariosId, // null
            auth()->user()->iCredId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Cargas_Horarias] ?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function obtenerPersonaDNI($dni)
    {
        $dataResult = DB::select('EXEC [grl].[Sp_SEL_personasXcDocumento_cDescripcion] ?',[$dni]);
       
        return response()->json($dataResult);
    }
    public function eliminarEncargado(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCargasHorariasId,
            auth()->user()->iCredId   
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Cargas_Horarias] ?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function editarEncargado(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCargasHorariasId,
            $request->iPersId,
            $request->iDocenteId,
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->cFilSigla,
            $request->cDNIPersona,
            $request->iCarreraId,
            $request->iActividadId,
            $request->iCursoActividadId,
            $request->cCodigoCurso,
            $request->iSeccionId,
            $request->iHorariosId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Cargas_Horarias] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function buscarEstudiante(Request $request) //para el modulo estudiante.
    {
        $dataResult = [];
        $parameters = [
            $request->cBusqueda,
            $request->iControlCicloAcad
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Estudiantes_Matricula] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function mostrarProgramaMat(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Combo_Programa_Ficha_Matricula] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function mostrarHorario(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iCarreraId,
            $request->iActividadId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Horario_Ciclo_Sede] ?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerCarrera()
    {
        
        try {
            $carreras = \DB::select('EXEC [ccu].[Sp_SEL_Combo_Carreras]');
            $response = ['validated'=> true, 'message' => 'se obtuvo las carreras profesionales correctamente', 'data' => $carreras];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function buscarEstudianteCarrera(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->cBusqueda,
            $request->iCarreraId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Estudiantes_Compendio] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function agregarEstudianteFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCompendioId,
            $request->iEstudianteId,
            $request->cEstudCodUniv,
            $request->iFilId,
            $request->cFilSigla,
            $request->iCarreraId,
            $request->nHorasCurso,
            $request->iActividadId,
            $request->iDepenId,
            $request->dFechaRegistroDetalle,
            auth()->user()->iCredId
            
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Compendio_Detalle_Proyeccion_Social] ?,?,?,?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function mostrarFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iDependId,
            $request->iCarreraId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Compendio_Dependencia_Carrera] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function mostrarEstudianteFolio($idFolio)
    {
        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Compendio_Detalle_Estudiantes] ?',[$idFolio]);
       
        return response()->json($dataResult);
    }

    public function obtenerEstadoFolio($idFolio)
    {
        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Compendio_por_Id] ?',[$idFolio]);
       
        return response()->json($dataResult);
    }

    public function actualizarFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCompendioId,
            $request->iCarreraId,
            $request->cReferencia,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Compendio_por_Id] ?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function verificarEstadoFolio()
    {
        $estado = 55;
        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Compendio_por_Dependencia_Estado] ?',[$estado]);
       
        return response()->json($dataResult);
    }

    public function guardarCambiosFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCompendioId,
            $request->iCarreraId,
            $request->dFechaRegistro,
            $request->cReferencia,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Compendio_por_Id] ?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function eliminarEstudianteFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCompendioId,
            $request->iCompendioDetalleId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Compendio_Detalle_Estudiante] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function cambiarEstadoFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iCompendioId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Compendio_Estado] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function buscarFolio(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->cBusqueda,
            $request->iDependId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Estudiantes_Compendio_por_Depedencia] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerFechaFinMatricula(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iControlCicloAcad,
		    $request->iFilId

        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Fecha_Fin_Matricula] ?,?',$parameters);
       
        return response()->json($dataResult);
    }
    public function obtenerCarrerasFiliales($id)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Combo_Carreras_Filiales] ?',[$id]);
       
        return response()->json($dataResult);
    }

    public function ingresarLugar(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->cLugarDsc,
            $request->iProyectoId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_INS_Proyecto_Lugar] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function buscarLugar($valor)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Proyecto_Lugares] ?',[$valor]);
       
        return response()->json($dataResult);
    }

    public function mostrarLugar($idProyecto)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Lugares] ?',[$idProyecto]);
       
        return response()->json($dataResult);
    }

    public function eliminarLugar(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iProyectoLugarId,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_DEL_Proyecto_Lugar] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerUltimoRegistro($iActividadId)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_CursoActividadId_Ultimo_Registro] ?',[$iActividadId]);
       
        return response()->json($dataResult);
    }

    public function editarHorario(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iHorariosId,
            $request->iDiaSemId,
            $request->cDiaSemDsc,
            $request->tHoraInicioM,
            $request->tHoraTerminoM,
            $request->tHoraInicioT,
            $request->tHoraTerminoT,
            auth()->user()->iCredId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_UPD_Horarios] ?,?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerAsistenciaSesion(Request $request)
    {
        $dataResult = [];
        $parameters = [
            $request->iProyectoId,
            $request->iNumeroSesion
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Proyecto_Asistencia_Sesion] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerCicloActual()
    {
        try {
            $cicloActual = \DB::select('EXEC [ccu].[Sp_SEL_Ciclo_Academico_Activo]');
            $response = ['validated'=> true, 'message' => 'se obtuvo el ciclo actual correctamente', 'data' => $cicloActual];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function obtenerHorarioCurso(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iControlCicloAcad,
		    $request->iFilId,
		    $request->iCarreraId,
		    $request->cEstudCodUniv
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Horario_Matricula_Estudiante] ?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerHorasEstudiante($codigo)
    {
        

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Estudiante_Record_Notas] ?',[$codigo]);
       
        return response()->json($dataResult);
    }

    public function obtenerHorasEstudianteDetalle(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->cEstudCodUniv,
		    $request->iActividadId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Estudiante_Record_Notas_Detalles] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerNotasEstudianteDetalle(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iHorariosId,
            $request->cEstudCodUniv
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Notas_Consolidado_Curso_Estudiante] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function buscarEstudianteFilial(Request $request) 
    {
        $dataResult = [];
        $parameters = [
            $request->cBusqueda,
            $request->iControlCicloAcad,
            $request->iFilId
        ];
        

        $dataResult = DB::select('EXEC [ccu].[Sp_GRAL_SEL_Estudiantes_Matricula_Filial] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerAsistenciaEstudiante(Request $request)
    {
        $dataResult = [];   
        
        $parameters = [
            $request->iHorariosId,
            $request->cEstudCodUniv
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Asistencia_Resumen_Estudiante] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerCursoEstudiante(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iActividadId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Horario_Curso_Estudiante] ?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerCursoCarreraEstudiante(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iCarreraId,
            $request->iActividadId
        ];

        // $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Horario_Curso_Carrera_Estudiante] ?,?,?,?',$parameters);
       
        // return response()->json($dataResult);
        
            
        try {
            $dataResult = \DB::select('EXEC [ccu].[Sp_SEL_Horario_Curso_Carrera_Estudiante] ?,?,?,?',$parameters);
            $response = ['validated'=> true, 'message' => 'se obtuvo los semestres correctamente', 'data' => $dataResult];
            $responseCode = 200;
        } catch (\Exception $e)  {
          $response = ['validated' => false, 'message' => $e->getMessage(), 'data' => []];
          $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function mostrarReporteAsistencia(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->iCargasHorariasId,
            $request->iPersId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_ActaDocente_PorcentajeAsistencia] ?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function mostrarReporteNota(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->iPersId,				
	        $request->iControlCicloAcad,	
	        $request->iFilId,
	        $request->iCarreraId,				
	        $request->cCodigoCurso,	
	        $request->iSeccionId,
	        $request->iHorariosId			
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Notas_Muestra_PromedioFinal] ?,?,?,?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function obtenerDocenteCurso(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->iControlCicloAcad,
            $request->iFilId,
            $request->iCarreraId,
            $request->iActividadId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_Horario_Ciclo_Docente] ?,?,?,?',$parameters);
       
        return response()->json($dataResult);
    }

    public function imprimirAsistenciaCurso( 
        $iCargasHorariasId,
        $iPersId)
    {

        $general = DB::select('EXEC [ccu].[Sp_SEL_ActaDocente_PorcentajeAsistencia] ?,?',array(
            $iCargasHorariasId,
            $iPersId,
        ));

        $general = ['validated' => true, 'mensaje' => 'Sp_SEL_ActaDocente_PorcentajeAsistencia', 'result' => $general];
        $response = 200;
        return response()->json(['general' => $general, 'res' => $response]);
    }

    public function consultarHorasProyectoEstudiante(Request $request)
    {
        $dataResult = [];       
        $parameters = [
            $request->cEstudCodUniv,
            $request->iActividadId
        ];

        $dataResult = DB::select('EXEC [ccu].[Sp_SEL_ListaActividadCocurricular_ProyeccionSocial] ?,?',$parameters);
       
        return response()->json($dataResult);
    }



}
