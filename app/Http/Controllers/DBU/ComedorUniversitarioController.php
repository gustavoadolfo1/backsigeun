<?php

namespace App\Http\Controllers\DBU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Generales\GrlPersonasController;
use Illuminate\Support\Facades\Response;
use DateTime;
use App\Http\Controllers\PideController;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use PHPExcel;
use PHPExcel_Shared_Font;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class ComedorUniversitarioController extends Controller

{
    /**
     * Todos los Procedimientos relacionados a la gestión de la Ficha Socioeconómica
     * 
     * Mod: DBU - Ficha Socioeconómica
     */
    public function buscarEstudiantesT(Request $request)
    {
        $data = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA]  ?, ?', array('CONSULTAR',$request->busqueda));
       
       // $data = \DB::select('exec dbu.[Sp_DBU_SEL_estudiantesPaginadoXcBusquedaXsSortDirXpageNumberXpageSize] ?, ?, ?, ?', array($request->busqueda,$request->carrera,$request->filial,$request->semestre, $request->orden ?? 'asc', $request->pagina ?? 1, $request->nRegistros ?? 10));
       switch ($request->opcion) {
        case 'buscar':
       
        return response()->json( $data );
        break;
        
        default:break;

    }


    }

    public function obtenerDatosEstudiante($codigo)    {
        $estudiante = \DB::select('exec [dbu].[Sp_DBU_SEL_info_basica_estudiante_x_coduniv] ?', array($codigo));
        $gpc = new GrlPersonasController();
        $request = new \Illuminate\Http\Request();
        $request->replace(['code' => $estudiante[0]->iPersId]);
        $estudiante[0]->fotoReniec = $gpc->getFotoReniec($request, true);
        return response()->json($estudiante[0]);
    }

    public function agregarBecario(Request $request) {
        switch ($request->opcion) {
            case 'AGREGAR':
                $parametros = [
                    $request->estudiante['iPersId'],
                    $request->estudiante['cEstudCodUniv'],
                    //$request->iControlCicloAcad,
                    $request->estudiante['iCarreraId'],
                    $request->estudiante['iFilId'],
                    $request->estudiante['cFilial'],
                    //$request->iCurricId,
                    1,
                    //$request->iTipBecId,
                    1,
                    $request->iControlCicloAcad,
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    '1',
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                ];

                $biometro = [
                    $request->estudiante['cEstudCodUniv'],
                    $request->estudiante['cEstudCodUniv'],
                    null,
                    null,
                    null,
                    $request->estudiante['cEstudCodUniv'] . '@gmail.com',
                    null,
                    null,
                    $request->estudiante['cEstudCodUniv'],
                    //$request->estudiante['cEstudCodUniv'],
                    //md5('A'.($request->estudiante['cEstudCodUniv']/100).'B'),
                    'S3iuW7Xnhx6cgznByK36FxE9npFfcn+qqH8UHNvFJrs=',
                    '2001-01-01 00:00:00.000',
                    '2031-01-01 00:00:00.000',
                    0,
                    null,
                    null,
                    null,
                    'N',
                    null,
                    null,
                    'N',
                    null,
                    null,
                    1,
                    'N',
                    '10',
                    null,
                    4,
                    date('Y-m-d'),
                    0,
                    0,
                    null,
                    date('Y-m-d'),
                    null,
                    null,
                    null,
                    //md5('A'.($request->estudiante['cEstudCodUniv']/100).'B'),
                    'S3iuW7Xnhx6cgznByK36FxE9npFfcn+qqH8UHNvFJrs=',
                    null,
                    null,
                    '',
                    ''
                ];
                try {
                    $data = \DB::select('EXEC [dbu].Sp_DBU_INS_UPD_Becarios ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                    if ($data[0]->iBecaId > 0) {
                        $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                        $codeResponse = 200;
                        $dataT = \DB::select('EXEC [dbu].[Sp_DBU_INS_UPD_t_usr] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $biometro);
                    } else {
                        $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información.'];
                        $codeResponse = 500;
                    }
                } catch (\Exception $e) {
                    $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
                    $codeResponse = 500;
                }
                break;
        }
        return response()->json($response, $codeResponse);
    }
    public function buscarPostulante(Request $request){
        $data = \DB::select('exec [dbu].[Sp_DBU_COMEDOR_CRUD_POSTULANTES]  ?, ?, ?', array('CONSULTAR',$request->carrera,$request->filial));
       
        return response()->json( $data );
    }

    public function buscar(Request $request){
        if (($request->carrera) !== 0) {
            $carrera = explode('-', $request->carrera);
            $parametros = [
                $carrera[1],
                $carrera[0],
                $request->semestre,
            ];
        }

        switch ($request->opcionBuscar) {
            case 'BuscarBecario':
                $data = \DB::select('EXEC [dbu].[Sp_DBU_SEL_becario_x_carrera_x_filial_x_cicloacademico] ?,?,?', $parametros);
                break;
                case 'BuscarPostulante':
                    $data = \DB::select('EXEC [dbu].[Sp_DBU_SEL_becario_x_carrera_x_filial_x_cicloacademico] ?,?,?', $parametros);
                    break;
            case 'BuscarBecarioM':
                $data = \DB::select('EXEC [dbu].[Sp_DBU_SEL_becario_x_carrera_x_filial_x_cicloacademico] ?,?,?', $parametros);
                break;
            case 'BuscarDocente':
                $data = \DB::select('EXEC [dbu].Sp_DBU_SEL_docente_x_carrera_x_filial_x_cicloacademico ?,?,?', $parametros);
                break;
            case 'BuscarBecarioModal':
                $data = \DB::select('EXEC [dbu].[Sp_DBU_SEL_becario_x_cicloacademico] ' . $request->semestre);
                break;
            case 'buscarHistorialEstudiante':
                $data = \DB::select('', $parametros);
                break;
        }
        return response()->json($data);
    }

    public function ImprimirFichaE(){
        $pdf = \PDF::loadView('dbu.PdfFichaEvaluacion', compact(['']))->setPaper('A4');
        return $pdf->download("PdfFichaEvaluacion_.pdf");
    }

    public function obtenerDatosFichasVI(){
        $MV = \DB::table('dbu.aspectos_visita_Motivo')->get();
        $OV = \DB::table('dbu.aspectos_visita_objetivos')->get();
        $VE = \DB::table('dbu.tipo_evaluacion_beca_detalle')->where('iTipEvaBecId',  1)->get();
        $DE = \DB::table('dbu.tipo_evaluacion_beca_detalle')->where('iTipEvaBecId',  2)->get();
        $RS = \DB::table('dbu.tipo_evaluacion_beca_detalle')->where('iTipEvaBecId',  3)->get();
        $CO = \DB::table('dbu.tipo_evaluacion_beca_detalle')->where('iTipEvaBecId',  4)->get();
        $RES = \DB::table('dbu.tipo_evaluacion_beca_detalle')->where('iTipEvaBecId', 5)->get();

        $CA = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  6)
            ->get();

        $NH = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  7)
            ->get();

        $OC = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  8)
            ->get();

        $IN = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  9)
            ->get();

        $LU = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  10)
            ->get();

        $TE = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  11)
            ->get();

        $MA = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId', 12)
            ->get();

        $SV = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  13)
            ->get();

        $EQ = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  14)
            ->get();

        $CON = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  15)
            ->get();

        $EST = \DB::table('dbu.tipo_evaluacion_beca_detalle')
            ->where('iTipEvaBecId',  16)
            ->get();

        return Response::json([
            'MV' => $MV, 'OV' => $OV, 'vivienda_estudiante' => $VE, 'dependencia_economica' => $DE, 'riesgo_social' => $RS,
            "convivencia" => $CO,
            "responsabilidad" => $RES,
            "carga" => $CA,
            "nro_hijos" => $NH,
            "ocupacion" => $OC,
            "ingreso" => $IN,
            "lugar" => $LU,
            "tenencia" => $TE,
            "material" => $MA,
            "servicios_v" => $SV,
            "equipamiento" => $EQ,
            "condiciones" => $CON,
            "estado" => $EST
        ]);
    }

    public function obtenerTipoServicios(){
        $serv = \DB::table('dbu.tipo_servicio')->get();
        return Response::json(['serv' => $serv]);
    }

    public function buscarEstudiantesSinBeca(Request $request){
        $data = \DB::select('exec dbu.[Sp_DBU_SEL_estudiantesSinBecaPaginadoXcBusquedaXsSortDirXpageNumberXpageSize] ?, ?, ?, ?', array($request->busqueda, $request->orden ?? 'asc', $request->pagina ?? 1, $request->nRegistros ?? 10));
        return response()->json($data);
    }

    public function obtenerListaAsistenciaFecha($fecha){
        $data = \DB::select(' exec [dbu].[Sp_DBU_INS_UPD_lista_asistencia_becarios_fecha] ?, ?', array($fecha, $fecha));
        return response()->json($data);
        //echo $fecha.' 00:00:00';
    }

    public function obtenerListaAsistenciaFechaBuscar($fecha,$texto){
        $data = \DB::select(' exec [dbu].[Sp_DBU_INS_UPD_lista_asistencia_becarios_fecha_texto] ?, ?', array($fecha, $texto));
        return response()->json($data);
        //echo $fecha.' 00:00:00';
    }

    public function getSemanasComedor(Request $request){
        $data = \DB::select(' exec [dbu].[Sp_DBU_COMEDOR_get_semanas] ?, ?', array($request->opcion, $request->busqueda));
        return response()->json($data);
        //echo $fecha.' 00:00:00';
    }

    public function getMenu(Request $request){
        $data = \DB::select(' exec [dbu].[Sp_DBU_COMEDOR_get_Menu] ?, ?, ?', array($request->opcion, $request->semana, $request->dia));
        return response()->json($data);
        //echo $fecha.' 00:00:00';
    }



    public function crudMenuComedor(Request $request){
        switch( $request->opcion){
            case 'CONSULTAR':
                $data = \DB::select ('exec dbu.[Sp_DBU_COMUNICADOS_crud_menuComedor] ?,?,?,?,?,?,?,?,?,?,?,?,?', array(  
                        $request->opcion,
                        '',                                                                                                              
                        $request->semana,
                        'tipo',
                        'lunes',
                        'martes',
                        'miercoles',
                        'jueves',
                        'viernes',
                        'sabado',
                        'domingo',
                        '1',
                        '1'
                 ));
            break;
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_menuComedor] ?,?,?,?,?,?', array(  
                        $request->opcion,                                                                                                              
                        $request->txtBuscar,
                        '-',
                        '-',
                        '-',
                        '-'
                ));
            break;
            
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_menuComedor] ?,?,?,?,?,?', array(
                        $request->opcion,                                                                                                              
                        $request->txtBuscar,
                        '-',
                        '-',
                        '-',
                        '-'
                ));
            break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_menuComedor] ?,?,?,?,?,?', array(
                    $request->opcion,                                                                                                              
                        $request->txtBuscar,
                        '-',
                        '-',
                        '-',
                        '-'
                ));
            break;
            case 'ESTADO':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_menuComedor] ?,?,?,?,?,?,?', array(
                    $request->opcion,                                                                                                              
                    $request->txtBuscar,
                    '-',
                    '-',
                    '-',
                    '-'
                ));
            break;
        }
        return response()->json( $data );
    }





    public function getComunicadosDbu(Request $request){
        $data = \DB::select(' exec [dbu].[Sp_DBU_COMUNICADOS_crud_comunicados] ?,?', array($request->opcion,$request->txtBuscar));
        return response()->json($data);
        //echo $fecha.' 00:00:00';
    }

    public function crudComunicadoDbu (Request $request){
        switch( $request->opcion){
            case 'CONSULTAR':
                $data = \DB::select ('exec dbu.[Sp_DBU_COMUNICADOS_crud_comunicados] ?,?,?,?,?,?,?', array(  
                        $request->opcion,                                                                                                              
                        $request->txtBuscar,
                        '2020-02-02',
                        '2020-02-02',
                        'titulo',
                        'cuerpo',
                        0));
            break;
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_comunicados] ?,?,?,?,?,?,?', array(  
                        $request->opcion,                                                                                                              
                        $request->txtBuscar,
                        $request->fechai,
                        $request->fechaf,
                        $request->titulo,
                        $request->cuerpo,
                        0));
            break;
            
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_comunicados] ?,?,?,?,?,?,?', array(
                    $request->opcion,                                                                                                              
                    $request->txtBuscar,
                    $request->fechai,
                    $request->fechaf,
                    $request->titulo,
                    $request->cuerpo,
                    $request->estado));
            break;
            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_comunicados] ?,?,?,?,?,?,?', array(
                    $request->opcion,                                                                                                              
                    $request->txtBuscar,
                    '2020-02-02',
                    '2020-02-02',
                    'titulo',
                    'cuerpo',
                    0));
            break;
            case 'ESTADO':
                $data = \DB::update('exec dbu.[Sp_DBU_COMUNICADOS_crud_comunicados] ?,?,?,?,?,?,?', array(
                    $request->opcion,                                                                                                              
                    $request->txtBuscar,
                    '2020-02-02',
                    '2020-02-02',
                    'titulo',
                    'cuerpo',
                    $request->estado));
            break;
        }
        return response()->json( $data );
    }

    
    
    public function GuardarFichaEvaluacion(Request $request) {
        $dt = new DateTime();
        $parametros = [
            $request->iPersId,
            $request->cEstudCodUniv,
            $request->iCarreraId,
            $request->iFilId,
            $request->cFicEvaSocPuntaje,
            $request->cFicEvaSocCategoria,
            '1900-01-01 00:00:00',
            'PENDIENTE',
            0,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac',
            $request->iFicEvaSocDetViviendaEst,
            $request->iFicEvaSocDetEconomiaEst,
            $request->iFicEvaSocDetRiesgoEst,
            $request->iFicEvaSocDetConvivenciaFam,
            $request->iFicEvaSocDetResponsabilidadFam,
            $request->iFicEvaSocDetCargaFam,
            $request->iFicEvaSocDetNumHijosEstatalFam,
            $request->iFicEvaSocDetNumHijosPrivadoFam,
            $request->iFicEvaSocDetOcupacionFam,
            $request->iFicEvaSocDetIngresoFam,
            $request->iFicEvaSocDetResidenciaFam,
            $request->iFicEvaSocDetTeneciaFam,
            $request->iFicEvaSocDetConstruccionFam,
            $request->iFicEvaSocDetServiciosFam,
            $request->iFicEvaSocDetEquipamientoFam,
            $request->iFicEvaSocDetHabitaFam,
            $request->iFicEvaSocDetSaludFam,
            '1900-01-01 00:00:00',
            '-',
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        //return response()->json( $parametros );

        try {
            /* $data = \DB::select('SELECT * from grl.personas', $parametros);
                return response()->json( $data );*/
            $data = \DB::select('EXEC dbu.Sp_DBU_INS_UPD_cabecera_y_detalle_ficha_evaluacion_socioeconomica ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);


            if ($data[0]->iFicEvaSocId > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => $e, 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse, $parametros);
    }

    public function ValidarFichaEvaluacion(Request $request)
    {
        $parametros = [
            $request->iPersId,
            'REVISADO'
        ];

        try {
            $data = \DB::select('dbu.Sp_DBU_INS_UPD_revisar_ficha_evaluacion_socioeconomica ?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => $e, 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse, $parametros);
    }

    public function RegistrarVisitaDomiciliaria(Request $request)
    {
        $parametros = [
            $request->iPersId,
            $request->cEstudCodUniv,
            $request->iCarreraId,
            $request->iFilId,
            $request->iAspVisMotId,
            $request->iAspVisObjId,
            $request->iFicInfVisId,
            '1900-01-01 00:00:00',
            $request->cFicVisDomInforme,
            $request->cFicVisDomResultados,
            $request->cFicVisDomFamiliarConverso,
            $request->cFicVisDomFamiliarCel,
            $request->cFicVisDomSituacion,
            '-',
            $request->cFicEvaSocPuntaje,
            $request->cFicEvaSocCategoria,
            '1900-01-01 00:00:00',
            '-',
            0,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {

            $data = \DB::select('EXEC [dbu].[Sp_DBU_INS_UPD_ficha_visita_domiciliaria] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->iBecaId > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => $e, 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
    }

    public function LeerDatosFichaEvaluacion($iPersId)
    {
        $data = \DB::select('EXEC [dbu].[Sp_DBU_SEL_DatosFichaEvaluacion_x_iPersId] ?', array($iPersId));
        return response()->json($data);
    }

    public function controlVerificacion(Request $request)
    {
        //return $request->all();
        $cicloAcademico = \DB::select('exec ura.[Sp_GRAL_cicloAcademicoActivo]');
        //return response()->json( $cicloAcademico );
        
        foreach ($request->Sestudiante as $cCodUniv) {
            $data = \DB::select('exec [ura].[Sp_OBU_verificarRequisitosMatriculaXcEstudCodUniv] ?', array($cCodUniv['cEstudCodUniv']));
            $data[0]->seguro_proveedor = null;
            $data[0]->errorPIDE = null;
            $request = new \Illuminate\Http\Request();
            $request->replace(['dni' => $cCodUniv['cPersDocumento']]);

            $pc = new PideController();
            $response = $pc->consultar($request, 'seguro', null, true);



            if(isset($response['data']->tipo_seguro)){
            if(( $response['data']->tipo_seguro) == 'EsSalud')
            {
               
                $year = round((($response['data']->data->fi_vig) * 1) / 10000, 0);
                $mes =  round(((($response['data']->data->fi_vig) * 1) % 10000) / 100, 0);
                $dia = (((($response['data']->data->fi_vig) * 1) % 10000) % 100);
                $fecha = $year . '-' . $mes . '-' . $dia;
              
                $actualizar= \DB::table('ura.check_obu')
                ->where('cEstudCodUniv',  $cCodUniv['cEstudCodUniv'])
                ->update(array('dCheckObuVtoSeguro'=> $fecha,'dCheckObuVerifSeguro'=>date("Y-m-d"),'cCheckObuSeguro'=>'EsSalud'));
            
            }

            if(( $response['data']->tipo_seguro) == 'Sis')
            {
              
                if(($response['data']->data->fecCaducidad)!=null){
                    $year = round((($response['data']->data->fecCaducidad) * 1) / 10000, 0);
                $mes =  round(((($response['data']->data->fecCaducidad) * 1) % 10000) / 100, 0);
                $dia = (((($response['data']->data->fecCaducidad) * 1) % 10000) % 100);
                $fecha = $year . '-' . $mes . '-' . $dia;
                $actualizar= \DB::table('ura.check_obu')
                ->where('cEstudCodUniv',  $cCodUniv['cEstudCodUniv'])
                ->update(array('dCheckObuVtoSeguro'=> $fecha,'dCheckObuVerifSeguro'=>date("Y-m-d"),'cCheckObuSeguro'=>'SIS INDEPENDIENTE'));
                
               
                }
                else {
                    $month = date('m');
                $year = date('Y');
                $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                $fecha = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                $actualizar= \DB::table('ura.check_obu')
                ->where('cEstudCodUniv',  $cCodUniv['cEstudCodUniv'])
                ->update(array('dCheckObuVtoSeguro'=> $fecha,'dCheckObuVerifSeguro'=>date("Y-m-d"),'cCheckObuSeguro'=>'SIS'));
                }
            
               
            }

           
        }

      
        
            
          
        }

         

    }

    public function controlVerificacionDocente(Request $request){ 

       
       $cicloAcademico = \DB::select('exec ura.[Sp_GRAL_cicloAcademicoActivo]');


       foreach ($request->Sdocente as $cCodDoc) {
          
        $request = new \Illuminate\Http\Request();
        $request->replace(['dni' => $cCodDoc['DocumentoDocente']]);
        
        $pc = new PideController();
        $response=$pc->consultar( $request, 'seguro', null, true);
       // return response()->json($response);
        
        if(isset($response['data']->tipo_seguro)){
            if(( $response['data']->tipo_seguro) == 'EsSalud')
            {
               
                $year = round((($response['data']->data->fi_vig) * 1) / 10000, 0);
                $mes =  round(((($response['data']->data->fi_vig) * 1) % 10000) / 100, 0);
                $dia = (((($response['data']->data->fi_vig) * 1) % 10000) % 100);
                $fecha = $year . '-' . $mes . '-' . $dia;
               
               
               $buscar = \DB::table('ura.check_obu_docente')
                ->where('iDocenteDni',  $cCodDoc['DocumentoDocente'])
                ->count();
                /*
                if($buscar==0){
                    $insert = \DB::table('ura.check_obu_docente')
                    ->insert(
                        ['dCheckObuVerifSeguro' => date("Y-m-d"), 'cCheckObuSeguro' => 'EsSalud', 'dCheckObuVtoSeguro'=>$fecha,'iDocenteDni'=>$cCodDoc['DocumentoDocente'],'cCheckObucUsuarioSis'=>'user' ] );
                }
                */
             
                $actualizar= \DB::table('ura.check_obu')
                ->where('cEstudCodUniv',  $cCodUniv['cEstudCodUniv'])
                ->update(array('dCheckObuVtoSeguro'=> $fecha,'dCheckObuVerifSeguro'=>date("Y-m-d"),'cCheckObuSeguro'=>'EsSalud'));
            
            }

            if(( $response['data']->tipo_seguro) == 'Sis')
            {
              
                if(($response['data']->data->fecCaducidad)!=null){
                    $year = round((($response['data']->data->fecCaducidad) * 1) / 10000, 0);
                $mes =  round(((($response['data']->data->fecCaducidad) * 1) % 10000) / 100, 0);
                $dia = (((($response['data']->data->fecCaducidad) * 1) % 10000) % 100);
                $fecha = $year . '-' . $mes . '-' . $dia;

                $buscar = \DB::table('ura.check_obu_docente')
                ->where('iDocenteDni',  $cCodDoc['DocumentoDocente'])
                ->count();
                /*
                if($buscar==0){
                    $insert = \DB::table('ura.check_obu_docente')
                    ->insert(
                        ['dCheckObuVerifSeguro' => date("Y-m-d"), 'cCheckObuSeguro' => 'SIS INDEPENDIENTE', 'dCheckObuVtoSeguro'=>$fecha,'iDocenteDni'=>$cCodDoc['DocumentoDocente'],'cCheckObucUsuarioSis'=>'user' ] );
                }
               */
                $actualizar= \DB::table('ura.check_obu')
                ->where('cEstudCodUniv',  $cCodUniv['cEstudCodUniv'])
                ->update(array('dCheckObuVtoSeguro'=> $fecha,'dCheckObuVerifSeguro'=>date("Y-m-d"),'cCheckObuSeguro'=>'SIS INDEPENDIENTE'));
                
               
                }
                else {
                    $month = date('m');
                $year = date('Y');
                $day = date("d", mktime(0,0,0, $month+1, 0, $year));
                $fecha = date('Y-m-d', mktime(0,0,0, $month, $day, $year));
                $buscar = \DB::table('ura.check_obu_docente')
                ->where('iDocenteDni',  $cCodDoc['DocumentoDocente'])
                ->count();
                /*
                if($buscar==0){
                    $insert = \DB::table('ura.check_obu_docente')
                    ->insert(
                        ['dCheckObuVerifSeguro' => date("Y-m-d"), 'cCheckObuSeguro' => 'SIS', 'dCheckObuVtoSeguro'=>$fecha,'iDocenteDni'=>$cCodDoc['DocumentoDocente'],'cCheckObucUsuarioSis'=>'user' ] );
                }
                */
                $actualizar= \DB::table('ura.check_obu')
                ->where('cEstudCodUniv',  $cCodUniv['cEstudCodUniv'])
                ->update(array('dCheckObuVtoSeguro'=> $fecha,'dCheckObuVerifSeguro'=>date("Y-m-d"),'cCheckObuSeguro'=>'SIS'));
                
                }
            
               
            }

           
        }

       
        }

    }

    function descargaAsistenciaPdf ($fecha) {

        $asistencia  = \DB::select(' exec [dbu].[Sp_DBU_INS_UPD_lista_asistencia_becarios_fecha] ?, ?', array($fecha,$fecha));
        $pdf = \PDF::loadView('dbu.AsistenciaPdf', compact(['asistencia','fecha']));

        return $pdf->stream();
        
    }

    public function getreporte(Request $request){
        $parametros = [
            $request->iControlCicloAcad,
            $request->iCarreraId,
            $request->iFilialId
        ];

        $data = \DB::select('SELECT cSQLDatos FROM dbu.reportes WHERE iReporteId=?', array( $request->iReporteId ));
        $data2 = \DB::select('SELECT cSQLOpciones FROM dbu.reportes WHERE iReporteId=?', array( $request->iReporteId ));
        $data4 = \DB::select('SELECT iBooleano FROM dbu.reportes WHERE iReporteId=?', array( $request->iReporteId ));

        $data1 = \DB::select($data[0]->cSQLDatos,$parametros);
        $data3 = \DB::select($data2[0]->cSQLOpciones,[]);
        return response()->json( [$data1, $data3, $data4[0]] );
    }

    public function listareportes(){

        $data = \DB::select('SELECT iReporteId,cNombreReporte FROM dbu.reportes');
        return response()->json( $data );
    }

    public function Atenciones(Request $request){      
        switch( $request->opcion){
            
            case 'CONSULTAR':
                $data = \DB::select('exec dbu.[Sp_DBU_ASISTENCIA_SOCIAL_CRUD_ATENCIONES] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,?,?,?,? ', array($request->opcion,$request->busqueda,'0','-','-','-',$request->dependencia,'0','0','-','-','-','-',date("Y-m-d")));
            break;
          
            case 'GUARDAR':
                $data = \DB::update('exec dbu.[Sp_DBU_ASISTENCIA_SOCIAL_CRUD_ATENCIONES] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,?,?,?,? ', array($request->opcion,'-',$request->iPersId,$request->nombres,$request->apellidos,$request->asunto,$request->dependencia,$request->documento,$request->celular,gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),$request->fecha));
            break;
            case 'ACTUALIZAR':
                $data = \DB::update('exec dbu.[Sp_DBU_ASISTENCIA_SOCIAL_CRUD_ATENCIONES] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,?,?,?,? ', array($request->opcion,$request->busqueda,'0','-','-',$request->asunto,'0','0','0',gethostname(),$request->getClientIp() ,'-',$request->server->get('REMOTE_ADDR'),date("Y-m-d")));
            break;

            case 'ELIMINAR':
                $data = \DB::update('exec dbu.[Sp_DBU_ASISTENCIA_SOCIAL_CRUD_ATENCIONES] ?, ?, ?, ?, ?, ?, ?, ?, ?,? ,?,?,?,?', array($request->opcion,$request->busqueda,'0','-','-','-','0','0','0','-','-','-','-','1900-01-01 00:00:00.000'));
                 break;
        }
        return response()->json( $data );
    }

    public function Configuracion(Request $request){
        
      
        switch ( $request->opcion ) 
        {           
            case 'GUARDAR_CONFIG':
                $data = \DB::update('exec dbu.[Sp_DBU_COMEDOR_CRU_CONFIGURACION] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
                    'GUARDAR',
                    $request->ciclo_academico,
                    $request->fecha_inicio,
                    $request->fecha_final,
                    0,
                    0,
                    $request->HoraInicio,
                    $request->HoraFin,
                    $request->HoraCierre,
                    'user',
                    gethostname(),
                    $request->getClientIp() ,
                    '-',
                    $request->server->get('REMOTE_ADDR')
                  
                 ));

                 for($i=0;$i<count($request->filial);$i++){
                    $data = \DB::update('exec dbu.[Sp_DBU_COMEDOR_CRU_CONFIGURACION] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
                    'GUARDAR_CONFIG',
                    $request->ciclo_academico,
                    $request->fecha_inicio,
                    $request->fecha_final,
                    $request->filial[$i]['iFilId'],
                    $request->total[$i],
                    $request->HoraInicio,
                    $request->HoraFin,
                    $request->HoraCierre,
                    'user',
                    gethostname(),
                    $request->getClientIp() ,
                    '-',
                    $request->server->get('REMOTE_ADDR')
                      
                     ));
                 }

                    break;

                    case 'CONSULTAR_CONFIGURACION':
                        $data = \DB::select('exec dbu.[Sp_DBU_COMEDOR_CRU_CONFIGURACION] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
                            $request->opcion,
                            '0',
                            date('Y-m-d'),
                            date('Y-m-d'),
                            0,
                            0,
                            '00:00:00',
                            '00:00:00',
                            '00:00:00',
                            'user',
                            gethostname(),
                            $request->getClientIp() ,
                            '-',
                            $request->server->get('REMOTE_ADDR')
                          
                         ));
        
                    break;

                    case 'CONSULTAR_TOTAL':
                        $data = \DB::select('exec dbu.[Sp_DBU_COMEDOR_CRU_CONFIGURACION] ?,?,?,?,?,?,?,?,?,?,?,?,?,?',array(
                            $request->opcion,
                            '0',
                            date('Y-m-d'),
                            date('Y-m-d'),
                            $request->filial,
                            0,
                            '00:00:00',
                            '00:00:00',
                            '00:00:00',
                            'user',
                            gethostname(),
                            $request->getClientIp() ,
                            '-',
                            $request->server->get('REMOTE_ADDR')
                          
                         ));
        
                    break;
                    

                    default:
                    break;
                  
                 } 
                

           
                 return response()->json( $data );
  
    }
    public function Solicitud(Request $request){
       

        switch ( $request->opcion ) 
        {           
            case 'GUARDAR':
                $semestre = \DB::table('dbu.configuracion')
        ->where('iSemestreEstado', 1)
        ->get(); 
                //return $semestre;
                $data = \DB::update('exec [dbu].[Sp_DBU_COMEDOR_CRU_COMEDOR_EXPEDIENTE]   ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?',array(
                   
                    $request->opcion,
                    0,
                    $request->iEstudId,
                    $semestre[0]->iSemestreAcad,
                    $request->motivo,
                    $request->cParticipaTaller,
                    $request->bEsIchunya,
                    $request->bEstado,
                    
                    $request->foto,
                    $request->archivo,
                    $request->cDetArch,
                    date('Y-m-d'),

                    'user',
                    gethostname(),
                    $request->getClientIp() ,
                    '-',
                    $request->server->get('REMOTE_ADDR')
                  
                 ));

            break;
           
            
            default:break;
            
        }

        return response()->json( $data );
    }

    function SolicitudBuscar ($iEstudId){
        $verEstado =  \DB::table('dbu.comedor_expediente')->where('iEstudId',  $iEstudId)->get();

        return response()->json( $verEstado );
    }

    public function SolicitudBeca(Request $request){
        $data = \DB::update('exec [dbu].[Sp_DBU_COMEDOR_CRUD_SOLICITUD]  ?, ?, ?,?, ?, ?,?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?', array($request->opcion,$request->estudiante,$request->puntaje_academica,$request->puntaje_ficha, date('Y-m-d'), 'user', gethostname(), $request->getClientIp() , '-', $request->server->get('REMOTE_ADDR'),$request->observacion,'0',$request->puntaje_integrante,$request->bCertMed,$request->bConstGest,$request->bRecibAlq,$request->bRecibLuz,$request->bRecibAgua,$request->bBoletaPag,$request->bDeclJurada,$request->bFrontViv));
       
        return response()->json( $data );
    }

    public function SolicitudRanking(Request $request)
    {

        switch ( $request->opcion ) 
        {     

            case 'CONSULTAR':
                $data = \DB::select('exec [dbu].[Sp_DBU_COMEDOR_CRUD_SOLICITUD]  ?, ?, ?,?, ?, ?,?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?', array($request->opcion,'0','0','0', date('Y-m-d'), 'user', gethostname(), $request->getClientIp() , '-', $request->server->get('REMOTE_ADDR'),'-',$request->filial,'-','-','-','-','-','-','-','-','-'));
       
            break;
        }
        return response()->json( $data );
    }

    public function buscarPersona($dni){
        //return $dni;
        $request = new \Illuminate\Http\Request();
                $request->replace(['dni' =>$dni]);
        
                $pc = new PideController();
                $response=$pc->consultar( $request, 'reniec', null, true);
                $data = $response;
               
        return response()->json( $data );
    }

    function ReporteFichaSocioeconomica () {
        $carrera_sede = \DB::table('ura.carreras_filiales')
                ->join('ura.carreras', 'ura.carreras_filiales.iCarreraId', '=', 'ura.carreras.iCarreraId')
                ->join('grl.filiales', 'ura.carreras_filiales.iFilId', '=', 'grl.filiales.iFilId')
                ->select('ura.carreras.iCarreraId','ura.carreras.cCarreraCarn','grl.filiales.iFilId','grl.filiales.cFilDescripcion')
                ->where('ura.carreras.iProgramasAcadId', 1)
                ->get();


        $data = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA]  ?, ?', array('CONSULTAR_PDF',''));
       
        
        $pdf = \PDF::loadView('dbu.ReporteFichaSocioeconomica', compact(['data','carrera_sede']))
        ->setPaper('a4', 'landscape');

        return $pdf->stream();
        
    }

    function ReportePostulante () {
        $carrera_sede = \DB::table('ura.carreras_filiales')
                ->join('ura.carreras', 'ura.carreras_filiales.iCarreraId', '=', 'ura.carreras.iCarreraId')
                ->join('grl.filiales', 'ura.carreras_filiales.iFilId', '=', 'grl.filiales.iFilId')
                ->select('ura.carreras.iCarreraId','ura.carreras.cCarreraCarn','grl.filiales.iFilId','grl.filiales.cFilDescripcion')
                ->where('ura.carreras.iProgramasAcadId', 1)
                ->get();


                $data = \DB::select('exec [dbu].[Sp_DBU_COMEDOR_CRUD_POSTULANTES]  ?, ?, ?', array('CONSULTAR_PDF','0','0'));
       
        
        $pdf = \PDF::loadView('dbu.ReportePostulante', compact(['data','carrera_sede']))
        ->setPaper('a4', 'portrait');

        return $pdf->stream();
        
    }

    public function obtenerSemestreComedor(){
        $data = \DB::table('dbu.configuracion')
                
                ->get();
        
                return response()->json( $data );
    }

    public function DescargarReporteDetalladoExcel()
    {
        header("Access-Control-Allow-Origin: *");
        
        $resumen = \DB::select('exec dbu.Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_REPORTE ?,?,?', array('RESUMEN','0','0'));

        Excel::create('Reporte Ficha Socioeconómica', function ($excel) use ($resumen) {

            $excel->sheet('Resumen', function ($sheet) use ($resumen) {
                //$cursos = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls ?,?,?,?,?,?', array(20192,1,2,'GP-413',1,324));
                
                $objPHPExcel = new PHPExcel();
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:S1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);

                $sheet->setCellValue('B5', 'RESUMEN DETALLADO DE LA FICHA SOCIOECONÓMICA');
                $sheet->mergeCells("B5:S5");
                $sheet->getStyle('B5')->getFont()->setName('Tahoma')->setBold(true)->setSize(18);

                /*$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('img/logo.png'));
                $drawing->setCoordinates('D1');

                $drawing->setWorksheet($sheet);
                */
                //$drawing->setWorksheet($event->sheet->getDelegate());
               // $drawing->setWorksheet($sheet);
                
                //$data = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('B9', 'N°');
                $sheet->setCellValue('C9', 'FILIAL');
                $sheet->setCellValue('E9', 'ESCUELA PROFESIONAL');
                $sheet->setCellValue('H9', 'INTERNET SI');

                $sheet->setCellValue('J9', 'INTERNET NO');

                $sheet->setCellValue('L9', 'MENOS DE 4 Mb');
                $sheet->setCellValue('N9', 'ENTRE 4 Y 9 Mb');
                $sheet->setCellValue('P9', 'ENTRE 10 Y 25 Mb');
                $sheet->setCellValue('R9', 'MAYOR DE 25 Mb');



                $sheet->getStyle('B9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->getStyle('J9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('L9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('N9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('P9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('R9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);


                $sheet->mergeCells("C9:D9");
                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:I9");

                $sheet->mergeCells("J9:K9");
                $sheet->mergeCells("L9:M9");
                $sheet->mergeCells("N9:O9");
                $sheet->mergeCells("P9:Q9");
                $sheet->mergeCells("R9:S9");

               
                $sheet->cells('B1', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('B5', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('B9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('C9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('E9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('H9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('J9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('L9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('N9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('P9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('R9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $data = json_decode(json_encode($resumen), true);
                
                $aa=10;
                $bb=0;
                $cc=0;
                $dd=0;
                $ee=0;
                $ff=0;
                $gg=0;

                foreach ($resumen as $key => $value) {
                    $x = ($key + 10);
                    $b = "B" . $x;
                    
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;
                    $k = "K" . $x;

                    $l = "L" . $x;
                    $m = "M" . $x;

                    $n = "N" . $x;
                    $o = "O" . $x;

                    $p = "P" . $x;
                    $q = "Q" . $x;

                    $r = "R" . $x;
                    $s = "S" . $x;

                    $sheet->setCellValue($b, $key + 1);

                    $sheet->setCellValue($c, $value->cFilDescripcion);
                    $sheet->mergeCells($c . ":" . $d);

                    $sheet->setCellValue($e, $value->cCarreraCarn);
                    $sheet->mergeCells($e . ":" . $g);

                    $sheet->setCellValue($h, $value->InternetSi);
                    $sheet->mergeCells($h . ":" . $i);

                    $sheet->setCellValue($j, $value->InternetNo);
                    $sheet->mergeCells($j . ":" . $k);

                    $sheet->setCellValue($l, $value->Internet1);
                    $sheet->mergeCells($l . ":" . $m);

                    $sheet->setCellValue($n, $value->Internet2);
                    $sheet->mergeCells($n . ":" . $o);
                    
                    $sheet->setCellValue($p, $value->Internet3);
                    $sheet->mergeCells($p . ":" . $q);
                    
                    $sheet->setCellValue($r, $value->Internet4);
                    $sheet->mergeCells($r . ":" . $s);

                    $sheet->cells($h . ":" . $s, function ($cells) {
                        $cells->setAlignment('center');
                    });
                    # code...
                $aa=$aa+1;
                $bb=$bb+(($value->InternetSi)*1);
                $cc=$cc+(($value->InternetNo)*1);
                $dd=$dd+(($value->Internet1)*1);
                $ee=$ee+(($value->Internet2)*1);
                $ff=$ff+(($value->Internet3)*1);
                $gg=$gg+(($value->Internet4)*1);
                }

                $sheet->setCellValue('E'.$aa, 'TOTAL');
                $sheet->mergeCells('E'.$aa. ":" .'G'.$aa);

                $sheet->setCellValue('H'.$aa, $bb);
                $sheet->mergeCells('H'.$aa. ":" .'I'.$aa);

                $sheet->setCellValue('J'.$aa, $cc);
                $sheet->mergeCells('J'.$aa. ":" .'K'.$aa);
                
                $sheet->setCellValue('L'.$aa, $dd);
                $sheet->mergeCells('L'.$aa. ":" .'M'.$aa);

                $sheet->setCellValue('N'.$aa, $ee);
                $sheet->mergeCells('N'.$aa. ":" .'O'.$aa);

                $sheet->setCellValue('P'.$aa, $ff);
                $sheet->mergeCells('P'.$aa. ":" .'Q'.$aa);

                $sheet->setCellValue('R'.$aa, $gg);
                $sheet->mergeCells('R'.$aa. ":" .'S'.$aa);
                //$sheet->fromArray($data,'A5');
                $sheet->cells('E'.$aa . ":" . 'S'.$aa, function ($cells) {
                    $cells->setAlignment('center');
                });
                $aa=$aa+1;
                $total = $bb + $cc;
                $sheet->setCellValue('B'.$aa, 'TOTAL DE FICHA SOCIOECONÓMICA');
                $sheet->mergeCells('B'.$aa . ":" . 'G'.$aa);
                $sheet->getStyle('B'.$aa)->getFont()->setName('Tahoma')->setBold(true)->setSize(14);

                $sheet->cells('B'.$aa, function ($cells) {
                    $cells->setAlignment('center');
                });


                $sheet->setCellValue('H'.$aa, $total);
                $sheet->mergeCells('H'.$aa . ":" . 'K'.$aa);
                $sheet->getStyle('H'.$aa)->getFont()->setName('Tahoma')->setBold(true)->setSize(14);

                $sheet->cells('H'.$aa, function ($cells) {
                    $cells->setAlignment('center');
                });

                $sheet->setOrientation('landscape');
            });

            foreach ($resumen as $key => $value) {
                $carrera = \DB::select('exec dbu.Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_REPORTE ?,?,?', array('CARRERA',$value->iCarreraId,$value->iFilId));
            $excel->sheet($value->cCarreraCarn, function ($sheet) use ($carrera,$value) {
                //$cursos = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls ?,?,?,?,?,?', array(20192,1,2,'GP-413',1,324));
                
                $objPHPExcel = new PHPExcel();
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:S1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);

                $sheet->setCellValue('B5', 'REPORTE FICHA SOCIOECONÓMICA '.$value->cCarreraCarn.' - '.$value->cFilDescripcion);
                $sheet->mergeCells("B5:S5");
                $sheet->getStyle('B5')->getFont()->setName('Tahoma')->setBold(true)->setSize(16);


                $sheet->setCellValue('B9', 'N°');
                $sheet->setCellValue('C9', 'CODIGO');
                $sheet->setCellValue('E9', 'APELLIDOS Y NOMBRES');
                $sheet->setCellValue('N9', 'ESTADO');



                $sheet->getStyle('B9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('N9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
            


                $sheet->mergeCells("C9:D9");
                $sheet->mergeCells("E9:M9");
                $sheet->mergeCells("N9:O9");
               

                $sheet->cells('B1', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('B5', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('B9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('C9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('E9', function ($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('N9', function ($cells) {
                    $cells->setAlignment('center');
                });
                
                $data = json_decode(json_encode($carrera), true);
                
                $aa=10;
                $bb=0;
                
                foreach ($carrera as $key => $value) {
                    $x = ($key + 10);
                    $b = "B" . $x;
                    
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $m = "M" . $x;

                    $n = "N" . $x;
                    $o = "O" . $x;

                   
                    $sheet->setCellValue($b, $key + 1);

                    $sheet->setCellValue($c, $value->cEstudCodUniv);
                    $sheet->mergeCells($c . ":" . $d);

                    $sheet->setCellValue($e, $value->cPersPaterno.' '.$value->cPersMaterno.', '.$value->cPersNombre);
                    $sheet->mergeCells($e . ":" . $m);
                    if($value->iEstado==1){
                    $sheet->setCellValue($n, 'COMPLETO');
                    $sheet->mergeCells($n . ":" . $o);
                    }
                    else{
                    $sheet->setCellValue($n, 'INCOMPLETO');
                    $sheet->mergeCells($n . ":" . $o);
                    }
                    $sheet->cells($c . ":" . $o, function ($cells) {
                        $cells->setAlignment('center');
                    });
                    # code...
                $aa=$aa+1;
                $bb=$bb+1;
                
                }

                $sheet->setCellValue('E'.$aa, 'TOTAL');
                $sheet->mergeCells('E'.$aa. ":" .'M'.$aa);

                $sheet->setCellValue('N'.$aa, $bb);
                $sheet->mergeCells('N'.$aa. ":" .'O'.$aa);

               
                $sheet->cells('E'.$aa . ":" . 'O'.$aa, function ($cells) {
                    $cells->setAlignment('center');
                });

                


                

                $sheet->setOrientation('landscape');
            });
        }
        })->download('XLSX');
    }

    function DescargarFichaSocioeconomica($iEstudId) {
       
        $data = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('FICHA_REPORTE',$iEstudId));
        $pariente = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('PARIENTES',$iEstudId));
        $economico = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('ECONOMICO',$iEstudId));
        $vivienda = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('VIVIENDA',$iEstudId));
        $alimentacion = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('ALIMENTACION',$iEstudId));
        $discapacidad = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('DISCAPACIDAD',$iEstudId));
        $salud = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('SALUD',$iEstudId));
        $otro = \DB::select('exec [dbu].[Sp_DBU_CRUD_FICHA_SOCIOECONOMICA_POR_CICLO]  ?,?', array('OTRO',$iEstudId));
        
        

        //return $pdf->stream();

       
        $pdf = \PDF::loadView('dbu.fichasocioeconomica', compact(['data','pariente','economico','vivienda','alimentacion','discapacidad','salud','otro']))
        ->setPaper('a4', 'portrait');
        //return $pdf->stream();
        return $pdf->download($data[0]->cEstudCodUniv.".pdf");
    }

}