<?php

namespace App\Http\Controllers\DBU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Generales\GrlPersonasController;
use Illuminate\Support\Facades\Response;
use DateTime;
use App\Http\Controllers\PideController;

class SaludController extends Controller
{
    //HOLA
    public function Presentacion (Request $request){
        switch( $request->opcion){
            case 'BUSCAR':
                $data = \DB::select ('exec dbu.[Sp_DBU_SALUD_CRUD_presentacion] ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                array(  $request->opcion,                                                                                                              
                        $request->busqueda,
                        '-',
                        'user',
                        '2020-01-30 00:00:00.000',
                        '-',
                        '-',
                        '-',
                        '-'));
            break;
            case 'CONSULTARP':
                //$data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_presentacion] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array($request->opcion,'-','-','0','0','1900-01-01 00:00:00.000','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'-','0'));
            break;
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_presentacion] ?, ?, ?, ?, ?, ?, ?, ?, ?', 
                            array(  $request->opcion,    
                                    '-',                                                                                  
                                    $request->nombre,
                                    'user',
                                    '2020-01-30 00:00:00.000',
                                    '-',
                                    '-',
                                    '-',
                                    '-')
                );
            break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_presentacion] ?,?,?,?,?,?,?,?,?', array(
                    $request->opcion, $request->busqueda, $request->nombre,'user_login','2020-01-01 00:00:00.000',gethostname(),$request->getClientIp(),'Usuario_pc','00:00:00:00:00:00:00:00'
                ));
            break;
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_presentacion] ?,?,?,?,?,?,?,?,?', array(
                    $request->opcion, $request->busqueda, $request->nombre,'user_login','2020-01-01 00:00:00.000',gethostname(),$request->getClientIp(),'Usuario_pc','00:00:00:00:00:00:00:00'
                ));
            break;
        }
        return response()->json( $data );
    }

    
    













//------------------------------------------------------------------------------------------------------------------------------

    public function Medicamento(Request $request){
        switch( $request->opcion){

            case 'BUSCAR_NOM_MED_SIAF':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', array(
                    $request->opcion, 
                    $request->busqueda,
                    $request->medic_cod,
                    $request->medic_nom,
                    $request->medic_id_presentacion,
                    $request->medic_fecha_expiracion,
                    $request->medic_cant,
                    $request->medic_cant_max,
                    $request->medic_cant_min,
                    $request->medic_dosis,
                    $request->medic_indica,
                    $request->medic_estado,
                    'user',
                    '1900-01-01 00:00:00.000'
                    ,gethostname()
                    ,$request->getClientIp() ,
                    '-',
                    $request->server->get('REMOTE_ADDR'),
                    '0'
                ));
            break;
            case 'CONSULTAR':
                    $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', 
                    array(  $request->opcion,
                            $request->busqueda,
                            $request->medic_cod,
                            $request->medic_nom,
                            $request->medic_id_presentacion,
                            $request->medic_fecha_expiracion,
                            $request->medic_cant,
                            $request->medic_cant_max,
                            $request->medic_cant_min,
                            $request->medic_dosis,
                            $request->medic_indica,
                            $request->medic_estado,
                            'user',
                            '1900-01-01 00:00:00.000',
                            gethostname(),
                            $request->getClientIp(),
                            '-',
                            $request->server->get('REMOTE_ADDR'),
                            $request->iPersId
                        ));
            break;
            case 'CONSULTARP':
                    $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', 
                    array(  $request->opcion,
                            $request->busqueda,
                            $request->medic_cod,
                            $request->medic_nom,
                            $request->medic_id_presentacion,
                            $request->medic_fecha_expiracion,
                            $request->medic_cant,
                            $request->medic_cant_max,
                            $request->medic_cant_min,
                            $request->medic_dosis,
                            $request->medic_indica,
                            $request->medic_estado,
                            'user',
                            '1900-01-01 00:00:00.000'
                            ,gethostname(),
                            $request->getClientIp(),
                            '-',
                            $request->server->get('REMOTE_ADDR')
                            ,'0'
                        ));
            break;
            case 'BUSCAR_NOM_MEDICAM_SIAF':
                    $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', 
                    array(  $request->opcion,
                            $request->busqueda,
                            $request->medic_cod,
                            $request->medic_nom,
                            $request->medic_id_presentacion,
                            $request->medic_fecha_expiracion,
                            $request->medic_cant,
                            $request->medic_cant_max,
                            $request->medic_cant_min,
                            $request->medic_dosis,
                            $request->medic_indica,
                            $request->medic_estado,
                            'user',
                            '1900-01-01 00:00:00.000',
                            gethostname(),
                            $request->getClientIp(),
                            '-',
                            $request->server->get('REMOTE_ADDR'),
                            $request->iPersId
                        ));
            break;
            case 'GUARDAR':
                    $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', 
                    array(  $request->opcion,
                            $request->busqueda,
                            $request->medic_cod,
                            $request->medic_nom ,
                            $request->medic_id_presentacion,
                            $request->medic_fecha_expiracion,
                            $request->medic_cant,
                            $request->medic_cant_max,
                            $request->medic_cant_min,
                            $request->medic_dosis,
                            $request->medic_indica,
                            $request->medic_estado,
                            'user',
                            '1900-01-01 00:00:00.000',
                            gethostname(),
                            $request->getClientIp(),
                            '1',
                            $request->server->get('REMOTE_ADDR'),
                            $request->filial
                        ));
            break;
            case 'ELIMINAR':
                     $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', 
                    array(  $request->opcion,
                            $request->busqueda,
                            $request->medic_cod,
                            $request->medic_nom ,
                            $request->medic_id_presentacion,
                            $request->medic_fecha_expiracion,
                            $request->medic_cant,
                            $request->medic_cant_max,
                            $request->medic_cant_min,
                            $request->medic_dosis,
                            $request->medic_indica,
                            $request->medic_estado,
                            'user',
                            '1900-01-01 00:00:00.000',
                            gethostname(),
                            $request->getClientIp(),
                            '1',
                            $request->server->get('REMOTE_ADDR'),
                            $request->iPersId
                        ));
            break;
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICAMENTO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', 
                    array(  $request->opcion,
                            $request->medic_id_medicamento,
                            $request->medic_cod,
                            $request->medic_nom,
                            $request->medic_id_presentacion,
                            $request->medic_fecha_expiracion,
                            $request->medic_cant,
                            $request->medic_cant_max,
                            $request->medic_cant_min,
                            $request->medic_dosis,
                            $request->medic_indica,
                            $request->medic_estado,
                            'user',
                            '1900-01-01 00:00:00.000',
                            gethostname(),
                            $request->getClientIp(),
                            '-',
                            $request->server->get('REMOTE_ADDR'),
                            $request->filial
                        ));
            break;
        }
        return response()->json( $data );
    }


    public function Enfermedad(Request $request){
        switch( $request->opcion){
            case 'CONSULTAR':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ENFERMEDAD] ?, ?, ?, ?', array($request->opcion,$request->busqueda,'-','0'));
            break;
            case 'AGREGAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ENFERMEDAD] ?, ?, ?, ?', array($request->opcion,'-',$request->nombre,'0'));
            break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ENFERMEDAD] ?, ?, ?, ?', array($request->opcion,'-','-',$request->id));
            break;
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ENFERMEDAD] ?, ?, ?, ?', array($request->opcion,'-',$request->nombre,$request->id));
            break;
        }
        return response()->json( $data );
    }

    public function CitasProgramadas(Request $request){
        switch($request->opcion){

            case 'CONSULTAR_FECHA_CITA':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CITAS_PROGRAMADAS] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?', array($request->opcion,$request->fecha,$request->id,'0','0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0','0'));
            break;

           
            case 'GUARDAR_CITA_PROGRAMADA':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITAS_PROGRAMADAS] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?', array($request->opcion,$request->fecha,'0',$request->horario,'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),$request->iPersId,$request->iDetalle));
            break;

            case 'CONSULTAR_CITAS_PROGRAMADAS':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CITAS_PROGRAMADAS] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?', array($request->opcion,$request->busqueda,'0','0',$request->filial,'0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0','0'));
            break;
            
            case 'ELIMINAR_CITA':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITAS_PROGRAMADAS] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?', array($request->opcion,$request->id,'0','0','0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0','0'));
            break;
            case 'ACTUALIZAR_CITA':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITAS_PROGRAMADAS] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?', array($request->opcion,$request->fecha,$request->id,$request->horario,'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0','0'));
            break;

            
            /*
            case 'CONSULTAR_ODON_ESTADO_SI':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array($request->opcion,$request->busqueda,'','','','',''));
            break;
            case 'CONSULTARP':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array($request->opcion,$request->busqueda,'0','-','-','0','0001-01-01'));
            break;
            case 'AGREGAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array($request->opcion,'-',$request->medic_id_present,$request->medic_cod,$request->medic_nom , $request->medic_cant,$request->medic_fecha_exp));
            break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array($request->opcion,$request->busqueda,'0','-','-','0','0001-01-01'));
            break;
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array($request->opcion,'-',$request->medic_id_present,$request->medic_cod,$request->medic_nom , $request->medic_cant,$request->medic_fecha_exp));
            break;*/
        }
        return response()->json( $data );
    }


   public function descargaHCPdf(){
        $pdf = \PDF::loadView('dbu.HistoriaClinicaPdf', compact(['']))->setPaper('A4');
        return $pdf->download("HistoriaClinicaPdf.pdf");
    }


    public function Medico(Request $request){
        switch( $request->opcion){
            case 'CONSULTAR_ESPECIALIDAD':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?,? ,?, ?', array($request->opcion,'-','0','0','0','-','-','-','-','-','-', '-', '-','-','0','-'));
            break;

            case 'CONSULTAR_CONSULTORIO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?', array($request->opcion,'-','0',$request->filial,'0','-','-','-','-','-','-', '-', '-','-','0','-'));
            break;

            case 'CONSULTAR_CONSULTORIO_ESTADO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?', array($request->opcion,$request->busqueda,'0','0','0','-','-','-','-','-','-', '-', '-','-','0','-'));
            break;

            case 'CONSULTAR':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?', array($request->opcion,$request->busqueda,'0','0','0','-','-','-','-','-','-', '-', '-','-','0','-'));
            break;
            case 'CONSULTAR_ACTIVIDAD':
                $data = \DB::table('dbu.salud_detalle_campana')
                ->join('dbu.salud_campana_medica', 'dbu.salud_detalle_campana.iCampanaId', '=', 'dbu.salud_campana_medica.iCampanaId')
                ->select('dbu.salud_detalle_campana.*', 'dbu.salud_campana_medica.*')
                ->where('iMedicoId', $request->busqueda)
                ->get();
            break;
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?', array($request->opcion,'-',$request->iPersId,$request->iFilId,$request->iEspecialidadId,'ACTIVO','user',gethostname(),$request->getClientIp() ,'1',$request->server->get('REMOTE_ADDR'),$request->user, $request->user,$request->colegiatura,$request->iTipoServicioId,$request->celular));
            break;
            case 'EDITAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?', array($request->opcion,$request->id,'0',$request->iFilId,$request->iEspecialidadId,$request->estado,'user',gethostname(),$request->getClientIp() ,$request->open,$request->server->get('REMOTE_ADDR'),'-','-',$request->colegiatura,$request->iTipoServicioId,$request->celular));
            break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_MEDICO] ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ? ,?, ?', array($request->opcion,$request->busqueda,'0','0','0','-','-','-','-','-','-', '-', '-','-','0','-'));
            break;
           
        }
        return response()->json( $data );
    }



    public function Paciente(Request $request){      
        switch( $request->opcion){
            case 'CONSULTAR_SERVICIO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRU_HISTORIA_CLINICA] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ', array($request->opcion,'-','0','-','-','-','-','-','-','-','-'));
            break;
            case 'CONSULTAR':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRU_HISTORIA_CLINICA] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ', array($request->opcion,$request->busqueda,'0','-','-','-','-','-','-','-','-'));
            break;
            case 'CONSULTAR_ESTADO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRU_HISTORIA_CLINICA] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ', array($request->opcion,$request->busqueda,'0','-','-','-','-','-','-','-','-'));
                
            break;
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRU_HISTORIA_CLINICA] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ', array($request->opcion,'-',$request->id,$request->name, $request->apellidos,$request->tel, 'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
            break;
            case 'GENERAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRU_HISTORIA_CLINICA] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ', array('VALIDAR_FICHA_ESTUDIANTE','-','0','-','-','-','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRU_HISTORIA_CLINICA] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,? ', array('VALIDAR_FICHA_DOCENTE','-','0','-','-','-','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
           
            break;
        }
        return response()->json( $data );
    }

    public function Test(Request $request){
        $data = \DB::select('exec dbu.[Sp_DBU_SALUD_R_TEST] ?', array($request->opcion));
        return response()->json( $data );
    }

    public function Cita(Request $request){   
      
        switch( $request->opcion){
           
            case 'CONSULTAR_TOPICO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;
           
            case 'GUARDAR':
                if($request->consultorio=='100'){
                    //NINGUNO
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('GUARDAR_TOPICO','-',$request->historia, $request->persona,$request->filial,$request->motivo,$request->temp,$request->fc,$request->pa,$request->spo,$request->peso,$request->fr,$request->talla,$request->imc,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                
                }
                else{
                   
                }
            break;
           
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array($request->opcion,$request->id,'0','0','0','-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
                 break;

            case 'ACTUALIZAR':
                if($request->consultorio=='100'){
                    //NINGUNO
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('ACTUALIZAR_TOPICO',$request->id,'0', '0','0',$request->motivo,$request->temp,$request->fc,$request->pa,$request->spo,$request->peso,$request->fr,$request->talla,$request->imc,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                
                }
                else{
                    $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array('GUARDAR_CONSULTORIO',$request->consultorio,'0',$request->id,'0','-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
                break;
                 
                }
            
             case 'GUARDAR_ATENCION_CITA':
                if($request->consultorio=='100'){
                    //NINGUNO
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', array($request->opcion,$request->id,$request->historia, '0','0',$request->motivo,$request->temp,$request->fc,$request->pa,$request->spo,$request->peso,$request->fr,$request->talla,$request->imc,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                
                }
                else{
                   
                }

            case 'CONSULTAR_ODONTOLOGIA_NO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;
            
            case 'CONSULTAR_ODONTOLOGIA_SI':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;

            case 'CONSULTAR_PSICOLOGIA_NO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;
            
            case 'CONSULTAR_PSICOLOGIA_SI':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;

            case 'CONSULTAR_MEDICINA_NO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;
            
            case 'CONSULTAR_MEDICINA_SI':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_ATENCION] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->busqueda,'0','0',$request->filial,'-','-','-','-','-','-','-','-','-','-','-','-','-','-'));
            break;

            

        }
        return response()->json( $data );
    }

    public function Resumen(Request $request){      
        switch( $request->opcion){
           
            case 'ATENCION':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_RESUMEN] ?,? ', array($request->opcion,'-'));
            break;

            case 'MEDICO_SUNEDU':
                $dni = $request->busqueda;
                $request = new \Illuminate\Http\Request();
                $request->replace(['dni' =>$dni]);
        
                $pc = new PideController();
                $response=$pc->consultar( $request, 'sunedu', null, true);
                $data = $response['data']->titulos;
              //  $data = \DB::select('exec dbu.[Sp_DBU_SALUD_RESUMEN] ?,? ', array($request->opcion,'-'));
            break;

            case 'MEDICO_DATOS':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_RESUMEN] ?,? ', array($request->opcion,$request->busqueda));
                if(isset($data[0])){
                    $gpc = new GrlPersonasController();
    
                    $request = new \Illuminate\Http\Request();
                    $request->replace(['code' => $data[0]->iPersId]);
    
                    $data[0]->cPersFotografia = $gpc->getFotoReniec($request, true);
                    }
                
              //  $data = \DB::select('exec dbu.[Sp_DBU_SALUD_RESUMEN] ?,? ', array($request->opcion,'-'));
            break;

            case 'PERSONA':
                $data = \DB::table('grl.personas')
                ->leftJoin('grl.persona_tipo_contactos', 'grl.personas.iPersId', '=', 'grl.persona_tipo_contactos.iPersId')
                ->join('ura.estudiantes', 'grl.personas.iPersId', '=', 'ura.estudiantes.iPersId')
                ->join('grl.filiales', 'ura.estudiantes.iFilId', '=', 'grl.filiales.iFilId')
                ->join('ura.carreras', 'ura.estudiantes.iCarreraId', '=', 'ura.carreras.iCarreraId')
                ->select('grl.personas.*', 'grl.persona_tipo_contactos.*', 'ura.estudiantes.*','ura.carreras.*')
        
                ->where('cPersDocumento',$request->busqueda)
                ->where('iTipoConId',2)
                ->count();
                if($data==0){

                    $data = \DB::table('grl.personas')
                ->leftJoin('grl.persona_tipo_contactos', 'grl.personas.iPersId', '=', 'grl.persona_tipo_contactos.iPersId')
                ->join('ura.docentes', 'grl.personas.iPersId', '=', 'ura.docentes.iPersId')
                ->join('ura.carreras', 'ura.docentes.iCarreraIdAdscito', '=', 'ura.carreras.iCarreraId')
                ->select('grl.personas.*', 'grl.persona_tipo_contactos.*', 'ura.docentes.*','ura.carreras.*')
        
                ->where('cPersDocumento',$request->busqueda)
                ->where('iTipoConId',2)
                ->count();

                    if($data == 0){
                        $data = \DB::table('grl.personas')
                        ->join('grl.persona_tipo_contactos', 'grl.personas.iPersId', '=', 'grl.persona_tipo_contactos.iPersId')
                      
                        ->select('grl.personas.*', 'grl.persona_tipo_contactos.*')
                
                        ->where('cPersDocumento',$request->busqueda)
                        ->where('iTipoConId',2)
                        ->get();
                    }
                    else {
                        $data = \DB::table('grl.personas')
                ->leftJoin('grl.persona_tipo_contactos', 'grl.personas.iPersId', '=', 'grl.persona_tipo_contactos.iPersId')
                ->join('ura.docentes', 'grl.personas.iPersId', '=', 'ura.docentes.iPersId')
                ->join('ura.carreras', 'ura.docentes.iCarreraIdAdscito', '=', 'ura.carreras.iCarreraId')
                ->select('grl.personas.*', 'grl.persona_tipo_contactos.*', 'ura.docentes.*','ura.carreras.*')
        
                ->where('cPersDocumento',$request->busqueda)
                ->where('iTipoConId',2)
                ->get();
                    }
                
            
            
            }

                else{
                    $data = \DB::table('grl.personas')
                ->join('grl.persona_tipo_contactos', 'grl.personas.iPersId', '=', 'grl.persona_tipo_contactos.iPersId')
                ->join('ura.estudiantes', 'grl.personas.iPersId', '=', 'ura.estudiantes.iPersId')
                ->join('grl.filiales', 'ura.estudiantes.iFilId', '=', 'grl.filiales.iFilId')
                ->join('ura.carreras', 'ura.estudiantes.iCarreraId', '=', 'ura.carreras.iCarreraId')
                ->select('grl.personas.*', 'grl.persona_tipo_contactos.*', 'ura.estudiantes.*','ura.carreras.*')
        
                ->where('cPersDocumento',$request->busqueda)
                ->where('iTipoConId',2)
                ->get();
                }

                
                if(isset($data[0])){
                $gpc = new GrlPersonasController();

                $request = new \Illuminate\Http\Request();
                $request->replace(['code' => $data[0]->iPersId]);

                $data[0]->fotoReniec = $gpc->getFotoReniec($request, true);
                }
            break;
           
            case 'CONSULTAR_FILIAL':
                $data = \DB::table('grl.filiales')->get();
            break;
            
        }
        return response()->json( $data );
    }

    public function descargaPdfFecha($fecha,$opcion){
        switch( $opcion){
           
            case 'ATENCIONPDF':
                $atencion = \DB::select('exec dbu.[Sp_DBU_SALUD_RESUMEN] ?, ?', array($opcion,$fecha));
                $pdf = \PDF::loadView('dbu.AtencionPdf', compact(['atencion','fecha']))->setPaper('A4','landscape');
                return $pdf->download($fecha."AtencionPdf.pdf");
            break;
           
           
        }
    }

    public function Odontologo(Request $request){      
        switch( $request->opcion){
           
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ODONTOGRAMA] ?,?,?,?,?,?,?,?,? ', array($request->opcion,'-',$request->idpersona,$request->idanamnesis,$request->diagnostico,$request->observacion,'0','0001-01-01',$request->idhistoria));
                $obtenerID=\DB::table('dbu.salud_odontograma')->where('id_persona','=',$request->idpersona)->get();

                foreach ($obtenerID as $key => $odo) {
                        $id=$odo->id_odontograma;
                }
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ODONTOGRAMA_DETALLE] ?,?,?,?,? ', array($request->opcion,"-",$id,$request->nd,"-"));
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array('ACTUALIZAR',$request->id,'0','0','0001-01-01','00: 00: 00.0000000','-'));
            break;
            
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ? ', array($request->opcion,$request->id,0,0,$request->fecha,'-','-'));
            break;

            case 'ACTUALIZAR_ATENCION':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ODONTOGRAMA] ?,?,?,?,?,?,?,?,? ', array($request->opcion,$request->idodontograma,'0','0',$request->diagnostico,$request->observacion,'0','0001-01-01','0'));
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_ODONTOGRAMA_DETALLE] ?,?,?,?,? ', array($request->opcion,"-",$request->idodontograma,$request->nd,"-"));
              
            break;
           
            
        }
        return response()->json( $data );
    }


    public function Psicologo(Request $request){      
        switch( $request->opcion){
           
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_PSICOLOGIA] ?,?,?,?,?,?,?,?,?,?,?,?,?,? ', array($request->opcion,'-',$request->iPersId,$request->iHisCli,$request->test,$request->puntaje,date("Y-m-d"),'-','usuario','equipo', $request->server->get('REMOTE_ADDR'),'1','mac', $request->id));
                $obtenerID=\DB::table('dbu.salud_psicologia')
                ->where('iPersId', $request->iPersId)
                ->where('iHisCliId', $request->iHisCli)
                ->where('iPuntaje', $request->puntaje)
                ->get();

                foreach ($obtenerID as $key => $odo) {
                        $id=$odo->iPsicoId;
                }
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_PSICOLOGIA_DETALLE] ?,?,?,? ', array($request->opcion,"-",$id,$request->pregunta));
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CITA] ?, ?, ?, ?, ?, ?, ?', array('ACTUALIZAR',$request->id,'0','0','0001-01-01','00: 00: 00.0000000','-'));
            break;
            
            case 'CONSULTAR_TEST':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_PSICOLOGIA] ?,?,?,?,?,?,?,?,?,?,?,?,?,? ', array($request->text,date("Y-m-d"),'0','0','-','0',date("Y-m-d"),'-','usuario','equipo', $request->server->get('REMOTE_ADDR'),'1','mac', $request->id));
               
            break;
           
           
            
        }
        return response()->json( $data );
    }

    public function RecetaMedica(Request $request){
        switch ( $request->opcion ) {
            case 'CONSULTA':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_RECETAMEDICA] ?,?,?,?,?,? ',array($request->opcion,$request->id,'-','-','-','-'));
                break;
            case 'AGREGAR':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_RECETAMEDICA] ?,?,?,?,?,? ',array($request->opcion,'-',$request->dosis,$request->dias,$request->atencion,$request->medicamento));
                break;
            case 'ELIMINAR':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_RECETAMEDICA] ?,?,?,?,?,? ',array($request->opcion,$request->id,'-','-','-','-'));
                break;
            case 'ACTUALIZAR':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_RECETAMEDICA] ?,?,?,?,?,? ',array($request->opcion,$request->id,$request->dosis,$request->dias,$request->atencion,$request->medicamento));
                break;
        }

        return response()->json( $data );
    }

    public function MedicamentoPorNombre(Request $request){
        $term = "%".$request->cod_medicamento."%";
        $data = \DB::select('SELECT * FROM [dbu].[salud_medicamento] where cod_medicamento like ?',array($term));
        return response()->json( $data );
    }

    public function Consultorio(Request $request){
       
        $buscar = \DB::table('dbu.salud_tipo_servicio')
                ->where('iFilId',  $request->iFilId)
                ->get();
        return response()->json( $data );
    }

    public function mantenimiento_consultorio(Request $request){
        switch ( $request->opcion ) {
           
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CONSULTORIO] ?,?,?,?,?,?,?,?,?,? ',array($request->opcion,'-',$request->descripcion,$request->filial,'ACTIVO','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                break;
            case 'CAMBIAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CONSULTORIO] ?,?,?,?,?,?,?,?,?,? ',array($request->opcion,$request->text,'-','0','-','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                break;
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CONSULTORIO] ?,?,?,?,?,?,?,?,?,? ',array($request->opcion,$request->text,$request->descripcion,$request->filial,'-','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CONSULTORIO] ?,?,?,?,?,?,?,?,?,? ',array($request->opcion,$request->text,'-','0','-','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR')));
                break;
        }

        return response()->json( $data );
    }

    public function horario(Request $request){
        switch ( $request->opcion ) {
           
            case 'SEMANA':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ',array($request->opcion,'-','00:00:00' ,'00:00:00' ,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),date("Y-m-d"),'-','0','-','-','0','-','-'));
                break;

          case 'CONSULTAR_HORARIO':
                $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ',array($request->opcion,$request->text,'00:00:00' ,'00:00:00' ,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),$request->fecha,'-',$request->medico_servicio,'-','-','0','-','-'));
                break;

           
           
        }

        return response()->json( $data );
    }


    public function horario_medico(Request $request){
        
        switch ( $request->opcion ) {
           
            case 'GUARDAR_HORARIO':
                //$data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ? ',array($request->opcion));
                //$request->all()
                //$data = $request->horario;
                //
                //return response()->json( $request->horario );
                foreach($request->horario as $index=>$hora){
                   
                    for ($i=1;$i<=5;$i++)
                    
                            {   $a = 'f'.$i;
                                if($hora['dia'.$i]==1){
                                   
                                     $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($request->opcion,'-',$hora['in'].':00' ,$hora['fi'].':00' ,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),$request->$a,$hora['estado'.$i],$request->medico_servicio,$request->hora_inicio.':00',$request->hora_final.':00',$request->intervalo,$request->ubicacion,$request->descripcion));
                                     //return response()->json( $data);
                                                    }
                    
                            }

                }
            break;   
          case 'ELIMINAR_HORA':
           // return response()->json( $request->text );
           $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',array($request->opcion,$request->text, '00:00:00' ,'00:00:00' ,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),date("Y-m-d"),'-','0','-','-','0','-','-'));
           
          break;
           
           
        }

      return response()->json( $data );
    }


    public function campana(Request $request){
        
        switch ( $request->opcion ) {
           
            case 'GUARDAR_CAMPANA':
                //$data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ? ',array($request->opcion));
                //$request->all()
                //$data = $request->horario;
                
               
                foreach($request->doctor as $index=>$doc){
 

                   $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array(
                       $request->opcion,
                       '-',
                       $request->nombre,
                       $request->descripcion,
                       $request->fecha,
                       $request->fil,
                       $request->imagen,
                       'user',
                       gethostname(),
                       $request->getClientIp() ,
                       '-',
                       $request->server->get('REMOTE_ADDR'),
                       $doc
                    )); 
                }
               
            break;

            case 'CONSULTAR_CAMPANA':
                //$data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ? ',array($request->opcion));
                //$request->all()
                //$data = $request->horario;
                //
               
                    if(isset($request->desde))
                    {
                        $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array('DESDE',$request->desde,'-','-',date('Y-m-d'),'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0'));
                
                    }
                    
                    if(isset($request->hasta))
                    {
                        $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array('HASTA',$request->hasta,'-','-',date('Y-m-d'),'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0'));
                
                    }

                    if(isset($request->desde) && isset($request->hasta))
                    {
                        $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array('DESDE_HASTA',$request->desde,$request->hasta,'-',date('Y-m-d'),'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0'));
                
                    }
                    if(!isset($request->desde) && !isset($request->hasta))
                    {
                        $data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array($request->opcion,'-','-','-',date('Y-m-d'),'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0'));
                
                    }
                   
                
               
            break;

            case 'BUSCAR_MEDICO_CAMPANA':
                $data = \DB::table('dbu.salud_detalle_campana')
                ->where('iCampanaId', $request->id)
                ->get();
               
            break;

            case 'ACTUALIZAR_CAMPANA':
                //$data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ? ',array($request->opcion));
                //$request->all()
                //$data = $request->horario;
                //
                $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array('ACTUALIZAR_DETALLE',$request->id,$request->nombre,$request->descripcion,$request->fecha,$request->fil,$request->imagen,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0'));
                
                foreach($request->doctor as $index=>$doc){
                   $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array($request->opcion,$request->id,$request->nombre,$request->descripcion,$request->fecha,$request->fil,$request->imagen,'user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),$doc));
                
                }
               
            break;

            case 'ELIMINAR_CAMPANA':
                //$data = \DB::select('exec dbu.[Sp_DBU_SALUD_CRUD_HORARIO] ? ',array($request->opcion));
                //$request->all()
                //$data = $request->horario;
                //
               
         
                   $data = \DB::update('exec dbu.[Sp_DBU_SALUD_CRUD_CAMPANA] ?,?,?,?,?,?,?,?,?,?,?,?,?',array($request->opcion,$request->id,'-','-',date('Y-m-d'),'0','0','user',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),'0'));
                
                
               
            break;
            
            default:break;
           
           
        }

       return response()->json( $data );
    }
    


    

}