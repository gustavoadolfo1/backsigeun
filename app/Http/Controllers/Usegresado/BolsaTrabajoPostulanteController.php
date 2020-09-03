<?php

namespace App\Http\Controllers\Usegresado;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BolsaTrabajoPostulanteController extends Controller
{
    ////////////////////////////CRUD POSTULACION//////////////////////////
    public function Postulacion(Request $request)
    
    {
     
        $this->validate(
            $request, 
            [
                'opcion' => 'required',
                'valorBusqueda' => 'required',
                'iAvisoId' => 'required',
                'iHojaVidaId' => 'required',
                'iEtapaPostId' => 'required'
                //'dtPostulacion' => 'required',
                
            ], 
            [
                'opcion.required' => 'La opción es requerido',
                'valorBusqueda.required' => 'El valor de búsqueda es requerido',
                'iAvisoId.required' => 'No se obtuvo el identificador del aviso',
                'iHojaVidaId.required' => 'No se obtuvo el identificador de la hoja de vida'
                //'dtPostulacion.required' => 'Hubo un problema al obtener la fecha de postulación'
            ]
        );

        $parametros =[
            $request->opcion ?? NULL,
            $request->valorBusqueda ?? NULL,
            $request->iAvisoId ?? NULL,
            $request->iHojaVidaId ?? NULL,
            $request->iEtapaPostId ?? NULL,
            date("Y-m-d H:i:s"),
            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

       
        try {
            $queryResult = \DB::select('exec [bseg].[Sp_BSEG_CRUD_postulacion] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Se hizo la consulta correctamente', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        
        }

        return response()->json( $response, $codeResponse );
    }

    ///////////////////////////////CRUD AVISO/////////////////////
    public function Aviso(Request $request)
    
    {
       
        $this->validate(
            $request, 
            [
                'opcion' => 'required',
                'valorBusqueda' => 'required',
                'iEmpresaId' => 'required',
                'iNivelPuesto' => 'required',
                'iAreaPuesto' => 'required',
                'iTipoTrabajoId' => 'required',
                'iTipoPuestoId' => 'required',
                'iTipoContratoId' => 'required',
                'iPrvnIdUbicacion' => 'required',
                'iTiempoExperienciaId' => 'required',
                'iConvenioId' => 'required',
                'cNombrePuesto' => 'required',
                'cDescPuesto' => 'required',
                'cResponsabilidades' => 'required',
                'cRequisitos' => 'required',
                //'dCreacion' => 'required',
                //'dInicio' => 'required',
                //'dFin' => 'required',
                'nSalarioMin' => 'required',
                'nSalarioMax' => 'required',
                'bAConvenir' => 'required',
                'bNotificarCorreo' => 'required',
                'cCorreoNotifica' => 'required',
                'bDiscapacitado' => 'required',
                'bOtraEmpresa' => 'required',
                'cEmpresaMostrar' => 'required',
                'cLogoMostrar' => 'required',
                'cUbicacion' => 'required',
                'iEstadoPublicacion' => 'required'
                
            ], 
            [
                'opcion.required' => 'La opción es requerido',
                'valorBusqueda.required' => 'El valor de búsqueda es requerido',
                'iEmpresaId.required' => 'No se obtuvo el identificador de la empresa',
                'iNivelPuesto.required' => 'No se obtuvo el identificador del nivel del puesto',
                'iAreaPuesto.required' => 'No se obtuvo el identificador del área de puesto',
                'iTipoTrabajoId.required' => 'No se obtuvo el identificador del tipo de trabajo',
                'iTipoPuestoId.required' => 'No se obtuvo el identificador del tipo de puesto',
                'iTipoContratoId.required' => 'No se obtuvo el identificador del tipo de contrato',
                'iPrvnIdUbicacion.required' => 'No se obtuvo el identificador de la provincia ',
                'iTiempoExperienciaId.required' => 'No se obtuvo el identificador del tiempo de experiencia',
                'iConvenioId.required' => 'No se obtuvo el identificador del convenio',
                'cNombrePuesto.required' => 'No se obtuvo el nombre del puesto',
                'cDescPuesto.required' => 'No se obtuvo la descripción del puesto',
                'cResponsabilidades.required' => 'No se obtuvo las responsabilidades',
                'cRequisitos.required' => 'No se obtuvo los requisitos',
                //'dCreacion.required' => 'No se obtuvo',
                //'dInicio.required' => 'No se obtuvo',
                //'dFin.required' => 'No se obtuvo',
                'nSalarioMin.required' => 'No se obtuvo el salario mínimo',
                'bAConvenir.required' => 'No se obtuvo',
                'bNotificarCorreo.required' => 'No se obtuvo',
                'cCorreoNotifica.required' => 'No se obtuvo',
                'bDiscapacitado.required' => 'No se obtuvo',
                'bOtraEmpresa.required' => 'No se obtuvo',
                'cEmpresaMostrar.required' => 'No se obtuvo',
                'cLogoMostrar.required' => 'No se obtuvo',
                'cUbicacion.required' => 'No se obtuvo',
                'iEstadoPublicacion.required' => 'No se obtuvo'
                
            ]
        );

        $parametros =[
            $request->opcion ?? NULL,
            $request->valorBusqueda ?? NULL,
            $request->iEmpresaId ?? NULL,
            $request->iNivelPuesto ?? NULL,
            $request->iAreaPuesto ?? NULL,
            $request->iTipoTrabajoId ?? NULL,
            $request->iTipoContratoId ?? NULL,
            $request->iPrvnIdUbicacion ?? NULL,
            $request->iTiempoExperienciaId ?? NULL,
            $request->iConvenioId ?? NULL,
            $request->cNombrePuesto ?? NULL,
            $request->cDescPuesto ?? NULL,
            $request->cResponsabilidades ?? NULL,
            $request->cRequisitos ?? NULL,
            $request->dCreacion ?? NULL,
            $request->dInicio ?? NULL,
            $request->dFin ?? NULL,
            $request->nSalarioMin ?? NULL,
            $request->bAConvenir ?? NULL,
            $request->bNotificarCorreo ?? NULL,
            $request->cCorreoNotifica ?? NULL,
            $request->bDiscapacitado ?? NULL,
            $request->bOtraEmpresa ?? NULL,
            $request->cEmpresaMostrar ?? NULL,
            $request->cLogoMostrar ?? NULL,
            $request->cUbicacion ?? NULL,
            $request->iEstadoPublicacion ?? NULL,

            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

       
        try {
            $queryResult = \DB::select('exec [bseg].[Sp_BSEG_CRUD_aviso] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Se hizo la consulta correctamente', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        
        }

        return response()->json( $response, $codeResponse );
    }


    ///////////////////////////////CRUD COMUNICADO POSTULACION/////////////////////
    public function Comunicado(Request $request)
    
    {
       
        $this->validate(
            $request, 
            [
                'opcion' => 'required',
                'valorBusqueda' => 'required',
                'iPostulacionId' => 'required',
                'iEmpresaId' => 'required',
                'iPersId' => 'required',
                'cComunicadoPos' => 'required',
                'bVisto' => 'required'
                
                
            ], 
            [
                'opcion.required' => 'La opción es requerido',
                'valorBusqueda.required' => 'El valor de búsqueda es requerido',
                'iPostulacionId.required' => 'No se obtuvo el identificador de la postulación',
                'iEmpresaId.required' => 'No se obtuvo el identificador de la empresa',
                'iPersId.required' => 'No se obtuvo el identificador de la persona',
                'cComunicadoPos.required' => 'No se obtuvo',
                'iTipoContratoId.required' => 'No se obtuvo'
                
                
            ]
        );

        $parametros =[
            $request->opcion ?? NULL,
            $request->valorBusqueda ?? NULL,
            $request->iPostulacionId ?? NULL,
            $request->iEmpresaId ?? NULL,
            $request->iPersId ?? NULL,
            $request->cComunicadoPos ?? NULL,
            $request->iTipoContratoId ?? NULL,
           

            auth()->user()->iCredId,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

       
        try {
            $queryResult = \DB::select('exec [bseg].[Sp_BSEG_CRUD_comunicado_postulacion] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Se hizo la consulta correctamente', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        
        }

        return response()->json( $response, $codeResponse );
    }
   
   
}
