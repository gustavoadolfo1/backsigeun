<?php

namespace App\Http\Controllers\DBU;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
class DBUFichaController extends Controller
{
    /**
     * Todos los Procedimientos relacionados a la gestión de la Ficha Socioeconómica
     * 
     * Mod: DBU - Ficha Socioeconómica
     */
    public function CrearFichaSocioeconomica($iEstudId){
    	try {
            $VerFicha =  \DB::table('dbu.fichasoc_cabecera')->where('iEstudId',  $iEstudId)->count();
            if($VerFicha==0){
                $ficha = \DB::select('exec dbu.Sp_DBU_FICHA_INS_CrearFichaxiEstudId ?', array($iEstudId));
            }
else{
    $ficha = \DB::table('dbu.fichasoc_cabecera')->where('iEstudId',  $iEstudId)->get();
}
            


            $response = ['validated' => true, 'mensaje' => 'Sp_DBU_FICHA_INS_CrearFichaxiEstudId.'];
        } catch (\Exception $e) {
            $ficha = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json([$ficha, 'res' => $response]);
    }

    public function CrearFamiliarEstudiante($iEstudId,$iFamiliaId){
    	try {
            $familiar = \DB::select('exec dbu.Sp_DBU_FICHA_INS_CrearParientexiEstudId ?,?', array($iEstudId,$iFamiliaId));


            $response = ['validated' => true, 'mensaje' => 'Sp_DBU_FICHA_INS_CrearParientexiEstudId.'];
        } catch (\Exception $e) {
            $familiar = 0;
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
        }

        return response()->json([$familiar, 'res' => $response]);
    }
    public function LeerFichaSocioeconomica($iEstudId){

        $ficha = \DB::select('exec dbu.Sp_DBU_FICHA_SEL_LeerFichaxiEstudId ?', array($iEstudId));

        return response()->json( $ficha );

    }
    public function LeerFichaSocioeconomicaPreData($iEstudId){
        $predata = \DB::select('SELECT cEstudSemeIngre, cEstudCorreo, cEstudTelef, iCarreraId FROM ura.estudiantes WHERE iEstudId = ?',array($iEstudId));
        return response()->json($predata);
    }
    public function ListaFamiliaresEstudiante($iFamiliaId){

        $lista = \DB::select('exec dbu.Sp_DBU_FICHA_SEL_ListaFamiliaresxiFamiliaId ?', array($iFamiliaId));

        return response()->json( $lista );

    }
    public function FichaEditarDatosGenerales(Request $request){
    	$parametros = [
    		$request->iEstudId,
    		$request->cDireccionNombreVia ?? NULL,
    		$request->cDireccionNumPuerta ?? NULL,
    		$request->cDireccionBlock ?? NULL,
    		$request->cDireccionInterior ?? NULL,
    		$request->cDireccionPiso ?? NULL,
    		$request->cDireccionMz ?? NULL,
    		$request->cDireccionLt ?? NULL,
    		$request->cDireccionKm ?? NULL,
    		$request->cReferenciaDomicilio ?? NULL,
    		$request->cGoogleMaps ?? NULL,
    		$request->iHijos ?? NULL,
            $request->iEstadoCivil ?? NULL,
            $request->iColegioTipo ?? NULL,
            $request->cColegioNombre ?? NULL,
            $request->iDptoId ?? NULL,
            $request->iPrvnId ?? NULL,
            $request->iDsttId ?? NULL,
            $request->iPaisId ?? NULL,
            $request->iTipoVia ?? NULL,
            $request->cTipoViaOtros ?? NULL,
            $request->cEstudCorreo ?? NULL,
            $request->cEstudTelef ?? NULL,
            $request->cEstudEstCivil ?? NULL,
            $request->cEstudFechaNac ?? NULL
            
        ];
       //return $parametros;
        try {
            $actualizar = \DB::select('EXEC [dbu].[Sp_DBU_FICHA_UPD_DatosGeneralesxiEstudId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => $e, 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        
       // dd($response);

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarAspectoFamiliar(Request $request){
    	$parametros = [
    		$request->iEstudId,
    		$request->iPadreVivo ?? NULL,
    		$request->iMadreViva ?? NULL,
            $request->iPadresEstadoCivil ?? NULL,
    		$request->iPadresVivenJuntos ?? NULL,
    		$request->iResidePadre ?? NULL,
    		$request->iResideMadre ?? NULL,
    		$request->iResideHermanos ?? NULL,
    		$request->iResideConyuge ?? NULL,
    		$request->iResideHijos ?? NULL,
    		$request->iResideOtros ?? NULL,
    		$request->cResideOtros ?? NULL,
            $request->iDireccionPadreTipoVia ?? NULL,
            $request->cDireccionPadreTipoViaOtros ?? NULL,
    		$request->cDireccionPadreNombreVia ?? NULL,
    		$request->cDireccionPadreNumPuerta ?? NULL,
    		$request->cDireccionPadreBlock ?? NULL,
    		$request->cDireccionPadreInterior ?? NULL,
    		$request->cDireccionPadrePiso ?? NULL,
    		$request->cDireccionPadreMz ?? NULL,
    		$request->cDireccionPadreLt ?? NULL,
    		$request->cDireccionPadreKm ?? NULL,
    		$request->cReferenciaDomicilioPadre ?? NULL,
            $request->cTelefonoPadre ?? NULL,
            $request->cGoogleMapsPadre ?? NULL,
            $request->iDireccionMadreTipoVia ?? NULL,
    		$request->cDireccionMadreTipoViaOtros ?? NULL,
    		$request->cDireccionMadreNombreVia ?? NULL,
    		$request->cDireccionMadreNumPuerta ?? NULL,
    		$request->cDireccionMadreBlock ?? NULL,
    		$request->cDireccionMadreInterior ?? NULL,
    		$request->cDireccionMadrePiso ?? NULL,
    		$request->cDireccionMadreMz ?? NULL,
    		$request->cDireccionMadreLt ?? NULL,
    		$request->cDireccionMadreKm ?? NULL,
    		$request->cReferenciaDomicilioMadre ?? NULL,
            $request->cTelefonoMadre ?? NULL,
            $request->cGoogleMapsMadre ?? NULL,
            $request->cEmergenciaNombre ?? NULL,
            $request->cEmergenciaParentesco ?? NULL,
            $request->cEmergenciaTelefono ?? NULL
    		
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_AspectoFamiliarxiEstudId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarAspectoEconomico(Request $request){
    	$parametros = [
    		$request->iEstudId,
    		$request->iIngresoFamiliar ?? NULL,
    		$request->iIngresoFamiliarMonto ?? NULL,
    		$request->iDependeDe ?? NULL,
    		$request->cDependeDeOtros ?? NULL,
    		$request->iApoyo ?? NULL,
            $request->iActividadEconomica ?? NULL,
    		$request->iActividadEconomicaOcupacion ?? NULL,
    		$request->cActividadEconomicaOcupacion ?? NULL,
    		$request->iActividadEconomicaCIIU ?? NULL,
    		$request->iIngresoEstudiante ?? NULL,
    		$request->iIngresoEstudianteMonto ?? NULL,
    		$request->iLabor ?? NULL,
    		$request->iTrabajoInterfiere ?? NULL,
            $request->cTrabajoDsc ?? NULL,
    		$request->iTrabajoHoras ?? NULL
            
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_AspectoEconomicoxiEstudId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarAspectoVivienda(Request $request){
        $velocidad=0;
        if($request->iInternet==0){$velocidad=0;}
        else{$velocidad=$request->iVelocidadInternet;}
    	$parametros = [
    		$request->iEstudId,
    		$request->iVivienda ?? NULL,
    		$request->cViviendaOtros ?? NULL,
    		$request->iViviendaNumeroPisos ?? NULL,
    		$request->iViviendaEstado ?? NULL,
    		$request->cViviendaEstadoOtros ?? NULL,
    		$request->iViviendaParedes ?? NULL,
    		$request->cViviendaParedesOtros ?? NULL,
    		$request->iViviendaPisos ?? NULL,
    		$request->cViviendaPisosOtros ?? NULL,
    		$request->iViviendaTecho ?? NULL,
    		$request->cViviendaTechoOtros ?? NULL,
    		$request->iViviendaTipo ?? NULL,
    		$request->cViviendaTipoOtros ?? NULL,
    		$request->iViviendaAmbientes ?? NULL,
    		$request->iViviendaHabitacionesDormir ?? NULL,
    		$request->iViviendaAgua ?? NULL,
    		$request->cViviendaAguaOtros ?? NULL,
    		$request->iViviendaBano ?? NULL,
    		$request->cViviendaBanoOtros ?? NULL,
    		$request->iElectricidad ?? NULL,
    		$request->iMechero ?? NULL,
    		$request->iVela ?? NULL,
    		$request->iPanelSolar ?? NULL,
    		$request->iLuzOtros ?? NULL,
    		$request->cLuzOtrosDsc ?? NULL,
    		$request->iEquipoSonido ?? NULL,
    		$request->iTelevisor ?? NULL,
    		$request->iServicioCable ?? NULL,
    		$request->iRefri ?? NULL,
    		$request->iCocinaGas ?? NULL,
    		$request->iTeleFijo ?? NULL,
    		$request->iCelular ?? NULL,
    		$request->iPC ?? NULL,
    		$request->iLaptop ?? NULL,
            $request->iInternet ?? NULL,
            $velocidad,
    		$request->iTablet ?? NULL,
    		$request->iAuto ?? NULL,
    		$request->iMoto ?? NULL,
    		$request->iOtros ?? NULL,
    		$request->cOtrosDsc ?? NULL
           
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_AspectoViviendaxiEstudId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarAlimentacion(Request $request){
    	$parametros = [
    		$request->iEstudId,
    		$request->iAlimentosDesayuno ?? NULL,
    		$request->cAlimentosDesayunoOtros ?? NULL,
    		$request->iAlimentosAlmuerzo ?? NULL,
    		$request->cAlimentosAlmuerzoOtros ?? NULL,
    		$request->iAlimentosCena ?? NULL,
    		$request->cAlimentosCenaOtros ?? NULL,
    		$request->iComedorUso ?? NULL,
    		$request->cComedorSemestres ?? NULL
          
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_AlimentacionxiEstudId] ?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarDiscapacidad(Request $request){
    	$parametros = [
    		$request->iEstudId,
    		$request->iLimitacionMover ?? NULL,
    		$request->iLimitacionVer ?? NULL,
    		$request->iLimitacionHablar ?? NULL,
    		$request->iLimitacionOir ?? NULL,
    		$request->iLimitacionEntender ?? NULL,
    		$request->iLimitacionRelacion ?? NULL,
    		$request->iOMAPED ?? NULL,
    		$request->iCONADIS ?? NULL,
           
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_DiscapacidadxiEstudId] ?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarSalud(Request $request){
    	$parametros = [
    		$request->iEstudId,
    		$request->iAsma ?? NULL,
    		$request->iDiabetes ?? NULL,
    		$request->iEpilepsia ?? NULL,
    		$request->iArtritis ?? NULL,
    		$request->iReumatismo ?? NULL,
    		$request->iHipertension ?? NULL,
    		$request->iEstres ?? NULL,
    		$request->iMalestarOtros ?? NULL,
    		$request->cMalestarOtros ?? NULL,
            $request->iSaludSeguroESSALUD ?? NULL,
            $request->iSaludSeguroSPS ?? NULL,
            $request->iSaludSeguroEPS ?? NULL,
            $request->iSaludSeguroFFAAPoli ?? NULL,
            $request->iSaludSeguroSIS ?? NULL,
    		$request->iSaludSeguroOtros ?? NULL,
    		$request->cSeguroOtros ?? NULL,
            $request->iSeguroPagoESSALUD ?? NULL,
            $request->iSeguroPagoSPS ?? NULL,
            $request->iSeguroPagoEPS ?? NULL,
            $request->iSeguroPagoFFAAPoli ?? NULL,
            $request->iSeguroPagoSIS ?? NULL,
            $request->iSeguroPagoOtros ?? NULL,
            $request->iAlergiaMed ?? NULL,
            $request->cAlergiaMed ?? NULL,
            $request->iAlergiaAlim ?? NULL,
            $request->cAlergiaAlim ?? NULL,
            $request->iAlergiaOtros ?? NULL,
    		$request->cAlergiaOtros ?? NULL
            
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_SaludxiEstudId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarOtros(Request $request){
    	$parametros = [
            $request->iEstudId,
            $request->iFutbol ?? NULL,
            $request->iVoley ?? NULL,
            $request->iBasquet ?? NULL,
            $request->iNatacion ?? NULL,
            $request->iDeporteOtros ?? NULL,
            $request->cDeporteOtros ?? NULL,
            $request->iClubDeportivo ?? NULL,
            $request->cClubDeportivo ?? NULL,
            $request->iDanza ?? NULL,
            $request->iTeatro ?? NULL,
            $request->iMusica ?? NULL,
            $request->iArteOtros ?? NULL,
            $request->cArteOtros ?? NULL,
            $request->iClubArtistico ?? NULL,
            $request->cClubArtistico ?? NULL,
            $request->iReligion ?? NULL,
            $request->cReligionOtros ?? NULL,
            $request->iCine ?? NULL,
            $request->iLectura ?? NULL,
            $request->iEscucharMusica ?? NULL,
            $request->iVideojuegos ?? NULL,
            $request->iJuegosOnline ?? NULL,
            $request->iReunionesConAmigos ?? NULL,
            $request->iPasear ?? NULL,
            $request->iPasatiempoOtros ?? NULL,
            $request->cPasatiempoOtros ?? NULL,
            $request->iConsultaPsicologica ?? NULL,
            $request->cConsultaPsicologica ?? NULL,
            $request->iAcudePadre ?? NULL,
            $request->iAcudeMadre ?? NULL,
            $request->iAcudeHermanos ?? NULL,
            $request->iAcudeAmigos ?? NULL,
            $request->iAcudeTutor ?? NULL,
            $request->iAcudePsicologo ?? NULL,
            $request->iAcudeOtros ?? NULL,
            $request->cAcudeOtros ?? NULL,
            $request->iRelacionPadresFamiliares ?? NULL,
            $request->iIntelEmoc ?? NULL,
            $request->iHabSocEmoc ?? NULL,
            $request->iControlEmoc ?? NULL,
            $request->iResilencia ?? NULL,
            $request->iAutoestima ?? NULL,
            $request->iDesarrolloOtros ?? NULL,
            $request->cDesarrolloOtros ?? NULL,
            $request->iTransporte ?? NULL,
            $request->iGastoPasaje ?? NULL,
            $request->iUsoTransporteUNAM ?? NULL,
            $request->cUsoTransporteUNAM ?? NULL
           
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_OtrosxiEstudId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function FichaEditarFamiliarEstudiante(Request $request){
    	$parametros = [
    		$request->iParienteId,
    		$request->iParienteEdad ?? NULL,
    		$request->iParienteParentesco ?? NULL,
    		$request->cParienteParentesco ?? NULL,
    		$request->cParienteSexo ?? NULL,
    		$request->iParienteEstadoCivil ?? NULL,
    		$request->cParienteEstadoCivil ?? NULL,
    		$request->iParienteGradoInstruccion ?? NULL,
    		$request->cParienteGradoInstruccion ?? NULL,
    		$request->cParienteOcupacion ?? NULL,
    		$request->iPareinteOcupacionCIIU ?? NULL,
            $request->cParienteResidenciaActual ?? NULL,
            $request->cParienteDni ?? NULL,
            $request->cParienteNombresyApellidos ?? NULL,
            $request->iParienteColegio ?? NULL,
            $request->iParienteMalestar ?? NULL,
            $request->iParienteColegioBeca ?? NULL,
    		$request->iParienteOcupacion ?? NULL,
    	];
    	try {
            $actualizar = \DB::SELECT('EXEC [dbu].[Sp_DBU_FICHA_UPD_EditarFamiliarxiParienteId] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros );
            $response = ['validated' => true, 'mensaje' => 'Datos actualizados correctamente.', 'result' => $actualizar];
            $codeResponse = 200;

        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //return response()->json($response, $codeResponse);
        return response()->json(['res' => $response]);
    }
    public function EliminarFamiliarListaFicha($iEstudId,$iParienteId,$iFamiliaId){
    	try {
            $del = \DB::select('exec dbu.[Sp_DBU_FICHA_DEL_EliminarParientexiEstudIdxiParienteId] ?,?',array($iEstudId,$iParienteId));
            $newlist = \DB::select('exec dbu.Sp_DBU_FICHA_SEL_ListaFamiliaresxiFamiliaId ?', array($iFamiliaId));
            $response = ['validated' => true, 'mensaje' => 'Modificación realizada.'];
        } catch (\Exception $e) {
            $del =[];
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];
            $newlist = 0;
        }

        return response()->json(['del'=>$del, 'res' => $response, 'lista' => $newlist]);
    }
    public function ObtenerIDEstudiante($cEstudCodUniv){
        $sel = \DB::select('SELECT iEstudId FROM ura.estudiantes WHERE cEstudCodUniv = ?',array($cEstudCodUniv));
        return response()->json($sel);
    }
    public function ObtenerNombreCarrera($iCarreraId){
        $sel = \DB::select('SELECT cCarreraDsc FROM ura.carreras WHERE iCarreraId = ?',array($iCarreraId));
        return response()->json($sel);
    }
    public function ListaPaises(){
        $sel = \DB::select('SELECT * FROM grl.paises');
        return response()->json($sel);
    }
    public function ListaDepartamentosPeru(){
        $sel = \DB::select('SELECT * FROM grl.departamentos');
        return response()->json($sel);
    }
    public function ListaProvinciasxDepartamento($iDptoId){
        $sel = \DB::select('SELECT * FROM grl.provincias where iDptoId = ?',array($iDptoId));
        return response()->json($sel);
    }
    public function ListaDistritosxProvincia($iPrvnId){
        $sel = \DB::select('SELECT * FROM grl.distritos where iPrvnId = ?',array($iPrvnId));
        return response()->json($sel);
    }
    public function descargaFicha(){
        $pdf = \PDF::loadView('dbu.fichasocioeconomica');
        return $pdf->stream();
    }
    public function descargaFichaDatos($iEstudId){
        $getficha = \DB::select('exec dbu.Sp_DBU_FICHA_SEL_LeerFichaxiEstudId ?', array($iEstudId));
        $ficha = $getficha[0];
        $getfamiliares = \DB::select('exec dbu.Sp_DBU_FICHA_SEL_ListaFamiliaresxiFamiliaId ?', array($ficha->iFamiliaId));
        $familiares = $getfamiliares;
        $getpais = \DB::select('SELECT cPaisNombre FROM grl.paises where iPaisId = ?',array($ficha->iPaisId));
        $getdepartamento = \DB::select('SELECT cDptoNombre FROM grl.departamentos where iDptoId = ?',array($ficha->iDptoId));
        $getprovincia = \DB::select('SELECT cPrvnNombre FROM grl.provincias where iPrvnId = ?',array($ficha->iPrvnId));
        $getdistrito = \DB::select('SELECT cDsttNombre FROM grl.distritos where iDsttId = ?',array($ficha->iDsttId));
        $getpais = json_decode(json_encode($getpais));
        if(isset($getpais[0])){
            $pais = $getpais[0]->cPaisNombre;
        }else{
            $pais = "No Especificado";
        }
        if(isset($getdepartamento[0])){
            $departamento = $getdepartamento[0]->cDptoNombre;
        }else{
            $departamento = "No Especificado";
        }
        if(isset($getprovincia[0])){
            $provincia = $getprovincia[0]->cPrvnNombre;
        }else{
            $provincia = "No Especificado";
        }
        if(isset($getdistrito[0])){
            $distrito = $getdistrito[0]->cDsttNombre;
        }else{
            $distrito = "No Especificado";
        }
        return response()->json(array($ficha,$familiares,$pais,$departamento,$provincia,$distrito));
    }
    public function GetSemestres(){
        $data = \DB::select('SELECT iControlCicloAcad FROM ura.controles');
        return $data;
    }
    
    public function obternerDatosFS(Request $request){
        
        //$nFS=json_encode($request->all());
        $x=0;
        foreach ($request->estudiantes as $estudiante) { 
            
            

            //$fs = $est[0]->iEstudId; OBTENER ID DE ESTUDIANTE
            
            $nfs[$x] = \DB::table('dbu.fichasoc_datosgenerales')
            ->where('iEstudId',   $estudiante['iEstudId'])
            ->count();
            $x++;
        }

    

        return Response::json(['nFS' => $nfs ]);

    }
    public function GetFichas(Request $request){
        $parametros = [
            $request->iEstudId
        ];

        $data = \DB::select('SELECT iFichaSocId, iEstudId, iControlCicloAcad FROM dbu.fichasoc_cabecera WHERE iEstudId = ?',$parametros);
        return $data;
    }

    public function LeerFichaSocioeconomicaXciclo($iEstudId,$iControlCicloAcad){

        $ficha = \DB::select('exec dbu.Sp_DBU_FICHA_SEL_LeerFichaxiEstudIdxCicloAcad ?,?', array($iEstudId,$iControlCicloAcad));

        return response()->json( $ficha );

    }

    public function verficha($iEstudId){

        $VerFicha =  \DB::table('dbu.fichasoc_cabecera')->where('iEstudId',  $iEstudId)->count();

        return response()->json( $VerFicha );

    }
}
