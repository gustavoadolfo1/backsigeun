<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\ClasesLibres\Generales\UtilControladores;
use App\Http\Controllers\Generales\RespuestasApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function foo\func;

class RemuneracionesController extends Controller
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
                    case 'categorias':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_categorias');
                        break;
                    case 'conceptos':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_conceptos');
                        break;
                    case 'conceptos_tipos':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_conceptos');
                        break;
                    case 'conceptos_clases':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_clases_conceptos');
                        break;
                    case 'conceptos_unidades_medida':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_unidades_medidas');
                        break;
                    case 'tipo_prestadores_categorias':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_prestadores_categoriasXiEntId ?', [1]);
                        if (isset($dataObj->idTipoPrestador)) {
                            $respuesta = collect($respuesta)->where('iTipoPrestadorId', $dataObj->idTipoPrestador)->values();
                        }
                        break;
                    case 'tipo_prestador':
                        if (is_numeric($data)){
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_prestadoresXiTipoPrestadorId ?', [$data]);
                        }
                        else {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_prestadoresXcTipoPrestadorNombre ?', ['%%']);
                        }
                        break;
                    case 'tipo_planillas':
                        $respuesta = UtilControladores::consultasSimples('EXEC rhh.Sp_SEL_tipos_planillasXiTipoPlanillaId ?', 'EXEC rhh.Sp_SEL_tipos_planillas');
                        break;
                    case 'tipo_planillas_tipo_prestador':
                        if (isset($dataObj->idTipoPlanilla)) {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_TipoPlanillaPrestadorXiTipoPlanillaId ?', [$dataObj->idTipoPlanilla]);
                        }
                        if (isset($dataObj->idTipoPlanillaPrestador)) {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_TipoPlanillaPrestadorXiTipoPlanillaPrestadorId ?', [$dataObj->idTipoPlanillaPrestador]);
                        }
                        break;



                    case 'motivos':
                        $respuesta = UtilControladores::consultasSimples('EXEC rhh.Sp_SEL_motivos_planillasXiMotPlanId ?', 'EXEC rhh.Sp_SEL_motivos_planillas');
                        break;
                    case 'motivos_planillas':
                        if (isset($dataObj->idTipoPlanilla)) {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_motivos_planillasXiEntIdXiTipoPlanillaId ?, ?', [1, $dataObj->idTipoPlanilla]);
                        }
                        if (isset($dataObj->idTipoPlanillaMotivo)) {
                            $respuesta = DB::select('EXEC rhh.Sp_SEL_tipos_motivos_planillasXiTipoMotPlanId ?', [$dataObj->idTipoPlanillaMotivo]);
                        }
                        break;




                    // SECCION PARA BASE DE CALCULO PARA SPP
                    case 'years':
                        $respuesta = DB::select('grl.Sp_SEL_years');
                        break;
                    case 'months':
                        $respuesta = DB::select('grl.Sp_SEL_months');
                        break;

                    case 'base_calculo_x_anio_mes':
                        $respuesta = DB::select('rhh.Sp_SEL_base_calculo_sppXiYearIdXiMonthId ?, ?', [$dataObj->idYear, $dataObj->idMonth]);
                        break;

                    case 'base_calculo_categorias':
                        if (isset($dataObj->idCategoria)) {
                            $respuesta = DB::select('rhh.Sp_SEL_base_calculo_categoriasXiEntIdXiTipoPrestadorIdXiCategId ?, ?, ?', [1, $dataObj->idTipoPrestador, $dataObj->idCategoria]);
                        }
                        else {
                            $respuesta = DB::select('rhh.Sp_SEL_base_calculo_categoriasXiEntIdXiTipoPrestadorId ?, ?', [1, $dataObj->idTipoPrestador]);
                        }
                        break;

                    case 'base_calculo_years':
                        if (isset($dataObj->idYear)) {
                            $respuesta = DB::select('rhh.Sp_SEL_base_calculo_yearsXiYearId ?', [$dataObj->idYear]);
                        }
                        if (isset($dataObj->idBaseCalculoYear)) {
                            $respuesta = DB::select('rhh.Sp_SEL_base_calculo_yearsXiBasCalYearId ?', [$dataObj->idBaseCalculoYear]);
                        }
                        break;

                    case 'horarios':
                        if (isset($dataObj->idHorario)) {
                            $respuesta = DB::select('rhh.Sp_SEL_horariosXiHorarioId ?', [$dataObj->idHorario]);
                        } else {
                            $respuesta = DB::select('rhh.Sp_SEL_horariosXiEntId ?', [1]);
                        }
                        break;

                    case 'tipos_horarios':
                        $respuesta = DB::select('rhh.Sp_SEL_tipos_horarios');
                        break;

                        // SECCION DE MANTENIMIENTO  - EXTRA

                    case 'clases_tareas':
                        $respuesta = UtilControladores::consultasSimples('bud.Sp_SEL_clases_tareasXiClaseTareaId ?', 'bud.Sp_SEL_clases_tareasXiEntId 1');
                        break;
                    case 'tipos_tareas':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('bud.Sp_SEL_tipos_tareasXiTipoTareaId ?', [$dataObj->id]));
                        }
                        elseif(isset($dataObj->idClaseTarea)){
                            $respuesta = collect(DB::select('bud.Sp_SEL_tipos_tareasXiClaseTareaId ?', [$dataObj->idClaseTarea]));
                        }
                        else {
                            $respuesta = collect(DB::select('bud.Sp_SEL_tipos_tareas'));
                            if (isset($dataObj->agrupado)){
                                $colRpta = collect();
                                foreach ($respuesta->groupBy('cClaseTareaNombre') as $idx => $dat){
                                    $colRpta->push([
                                        'id' => $dat->first()->iClaseTareaId,
                                        'nombre' => $idx,
                                        'data' => $dat,
                                    ]);
                                }
                                $respuesta = $colRpta;
                            }
                        }
                        break;
                    case 'tipos_tareas_todos':
                        $respuesta = collect(DB::select('bud.Sp_SEL_tipos_tareas'));
                        $colRpta = collect();
                        foreach ($respuesta->groupBy('cClaseTareaNombre') as $idx => $dat){
                            $colRpta->push([
                                'id' => $dat->first()->iClaseTareaId,
                                'nombre' => $idx,
                                'data' => $dat,
                            ]);
                        }
                        $respuesta = $colRpta;




                        break;
                    case 'tareas':
                        if (isset(request()->id)){
                            $respuesta = collect(DB::select('bud.Sp_SEL_tareasXiTareaId ?', [request()->id]));
                        }
                        elseif(isset(request()->anio)){
                            if (isset(request()->txtBuscar)) {
                                $respuesta = collect(DB::select('bud.Sp_SEL_tareasXiEntIdXiYearIdXcCampoBusqueda ?, ?, ?, ?, ?', [1, request()->anio, request()->txtBuscar, 1, 100000]));
                            }
                            else {
                                $respuesta = collect(DB::select('bud.Sp_SEL_tareasXiEntIdXiYearIdXcCampoBusqueda ?, ?, ?, ?, ?', [1, request()->anio, '%%', 1, 100000]));
                            }

                        }
                        break;
                    case 'fuente_financiamiento':
                        $respuesta = UtilControladores::consultasSimples('bud.Sp_SEL_fuente_financiamientoXiFteFtoId ?', 'bud.Sp_SEL_fuente_financiamientoXiEntId 1');
                        break;
                    case 'motivos_baja_registro':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_motivos_baja_registroXiMotBajRegId ?', 'rhh.Sp_SEL_motivos_baja_registro');
                        break;

                        // FIN SECCION MANTENIMIENTO


                    //  SECCION DE PRESTADOR
                    case 'prestadores':


                        if (isset($dataObj->idPrestador) || isset(request()->idPrestador)) {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadoresXiPrestadorId ?', [request()->idPrestador??$dataObj->idPrestador]));
                        }
                        else {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadoresXiEntIdXcCampoBusqueda ?, ?, ?, ?', [
                                1,
                                request()->txtBuscar??'%%',
                                request()->pagina??null,
                                request()->numItems??10000
                            ]));
                        }
                        break;
                    case 'condiciones_laborales':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_condiciones_laboralesXiCondLab ?', [$dataObj->id]));
                        }
                        else {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_condiciones_laborales'));
                        }
                        break;
                    case 'tipos_pagos':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_tipos_pagosXiTipoPagoId ?', 'rhh.Sp_SEL_tipos_pagos');
                        break;
                    case 'cargos':
                        $respuesta = DB::select('EXEC rhh.Sp_SEL_cargosXcCargosNombre ?', [$dataObj->txtBuscar??'%%']);
                        break;
                    case 'regimenes_pensionarios':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_regimenes_pensionariosXiRegimPensionId ?', [$dataObj->id]));
                        }
                        elseif(isset($dataObj->tipo)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_regimenes_pensionariosXiTipoRegimPensionId ?', [$dataObj->tipo]));
                        }
                        else {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_regimenes_pensionarios'));
                            // print_r($respuesta);
                            $respuesta = $respuesta->where('iRegimPensionEstado', '1')->values();
                        }
                        break;
                    case 'situaciones_prestadores':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_situaciones_prestadoresXiSituaPrestadId ?', 'rhh.Sp_SEL_situaciones_prestadores');
                        break;
                    case 'ocupaciones':
                        // return response()->json($dataObj->id);

                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_ocupacionesXiOcupaId ?', [$dataObj->id]));
                        }
                        elseif(isset($dataObj->txtBuscar)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_ocupaciones ?', [$dataObj->txtBuscar, 1, 10000]));
                        }
                        else {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_ocupaciones'));
                        }
                        //$respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_ocupacionesXiOcupaId ?', 'rhh.Sp_SEL_ocupaciones');
                        break;
                    case 'situaciones_educativas':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_situaciones_educativasXiSituEduId ?', 'rhh.Sp_SEL_situaciones_educativas');
                        break;


                    case 'vinculos_familiares':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_vinculos_familiaresXiVinculoFamiliarId ?', 'rhh.Sp_SEL_vinculos_familiares');
                        break;
                    case 'derechohabientes':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadores_derechos_habientesXiPrestadorDerHabId ?', [$dataObj->id]));
                        }
                        if (isset($dataObj->idPrestador)) {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadores_derechos_habientesXiPrestadorId ?', [$dataObj->idPrestador]));
                        }
                        break;
                    case 'conceptos_fijos':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadores_conceptos_fijosXiPrestadorConcepFijoId ?', [$dataObj->id]));
                        }
                        if (isset($dataObj->idPrestador)) {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadores_conceptos_fijosXiPrestadorId ?', [$dataObj->idPrestador]));
                        }
                        break;
                    case 'conceptos_notas':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_notas_prestadoresXiNotaPrestadorId ?', [$dataObj->id]));
                        }
                        if (isset($dataObj->idPrestador)) {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_notas_prestadoresXiPrestadorId ?', [$dataObj->idPrestador]));
                        }
                        break;
                    case 'periodos_laborales':
                        if (isset($dataObj->id)){
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadores_periodos_laboralesXiPrestadorPerLabId ?', [$dataObj->id]));
                        }
                        if (isset($dataObj->idPrestador)) {
                            $respuesta = collect(DB::select('rhh.Sp_SEL_prestadores_periodos_laboralesXiPrestadorId ?', [$dataObj->idPrestador]));
                        }
                        break;


                    // FIN SECCION DE PRESTADOR



                    // INICIO TAREOS
                    case 'credenciales_tareos':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_credenciales_tareosXiCredTareoId ?', 'rhh.Sp_SEL_credenciales_tareosXiEntId 1');
                        break;
                    case 'periodicidades':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_periodicidadesXiPeriodicidadId ?', 'rhh.Sp_SEL_periodicidades');
                        break;
                    case 'credenciales_tareos_prestadores':
                        $respuesta = UtilControladores::consultasSimples('rhh.Sp_SEL_credenciales_tareos_prestadoresXiCredTareoId ?', null);
                        break;
                    // FIN TAREOS


                    // NO USADO
                    case 'sbs_comisiones_y_primas_de_seguro_spp':


                        // Seccion Consulta
                        $param = ($dataObj->idYear == today()->year && $dataObj->idMonth == today()->month) ? '' : $dataObj->idYear .'-'. sprintf("%02d", $dataObj->idMonth);
                        $respuesta = $this->sbs_comisiones_y_primas_de_seguro_spp($param);


                        break;

                }
            }
            $respuestas[$tipo] = $respuesta;
        }
        return response()->json($respuestas);
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
                        case 'categorias':
                            if (!is_object($dataObj)) {
                                $regimenesSPP = DB::select('EXEC rhh.Sp_DEL_categordias ?', $dataObj );
                                return response()->json($regimenesSPP);
                            } else {
                                $dataGuardar = [
                                    $dataObj->nombre,

                                    auth()->user()->iCredId,
                                    null,
                                    $request->getClientIp(),
                                    null,
                                ];
                                if (isset($dataObj->id) && $dataObj->id != -1) {
                                    array_unshift($dataGuardar, $dataObj->id);
                                    $regimenesSPP = DB::select('EXEC rhh.Sp_UPD_categorias ?,     ?,       ?, ?, ?, ?', $dataGuardar );
                                } else {
                                    $regimenesSPP = DB::select('EXEC rhh.Sp_INS_categorias ?,       ?, ?, ?, ?', $dataGuardar );
                                }
                            }
                            $jsonResponse = UtilControladores::respuestasSimple($tipo, $regimenesSPP);
                            break;
                        case 'conceptos':
                            if (!is_object($dataObj)) {
                                $regimenesSPP = DB::select('EXEC rhh.Sp_DEL_conceptos ?', $dataObj );
                            } else {
                                $dataGuardar = [
                                    $dataObj->nombre,
                                    $dataObj->abreviatura,
                                    $dataObj->campo,
                                    $dataObj->idTipo,
                                    $dataObj->idClase,
                                    $dataObj->idUnidadMedida,

                                    auth()->user()->iCredId,
                                    null,
                                    $request->getClientIp(),
                                    null,
                                ];
                                if (isset($dataObj->id) && $dataObj->id != -1) {
                                    array_unshift($dataGuardar, $dataObj->id);
                                    $regimenesSPP = DB::select('EXEC rhh.Sp_UPD_conceptos ?,     ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                                } else {
                                    $regimenesSPP = DB::select('EXEC rhh.Sp_INS_conceptos ?, ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar );
                                }
                            }
                            $jsonResponse = UtilControladores::respuestasSimple($tipo, $regimenesSPP);
                            break;
                        case 'base_calculo_categorias':
                            if (!is_object($dataObj)) {
                                $res = DB::select('rhh.Sp_DEL_base_calculo_categorias ?', $dataObj );
                            } else {
                                if (isset($dataObj->idBaseCalculoCategoria) && $dataObj->idBaseCalculoCategoria != -1) {
                                    $dataGuardar = [
                                        $dataObj->idBaseCalculoCategoria,
                                        $dataObj->valor,

                                        auth()->user()->iCredId,
                                        null,
                                        $request->getClientIp(),
                                        null,
                                    ];
                                    $res = DB::select('rhh.Sp_UPD_base_calculo_categorias ?,     ?,        ?, ?, ?, ?', $dataGuardar );
                                } else {
                                    $dataGuardar = [
                                        1,
                                        $dataObj->idTipoPrestador,
                                        $dataObj->idCategoria,
                                        $dataObj->idConcepto,
                                        $dataObj->valor,

                                        auth()->user()->iCredId,
                                        null,
                                        $request->getClientIp(),
                                        null,
                                    ];
                                    $res = DB::select('rhh.Sp_INS_base_calculo_categorias ?, ?, ?, ?, ?,        ?, ?, ?, ?', $dataGuardar );
                                }
                            }
                            $jsonResponse = UtilControladores::respuestasSimple($tipo, $res);
                            break;
                        case 'base_calculo_years':
                            if (!is_object($dataObj)) {
                                $res = DB::select('rhh.Sp_DEL_base_calculo_years ?', $dataObj );
                            } else {
                                if (isset($dataObj->idBaseCalculoYear) && $dataObj->idBaseCalculoYear != -1) {
                                    $dataGuardar = [
                                        $dataObj->idBaseCalculoYear,
                                        $dataObj->valor,

                                        auth()->user()->iCredId,
                                        null,
                                        $request->getClientIp(),
                                        null,
                                    ];
                                    $res = DB::select('rhh.Sp_UPD_base_calculo_years ?,     ?,        ?, ?, ?, ?', $dataGuardar );
                                } else {
                                    $dataGuardar = [
                                        $dataObj->idYear,
                                        $dataObj->idConcepto,
                                        $dataObj->valor,

                                        auth()->user()->iCredId,
                                        null,
                                        $request->getClientIp(),
                                        null,
                                    ];
                                    $res = DB::select('rhh.Sp_INS_base_calculo_years ?, ?, ?,        ?, ?, ?, ?', $dataGuardar );
                                }
                            }
                            $jsonResponse = UtilControladores::respuestasSimple($tipo, $res);
                            break;
                        case 'horarios':
                            $paramsD = [
                                null,
                                $dataObj->idTipoHorario??null,
                                $dataObj->nombre??null,
                            ];

                            $arrParamsIns = $paramsD;
                            $arrParamsIns[0] = 1;

                            $arrParamsUpd = $paramsD;
                            $arrParamsUpd[0] = $dataObj->idHorario??null;

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idHorario',
                                [
                                    'ins' => ['proc' => 'rhh.Sp_INS_horarios', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'rhh.Sp_UPD_horarios', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'rhh.Sp_DEL_horarios', 'params' => $dataObj],
                                ]
                            ));
                            break;
                        case 'prestadores':
                            $paramsD = [
                                $dataObj->idDependencia??null,
                                $dataObj->idCondicionLaboral??null,
                                $dataObj->idTipoPago??null,
                                $dataObj->idBanco??null,
                                $dataObj->numeroDeCuenta??null,
                                $dataObj->idTipoPlanilla??null,
                                $dataObj->idTipoPrestador??null,
                                $dataObj->idCategoria??null,
                                $dataObj->idCargo??null,
                                $dataObj->idHorario??null,
                                $dataObj->idRegimenPensionario??null,
                                $dataObj->codigoSPP??null,
                                $dataObj->aporteObligatorio??null,
                                $dataObj->seguro??null,
                                $dataObj->comision??null,
                                $dataObj->ruc??null,
                                $dataObj->idSituacion??null,
                                $dataObj->idOcupacion??null,
                                $dataObj->idSituacionEducativa??null,
                            ];

                            $arrParamsIns = array_merge([1, $dataObj->idPersona??null], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idPrestador??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idPrestador',
                                [
                                    'ins' => ['proc' => 'rhh.Sp_INS_prestadores', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'rhh.Sp_UPD_prestadores', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'rhh.Sp_DEL_prestadores', 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'clases_tareas':
                            $paramsD = [
                                $dataObj->nombre??null,
                                $dataObj->sigla??null,
                                $dataObj->orden??null,
                            ];

                            $arrParamsIns = array_merge([1], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idClaseTarea??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idClaseTarea',
                                [
                                    'ins' => ['proc' => 'bud.Sp_INS_clases_tareas', 'params' => $arrParamsIns],
                                    'upd' => ['proc' => 'bud.Sp_UPD_clases_tareas', 'params' => $arrParamsUpd],
                                    'del' => ['proc' => 'bud.Sp_DEL_clases_tareas', 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'tipos_tareas':
                            $patronProc = 'bud.Sp_###_tipos_tareas';
                            $paramsD = [
                                $dataObj->nombre??null,
                                $dataObj->sigla??null,
                                $dataObj->orden??null,
                            ];

                            $arrParamsIns = array_merge([$dataObj->idClaseTarea], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idTipoTarea??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idTipoTarea',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'tareas':
                            $patronProc = 'bud.Sp_###_tareas';
                            $paramsD = [
                                $dataObj->secuenciaFuncional??null,
                                $dataObj->idTipoTarea??null,
                                $dataObj->codigo??null,
                                $dataObj->nombre??null,
                            ];

                            $arrParamsIns = array_merge([1, $dataObj->anio??null], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idTarea??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idTarea',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'fuente_financiamiento':
                            $patronProc = 'bud.Sp_###_fuente_financiamiento';
                            $paramsD = [
                                $dataObj->fuenteFinanciamiento??null,
                                $dataObj->rubro??null,
                                $dataObj->tipoRecurso??null,
                                $dataObj->codigo??null,
                                $dataObj->nombre??null,
                                $dataObj->abreviatura??null,
                            ];

                            $arrParamsIns = array_merge([1], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->iFteFtoId], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'iFteFtoId',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'derechohabientes':
                            $patronProc = 'rhh.Sp_###_prestadores_derechos_habientes';
                            $paramsD = [
                                $dataObj->idVinculoFamiliar??null,
                            ];

                            $arrParamsIns = array_merge([$dataObj->idPrestador??null, $dataObj->idPersonaDerechoHabiente??null], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idPrestadorDerechoHabiente??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idPrestadorDerechoHabiente',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;

                        case 'conceptos_fijos':
                            $patronProc = 'rhh.Sp_###_prestadores_conceptos_fijos';
                            $paramsD = [
                                $dataObj->valor??null,
                            ];

                            $arrParamsIns = array_merge([$dataObj->idPrestador??null, $dataObj->idConcepto??null], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idPrestadorConceptoFijo??null], $paramsD, [(isset($dataObj->estado) && $dataObj->estado)?1:0]);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idPrestadorConceptoFijo',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;
                        case 'conceptos_notas':

                            $colAnterior = collect($dataObj->conceptosOrig);

                            $add = [];
                            foreach ($dataObj->conceptos as $idxAct => $dAct) {
                                if ($dAct){
                                    $add[] = $idxAct;
                                }
                            }

                            $datRetArray = [];

                            $eliminar = $colAnterior->whereNotIn('iConcepId',$add)->values();

                            foreach ($eliminar as $el) {
                                $rpt = DB::select('EXEC rhh.Sp_DEL_notas_prestadores ?', [$el->iNotaPrestadorId]);
                                $datRetArray[] = $rpt;
                            }

                            foreach ($add as $ad) {
                                if ($colAnterior->where('iConcepId', $ad)->count() == 0){
                                    $res = DB::select('rhh.Sp_INS_notas_prestadores ?, ?,    ?, ?, ?, ?', [
                                        $dataObj->idPrestador??null,
                                        $ad??null,

                                        auth()->user()->iCredId,
                                        null,
                                        request()->getClientIp(),
                                        null,
                                    ] );
                                    $datRetArray[] = $res;
                                }
                            }

                            $jsonResponse = [
                                'error' => false,
                                'msg' => 'Se guardo Correctamente',
                                'data' => [
                                    'anterior' => $dataObj->conceptosOrig,
                                    'actual' =>$dataObj->conceptos,
                                    'res' => $datRetArray,
                                ]
                            ];
                            break;

                        case 'periodos_laborales':
                            // Sp_INS_prestadores_periodos_laborales
                            $patronProc = 'rhh.Sp_###_prestadores_periodos_laborales';
                            $paramsD = [
                                $dataObj->inicio??null,
                                $dataObj->fin??null,
                                $dataObj->idMotivoBaja??null,
                            ];

                            $arrParamsIns = array_merge([$dataObj->idPrestador??null,], $paramsD);

                            $arrParamsUpd = array_merge([$dataObj->idPrestadorPeriodoLaboral??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimiento(
                                $dataObj,
                                'idPrestadorPeriodoLaboral',
                                [
                                    'ins' => ['proc' => str_replace('###', 'INS',$patronProc), 'params' => $arrParamsIns],
                                    'upd' => ['proc' => str_replace('###', 'UPD',$patronProc), 'params' => $arrParamsUpd],
                                    'del' => ['proc' => str_replace('###', 'DEL',$patronProc), 'params' => $dataObj],
                                ]
                            ));
                            break;
                        case 'credenciales_tareos':
                            $patronProc = 'rhh.Sp_###_credenciales_tareos';
                            $paramsD = [
                                request()->idCredencial??null,
                                request()->idTipoPlanilla??null,
                                request()->idTarea??null,
                                request()->idDependencia??null,
                                request()->idPeriodicidad??null,
                                request()->idMotivoPlanilla??null,
                            ];

                            $arrParamsIns = array_merge([1], $paramsD);

                            $arrParamsUpd = array_merge([request()->idCredencialTareo??null], $paramsD);

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimientoReq(
                                'idCredencialTareo',
                                [str_replace('###', 'INS',$patronProc), $arrParamsIns],
                                [str_replace('###', 'UPD',$patronProc), $arrParamsUpd],
                                str_replace('###', 'DEL',$patronProc)
                            ));
                            break;
                        case 'credenciales_tareos_prestadores':
                            $patronProc = 'rhh.Sp_###_credenciales_tareos_prestadores';

                            $arrParamsIns = [
                                request()->idCredencialTareo??null,
                                request()->idPrestador??null,
                            ];

                            $arrParamsUpd = [
                                request()->idCredencialTareoPrestador??null,
                                request()->idTipoPrestador??null,
                                request()->idCategoria??null,
                                request()->idCargo??null,
                            ];

                            $jsonResponse = UtilControladores::respuestasSimple($tipo, UtilControladores::mantenimientoReq(
                                'idCredencialTareoPrestador',
                                [str_replace('###', 'INS',$patronProc), $arrParamsIns],
                                [str_replace('###', 'UPD',$patronProc), $arrParamsUpd],
                                str_replace('###', 'DEL',$patronProc)
                            ));
                            break;
                    }
                    break;

                case 'tipoprestador_categorias':

                    foreach ($dataObj->idsCategorias as $idxAct => $dAct) {
                        foreach ($dataObj->idsCategoriasOrig as $idxAnt => $dAnt) {
                            if ($dAct->iCategId == $dAnt->iCategId) {
                                unset($dataObj->idsCategorias[$idxAct]);
                                unset($dataObj->idsCategoriasOrig[$idxAnt]);
                            }
                        }
                    }
                    /*
                    return response()->json([
                        'agregar' => $dataObj->idsCategorias,
                        'eliminar' => $dataObj->idsCategoriasOrig,
                    ]);
                    */


                    $idsEliminar = [];
                    // ELIMINAR
                    foreach ($dataObj->idsCategoriasOrig as $idxE => $dElim) {
                        $idsEliminar[] = [$dElim->iTipoPrestadorCategId, $idxE];
                    }
                    $idxEdit = -1;
                    if (count($idsEliminar) > 0) {
                        $idxEdit = 0;
                    }

                    $errorAgregando = false;
                    // AGREGAR

                    foreach ($dataObj->idsCategorias as $dDif) {
                        if (is_null($dDif->iCategId)){
                            $dataGuardar = [
                                $dDif->cCategNombre,

                                auth()->user()->iCredId,
                                null,
                                $request->getClientIp(),
                                null,
                            ];
                            $regimenesSPP = DB::select('EXEC rhh.Sp_INS_categorias ?,       ?, ?, ?, ?', $dataGuardar );
                            if (isset($regimenesSPP[0]->iResult) && ($regimenesSPP[0]->iResult > 0)) {
                                $dDif->iCategId = $regimenesSPP[0]->iCategId;
                            }
                        }

                        if (!is_null($dDif->iCategId)) {
                            $dataGuardar = [
                                1,
                                $dataObj->idTipoPrestador,
                                $dDif->iCategId,

                                auth()->user()->iCredId,
                                null,
                                $request->getClientIp(),
                                null,
                            ];
                            /*

                            if ($idxEdit != -1 && $idxEdit < count($idsEliminar)){
                                $dataGuardar[0] = $idsEliminar[$idxEdit][0];
                                unset($dataObj->idsCategoriasOrig[$idsEliminar[$idxEdit][1]]);
                                $rpt = DB::select('EXEC rhh.Sp_UPD_tipos_prestadores_categorias ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            }
                            else {
                            }
                            */
                            $rpt = DB::select('EXEC rhh.Sp_INS_tipos_prestadores_categorias ?, ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            $datRetArray[] = $rpt;
                        }
                        else {
                            if (!$errorAgregando){
                                $errorAgregando = true;
                            }
                        }
                    }

                    foreach ($dataObj->idsCategoriasOrig as $idxE => $dElim) {
                        // $idsEliminar[] = [$dElim->iTipoPrestadorCategId, $idxE];
                        $rpt = DB::select('EXEC rhh.Sp_DEL_tipos_prestadores_categorias ?', [$dElim->iTipoPrestadorCategId]);
                        $datRetArray[] = $rpt;
                    }


                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente' . ($errorAgregando ? ' con algunos errores' : ''),
                        'data' => [
                            'anterior' => $dataObj->idsCategoriasOrig,
                            'actual' =>$dataObj->idsCategorias,
                            'res' => $datRetArray,
                        ]
                    ];

                    // return response()->json($jsonResponse);
                    // return response()->json($dataObj);
                    break;

                case 'tipoplanillas_tipoprestador':

                    foreach ($dataObj->idsPrestadores as $idxAct => $dAct) {
                        foreach ($dataObj->idsPrestadoresOrig as $idxAnt => $dAnt) {
                            if ($dAct->iTipoPrestadorId == $dAnt->iTipoPrestadorId) {
                                unset($dataObj->idsPrestadores[$idxAct]);
                                unset($dataObj->idsPrestadoresOrig[$idxAnt]);
                            }
                        }
                    }

                    $idsEliminar = [];
                    // ELIMINAR
                    foreach ($dataObj->idsPrestadoresOrig as $idxE => $dElim) {
                        $idsEliminar[] = [$dElim->iTipoPrestadorCategId, $idxE];
                    }

                    // AGREGAR
                    foreach ($dataObj->idsPrestadores as $dDif) {
                        $dataGuardar = [
                            $dataObj->idTipoPlanilla,
                            $dDif->iTipoPrestadorId,

                            auth()->user()->iCredId,
                            null,
                            $request->getClientIp(),
                            null,
                        ];
                        $rpt = DB::select('EXEC rhh.Sp_INS_tipos_planillas_prestadores ?, ?,        ?, ?, ?, ?', $dataGuardar);
                        $datRetArray[] = $rpt;
                    }

                    foreach ($dataObj->idsPrestadoresOrig as $idxE => $dElim) {
                        $rpt = DB::select('EXEC rhh.Sp_DEL_tipos_planillas_prestadores ?', [$dElim->iTipoPlanillaPrestadorId]);
                        $datRetArray[] = $rpt;
                    }


                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => [
                            'anterior' => $dataObj->idsPrestadoresOrig,
                            'actual' =>$dataObj->idsPrestadores,
                            'res' => $datRetArray,
                        ]
                    ];

                    // return response()->json($jsonResponse);
                    // return response()->json($dataObj);
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

                    // return response()->json($jsonResponse);
                    // return response()->json($dataObj);
                    break;

                case 'llenar_data_sbs_comisiones_y_primas_de_seguro_spp':
                    // Seccion Consulta
                    $param = ($dataObj->idYear == today()->year && $dataObj->idMonth == today()->month) ? '' : $dataObj->idYear .'-'. sprintf("%02d", $dataObj->idMonth);
                    $regimenesSPP = $this->sbs_comisiones_y_primas_de_seguro_spp($param);

                    $coleccionRespuestas = collect();

                    foreach ($regimenesSPP as $reg) {
                        // return response()->json($reg);
                        $dActual = collect(DB::select('rhh.Sp_SEL_base_calculo_sppXiYearIdXiMonthId ?, ?', [$dataObj->idYear, $dataObj->idMonth]));

                        $buscador = $dActual->first(function ($value, $key) use ($reg) {
                            return trim($value->cRegimPensionAbreviatura) == trim($reg->nombre);
                        });

                        $preD = [$dataObj->idYear, $dataObj->idMonth, $buscador->iRegimPensionId];
                        $pos = [auth()->user()->iCredId, null, $request->getClientIp(), null];

                        // Seccion Alimentar
                        if ($buscador) {


                            $insert_comFlujo = DB::select(
                                'rhh.Sp_INS_UPD_base_calculo_spp ?, ?, ?, ?, ?,       ?, ?, ?, ?',
                                array_merge($preD, [3, str_replace('%', '', $reg->comision_flujo)], $pos)
                            );

                            $insert_comMixta = DB::select(
                                'rhh.Sp_INS_UPD_base_calculo_spp ?, ?, ?, ?, ?,       ?, ?, ?, ?',
                                array_merge($preD, [2, str_replace('%', '', $reg->comision_mixta_flujo)], $pos)
                            );

                            $insert_primaSeguro = DB::select(
                                'rhh.Sp_INS_UPD_base_calculo_spp ?, ?, ?, ?, ?,       ?, ?, ?, ?',
                                array_merge($preD, [4, str_replace('%', '', $reg->prima_seguros)], $pos)
                            );

                            $insert_fondoPension = DB::select(
                                'rhh.Sp_INS_UPD_base_calculo_spp ?, ?, ?, ?, ?,       ?, ?, ?, ?',
                                array_merge($preD, [1, str_replace('%', '', $reg->obligatorio)], $pos)
                            );

                            $insert_topeSeguro = DB::select(
                                'rhh.Sp_INS_UPD_base_calculo_spp ?, ?, ?, ?, ?,       ?, ?, ?, ?',
                                array_merge($preD, [5, str_replace(',', '', $reg->max_asegurable)], $pos)
                            );

                            $rt = [
                                'regimen' => $reg->nombre,
                                'insert_comFlujo' => $insert_comFlujo,
                                'insert_comMixta' => $insert_comMixta,
                                'insert_primaSeguro' => $insert_primaSeguro,
                                'insert_fondoPension' => $insert_fondoPension,
                                'insert_topeSeguro' => $insert_topeSeguro,
                            ];
                        } else {
                            $rt = [
                                'regimen' => $reg->nombre,
                                'insert_comFlujo' => $buscador,
                                'insert_comMixta' => null,
                                'insert_primaSeguro' => null,
                                'insert_fondoPension' => null,
                                'insert_topeSeguro' => null,
                            ];
                        }
                        $coleccionRespuestas->push($rt);
                    }

                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $coleccionRespuestas
                    ];

                    break;

                case 'llenar_data_sbs_comisiones_y_primas_de_seguro_spp_unidad':
                    $dataGuardar = [
                        $dataObj->idYear,
                        $dataObj->idMonth,
                        $dataObj->idRegimen,
                        $dataObj->idConcepto,
                        $dataObj->valor,

                        auth()->user()->iCredId,
                        null,
                        $request->getClientIp(),
                        null
                    ];
                    $retData = DB::select('rhh.Sp_INS_UPD_base_calculo_spp ?, ?, ?, ?, ?,       ?, ?, ?, ?', $dataGuardar);

                    $jsonResponse = UtilControladores::respuestasSimple($tipo, $retData);

                    break;

            }
            DB::commit();
        }
        catch (\Exception $e) {
            $jsonResponse = $this->returnError($e);
            DB::rollback();
        }
        return response()->json($jsonResponse);
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
