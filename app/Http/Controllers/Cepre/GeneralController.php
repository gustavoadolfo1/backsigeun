<?php

namespace App\Http\Controllers\Cepre;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function getCriterios(){
        $documents = \DB::select('select * from [grl].[tipo_Identificaciones]');
        $departamentos = \DB::select('select * from [acad].[departamentos]');
        $tcolegios = \DB::select('select * from [acad].[gestion_educativa]');
        $carreras = \DB::select('select * from [ura].[carreras] where iProgramasAcadId = 1');
        $sedes = \DB::select('select * from [grl].[filiales]');
        $turnos = \DB::select("exec [acad].[SP_SEL_turnosXiFilId] 1, 'CEPRE' ");
        $aulas = \DB::select("select * FROM ura.aulas as a inner join ura.carreras_filiales as cf on a.iCarreraId=cf.iCarreraId and a.iFilId=cf.iFilId
        inner join ura.carreras as c on cf.iCarreraId=c.iCarreraId
        where c.cCarreraSigla='CEPRE'");

        $cicloApertuta = \DB::select('select * from acad.grupos where bEstadoInscripciones = 1');

        $response = [
            'documents' =>  $documents,
            'depart' =>  $departamentos,
            'tcolegios' => $tcolegios,
            'carreras' =>  $carreras,
            'sedes' =>  $sedes,
            'turnos' => $turnos,
            'aulas' =>  $aulas,
            'cicloApertura' => $cicloApertuta[0]->cGrupoDsc
        ];
        return response()->json( $response );
    }
    public function getProvin($dep){
        $provincias = \DB::table('acad.provincias')->where('cDepId', $dep)->get();
        return response()->json( $provincias );
    }

    public function getDistritos($departamento, $provincia){
        $distritos = \DB::table('acad.distritos')->where('cDepId', $departamento)->where('cProvId', $provincia)->get();
        return response()->json( $distritos );
    }

    public function getAcadColegios($depId, $provId){
        $colegios = \DB::select('EXEC [acad].[Sp_SEL_colegiosXcDepIdXcProvId] ?, ?', [$depId, $provId]);
        return response()->json( $colegios );
    }

    public function saveData(Request $request){
        $cicloApertuta = \DB::select('select * from acad.grupos where bEstadoInscripciones = 1');
        $data = [
            'iNacionId' => 193,
            'cCicloApertura'=>$cicloApertuta[0]->cGrupoDsc,
            'iTipoIdent' =>  intval($request->tipoDoc),
            'cPreDocument' =>  $request->numeroDoc,
            'cPrePaterno' =>  $request->apellidos1,
            'cPreMaterno' =>  $request->apellidos2,
            'cPreNombre' =>  $request->nombres,
            'cPreSexo' =>  $request->sexo,
            'cPreTurno' => $request->turno,
            'dPreNacimiento' =>  $request->fechan,
            'cPreTelefono' =>  $request->telefono,
            'cPreEmail' =>  $request->correo,
            'iFilId' =>  intval($request->sede),
            'cPreDireccion' =>  $request->direccion1,
            'iCarreraId' =>  intval($request->carrera),
            'iDepartamento' =>  intval($request->departamento),
            'iProvinciaId' =>  intval($request->provincia),
            'iDistritoId' => 0,
            'cDescColegio' =>$request->colegio,
            'iTipoColegioId' => intval($request->tcolegio),
            'iPreEgreso' => intval($request->anioTermino),
            'cPreApoderado' => $request->apoderado,
            'cPreDireccionAp' => $request->direccion2,
            'cPreTelefonoAp' => $request->telegonop,

            'cUsuarioSis' =>  'web',
            'cEquipoSis' =>  'local',
            'cIpSis' => 'no detect',
            'cMacSis' =>  'no detect',
        ];
        try {
            \DB::table('cepre.inscripciones')->insert([$data]);

            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response );
    }
    public function getCicloControl($fil){
        $cicloAcademico = \DB::select('exec [acad].[SP_SEL_controlXiTipoEstado] 1 ,' . $fil. ', ');
        $resultData =  ['academico' =>  $cicloAcademico[0]];
        return response()->json( $resultData );
    }
    public function getAllCicloControl($fil){
        $resultData = \DB::select('select * from acad.grupos where iProgramasAcadId = 6 and iFilId =' . $fil);
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
    public function getPreInscripciones($ciclo,$fil){
        $format = "'".$ciclo."'";
        $resultData = \DB::select('exec cepre.SP_SEL_inscritosOnline '.$format.','.$fil);
        return response()->json( $resultData );
    }
    public function getAulasCepre(){
        $aulas = \DB::select("select * FROM ura.aulas as a inner join ura.carreras_filiales as cf on a.iCarreraId=cf.iCarreraId and a.iFilId=cf.iFilId
        inner join ura.carreras as c on cf.iCarreraId=c.iCarreraId
        where c.cCarreraSigla='CEPRE'");
        return response()->json( $aulas );
    }
    public function saveAula(Request $request){
        $queryResult = \DB::table('ura.aulas')->insert([
            'cAulasDesc' => $request->cAulasDesc,
            'cAulasOrden' => 1,
            'iCarreraId' => 20,
            'iFilId' => $request->iFilId,
            'iTiposAulasId' => 6,
            ]
        );
        return response()->json( $queryResult );

    }
    public function VerificarInscripcion(Request $request){
        $doc = $request->dni;
        $cic = $request->ciclo;
        $people = \DB::select("select * from cepre.inscripciones where cPreDocument = ". $doc ." and cCicloApertura = '". $cic ."'");
        return response()->json( $people );
    }
    public function getSedesCepre($dni){
        $data = \DB::select('exec [cepre].[Sp_SEL_filialesCepresXcCredUsuario] '. $dni);
        return response()->json( $data );
    }
    public function editIns(Request $request){
        $data = [
            'iGruposId' => $request->ciclo,
            'iTipoIdent' => $request->iTipoIdent,
            'iNacionId' => 173,
            'cPreDocument' => $request->documento,
            'cPrePaterno' => $request->apellidoPaterno,
            'cPreMaterno' => $request->apellidoMaterno,
            'cPreNombre' => $request->nombres,
            'cPreSexo' => $request->sexo,
            'dPreNacimiento' => $request->fechaNac,
            'cPreTelefono' => $request->telefono,
            'cPreEmail' => $request->correo,
            'iFilId' => $request->filial,
            'cPreDireccion' => $request->direccion,
            'iCarreraId' => $request->carrera,
            'cDepartamentoId' => $request->departamento,
            'cProvinciaId' => $request->provincia,
            'iTurnosId' => $request->turno,
            'cDescColegio' => $request->colegio,
            'cColeCodModular' => $request->cColeCodModular,
            'iTipoColegioId' => $request->tipoColegio,
            'iPreEgreso' => $request->egreso,
            'cPreApoderado' => $request->apoderado,
            'cPreDireccionAp' => $request->direccionAp,
            'cPreTelefonoAp' => $request->telefonoAp
        ];
        $result = \DB::table('cepre.inscripciones')
            ->where('iInscripId', $request->iInscripId)
            ->update($data);
        return response()->json( $result );
    }
    public function getTurnos($fil){
        $dataResults = \DB::select(" exec [acad].[SP_SEL_turnosXiFilId] ?,'CEPRE' ",[$fil]);
        return response()->json( $dataResults );
    }
    public function saveTurnos(Request $request){
        $loadData = [
            $request->iTurnosId,
            intval($request->iFilId),
            $request->cTurno,
            intval($request->iGruposId),
            'CEPRE',
            '--',
            '--',
            '--',
        ];
        // return response()->json( $loadData );
        try {
            $queryResult = \DB::select('exec acad.SP_INS_UPD_turnos ?,?,?,?,?,?,?,?', $loadData);
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
    }
    public function getConceptosTurnos(){
        try {
            $queryResult = \DB::select("exec [acad].[SP_SEL_conceptosAcademicosXcModulo] 'CEPRE'");
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];
            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
    }
    public function confirmarIns(Request $request){
        $loadData = [
            intval($request->iInscripId),
            intval($request->iAula),
            intval($request->iCondicion),
            'CEPRE',
            '--',
            '--',
            '--',
        ];

        try {
            $queryResult = \DB::select("exec [cepre].[SP_INS_confirmacion] ?,?,?,  ?,?,?,?" , $loadData);
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];
            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
    }
    public function generarCodigo(Request $request){
        $loadData = [
            intval($request->iEstudServId ),
            intval($request->iNumExpediente),
            'CEPRE',
            '--',
            '--',
            '--',
        ];
        try {
            $queryResult = \DB::select("exec [cepre].[SP_UPD_expedienteEstudiante] ?,?,  ?,?,?,?" , $loadData);
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];
            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
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
    public function uploadFile(Request $request){
        $ordenArchivo = NULL;
        $file = $request->oneFile;
        $name = time().'.'.$request->oneFile->getClientOriginalExtension();
        if ($request->hasFile('oneFile')) {
            request()->oneFile->move(public_path('fotosCEPRE'), $name);
        } else {
            $ordenArchivo = $request->oneFile;
        }
        return response()->json( [ 'file' => $name ] );
    }
    public function getEstudiantesXaulaxturno($iGrupoId,$iTurno,$iAulaId){
        $loadData = [
            'CEPRE',
            1,
            $iGrupoId,
            $iTurno,
            $iAulaId
        ];
        // return response()->json( $loadData );
        try {

            $queryResult = \DB::select("exec [acad].[SP_SEL_estudiantesAcademico] ?,?,?,?,?" , $loadData);
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];
            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
    }
    public function getCriteriosEstudiantes($fil){

        $turnos = \DB::select(" exec [acad].[SP_SEL_turnosXiFilId] ?,'CEPRE' ",[$fil]);
        $aulas = \DB::select("exec [acad].[SP_SEL_aulasxiFilId ] 'CEPRE',".$fil);

        $response = ['turnos' => $turnos, 'aulas' => $aulas ];

        return response()->json( $response );
    }
    public function puchangeExpediente(Request $request){
        $loadData = [
            $request->iEstudServId,
            $request->iNumExpediente,

            'CEPRE',
            '--',
            '--',
            '--',

        ];
        try {

            $queryResult = \DB::select("exec [cepre].[SP_UPD_expedienteEstudiante] ?,?,   ?,?,?,?" , $loadData);
            $response = ['validated' => true, 'mensaje' => '', 'queryResult' => $queryResult ];
            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }
        return response()->json( $response,$codeResponse );
    }
}
