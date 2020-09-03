<?php

namespace App\Http\Controllers\Tram;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class TramiteOnController extends Controller
{
    public function __construct()
    {
        setlocale(LC_TIME,'Spanish');
    }
    public function conceptosTramites(Request $request)
    {
        $tramites =
            DB::select(
                'EXEC [grl].[Sp_SEL_conceptos_tramites_estudiantesXiEntIdXcEstudCodUniv] ?,?',
                [$request->iEntId, $request->cEstudCodUniv]

            );
        return response()->json($tramites, 200);
    }

    public function conceptosImportes(Request $request)
    {
        $tramites =
            DB::select(
                'EXEC grl.Sp_SEL_Conceptos_importes_estudiantesXiConcepIdXiCantidad ?,?',
                [$request->iConcepId,$request->iCantidad]

            );
        return response()->json($tramites, 200);
    }

    public function listTramite(Request $request)
    {
        $tramites =
            DB::select(
                'EXEC tram.Sp_SEL_tramitesXcEstudCodUniv ?',
                [$request->cEstudCodUniv]

            );
        return response()->json($tramites, 200);
    }

    public function tramitesEstudCodUniv(Request $request) {
        $adjunto = $request->cTramAdjuntarArchivo;
        /*
        if ($request->get('fotografia') == '') {
            $uploadedFile = $request->file('fotografia');
            $filename = time().'-'.$uploadedFile->getClientOriginalName();

            $archivoFoto =Storage::disk('public')->putFileAs(
                'certEstudios/fotos',
                $uploadedFile,
                $filename
            );
            $adjunto = $archivoFoto;
            // return $archivoFoto;
        }
        */
        // dd($request->fotografia);
        // return response()->json(Storage::url('files.asd'));
        if (preg_match('/^data:image\/(\w+);base64,/', $adjunto)) {
            $data = substr($adjunto, strpos($adjunto, ',') + 1);
            $filename = $request->cEstudCodUniv.'-'.time().'.jpg';
            $data = base64_decode($data);
            $filePath = 'certEstudios/fotos/' . $filename;
            Storage::disk('public')->put($filePath, $data);
            /*$archivoFoto =Storage::disk('public')->putFileAs(
                'certEstudios/fotos',
                $data,
                $filename
            );*/
            $adjunto = $filePath;
            // dd("stored");
        }

        /*
        if (strlen($adjunto) > 50) {
            $filename = time().'.jpg';
            $archivoFoto =Storage::disk('public')->putFileAs(
                'certEstudios/fotos',
                base64_decode($adjunto),
                $filename
            );
            dd($archivoFoto);
            // $adjunto = $archivoFoto;
        }*/

        //dd($adjunto);

        $this->validate(
            $request,
            [
                'cEstudCodUniv' => 'required',
                'iConcepId' => 'required',
                'iCantidad' => 'required',
                // 'fotografia' => 'image|mimes:jpeg,jpg|max:1024|dimensions:min_width=378,min_height=508,ratio=0,74',

            ],
            [
                'cEstudCodUniv.required' => 'Debe seleccionar un aula.',
                'iConcepId.required' => 'Hubo un problema al verificar la carrera.',
                'iCantidad.required' => 'Hubo un problema al verificar la filial.',
/*
                'fotografia.image' => 'El archivo adjunto debe ser una imagen',
                'fotografia.mimes' => 'El archivo adjunto debe ser formato JPG o JPEG',
                'fotografia.dimensions' => 'La imagen adjunta debe ser de tamaño 378 x 508 px.',
                'fotografia.ratio' => 'La imagen adjunta debe ser de tamaño 378 x 508 px.',
*/

            ]
        );
        $parametros = [
            $request->cEstudCodUniv,
            $request->iConcepId,
            $request->iTipoDocEstudId,
            $request->iCantidad,
            $request->cTramContenido,
            // $request->cTramAdjuntarArchivo,
            $adjunto,
            $request->iFilId,
            $request->cTramObservaciones,

            $request->cEquipoSis,
            $request->cIpSis,
            $request->cMacNicSis
        ];

        // return response()->json($parametros);


        try {
            $tramites =
            DB::select(
                // "EXEC tram.Sp_INS_tramitesXcEstudCodUniv '2010204017',10030,7,3,'','RUTA DE ARCHIVO',1,'OBSERVACIONES','','',''",
                'EXEC tram.Sp_INS_tramitesXcEstudCodUniv ?,?,?,?,?,?,?,?,       ?,?,?',
                $parametros
            );

            if ($tramites[0]->iResult > 0) {
                $response = ['validated' => true, 'mensaje' => 'El registro se guardo correctamene.'];
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
            }
        } catch (\Exception $e) {
            $response = [
                        'error' => true,
                        'errorLaravel' => $e->getMessage(),
                        'data' => null
                    ];
        }

        return response()->json($response);
    }

    public function deleteTramite($iTramId)
    {
        $dataTramite = DB::select('EXEC tram.Sp_SEL_tramitesXiTramId ?', [$iTramId]);
        $filePath = $dataTramite[0]->cTramAdjuntarArchivo;
        Storage::delete($filePath);

        $tramites = DB::select(
                'tram.Sp_DEL_tramites_estudiantesXiTramId ?',
                ["{$iTramId}"]
            );

        return response()->json($tramites);
    }

    public function seguimientoTraminte($iTramId)
    {
        $tramites =
            DB::select(
                'EXEC tram.Sp_SEL_tramites_movimientosXiTramId ?',
                [$iTramId]

            );
        return response()->json($tramites);
    }

    public function documentosEstudiantesDASA($id)
    {
        $tramites =
            DB::select(
                'EXEC tram.Sp_SEL_documentos_estudiantes_DASAXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?,?',
                array(1,$id,'',0,'',0,0,'','',0)
            );
        return response()->json($tramites);
    }
    public function requisitosBusqueda()
    {
        $tipoBus = DB::select( 'EXEC tram.Sp_SEL_Criterio_Busqueda_Tramites');
        $periodos = DB::select( 'EXEC tram.Sp_SEL_tramites_iTramYearDocumento');
        return response()->json( [ 'periodos' => $periodos, 'tipos' => $tipoBus ] );
    }
    public function requisitosBusquedaDependencias($dep)
    {
        $dependencias = DB::select( 'EXEC tram.Sp_SEL_dependenciasXiTramYearDocumento ?',
        array( $dep ) );
        return response()->json( $dependencias );
    }
    public function busquedaDocOp1(Request $request)
    {
        if(!$request->texto){
            $request->texto = '';
        }
        $resultado =
            DB::select(
                'EXEC tram.Sp_SEL_tramitesXiDepenEmisorIdXcConsultaVariablesCampos ?,?,?,?,?,?,?,?,?',
                array( $request->dependencia,'',0,0,'','',$request->periodo,$request->tipo,$request->texto)
            );
        return response()->json( $resultado );
    }
    public function detalleSeguimiento2($idTram)
    {
        $resultado =
            DB::select(
                'EXEC tram.Sp_SEL_Tramites_Referencias_SeguimientoXiTramId ?',
                array($idTram)
            );
        return response()->json( $resultado );
    }

    public static function detalleSeguimiento($idTram, $interno = false){
        $resultado = DB::select('EXEC tram.Sp_SEL_Tramites_Referencias_SeguimientoXiTramId ?', array($idTram));

        if (count($resultado) > 0) {
            $header = null;
            $fecIni = null;
            $fecMay = null;
            foreach ($resultado as $res) {

                // return response()->json($res);

                if (is_null($fecIni)) {
                    $fecIni = $res->dtTramMovFechaHoraEnvio;
                }

                if ($res->bPrincipal == 1){
                    $header = $res;
                }

                $res->cEstadoTramiteNombre = explode("#",  $res->cEstadoTramiteNombre)[1];

                if (!is_null($res->dtTramMovFechaHoraRecibido)) {
                    // $res->date = Date::parse($res->dtTramMovFechaHoraRecibido)->formatLocalized('%Y/%m/%d  %R');
                    $res->date = Date::parse($res->dtTramMovFechaHoraRecibido)->format('d/m/Y H:i');
                } else {
                    $res->data = '-';
                }

                $res->ttr = '-';

                if (!is_null($res->dtTramMovFechaHoraEnvio) && !is_null($res->dtTramMovFechaHoraRecibido)) {
                    $res->ttr = Date::parse($res->dtTramMovFechaHoraEnvio)->diffForHumans(Date::parse($res->dtTramMovFechaHoraRecibido), true, false, 3); //self::calculaTime($res->dtTramMovFechaHoraEnvio, $res->dtTramMovFechaHoraRecibido);
                }

                if (!is_null($res->dtTramMovFechaHoraRecibido)) {
                    if (is_null($fecMay)) {
                        // $fecMay = Date::parse($res->dtTramMovFechaHoraRecibido);
                        $fecMay = $res->dtTramMovFechaHoraRecibido;
                    }
                    else {
                        if ( Date::parse($res->dtTramMovFechaHoraRecibido)->greaterThan( Date::parse($fecMay) ) ) {
                            $fecMay = $res->dtTramMovFechaHoraRecibido;
                        }
                    }
                }

                /*
                if ( !is_null($header) && !is_null($res->dtTramMovFechaHoraRecibido)){
                    $res->ttr = self::calculaTime($header->dtTramFechaDocumento,$res->dtTramMovFechaHoraRecibido);
                }*/
            }

            $totalDias = '-';
            if (!is_null($fecMay)) {
                $totalDias = Date::parse($fecIni)->diffForHumans(Date::parse($fecMay), true, false, 3);
            }

            $headerDate = '-';
            if(!$header->dtTramFechaDocumento || $header->dtTramFechaDocumento != null){
                // $headerDate = Date::parse($header->dtTramFechaDocumento)->formatLocalized('%A %d %B %Y ');
                $headerDate = Date::parse($header->dtTramFechaDocumento)->format('d \d\e F Y');
            }


            $nlast = count($resultado);
            $ultimaDep =  $resultado[$nlast - 1]->cDepenReceptorNombre;
            $ultimaHora =  $resultado[$nlast - 1]->cDepenReceptorNombre;


            /*
            if(count($resultado) > 0){
                if( !is_null($resultado[0]->dtTramMovFechaHoraRecibido) ){
                    $totalDias = self::calculaTime($header->dtTramFechaDocumento, $resultado[0]->dtTramMovFechaHoraRecibido);
                }
                if( !is_null($resultado[$nlast - 1]->dtTramMovFechaHoraRecibido) ){
                    $totalDias = self::calculaTime($header->dtTramFechaDocumento, $resultado[$nlast - 1]->dtTramMovFechaHoraRecibido);
                }
            }
            */

            if(!is_string($headerDate)){
                $headerDate = '-';
            }

            $retData = ['data'=>$resultado,'header'=>$header,'ultimaDep'=>$ultimaDep, 'totalDias'=>$totalDias,'headerDate'=> utf8_encode($headerDate), 'f' => [$fecIni, $fecMay] ];

        }
        else {
            $retData = [];
        }


        if ($interno) {
            return $retData;
        }

        return response()->json($retData);



        /*
            $header = [];
            for ($i=0; $i < count($resultado) ; $i++) {

                if($resultado[$i]->bPrincipal == 1){
                    $header = $resultado[$i];
                }

                $estadoTemp = '';
                $estadoTemp = explode("#",  $resultado[$i]->cEstadoTramiteNombre);;
                $resultado[$i]->cEstadoTramiteNombre = $estadoTemp[1];

                if($resultado[$i]->dtTramMovFechaHoraRecibido != null ){
                    $resultado[$i]->date = Carbon::parse($resultado[$i]->dtTramMovFechaHoraRecibido)->formatLocalized('%Y/%m/%d  %R');
                }else{
                    $resultado[$i]->date = '-';
                }

                $resultado[$i]->ttr = '-';
                if(count($resultado) > 0){
                    if($resultado[$i]->dtTramMovFechaHoraRecibido != null){
                        $resultado[$i]->ttr = self::calculaTime($header->dtTramFechaDocumento,$resultado[$i]->dtTramMovFechaHoraRecibido);
                    }else{
                        $resultado[$i]->ttr = '-';
                    }

                }
            }
            if(!$header->dtTramFechaDocumento || $header->dtTramFechaDocumento != null){
                $headerDate = Carbon::parse($header->dtTramFechaDocumento)->formatLocalized('%A %d %B %Y ');
            }else{
                $headerDate = '-';
            }


            $nlast = count($resultado);
            $ultimaDep =  $resultado[$nlast - 1]->cDepenReceptorNombre;
            $ultimaHora =  $resultado[$nlast - 1]->cDepenReceptorNombre;

            if($resultado > 0){
                if($resultado[$nlast - 1]->dtTramMovFechaHoraRecibido != null){
                    $totalDias = self::calculaTime($header->dtTramFechaDocumento, $resultado[$nlast - 1]->dtTramMovFechaHoraRecibido);
                }else{
                    $totalDias = '-';
                }
            }else{
                if($resultado[0]->dtTramMovFechaHoraRecibido != null){
                    $totalDias = self::calculaTime($header->dtTramFechaDocumento, $resultado[0]->dtTramMovFechaHoraRecibido);
                }else{
                    $totalDias = '-';
                }
            }
            if(is_string($headerDate) == false){
                $headerDate = '-';
            }

            */

    }
    public static function calculaTime($time1, $time2)
    {
        $totalDataDias =
            DB::select( 'EXEC grl.Sp_DiasHabilesXiEntIdXdfecha_iniXdFecha_fin 1,?,? ',
                 array( Date::parse($time1)->formatLocalized('%Y%m%d  %R'), Date::parse($time2)->formatLocalized('%Y%m%d  %R'))
            );
        $totalDias = $totalDataDias[0]-> nDiasHabiles;
        $separeDate = explode(".", $totalDias);
        $decimalDays = '0.'.$separeDate[1];
        $decimalDays = floatval($decimalDays);
        $decimalDays = $decimalDays * 24;
        $tiempoResult =  intval($separeDate[0]). ' Dias, '. intval($decimalDays). ' Horas.';

        return $tiempoResult;
    }
    public function ListaArchivador(Request $request)
    {
        $resultado = [];

        if($request->tipo == 1){
            $resultado =
                DB::select('EXEC tram.Sp_SEL_tramites_archivadosXiDepenIdXcConsultaVariablesCampos ?,?,?,?,?,?',
                 array($request->dp,$request->dia,'','','','')
                );
        }
        if($request->tipo == 2){
            $resultado =
                DB::select('EXEC tram.Sp_SEL_tramites_archivadosXiDepenIdXcConsultaVariablesCampos ?,?,?,?,?,?',
                 array($request->dp,'',$request->periodo, $request->mes,'','')
                );
        }
        if($request->tipo == 3){
            $resultado =
                DB::select( 'EXEC tram.Sp_SEL_tramites_archivadosXiDepenIdXcConsultaVariablesCampos ?,?,?,?,?,?',
                 array($request->dp,'','','',$request->rango1,$request->rango2)
                );
        }
        return response()->json( $resultado );
    }
    public function ListaGeneral(Request $request)
    {
        $resultado = [];
        // return response()->json( $request );
        if(!$request->text){
            $request->text = '';
        }
        $resultado =
            DB::select('EXEC tram.Sp_SEL_tramitesXiEntIdXcConsultaVariablesCampos ?, ?, ?, ?, ?, ?, ?, ?, ?',
                array(1,'',0,0,'','',$request->periodo,$request->param,$request->text)
            );
        return response()->json( $resultado );
    }
}
