<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\UraEstudiante;
use App\Http\Controllers\PideController;

class EstudianteController extends Controller
{
    /**
     * Obtiene informacion de contacto de un estudiante
     */
    public function obtenerDatosContacto($codigo)
    {
        try {
            $data = \DB::select('exec [ura].[Sp_ESTUD_SEL_datosContacto] ?', array( $codigo ));
            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $data = ['mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        
        return response()->json( $data, $codeResponse );
    }

    /**
     * edita informacion de contacto de un estudiante
     */
    public function editarDatosContacto(Request $request)
    {
        # code...EXEC [ura].[Sp_ESTUD_UPD_datosContacto] @_cEstudCorreo varchar(200), @_cEstudTelef varchar(50), @cClave varchar(20), @cEstudCodUniv varchar(20), @iFilId int=0, @iCarreraId int
        $this->validate(
            $request, 
            [
                'correo' => 'required',
                'telefono' => 'required',
                'clave' => 'required|min:6',
                'clave2' => 'required|min:6|same:clave',
                'codigoUniv' => 'required',
                'filId' => 'required',
                'carreraId' => 'required',
            ], 
            [
                'correo.required' => 'El campo Correo es obligatorio.',
                'telefono.required' => 'El campo Celular es obligatorio.',
                'clave.required' => 'Hubo un problema al verificar el tipo de matrícula.',
                'codigoUniv.required' => 'No se pudo identificar la regularidad',
                'filId.required' => 'Hubo un problema al obtener información de los cursos.',
                'carreraId.required' => 'Hubo un problema al obtener información de los conceptos.',
                'clave.required' => 'El campo Nueva contraseña es obligatorio',
                'clave.min' => 'Los campos contraseña debe ser de al menos :min caracteres',
                'clave2.required' => 'El campo Repite contraseña es obligatorio',
                'clave2.min' => 'El campo contraseña debe ser de al menos :min caracteres',
                'clave2.same' => 'Los campos contraseña no coinciden.',
            ]
        );

        $parametros =[
            $request->correo ?? NULL,
            $request->telefono ?? NULL,
            $request->clave ?? NULL,
            $request->codigoUniv ?? NULL,
            $request->filId ?? NULL,
            $request->carreraId ?? NULL,
            //auth()->user()->cCredUsuario,
            'user',
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        //dd($parametros);

        try {
            $queryResult = \DB::select('exec [ura].[Sp_ESTUD_UPD_datosContacto] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Se editaron sus datos correctamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        
        }

        return response()->json( $response, $codeResponse );
    }

    public function getAsistenciaEstudianteCurso($codigo, $cursoCod, $cicloAcad)
    {
        $data = \DB::select('exec [ura].[Sp_ESTU_SEL_asistenciaEstudiante] ?, ?, ?', array( $codigo, $cursoCod, $cicloAcad ));

        return response()->json( $data );
    }

    public function getNotasEstudianteCurso($codigo, $cursoCod, $cicloAcad)
    {
        $data = \DB::select('exec [ura].[Sp_ESTU_SEL_notasEstudiante] ?, ?, ?', array( $codigo, $cursoCod, $cicloAcad ));
        foreach ($data[0] as $index => $value) {
            $data[0]->$index = json_decode($value);
        }

        return response()->json( $data[0] );
    }

    public function getReporteEncuesta($codigo, $cicloAcad)
    {
        $data = \DB::connection('mysql')->table('encuesta_reg')->where('CODI_UNIV', $codigo)->where('CICL_ACAD',$cicloAcad)->count();

        $detalles = \DB::select('exec [ura].[Sp_ESTUD_SEL_boletaNotasXcEstudCodUnivXcicloAcad] ?, ?', array($codigo, $cicloAcad));

        $isSuccess = true;
        if ($data < count($detalles)) {
            $isSuccess = false;
        }

        return response()->json( [ 'count' => $data, 'isSuccess' => $isSuccess, 'detalles' => $detalles ] );
    }

    public function getSustitutorio(Request $request){

        $parametros = [
            $request->iControlCicloAcad,
            $request->cEstudCodUniv
        ];

        $data = \DB::select('exec [ura].[Sp_DOCE_SEL_Notas_MuestraCursos_EstudianteSustitutorio] ?,?',$parametros);

        return response()->json($data);
    }

    public function genPreDeuda(Request $request){

        $parametros = [
            $request->cCursos_json,
            $request->iEstudId,
            $request->iControlCicloAcad,
            'user',
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select('exec [ura].[Sp_ESTUD_INS_generaPreDeudaSustitutorio] ?,?,?,?,?,?,?',$parametros);
            $response = ['validated' => true, 'mensaje' => 'Sustitutorio generado, vaya a pagar en caja.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        
        }

        return response()->json( $response, $codeResponse );

    }

    public function getAtencion($iPersId,$desde,$hasta)
    {   
        if($desde=="1" || $hasta == "1" ) {
        $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array('CONSULTAR_PERSONA',$iPersId,'0','0','0','-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
        }
        else{
         $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array('CONSULTAR_PERSONA_FECHA',$iPersId,'0','0','0',$desde,$hasta,'-','-','-','-','-','-','-','-','-','-','-','-'));
       
        }

      
        return response()->json(  $data  );
    }
}
