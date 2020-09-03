<?php

namespace App\Http\Controllers\RecursosHumanos;
use App\ClasesLibres\Generales\UtilControladores;
use App\Http\Controllers\Generales\RespuestasApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function foo\func;


class ContratosController extends Controller
{

//
    public function getData(Request $request, $tipos) {

        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));
        $arrTipos = explode('&', $tipos);
        $respuestas = [];

        foreach ($arrTipos as $tipo){
            $prFT = explode('.', $tipo);
            $datPrefix = count($prFT);
            if ($datPrefix > 1) {
                switch ($prFT[0]) {
                    case 'grl':
                        $respuesta = app(RespuestasApiController::class)->getData($request, $prFT[1], true);
                        break;
                }
            }
            else {

                switch ($tipo) {

                    case 'tipo_identificacion':
                        $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Identificaciones');
                        break;
                    case 'categorias':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_categorias');
                        break;
                    case 'conceptos':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_conceptos');
                        break;
                    case 'fases_convocatorias':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_fases_convocatorias');
                        break;

                    case 'convocatorias_detalles':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_convocatorias_detalles');
                        break;
                    case 'tipo_estado':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_estados_contratos');
                        break;
                    case 'tipo_contrato':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_contratos');
                        break;
                    case 'tipo_prestador':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_prestadoresXiTipoPrestadorId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_prestadoresXcTipoPrestadorNombre ?', ['%%']);
                        }
                        break;

                    case 'tipo_convocatorias':
                        if (is_numeric($data)){
                            $respuesta = DB::select('rhh.Sp_SEL_convocatoriasXiConvocatoriaId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('rhh.Sp_SEL_convocatoriasXcConvNombre ?', ['%%']);
                        }
                        break;
                    case 'tipo_planillas':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_planillasXiTipoPlanillaId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_planillas');
                        }
                        break;
                    case 'estados':
                        if (is_numeric($data)){
                            $respuesta = DB::select('rhh.Sp_SEL_estados_ConvocatoriasXiEstConvId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('rhh.Sp_SEL_estados_Convocatorias');
                        }
                        break;
                    case 'carreras':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiDepenId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiEntIdXcDepenNombre 1, "Escuela profesional "');
                        }
                        break;
                    case 'dependencias':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiEntIdXcDepenNombre 1, ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiDepenId');
                        }
                        break;
                    case 'oficinas':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiDepenId', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC grl.Sp_SEL_dependenciasXiEntIdXcDepenNombre 1, " "');
                        }
                        break;
                    case 'dedicacion':
                        if (is_numeric($data)){
                            $respuesta = DB::select('rhh.Sp_SEL_tipos_dedicacionesXiTipoDedicaId', [$data]);
                        }
                        else {
                            $respuesta = DB::select('exec rhh.Sp_SEL_tipos_dedicaciones');
                        }
                        break;
                    case 'filiales':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_filialesXiFilId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_filiales');
                        }
                        break;

                    // SECCION PARA BASE DE CALCULO PARA SPP

                    //FIN SECCION MANTENIMIENTO


                    //  SECCION DE PRESTADOR
                    case 'convocatorias':
                 //       if (isset($dataObj->idPrestador)) {
                 //           $respuesta = collect(DB::select('rhh.Sp_SEL_prestadoresXiPrestadorId ?', [$dataObj->idPrestador]));
                 //       }
                 //       else {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_ConvocatoriasXcCampoBusqueda ?, ?, ?', [

                                $dataObj->datBusqueda??'%%',
                                $dataObj->pagina??null,
                                $dataObj->numItems??null
                            ]));
                //        }
                        break;

                    case 'lista_contratos':

                        $respuesta = collect(DB::select('rhh.Sp_SEL_contratosXcCampoBusqueda ?, ?, ?', [

                            $dataObj->datBusqueda??'%%',
                            $dataObj->pagina??null,
                            $dataObj->numItems??null
                        ]));

                        break;

                    case 'postulantes_fases':

                        $respuesta = collect(DB::select('rhh.Sp_SEL_postulantes_convocatorias_fases_XcCampoBusqueda ?,?,?, ?, ?', [

                            $dataObj->datBusqueda??'%%',
                            $dataObj->cConvNombre??null,
                            $dataObj->cFaseConvNombre??null,
                            $dataObj->pagina??null,
                            $dataObj->numItems??null
                        ]));

                        break;

                    case 'lista_regimenes':

                        $respuesta = collect(DB::select('rhh.Sp_SEL_regimenesXcCampoBusqueda ?, ?, ?', [

                            $dataObj->datBusqueda??'%%',
                            $dataObj->pagina??null,
                            $dataObj->numItems??null
                        ]));

                        break;
                    case 'postulantes':
                        //       if (isset($dataObj->idPrestador)) {
                        //           $respuesta = collect(DB::select('rhh.Sp_SEL_prestadoresXiPrestadorId ?', [$dataObj->idPrestador]));
                        //       }
                        //       else {
                        $respuesta = collect(DB::select('rhh.Sp_SEL_postulantesXcCampoBusqueda ?, ?, ?', [

                            $dataObj->datBusqueda??'%%',
                            $dataObj->pagina??null,
                            $dataObj->numItems??null
                        ]));
                        //        }
                        break;

                    case 'postulantes_dni':
                        $respuesta = collect(DB::select('rhh.Sp_SEL_personasXcPersDocumento ? ', [

                            $dataObj->datBusqueda??'%%',

                        ]));

                        break;

                    case 'tipo_condicion':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_condiciones_laboralesXiCondLab ?', [$dataObj->id]));
                        }
                        else {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_condiciones_laborales'));
                        }
                        break;
                    case 'cargos':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_cargosXcCargosNombre ?', [$dataObj->txtBuscar??'%%']);
                        break;


                    // FIN SECCION DE PRESTADOR

                    case 'fases':
                        if (isset($dataObj->id)){
                          //  $respuesta = collect(DB::select('exec rhh.Sp_SEL_fases_convocatoriasXiConvocatoriaId ?', [$dataObj->id]));
                        }
                        if (isset($dataObj->idConvocatoria)) {
                            $respuesta = collect(DB::select('exec rhh.Sp_SEL_fases_convocatoriasXiConvocatoriaId ?', [$dataObj->idConvocatoria]));
                        }
                        //$respuesta = collect(DB::select('exec rhh.Sp_SEL_fases_convocatoriasXiConvocatoriaId 8'));
                        break;

                    case 'archivos':
                        if (isset($dataObj->id)){
                            //  $respuesta = collect(DB::select('exec rhh.Sp_SEL_fases_convocatoriasXiConvocatoriaId ?', [$dataObj->id]));
                        }
                        if (isset($dataObj->idConvocatoria)) {
                            $respuesta = collect(DB::select('EXEC rhh.Sp_SEL_convocatoria_detallesXiConvocatoriaId ?', [$dataObj->idConvocatoria]));
                        }
                        //$respuesta = collect(DB::select('exec rhh.Sp_SEL_fases_convocatoriasXiConvocatoriaId 8'));
                        break;

                    case 'cabecera_archivos':
                        if (isset($dataObj->idConvocatoria)) {
                            $respuesta = collect(DB::select('EXEC rhh.Sp_SEL_cabecera_convocatoria_detallesXiConvocatoriaId  ?', [$dataObj->idConvocatoria]));
                        }

                        break;

                    case 'convocatorias_page':
                        if (isset($dataObj->idTipoPlanilla)) {
                            $respuesta = collect(DB::select('EXEC rhh.Sp_SEL_convocatoriasXiTipoPlanillaId  ?', [$dataObj->idTipoPlanilla]));
                        }
                        break;
                    // INICIO TAREOS
                    // FIN TAREOS
                    // NO USADO


                }
            }
            $respuestas[$tipo] = $respuesta;
        }
        return $this->retornoJson($respuestas);
        /*
        if (count($arrTipos) > 1){
            return $this->retornoJson($respuestas);
        }
        */
        return $this->retornoJson($respuesta);
    }



    public function setData(Request $request, $tipo, $subtipo = null) {
        $data =  $request->get('data') ;
        $dataObj = json_decode(json_encode($data));

        $jsonResponse = [];
        DB::beginTransaction();
        try {
            switch ($tipo) {
                case 'mantenimiento':
                    switch ($subtipo){

                        case 'convocatorias':
                            $paramsD = [
                                $dataObj->nombreConvocatoria??null,
                                $dataObj->idFilial??null,
                                $dataObj->idDependencia??null,
                                $dataObj->idTipoPlanilla??null,
                                $dataObj->idCategoria??null,
                                $dataObj->idDedicacion??null,
                                $dataObj->idTipoPrestador??null,
                                $dataObj->idCargo??null,
                                $dataObj->idEstado??null,
                            ];

                                // $arrParamsIns = $paramsD;
                            $arrParamsIns = array_merge([], $paramsD);
                            $arrParamsUpd = array_merge([$dataObj->idConvocatoria??null], $paramsD);

                            $jsonResponse = $this->respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idConvocatoria',
                                [
                                    'ins' => ['proc' => 'rhh.Sp_INS_convocatorias', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'exec rhh.Sp_UPD_convocatorias', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'exec rhh.Sp_DEL_convocatorias', 'params' => $dataObj],
                                ]
                            ));
                            break;
                        case 'postulantes':
                            $paramsD = [
                                $dataObj->idPersona??null,
                                $dataObj->idTipoConvocatoria??null,
                                $dataObj->idFases??null,

                            ];

                            // $arrParamsIns = $paramsD;
                            $arrParamsIns = array_merge([], $paramsD);
                            $arrParamsUpd = array_merge([$dataObj->idPostulante??null], $paramsD);

                            $jsonResponse = $this->respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idPostulante',
                                [
                                    'ins' => ['proc' => 'rhh.Sp_INS_postulantes', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'rhh.Sp_UPD_postulantes', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'rhh.Sp_DEL_postulantes', 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'personas':
                            $paramsD = [
                                $dataObj->tipoPersona??null,
                                $dataObj->tipoIdentificacion??null,
                                $dataObj->numeroDocumento??null,
                                $dataObj->apellidoPaterno??null,
                                $dataObj->apellidoMaterno??null,
                                $dataObj->nombresCompletos??null,
                                $dataObj->sexo??null,
                                $dataObj->fechaNacimiento??null,
                                $dataObj->razonSocialNombre??null,
                                $dataObj->RazonSocialCorto??null,
                                $dataObj->RazonSocialSigla??null,
                                $dataObj->RepresentateLegal??null,


                            ];

                            // $arrParamsIns = $paramsD;
                            $arrParamsIns = array_merge([], $paramsD);
                            $arrParamsUpd = array_merge([], $paramsD);

                            $jsonResponse = $this->respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idPersona',
                                [
                                    'ins' => ['proc' => 'grl.Sp_INS_personas', 'params' => $arrParamsIns],
                                   'upd' => ['', 'params' => $arrParamsUpd],
                                   'del' => ['', 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'contratos':
                            $paramsD = [
                                $dataObj->numeroContrato??null,
                                $dataObj->idFilial??null,
                                $dataObj->idTipoPlanilla??null,
                                $dataObj->idTipoPrestador??null,
                                $dataObj->idTipoContrato??null,
                                $dataObj->idDependencia??null,
                                $dataObj->idCargo??null,
                                $dataObj->idCategoria??null,
                                $dataObj->idTipoDedicacion??null,
                                $dataObj->idTipoCondicion??null,
                                $dataObj->fechaInicio??null,
                                $dataObj->fechaFin??null,
                                $dataObj->numeroResolucion??null,
                                $dataObj-> idTipoEstado??null,

                            ];

                            // $arrParamsIns = $paramsD;
                            $arrParamsIns = array_merge($paramsD,[ $dataObj->idPersona??null]);
                            $arrParamsUpd = array_merge([$dataObj->idContrato??null], $paramsD);

                            $jsonResponse = $this->respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idContrato',
                                [
                                    'ins' => ['proc' => 'rhh.Sp_INS_contratos', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'rhh.Sp_UPD_contratos', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'rhh.Sp_DEL_contratos', 'params' => $dataObj],
                                ]
                            ));
                            break;
                        case 'regimenes':
                            $paramsD = [
                                $dataObj->nombreRegimen??null,
                                $dataObj->abreviaturaRegimen??null,

                            ];

                            // $arrParamsIns = $paramsD;
                            $arrParamsIns = array_merge([ $dataObj->numeroRegimen??null], $paramsD);
                            $arrParamsUpd = array_merge([$dataObj->idRegimen??null], $paramsD);

                            $jsonResponse = $this->respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idRegimen',
                                [
                                    'ins' => ['proc' => 'rhh.Sp_INS_regimenes', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'rhh.Sp_UPD_regimenes', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'rhh.Sp_DEL_regimenes', 'params' => $dataObj],
                                ]
                            ));
                            break;


                        case 'fases':
                            $patronProc = 'rhh.Sp_###_fases_convocatorias';
                            $paramsD = [
                                $dataObj->idFases??null,

                            ];

                            $arrParamsIns = array_merge($paramsD,[$dataObj->idConvocatoriaFase??null]);

                            $arrParamsUpd = array_merge([$dataObj->iFaseId??null], $paramsD);

                            $jsonResponse = $this->respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'iFaseId',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'convocatoria_detalles':

                            $datGuardar = null;
                            $arrayArchivos = request()->archivo??null;
                            if (is_array($arrayArchivos)){
                                $dataGuardar = UtilControladores::moverArchivo($arrayArchivos, 'RecursosHumanos/convocatorias/');
                            }

                            $patronProc = 'rhh.Sp_###_convocatoria_detalles';
                            $dataNueva = [
                                request()->idConvocatoria??null,
                                request()->idFases??null,
                                request()->nombreArchivo??null,
                                $dataGuardar,
                                request()->descripcionArchivo??null
                            ];

                            $arrParamsIns = array_merge([],$dataNueva);

                            $arrParamsUpd = array_merge([request()->iConvDetId??null], $dataNueva);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimientoReq(
                                'iConvDetId',
                                [str_replace('###', 'INS',$patronProc), $arrParamsIns],
                                [str_replace('###', 'UPD',$patronProc), $arrParamsUpd],
                                str_replace('###', 'DEL',$patronProc)
                            ));
                            break;

                    }
                    break;



                case 'tipoplanillas_tipomotivos':

                    foreach ($dataObj->idsMotivosPlanillas as $idxAct => $dAct) {
                        foreach ($dataObj->idsMotivosPlanillasOrig as $idxAnt => $dAnt) {
                            if ($dAct->iMotPlanId == $dAnt->iMotPlanId) {
                                unset($dataObj->idsMotivosPlanillas[$idxAct]);
                                unset($dataObj->idsMotivosPlanillasOrig[$idxAnt]);
                            }
                        }
                    }

                    $idsEliminar = [];
                    // ELIMINAR
                    foreach ($dataObj->idsMotivosPlanillasOrig as $idxE => $dElim) {
                        $idsEliminar[] = [$dElim->iTipoMotPlanId, $idxE];
                    }

                    // AGREGAR
                    foreach ($dataObj->idsMotivosPlanillas as $dDif) {
                        $dataGuardar = [
                            1,
                            $dataObj->idTipoPlanilla,
                            $dDif->iMotPlanId,

                            auth()->user()->iCredId,
                            null,
                            $request->getClientIp(),
                            null,
                        ];
                        $rpt = DB::select('EXEC rhh.Sp_INS_tipos_motivos_planillas ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                        $datRetArray[] = $rpt;
                    }

                    foreach ($dataObj->idsMotivosPlanillasOrig as $idxE => $dElim) {
                        $rpt = DB::select('EXEC rhh.Sp_DEL_tipos_motivos_planillas ?', [$dElim->iTipoMotPlanId]);
                        $datRetArray[] = $rpt;
                    }


                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => [
                            'anterior' => $dataObj->idsMotivosPlanillasOrig,
                            'actual' =>$dataObj->idsMotivosPlanillas,
                            'res' => $datRetArray,
                        ]
                    ];

                    // return $this->retornoJson($jsonResponse);
                    // return $this->retornoJson($dataObj);
                    break;

            }
            DB::commit();
        }
        catch (\Exception $e) {
            $jsonResponse = $this->returnError($e);
            DB::rollback();
        }
        return $this->retornoJson($jsonResponse);
    }



    private function consultasSimples($dataObj, $procConId, $procSinId) {
        if (isset($dataObj->id)){
            $respuesta = DB::select($procConId, [$dataObj->id]);
        }
        else {
            $respuesta = DB::select($procSinId);
        }
        return $respuesta;
    }

    private function respuestasSimple($tipo, $respuesta, $multiple = false){

        if ($multiple) {
            $todoOK = true;
            foreach ($respuesta as $rpt) {
                // abort(503, 'Error: Error de Procedimiento ('.$tipo.') '. json_encode($rpt[0]->iResult));
                if ($todoOK && $rpt[0]->iResult == 0){
                    $todoOK = false;
                }
            }
            if ($todoOK) {
                $jsonResponse = [
                    'error' => false,
                    'msg' => 'Se guardo Correctamente',
                    'data' => $respuesta
                ];
            }
            else {
                $jsonResponse = [
                    'error' => true,
                    'msg' => 'Existen algunos errores',
                    'data' => $respuesta,
                ];
            }
        }
        else {
            if (isset($respuesta[0]->iResult)) {
                if ($respuesta[0]->iResult > 0) {
                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta
                    ];
                }
                else {
                    abort(503, 'Error: Error de Procedimiento ('.$tipo.') '. json_encode($respuesta));
                }
            } else {
                abort(503, 'Error: Error de Sistema ('.$tipo.')');
            }
        }



        return $jsonResponse;
    }

    private function retornoJson($data){
        return response()->json($data);
    }

    public static function returnError($e){
        $msgResuelto = '';
        if (isset($e->errorInfo)){
            $msgResuelto = substr($e->errorInfo[2], 54); //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
        }
        abort(503, ($msgResuelto != '' ? $msgResuelto : $e->getMessage()));
    }

    /**
     * @param string $periodo
     * @return \Illuminate\Support\Collection
     */
    public function sbs_comisiones_y_primas_de_seguro_spp($periodo = '') {
        $client = new \GuzzleHttp\Client();
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $response =$client->request('GET', 'https://www.sbs.gob.pe/app/spp/empleadores/comisiones_spp/Paginas/comision_prima.aspx', ['cookies' => $jar]);

        //dd($response->getBody()->getContents());
        $dataDiDom = new \DiDom\Document($response->getBody()->getContents());
        if ($periodo != '') {
            $elem = $dataDiDom->first("#__VIEWSTATE");
            $elem2 = $dataDiDom->first("#__VIEWSTATEGENERATOR");
            $elem3 = $dataDiDom->first("#__EVENTVALIDATION");

            $response = $client->request('POST', 'https://www.sbs.gob.pe/app/spp/empleadores/comisiones_spp/Paginas/comision_prima.aspx', [
                'form_params' => [
                    '__VIEWSTATE' => $elem->getAttribute('value'),
                    '__VIEWSTATEGENERATOR' => $elem2->getAttribute('value'),
                    '__EVENTVALIDATION' => $elem3->getAttribute('value'),
                    'cboPeriodo' => $periodo,
                    'btnConsultar' => 'Buscar+Datos'
                ]
            ]);
            //dd($response2->getBody()->getContents());
            $dataDiDom = new \DiDom\Document($response->getBody()->getContents());
        }

        $elementos = $dataDiDom->find('.JER_filaContenido');

        $resultados = collect();

        foreach ($elementos as $elem) {
            $afp = (object)[];
            $celdas = $elem->find('td');
            // dump(count($celdas));
            $afp->nombre = trim($celdas[0]->text());
            $afp->comision_fija = trim($celdas[1]->text());
            $afp->comision_flujo = trim($celdas[2]->text());
            if (count($celdas) == 6){
                $afp->comision_mixta_flujo = null;
                $afp->comision_mixta_anual_saldo = null;

                $afp->prima_seguros = trim($celdas[3]->text());
                $afp->obligatorio = trim($celdas[4]->text());
                $afp->max_asegurable = trim($celdas[5]->text());
            }
            if (count($celdas) == 8){
                $afp->comision_mixta_flujo = trim($celdas[3]->text());
                $afp->comision_mixta_anual_saldo = trim($celdas[4]->text());

                $afp->prima_seguros = trim($celdas[5]->text());
                $afp->obligatorio = trim($celdas[6]->text());
                $afp->max_asegurable = trim($celdas[7]->text());
            }
            $resultados->push($afp);
        }
        return $resultados;
    }
}
