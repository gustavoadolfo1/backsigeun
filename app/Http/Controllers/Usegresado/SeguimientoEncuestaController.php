<?php

namespace App\Http\Controllers\Usegresado;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SeguimientoEncuestaController extends Controller
{
    public function obternerTipoEncuesta()
    {
        $tipo_encuesta =  \DB::select('exec bseg.Sp_BSEG_CRUD_tipo_encuesta ?,?,?,?,?,?,?', array('CONSULTAR','-','0','-','-','-','-'));
        return Response::json( $tipo_encuesta);
    }
    public function obternerListaConvenios()
    {
        $convenio =  \DB::select('exec bseg.Sp_BSEG_SEL_ConveniosXconsultaVariablesCampos ?,?', array('conveniosVigentes','-'));
        return Response::json( $convenio);
    }
    public function obternerTipoCursos()
    {
        $tipo_curso =  \DB::select('exec bseg.Sp_BSEG_CRUD_tipo_curso ?,?,?,?,?,?,?,?', array('CONSULTAR','-','-','-','0','-','-','-'));
        return Response::json( $tipo_curso);
    }

    public function obternerFilial()
    {
        $filial =  \DB::select('exec bseg.Sp_BSEG_SEL_Filial');
        return Response::json( $filial);
    }

    public function obternerTipoTrabajo()
    {
        $tipotrabajo =  \DB::select('exec bseg.Sp_BSEG_CRUD_tipo_trabajo ?,?,?,?,?,?,?', array('CONSULTAR','-','-','0','-','-','-'));
        return Response::json( $tipotrabajo);
    }

    public function obternerTipoPuesto()
    {
        $tipopuesto =  \DB::select('exec bseg.Sp_BSEG_CRUD_tipo_puesto ?,?,?,?,?,?,?', array('CONSULTAR','-','-','0','-','-','-'));
        return Response::json( $tipopuesto);
    }

    public function obternerAreaTrabajo()
    {
        $area =  \DB::select('exec bseg.Sp_BSEG_CRUD_area_trabajo ?,?,?,?,?,?,?', array('CONSULTAR','-','-','0','-','-','-'));
        return Response::json( $area);
    }

    public function obternerSector()
    {
        $sector =  \DB::select('exec bseg.Sp_BSEG_CRUD_sector ?,?,?,?,?,?,?', array('CONSULTAR','-','-','0','-','-','-'));
        return Response::json( $sector);
    }

    public function obternerEntidad()
    {
        $entidad = \DB::table('con.entidades')
        ->get();
        return Response::json( $entidad);
    }

    public function obternerPais()
    {
        $pais =  \DB::select('exec bseg.Sp_BSEG_SEL_nacionalidadesXcNacionNombre ?', array(''));
        return Response::json( $pais);
    }

    public function obternerSelected()
    {
        

        $empresa        =  \DB::select ('exec [bseg].[Sp_BSEG_CRUD_empresa] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
        array(  'CONSULTAR',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '', 
                
                auth()->user()->cCredUsuario,
                gethostname(),
                '',
                'mac'
                
    
                ));

        $nivel_puesto    =  \DB::select ('exec [bseg].[Sp_BSEG_CRUD_nivel_puesto] ?, ?, ?, ?, ?, ?, ?', 
        array(  'CONSULTAR',
                '',
                '',
                
                auth()->user()->cCredUsuario,
                gethostname(),
                '',
                'mac'
                
    
                ));

        $area_puesto    =  \DB::select ('exec [bseg].[Sp_BSEG_CRUD_area_puesto] ?, ?, ?, ?, ?, ?, ?', 
        array(   'CONSULTAR',
                '',
                '',
                
                auth()->user()->cCredUsuario,
                gethostname(),
                '',
                'mac'
                
    
                ));

        $tipo_trabajo    =  \DB::select ('exec [bseg].[Sp_BSEG_CRUD_tipo_trabajo] ?, ?, ?, ?, ?, ?, ?', 
        array(   'CONSULTAR',
                '',
                '',
                
                auth()->user()->cCredUsuario,
                gethostname(),
                '',
                'mac'
                
    
                ));
        $tipo_puesto     =  \DB::select ('exec [bseg].[Sp_BSEG_CRUD_tipo_puesto] ?, ?, ?, ?, ?, ?, ?', 
        array(   'CONSULTAR',
                '',
                '',
                
                auth()->user()->cCredUsuario,
                gethostname(),
                '',
                'mac'
                
    
                ));
        $tipo_contrato  =  \DB::select ('exec [bseg].[Sp_BSEG_CRUD_tipo_contrato] ?, ?, ?, ?, ?, ?, ?', 
        array(   'CONSULTAR',
                '',
                '',
                
                auth()->user()->cCredUsuario,
                gethostname(),
                '',
                'mac'
                
    
                ));
        $provincia      = \DB::table('grl.provincias')->get();
        $convenio      = \DB::table('con.convenios')->get();

       return Response::json([
           'empresa' => $empresa,
           'nivel_puesto' => $nivel_puesto,
           'area_puesto'=>$area_puesto,
           'tipo_trabajo'=>$tipo_trabajo,
           'tipo_puesto'=>$tipo_puesto,
           'tipo_contrato'=>$tipo_contrato,
           'provincia'=>$provincia,
           'convenio'=>$convenio
           
           ]);
    }

    public function BuscarEgresado(Request $request){      
        switch( $request->opcion){
           
            case 'egresadosXcodigo':
                $data = \DB::select('exec bseg.[Sp_BSEG_SEL_EgresadosXconsultaVariablesCampos] ?,? ', array($request->opcion,$request->busqueda));
            break;
 
        }
        return response()->json( $data );
    }

    public function BuscarGraduado(Request $request){      
        switch( $request->opcion){
           
            case 'graduadosXresolucion':
                $data = \DB::select('exec bseg.[Sp_BSEG_SEL_GraduadosXconsultaVariablesCampos] ?,? ', array($request->opcion,$request->busqueda));
            break;
 
        }
        return response()->json( $data );
    }
   
    public function Taller(Request $request){
        switch( $request->opcion){
            case 'GUARDAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_curso] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        $request->convenio,
                        $request->tipo,
                        $request->filial, 
                        $request->tema,   
                        $request->objetivo,
                        $request->descripcion, 
                        $request->cupo, 
                        $request->inicio, 
                        $request->fin, 
                        $request->imagen,
                        ' ',
                        $request->certificado, 
                        $request->documento, 
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;

            case 'CONSULTAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_curso] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '', 
                        '',
                        '',
                        '',
                        ' ',
                        '',
                        '', 
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;
           default:break;
        }
       
    }


    public function Practicas(Request $request){
        switch( $request->opcion){
            case 'GUARDAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_practicas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        $request->iPersIdEstudiante,
                        $request->iTipoTrabajoId,
                        $request->iTipoPuestoId,
                        $request->iAreaTrabajoId,
                        '1', 
                        $request->iSectorIdEntidad,   
                        $request->bEntidadPub,
                        $request->cEntidad, 
                        $request->cPuesto, 
                        $request->dInicio, 
                        $request->dFin, 
                        $request->cContacto,
                        $request->cCargoContacto,
                        $request->cTelfContacto, 
                        $request->cCorreoContacto, 
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;

            case 'CONSULTAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_practicas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        $request->iEstudId?? NULL,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '', 
                        '',
                        '',
                        '',
                        ' ',
                        '',
                        '', 
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;
           default:break;
        }
       
    }
 
    public function DatosPersonales(Request $request){
        switch( $request->opcion){
            case 'EDITAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_INS_UPD_DatosPersonales] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  
                        $request->iPersId,
                        $request->cPersDocumento,
                        $request->cPersPaterno,
                        $request->cPersMaterno, 
                        $request->cPersNombre,   
                        $request->cPersSexo,
                        $request->dPersNacimiento, 
                        $request->iNacionId, 
                        $request->celular, 
                        $request->correo, 
                         
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;

            
           default:break;
        }
       
    }


    ///////////////////////////////CRUD COMUNICADO POSTULACION/////////////////////
    public function Encuesta(Request $request)
    
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
            $queryResult = \DB::select('exec [bseg].[Sp_BSEG_CRUD_encuesta ] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Se hizo la consulta correctamente', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        
        }

        return response()->json( $response, $codeResponse );
    }


    public function Empresas(Request $request){
        switch( $request->opcion){
            case 'GUARDAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_empresa] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        $request->iPersId,
                        $request->iSectorId,
                        $request->iEntidadId,
                        $request->cNombreMostrar,
                        $request->cLogo ?? '---',
                        $request->dFundacion,   
                        $request->cHistoria,
                        $request->cServicios, 
                        $request->cExperiencia, 
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;

            case 'CONSULTAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_empresa] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '', 
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;
           default:break;
        }
       
    }

    public function Avisos(Request $request){
        switch( $request->opcion){
            case 'GUARDAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_aviso] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        $request->iEmpresaId,
                        $request->iNivelPuesto,
                        1,
                        $request->iTipoTrabajoId,
                        $request->iTipoPuestoId,
                        $request->iTipoContratoId,   
                        $request->iPrvnIdUbicacion,
                        //$request->iTiempoExperienciaId, 
                        1,
                        $request->iConvenioId,
                        

                        $request->cNombrePuesto,
                        $request->cDescPuesto,
                        $request->cResponsabilidades,
                        $request->cRequisitos,
                        //$request->dCreacion,
                        //$request->dInicio,   
                        //$request->dFin,
                        date('Y-m-d'),
                        date('Y-m-d'),
                        date('Y-m-d'),
                        
                        $request->nSalarioMin, 
                        $request->nSalarioMax,
                        $request->bAConvenir,
                        $request->bNotificarCorreo,

                        $request->cCorreoNotifica,
                        $request->bDiscapacitado,
                        $request->bOtraEmpresa,
                        $request->cEmpresaMostrar ?? 'SI',
                        $request->cLogoMostrar ?? 'SI',
                        $request->cUbicacion,   
                        $request->iEstadoPublicacion,
                        

                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;

            case 'CONSULTAR':
                
                $data = \DB::select ('exec [bseg].[Sp_BSEG_CRUD_aviso] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '', 
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '400.00',
                        '500.00',
                        '', 
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        
                        auth()->user()->cCredUsuario,
                        gethostname(),
                        '',
                        'mac'
                        
            
                        ));
                        return response()->json( $data);
            break;
           default:break;
        }
       
    }
   
}
