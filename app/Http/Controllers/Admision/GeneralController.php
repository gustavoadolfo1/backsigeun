<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

use Mike42\Escpos\Printer; 
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
//use Mike42\Escpos\CapabilityProfile;

class GeneralController extends Controller
{
    public function getCriteriosAdmision()
    {
        $documents = DB::table('grl.tipo_Identificaciones')->whereIn('iTipoIdentId', [1, 3])->get();
        $departamentos = DB::table('acad.departamentos')->get();
        $tcolegios = DB::table('acad.gestion_educativa')->get();
        $procesoAdmision = DB::table('adm.proceso_admision')->where('bProcAdmEst', 1)->first();
        $carreras = DB::table('adm.carreras_filiales_proceso_admision as cf')
                        ->select('c.iCarreraId', 'cCarreraDsc', 'f.iFilId', 'cFilDescripcion')
                        ->join('ura.carreras as c', 'cf.iCarreraId', '=', 'c.iCarreraId')
                        ->join('grl.filiales as f', 'cf.iFilId', '=', 'f.iFilId')
                        ->where('iProgramasAcadId', 1)->where('iProcAdmId', $procesoAdmision->iProcAdmId)
                        ->orderBy('cCarreraDsc')->get();
        // $carreras = DB::table('ura.carreras as c')
        //                 ->select('cf.iCarrFilId', 'c.iCarreraId', 'cCarreraDsc', 'f.iFilId', 'cFilDescripcion')
        //                 ->join('ura.carreras_filiales as cf', 'c.iCarreraId', '=', 'cf.iCarreraId')
        //                 ->join('grl.filiales as f', 'cf.iFilId', '=', 'f.iFilId')
        //                 ->where('iProgramasAcadId', 1)->orderBy('cCarreraDsc')->get();

        $modosPreparacion = DB::table('adm.modo_preparacion')->get();

        $tiposModalidadHabilitados = [];
        if($procesoAdmision->bOrdinario == 1) {
            $tiposModalidadHabilitados[] = 1;
        }
        if($procesoAdmision->bExtraOrdinario == 1) {
            $tiposModalidadHabilitados[] = 2;
        }

        $modalidades = DB::table('ura.modalidades as m')
                        ->join('ura.tipos_modalidades as tm', 'm.iTipoModalidadId', '=', 'tm.iTipoModalidadId')
                        ->where('bModalidadAdmision', 1)
                        ->whereIn('m.iTipoModalidadId', $tiposModalidadHabilitados)
                        ->get();
        $gruposAdmision = DB::table('acad.grupos as g')->select('g.*', 'f.cFilDescripcion')->join('grl.filiales as f', 'g.iFilId', '=', 'f.iFilId')->where('bEstadoInscripciones', 1)->where('iProgramasAcadId', 7)->get();

        $response = [
            'documents' =>  $documents,
            'depart' =>  $departamentos,
            'tcolegios' => $tcolegios,
            'carreras' =>  $carreras,
            'gruposAdmision' => $gruposAdmision,
            'modosPreparacion' => $modosPreparacion,
            'modalidades' => $modalidades
        ];
        return response()->json( $response );
    }

    public function verificarInscripcion(Request $request) {

        // $inscripcion = DB::table('adm.inscripciones as i')
        //                 ->select('i.*', 'ip.iInscripPagosId')
        //                 ->leftJoin('adm.inscripciones_pagos as ip', 'i.iInscripId', '=', 'ip.iInscripId')
        //                 ->where('cDocumento', $request->dni)
        //                 ->where('iGrupoControl', $request->ciclo)
        //                 //->whereNull('ip.iInscripPagosId')
        //                 ->first();
        
        $inscripcion = \DB::select("exec [adm].[SP_SEL_buscaInscripcion] '" . $request->dni . "'");

        return response()->json( ['inscripcion' => $inscripcion[0] ?? null ]);

        // $inscripcion = DB::table('adm.inscripciones as i')->join('adm.inscripciones_pagos as ip', 'i.iInscripId', '=', 'ip.iInscripId')->where('cDocumento', $request->dni)->where('iGrupoControl', $request->ciclo)->where()->first();

        return response()->json( ['inscripcion' => $inscripcion ]);
    }

    public function getCicloControl($fil){
        $cicloAcademico = \DB::select('exec [acad].[SP_SEL_controlXiTipoEstado] 1 ,' . $fil. ',7');
        $resultData =  ['academico' =>  $cicloAcademico[0]];
        return response()->json( $resultData );
    }

    public function getAllCicloControl($fil){
        $resultData = \DB::select('select * from acad.grupos where iProgramasAcadId = 7 and iFilId =' . $fil);
        return response()->json( $resultData );
    }

    public function selControl(Request $request){
        $data0 = [
            $request->ciclo,
            $request->filial,
        ];
        $data = [
            $request->ciclo,
            $request->ins,
            $request->filial,
        ];
        $queryResult = \DB::select('[acad].[SP_UPD_control] ?,1,0,?',$data0);
        $queryResult2 = \DB::select('[acad].[SP_UPD_control] ?,?,1,?',$data);
        $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult,'queryResult2' => $queryResult2 ];
        return response()->json( $response );
    }

    public function getSedesCepre($dni){
        $data = \DB::select('exec [adm].[Sp_SEL_filialesAdmisionXcCredUsuario] '. $dni);
        return response()->json( $data );
    }
    
    public function addFoto(Request $request){
        $loadData = [
            intval($request->iEstudServId ),
            intval($request->cFoto),
            'CEPRE',
            '--',
            '--',
            '--', 
        ];
        try {
            
            $queryResult = \DB::select("exec [acad].[SP_UPD_fotoEstudiante] ?,?,  ?,?,?,?" , $loadData);
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];
            $codeResponse = 200;
        } catch (\Exception $e) {
            
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
    }
    
    public function getPreInscripciones($fil, $filtro, $modalidad, $lugar, $carrera, $sexo, $extraordianrio='0'){

        $carreraFilial = explode('-', $carrera);

        $resultData = \DB::select("exec adm.SP_SEL_inscritosOnline ?, ?, ?, ?, ?, ?, ?, ?", [ $fil, $filtro, $modalidad, $lugar, $carreraFilial[0], $carreraFilial[1], $sexo,$extraordianrio ]);
        return response()->json( $resultData );
    }

    public function guardarInscripcionAdmision(Request $request)
    {
        $fechaNac = date('Y-m-d', strtotime($request->fechan));

        $carreraFilial = explode('-', $request->carrera);

        $parametros = [
            $request->inscripcionId,
            $request->grupoId,
            $request->grupoControl,
            $request->nacionalidad,
            $request->tipoDoc,
            $request->numeroDoc,
            $request->apellidos1,
            $request->apellidos2,
            $request->nombres,
            $request->sexo,
            $fechaNac,
            NULL,
            $request->telefono,
            NULL,
            $request->correo,
            $request->direccion1,
            $carreraFilial[0],
            $request->colegio,
            $request->gestion,
            $request->anioTermino,
            $request->preparacion,
            $request->modalidad,
            $carreraFilial[1],

            NULL,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'

        ];
        
        try {
            $queryResult = \DB::select('EXEC [adm].[Sp_INS_UPD_inscripcionesOnline] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    
    public function getPrograma(){
        $resultData = DB::table('adm.proceso_admision')->orderBy('cProcAdmDoc', 'desc')->get();
        
        return response()->json( $resultData );
    }

    public function getTipoModalidades(){
        $modalidades = DB::table('ura.tipos_modalidades as m')->whereIn('iTipoModalidadId', [1, 2])->get();
        
        return response()->json( $modalidades );
    }

    public function modalidadRequisitos($mod){
        $resultData = \DB::select('select req.iProcAdmId,req.iRequisitosId,r.cRequisitosDsc from adm.proceso_admision_requisitos  as req
        INNER JOIN ura.modalidades as m ON m.cModalidadCod = req.cModalidadCod 
        INNER JOIN adm.requisitos as r ON r.iRequisitosId = req.iRequisitosId 
        where req.cModalidadCod =' . $mod);
        return response()->json( $resultData );
    }
    public function upImg(Request $request){
        $this->validate(
            $request,[
                'foto' => 'required'
            ]
        );

        if (preg_match('/^data:image\/(\w+);base64,/', $request->foto)) {
            $data = substr($request->foto, strpos($request->foto, ',') + 1);
            $filename = $request->dni.'.jpg';
            $data = base64_decode($data);
            $filePath = 'adm/fotos/' . $filename;
            Storage::disk('public')->put($filePath, $data);
            $request->foto = $filePath;
            
            $resultData = DB::table('adm.inscripciones')
            ->where('iInscripId', $request->id)
            ->update(['cFotografia' => $filename]);
            return response()->json( $filePath );
        }
    }
    public function uploadFile(Request $request){
        $file = $request->foto;
        if ($request->hasFile('foto')) {
            $filename = $request->dni.'.jpg';
            $filePath = 'adm/fotos/';
            $archivo = $request->file('foto');
            $archivo->storePubliclyAs($filePath,$filename);
            $resultData = DB::table('adm.inscripciones')
                ->where('iInscripId', $request->inscripcionId)
                ->update(['cFotografia' => $filename]);
            
            return response()->json( [ 'file' => $filePath . $filename ] );
        } else {
            return response()->json( [ 'error' => true ], 500 );
        }
        
    }
    public function changeEstadoRequisito($id, $estado, Request $request){
        $resultData = DB::table('adm.inscripciones')
        ->where('iInscripId', $id)
        ->update([
            'bRequisitos' => $estado,
            'cRequisitosObs' => $request->obs,
        ]);
        return response()->json( $resultData );
    }
    public function validarDataInscripcion($id, $filial, Request $request){

        $parametros = [
            $id,
            $filial,

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $queryResult = \DB::select('EXEC [adm].[SP_UPD_validarPostulanteXiInscripIdXiFilIdInscrito] ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'message' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    
    public function traerModalidadxRequisitos(){
        $modalidades = \DB::select('select * from ura.modalidades');
        $resultData = \DB::select('select req.iProcAdmId,req.iRequisitosId,m.cModalDsc,r.cRequisitosDsc from adm.proceso_admision_requisitos  as req
        INNER JOIN ura.modalidades as m ON m.cModalidadCod = req.cModalidadCod 
        INNER JOIN adm.requisitos as r ON r.iRequisitosId = req.iRequisitosId ');

        return response()->json( [ 'modalidades' => $modalidades, 'todos' => $resultData ]);
    }
    public function getFiltrosInscripciopnes(){

        $gruposAdmision = DB::table('acad.grupos as g')->select('g.*', 'g.iFilId as id', 'f.cFilDescripcion as name')->join('grl.filiales as f', 'g.iFilId', '=', 'f.iFilId')->where('bEstadoInscripciones', 1)->where('iProgramasAcadId', 7)->get();

        $filtros = \DB::select('EXEC [adm].[SP_SEL_filtroInscripciones]');

        $modalidades = DB::table('ura.tipos_modalidades as m')->whereIn('iTipoModalidadId', [1, 2])->get();

        $procesoAdmision = DB::table('adm.proceso_admision')->where('bProcAdmEst', 1)->first();
        $carreras = DB::table('adm.carreras_filiales_proceso_admision as cf')
                        ->select('c.iCarreraId', 'cCarreraDsc', 'f.iFilId', 'cFilDescripcion')
                        ->join('ura.carreras as c', 'cf.iCarreraId', '=', 'c.iCarreraId')
                        ->join('grl.filiales as f', 'cf.iFilId', '=', 'f.iFilId')
                        ->where('iProgramasAcadId', 1)->where('iProcAdmId', $procesoAdmision->iProcAdmId)
                        ->orderBy('cCarreraDsc')->get();

        $filiales2 = DB::table('grl.filiales')->select('iFilId', 'cFilDescripcion')->get();

        $extraordianrios = DB::table('ura.modalidades')->where('iTipoModalidadId',2)->get();

        $data = ['filiales' => $gruposAdmision, 'filiales2' => $filiales2, 'carreras' => $carreras, 'filtros' => $filtros, 'modalidades' => $modalidades, 'extraordinarios' => $extraordianrios];

        // $resultData = \DB::select('select iFilId as id ,cFilDescripcion as name from grl.filiales');

        return response()->json( $data );
    }
    public function editInsAdm(Request $request){
        $data = [
            'cModalidadCod' => $request->cModalidadCod,
            'cNombre' => $request->cNombre,
            'cMaterno' => $request->cMaterno,
            'iTipoIdent' => $request->iTipoIdent,
            'cPaterno' => $request->cPaterno,
            'iCarreraId' => $request->carrera,
            'iGruposId' => $request->ciclo,
            'cColeCodModular' => $request->cColeCodModular,
            'cEmail' => $request->cEmail,
            'cDepartamentoId' => $request->departamento,
            'cDireccion' => $request->cDireccion,
            'cDocumento' => $request->cDocumento,
            'dNacimiento' => $request->dNacimiento,
            'iFilId' => $request->filial,
            'iPreEgreso' => $request->iPreEgreso,
            'cProvinciaId' => $request->provincia,
            'cSexo' => $request->cSexo,
            'cTelefono' => $request->cTelefono,
            'iModoPreparacionId' => $request->tipoColegio
        ];
        // return response()->json( $data );
        $result = \DB::table('adm.inscripciones')
            ->where('iInscripId', $request->iInscripId)
            ->update($data);
        return response()->json( $result );
    }

    public function getdataInscripcionesDashboard($filId)
    {
        $queryResult = \DB::select('EXEC [adm].[SP_SEL_dashboard] ?', [ $filId ]);

        $procesoAdmision = DB::table('adm.proceso_admision')->where('bProcAdmEst', 1)->first();

        $data = DB::table('adm.inscripciones')->select(DB::raw('count(*) as total, dPreinscripcion'))->where('iGrupoControl', $procesoAdmision->iCicloControl)->where('iFilid', $filId)->orderBy('dPreinscripcion')->groupBy('dPreinscripcion')->get();

        

        return response()->json( [ 'dataInscripciones' => $queryResult[0], 'inscripcionesPorFecha' => $data ] );
    }
    public function getAulas($id){
        $queryResult = \DB::select("exec [acad].[SP_SEL_aulasxiFilId ] 'ADMISION', ?" , [ $id ]);
        return response()->json( $queryResult );
    }
    public function getTipoAulas(){
        $tiposAulas = DB::table('ura.tipos_aulas')->get();
        return response()->json( $tiposAulas );
    }
    public function saveUpdAulas(Request $request){
        $data = [
            $request->iAulaCod ?? 0,  /*id aulas*/
            'ADMISION', 
            $request->iTiposAulasId, /*tipo aulas*/
            $request->iFilId, 
            $request->cAulasDesc, /*nombre aulas*/
            $request->iAulasOrden, /*orden aulas*/
            $request->nAulasAforo, /*nombre aulas*/
            $request->bAulaActivo, /*estado aula*/
            $request->iAulaPiso , /*ubicacion aula*/

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        $queryResult = \DB::select("exec [acad].[Sp_INS_UPS_aulas] ?,?,?,?,?,?,?,?,?     ,?,?,?,?" , $data);
        return response()->json( $queryResult );
    }
    public function deleteAulas($id){
        $del = \DB::table('ura.aulas')->where('iAulaCod', $id)->delete();
        return response()->json( $del );
    }
    public function getDistribuciones($idFil){
        $queryResult = \DB::select("exec [adm].[SP_SEL_proceso_distribucionXiFilId] ?" , [ $idFil ]);
        return response()->json( $queryResult );
    }
    public function getDistribucionesAulas($iProcesoDistribId){
        $queryResult = \DB::select("exec [adm].[SP_SEL_proceso_aulas_distribucionXiProcesoDistribId] ?" , [ $iProcesoDistribId ]);
        return response()->json( $queryResult );
    }
    public function getDistribucionesAulasPersonas($iProcesoDistribId,$aula){
        $queryResult = \DB::select("exec [adm].[SP_SEL_aulas_distribucion_personasXiProcesoDistribIdXiAulaCod] ?,?" , [ $iProcesoDistribId, $aula ]);
        //dd($queryResult);
        $pdf = PDF::loadView('admision.padron1', [ 'data' => $queryResult ])->setPaper('A4');
        return $pdf->stream();

        return response()->json( $queryResult );
    }
    public function getDistribucionesAulasPersonas2($iProcesoDistribId,$aula){
        $queryResult = \DB::select("exec [adm].[SP_SEL_aulas_distribucion_personasXiProcesoDistribIdXiAulaCod] ?,?" , [ $iProcesoDistribId, $aula ]);
        //dd($queryResult);
        $pdf = PDF::loadView('admision.padron2', [ 'data' => $queryResult ])->setPaper('A4');
        return $pdf->stream();

        return response()->json( $queryResult );
    }
    public function getDistribucionesAulasPersonas3($iProcesoDistribId,$aula){
        $queryResult = \DB::select("exec [adm].[SP_SEL_aulas_distribucion_personasXiProcesoDistribIdXiAulaCod] ?,?" , [ $iProcesoDistribId, $aula ]);
        //dd($queryResult);
        $pdf = PDF::loadView('admision.padron3', [ 'data' => $queryResult ])->setPaper('A4');
        return $pdf->stream();

        return response()->json( $queryResult );
    }
    public function procesarDistribucion(Request $request){

        $this->validate(
            $request,
            [
                'iTipoModalidadId' => 'required',
                'iProcAdmId' => 'required',
                'iGruposId' => 'required',
            ],
            [
                'iTipoModalidadId.required' => 'Debe seleccionar una modalidad de la lista.',
            ]
        );

        $data = [
            $request->iTipoModalidadId, 
            $request->iProcAdmId, /*20201*/
            $request->iGruposId, /*78*/
            'ADMISION',	
            $request->json_aulas,	

            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];
        $queryResult = \DB::select("exec [adm].[SP_PROC_distribucionExamen] ?,?,?,?,?,  ?,?,?,?" , $data);
        return response()->json( $queryResult );
    }
    public function getnProcesados($idGrupo){
        $queryResult = \DB::select("exec [adm].[Sp_SEL_inscritos_a_Procesar] ?" , [ $idGrupo ]);
        return response()->json( $queryResult );
    }

    public function cargarFotos(Request $request)
    {
        $this->validate(
            $request, [
                'procesoAdmision' => 'required'
            ]
        );

        $exceptions = [];
        $filasNoActualizadas = [];
        $withErrors = false;

        try {
            foreach ($request->files as $index => $file) {

            
                $archivo = $request->file($index);

                $filename = $file->getClientOriginalName();
                $filePath = 'adm/fotos/';

                $archivo->storePubliclyAs($filePath, $filename);

                $dni = explode( '.', $filename);

                $queryResult = \DB::select('EXEC [adm].[SP_UPD_fotografiaPostulante] ?, ?, ?', [ $request->procesoAdmision, $dni[0], $filename ]);

                if ($queryResult[0]->iResult == 0) {
                    $filasNoActualizadas[] = $queryResult[0];
                }
          
            }
        } catch (\Exception $e) {
            $exceptions[] = $e;
            $withErrors = true;
        }

        if ($withErrors) {
            return response()->json( $exceptions, 500 );
        } else {
            return response()->json( ['message' => 'Fotos subidas con éxito', 'exceptions' => $exceptions, 'filasNoActualizadas' => $filasNoActualizadas], 200 );
        }
    }
    public function getAsistenciaPuerta(Request $request, $dni){

        $request->dni = $dni;
      
        $withErrors = false;
        try {
            $queryResult = \DB::select('EXEC [adm].[SP_SEL_ubicacionExamenXcPersDocumento] ?', [  $request->dni ]);
        } catch (\Exception $e) {
            $exceptions[] = $e;
            $withErrors = true;
        }
       // $this->print($request,$queryResult);
        
        if ($withErrors) {
            return response()->json( $exceptions, 500 );
        } else {
            return response()->json( $queryResult, 200 );
            
        }
    }
    public function print($config,$data){

        try {
            
            $connector = new WindowsPrintConnector("smb://". $config->equipo ."/". $config->impresora);
            $printer = new Printer($connector);
            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("UNIVERSIDAD NACIONAL DE MOQUEGUA\n\n");
            $printer->text("DNI::".  trim($data[0]->cDocumento) . "   CODIGO::".  trim($data[0]->cCodPostulante) ."\n\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("EXAMEN::".trim($data[0]->cModalDsc)."\n"."PARA::". trim($data[0]->carrera)."\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("-------------------------------------"."\n");
            $printer->text("AULA::". ($data[0]->cAulasDesc_ ?? 'No Asignado') ."  PISO::". ($data[0]->iAulaPiso_ ?? 'No Asignado') ."   ORDEN::". ($data[0]->cAulasOrden_ ?? 'No Asignado') . "\n");
            $printer->text("INGRESO ::");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer -> cut();
            $printer -> close();
        } catch (Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }


    public function descargarTXT($tipoExportacion, $procesoAdm)
    {
        $postulantes = \DB::select("exec [adm].[Sp_GEN_archivoPlanoPostulantesXiGrupoControlXiTipoModalidadId] ?, ?" , [ $procesoAdm, $tipoExportacion]);

        $fd = fopen ("archivo_examen.txt", "w");

        foreach ($postulantes as $i => $postulante) {
            $linea = vsprintf("%08s", $postulante).chr(13).chr(10); 
	        fwrite ($fd, $linea);
        }

        fclose($fd);

        return response()->json([ 'ruta' => 'archivo_examen.txt' ]);
    }

    public function descargarTXTRuta()
    {
        $pathtoFile = public_path().'/archivo_examen.txt';
        return response()->download($pathtoFile);
    }
    public function cambioCarrera(Request $request){
        $data = [
            $request->iCarreraId,
            $request->iFilId_Carrera,
            $request->iInscripId,
            $request->iGrupoControl,
        ];
        $queryResult = \DB::select("exec [adm].[Sp_UPD_actualizaCarrera] ?,?,?,?" , $data);
        return response()->json( $queryResult );
    }

    public function getProcesosGruposFiliales()
    {
        $procesos = DB::table('adm.proceso_admision')->get();

        $proceso = $procesos->first(function ($proceso, $key) {
            return $proceso->bProcAdmEst == 1;
        });

        $grupos = DB::table('acad.grupos')->where('iProgramasAcadId', 7)->where('iGrupoControl', $proceso->iCicloControl)->get();

        $proceso->grupos = $grupos;

        $filiales = DB::table('grl.filiales')->select('iFilId', 'cFilDescripcion')->where('iFilEstado', 1)->get();

        return response()->json( [ 'procesos' => $procesos, 'proceso' => $proceso, 'filiales' => $filiales ] );
    }

    public function enviarExpedienteDASA($ingrsanteId)
    {
        try {
            $queryResult = \DB::select('exec [adm].[Sp_UPD_EnviarExpediente_a_DasaXiIngresanteId] ?, ?', [$ingrsanteId, auth()->user()->cCredUsuario] );

            $response = ['validated' => true, 'message' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
