<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\UraEstudiante;
use App\Http\Controllers\PideController;

class MatriculaController extends Controller
{
    public function obtenerHorarios($codigoUniv, $cicloAcad)
    {
        $horarios = \DB::select('exec ura.Sp_ESTUD_SEL_horarioClasesXcEstudCodUnivXiControlCicloAcad ?, ?', array( $codigoUniv, $cicloAcad));

        return response()->json( $horarios );
    }

    public function obtenerSemestresAcademicosEstudiante($codigoUniv)
    {
        $semestres = \DB::select('exec ura.Sp_ESTUD_Sel_SemestresAcademicosMatricXcEstudCodUniv ?', array( $codigoUniv));

        return response()->json( $semestres );
    }

    /**
     * Muestra los cursos disponibles del estudiante para el proceso de matricula
     * 
     * Mod: Estudiante - Matricula
     */
    public function obtenerCursosDisponiblesMatricula($codUniv)
    {
        try {
            $response = \DB::select('exec [ura].[Sp_ONLINE_cursosDisponibleMatriculaXcEstudCodUniv] ?', array( $codUniv ));

            $data = [];
            foreach ($response[0] as $key => $value) {
                $data[$key] = [];
                if ($value != null) {
                    $data[$key] = json_decode($value);
                }
                if ($key == 'cursos_disponibles' && $value) {
                    foreach ($data[$key] as $curso) {
                        $horario_curso = json_decode($curso->horario_curso);
                        $curso->horario_curso = $horario_curso;
                    }
                }
            }

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            $data = ['mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        
        return response()->json( $data, $codeResponse );
    }
    
    /**
     * Guarda la proforma de matrícula
     * 
     */
    public function guardarProforma(Request $request)
    {
        $this->validate(
            $request, 
            [
                'idProforma' => 'required',
                'codEstudiante' => 'required',
                'tipoMat' => 'required',
                'regular' => 'required',
                'cursos' => 'required',
                'conceptos' => 'required',
            ], 
            [
                'idProforma.required' => 'Hubo un problema al obtener el ID de Proforma.',
                'codEstudiante.required' => 'Hubo un problema al obtener el código del estudiante',
                'tipoMat.required' => 'Hubo un problema al verificar el tipo de matrícula.',
                'regular.required' => 'No se pudo identificar la regularidad',
                'cursos.required' => 'Hubo un problema al obtener información de los cursos.',
                'conceptos.required' => 'Hubo un problema al obtener información de los conceptos.',
            ]
        );

        $cursos = $this->formatter($request->cursos, 'cursos');
        $electivos = $this->formatter($request->electivos, 'cursos');
        $cursoExtra = $this->formatter($request->cursoExtra, 'cursos');
        $conceptos = $this->formatter($request->conceptos, 'conceptos');

        $ip = $request->server->get('REMOTE_ADDR');

        try {
            $queryResult = \DB::select("exec [ura].[Sp_ESTUD_INS_matriculaOnline] $request->idProforma, '$request->codEstudiante', $request->tipoMat, $request->regular, 'user', 'equipo', '$ip', 'mac', '$cursos', '$conceptos', '$cursoExtra', '$electivos'");

            /*$phone = $queryResult[0]->cCelular;
            $monto = $queryResult[0]->nConcepReqImptTotal;

            $pide = new PideController();
            $response = $pide->consultar('sms', ['celular' => $phone, 'mensaje' => "Se generó su proforma correctamente, el monto es: $monto"]);*/
            
            $response = ['validated' => true, 'mensaje' => 'Se guardó la proforma y ficha de matrícula exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            //return response()->json( $e );
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'code' => $e->getCode(), 'exception' => $e];
            $codeResponse = 500;
        }
        
        return response()->json( $response, $codeResponse );

    }

    /**
     * Elimina una proforma por ID
     */
    public function eliminarProforma($idProforma, $codigo)
    {
        try {
            $queryResult = \DB::select("EXEC [ura].[Sp_ESTUD_DEL_proformasXiProforId] ?, ?", array($idProforma, $codigo));

            $response = ['validated' => true, 'mensaje' => 'Se eliminó la proforma de matrícula exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function formatter($array, $tipo)
    {
        $nRegistros = count($array);
        $data = "";
        foreach ($array as $key => $registro) {
            $data .= $this->formatFila($registro, $tipo);
            if( $key < $nRegistros - 1) {
                $data .= '|';
            }
        }
        
        return $data;
    }

    public function formatFila($registro, $tipo)
    {
        if (array_key_exists('seccId', $registro)) {
            $seccId = $registro['seccId'];
        }
        else {
            $seccId = 1;
        }
        $row = "";
        switch ($tipo) {
            case 'cursos':
                $row .= $registro['iCurricId'].",".$registro['cCurricAnio'].",".$registro['iCurricCursoId'].",".$registro['iCarreraId'].",''".$registro['cCurricCursoCod']."'',''".$registro['cCurricDetCicloCurso']."'',".$registro['iCurricDetHrsPCurso'].",".$registro['iCurricDetHrsTcurso'].",".$registro['nCurricDetCredCurso'].",".$registro['num_matricula'].",''".$registro['tipo_curso']."'',". $seccId .",".$registro['iConceptoItem'].",".$registro['nMontoCaja'];
                break;

            case 'conceptos':
                $row .= $registro['iCodConcepto'].",".$registro['nMonto'];
                break;

            default:
                # code...
                break;
        }

        return $row;
    }
}
