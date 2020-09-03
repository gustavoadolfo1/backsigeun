<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> LISTA DE PRESUPUESTO </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
</head>
<style>
    @page {
        margin: 90px 40px !important;
    }

    #header {
        position: fixed;
        left: 0px;
        top: -60px;
        right: 0px;
        height: 60px;
        text-align: center;
    }

    #footer {
        position: fixed;
        left: 0px;
        bottom: -150px;
        right: 0px;
        height: 120px;
        text-align: center;
    }

    #footer .page:after {
        content: counter(page);
    }

    .titulo1 {
        text-align: left;
        font-size: 13px;
        padding-top: 20px;
    }

    .titulo2 {
        text-align: left;
        font-size: 13px;
        padding-top: 20px;
        padding-left: 20px;
    }

    .contenido1 {
        text-align: left;
        font-size: 13px;
        padding-left: 20px;
    }

    .contenido2 {
        text-align: left;
        font-size: 13px;
        padding-left: 40px;
    }

    .tabla-titulo {
        text-align: center;
        font-size: 13px;
    }

    .tabla-contenido {
        font-size: 10px;
    }

</style>
<>
<div id="header">
    <table style="font-size:13px" width="100%">
        <tr>
            <td width="15" style="text-align:left;"><em><img src="./img/logo.png" id="img-logo"
                                                             style="height:15px; position: relative; float: left; margin-left: 1px; bottom: -10px">
                </em></td>
            <td style="text-align:left; margin-left: 20px; position: relative"><em> Universidad Nacional de
                    Moquegua</em></td>
            <td style="text-align:center;"><em></em></td>
            <td style="text-align:right;"><em></em></td>

        </tr>
    </table>
    <hr style="margin-top:-2px">
</div>
<div id="footer">
    <hr>

    <table style="font-size:13px;margin-top:-10px" width="100%">
        <tr>

            <td style="text-align:left;"><em>Fecha: <?php echo date("Y-m-d") ?></em></td>
            <td style="text-align:center;"><em></em></td>
            <td style="text-align:right;" class="page"><em>Página&nbsp;&nbsp;</em></td>

        </tr>
    </table>

</div>

<table align="center" style="margin-top: -10px;" width="100%">
    <tr style="font-size: 14px; text-align: center">
        <th><strong>INFORME DE AVANCE TÉCNICO FINANCIERO DE PROYECTO (ITF)</strong></th>
    </tr>
    <tr style="">
        <th class="titulo1"><strong>1. Datos del Proyecto de Investigación</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table>
                <tr>
                    <td><b>Título de Proyecto </b></td>
                    <td>:</td>
                    <td>{{ $dataProy[0]->cNombreProyecto }}</td>
                </tr>
                <tr>
                    <td><b>Director del Proyecto </b></td>
                    <td>:</td>
                    <td>{{ $dataProy[0]->director }}</td>
                </tr>
                <tr>
                    <td><b>Periodo de Ejecución </b></td>
                    <td>:</td>
                    <td>Del {{ $dataHito[0]->dtFechaInicio }} al {{ $dataHito[0]->dtFechaFin }}}</td>
                </tr>
                <tr>
                    <td><b>Resolución de aprobación </b></td>
                    <td>:</td>
                    <td>{{ $dataProy[0]->cResProyecto }}</td>
                </tr>
                <td><b>Fecha de Informe </b></td>
                <td>:</td>
                <td>{{ $dataInfTec[0]->dtFechaInfoAvTec }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="">
        <th class="titulo1"><strong>2. Resumen Ejecutivo</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            {{ $dataInfTec[0]->cResumenEjecutivo }}
        </td>
    </tr>
    <tr style="">
        <th class="titulo1"><strong>3. Informe de Avance Técnico</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th>Nivel</th>
                    <th>Indicadores</th>
                    <th width="60px">Ejecutado</th>
                    <th width="60px">Programado</th>
                    <th width="60px">Nivel de Avance</th>
                </tr>
                <tr class="tabla-titulo">
                    <th>Objetivo General</th>
                    <th colspan="4">Indicadores de Propósito</th>
                </tr>
                @foreach($objGral as $index=>$a)
                    <tr class="tabla-contenido">
                        @if ($index == 0)
                            <td style="text-align: justify" rowSpan="{{$a->numInd}}"> {{$a->cObjetivo}}</td>
                        @endif
                        <td style="text-align: justify;">{{$a->cIndicador}}</td>
                        <td style="text-align: right;">{{$a->totalEjecAntesHito + $a->totalEjecEnHito}}</td>
                        <td style="text-align: right;">{{$a->iMeta}}</td>
                        <td style="text-align: right;">{{ number_format((($a->totalEjecAntesHito * 1 + $a->totalEjecEnHito * 1) / $a->iMeta) * 100, 2)}}</td>
                    </tr>
                @endforeach
                <tr class="tabla-titulo">
                    <th>Componente/Objetivo Específico</th>
                    <th colspan="4">Indicadores de Producto</th>
                </tr>
                @php $idObjAnt = ''; @endphp
                @foreach($objEspecifico as $index2=>$a)
                    <tr class="tabla-contenido">
                        @if ($idObjAnt !== $a->iObjetivoId)
                            <td style="text-align: justify" rowSpan="{{$a->numInd}}"> {{$a->cObjetivo}}</td>
                            @php $idObjAnt = $a->iObjetivoId; @endphp
                        @endif
                        <td style="text-align: justify;">{{$a->cIndicador}}</td>
                        <td style="text-align: right;">{{$a->totalEjecAntesHito + $a->totalEjecEnHito}}</td>
                        <td style="text-align: right;">{{$a->iMeta}}</td>
                        <td style="text-align: right;">{{ number_format((($a->totalEjecAntesHito * 1 + $a->totalEjecEnHito * 1) / $a->iMeta) * 100, 2)}}</td>
                    </tr>
                @endforeach

                <tr class="tabla-titulo">
                    <th></th>
                    <th colspan="4">Actividades</th>
                </tr>
                @php $idObjAnt = ''; @endphp
                @foreach($act as $index2=>$a)
                    <tr class="tabla-contenido">
                        @if ($idObjAnt !== $a->iObjetivoId)
                            <td style="text-align: justify" rowSpan="{{$a->numAct}}"> {{$a->cObjetivo}}</td>
                            @php $idObjAnt = $a->iObjetivoId; @endphp
                        @endif
                        <td style="text-align: justify;">{{$a->cActividadDescripcion}}</td>
                        <td style="text-align: right;">{{$a->totalEjecAntesHito + $a->totalEjecEnHito}}</td>
                        <td style="text-align: right;">{{$a->iMeta}}</td>
                        <td style="text-align: right;">{{ number_format((($a->totalEjecAntesHito * 1 + $a->totalEjecEnHito * 1) / $a->iMeta) * 100, 2)}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>3.1 Avances en la ejecución al hito, respecto a lo programado en el Plan de
                Trabajo</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th>Descripción</th>
                    <th width="60px">Cumplido Si/No</th>
                    <th width="250px">Medio de Verificación</th>
                </tr>
                @foreach($hito as $index=>$a)
                    <tr class="tabla-contenido">
                        <td style="text-align: justify;">{{$a->cIndicadorHito}}</td>
                        @if ($a->iMeta == $a->iCantidad)
                            <td style="text-align: center">Si</td>
                        @else
                            <td style="text-align: center">No</td>
                        @endif
                        <td style="text-align: justify;">{{$a->cMedioVerificacion}}</td>
                    </tr>
                @endforeach
                <tr class="tabla-titulo">
                    <th colspan="3">Detalle del avance logrado o causas de incumplimiento</th>
                </tr>
                <tr class="tabla-titulo">
                    <td colspan="3" class="tabla-contenido">{{ $dataInfTec[0]->cDetAvIndHito }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="">
        <td class="titulo2"><strong>Si su respuesta es "NO", señalar:</strong></td>
    </tr>

    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th>Indicador</th>
                    <th width="60px">Indicador en el siguiente Hito</th>
                    <th width="250px">Fecha en que se cumplirá</th>
                </tr>
                @foreach($hito as $index=>$a)
                    <tr class="tabla-contenido">
                        @if ($a->iMeta == $a->iCantidad)
                        @else
                            <td style="text-align: justify;">{{$a->cIndicadorHito}}</td>
                            @if ($a->iAfectaIndSgtHito == 1)
                                <td style="text-align: center">Si</td>
                            @else
                                <td style="text-align: center">No</td>
                            @endif

                            <td style="text-align: center">{{$a->dtFechaCumplir}}</td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>3.2 Otros resultados logrados en el periodo del Hito</strong></th>
    </tr>
    <tr>
        <td>
            <ul class="contenido2">
                @foreach($resultado as $index=>$b)
                    <li>{{$b->cResultadoHito}}</li>
                @endforeach
            </ul>
        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>3.3 Riesgos para el cumplimiento de los indicadores durante el periodo</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th width="50%">Descripción del Riesgo</th>
                    <th width="50%">Acciones tomadas
                        Contingencia/Mitigado
                    </th>
                </tr>
                @foreach($riesgo as $index=>$c)
                    <tr class="tabla-contenido">
                        <td style="text-align: justify;">{{$c->cRiesgoHito}}</td>
                        <td style="text-align: justify">{{$c->cEstadoRiesgo}} : {{$c->cAccionTomada}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>


    <tr style="">
        <th class="titulo2"><strong>3.4 Otros problemas manifestados en el período del hito que no se encuentran en el
                documento de gestión, causas y consecuencias</strong></th>
    </tr>
    <tr>
        <td><strong class="contenido1">Problemas Técnicos</strong>
            <ul class="contenido2">
                @foreach($problema as $index=>$c)
                    @if ($c->iTipoObservacionId == 2)
                        <li>{{$c->cProblemaHito}}</li>
                    @endif
                @endforeach
            </ul>
        </td>
    </tr>
    <tr>
        <td><strong class="contenido1">Problemas Administrativos y/o financieros</strong>
            <ul class="contenido2">
                @foreach($problema as $index=>$c)
                    @if ($c->iTipoObservacionId == 1)
                        <li>{{$c->cProblemaHito}}</li>
                    @endif
                @endforeach
                @foreach($problema as $index=>$c)
                    @if ($c->iTipoObservacionId == 3)
                        <li>{{$c->cProblemaHito}}</li>
                    @endif
                @endforeach
            </ul>
        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>3.5 Implementación de recomendaciones de la última supervisión al proyecto por parte
                de VPI (DGI) según el acta de supervisión</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            @foreach($observacion as $index=>$d)
                <table>
                    <tr>
                        <td><b>Fecha del acta </b></td>
                        <td>:</td>
                        <td>{{ $d->dtFechaActa }}</td>
                    </tr>
                    <tr>
                        <td><b>Lugar </b></td>
                        <td>:</td>
                        <td>{{ $d->cLugar }}</td>
                    </tr>
                    <tr>
                        <td><b>Recomendaciones </b></td>
                        <td>:</td>
                        <td>{{ $d->cRecomendacion }}</td>
                    </tr>
                    <tr>
                        <td><b>Resultados </b></td>
                        <td>:</td>
                        <td>{{ $d->cResultado }}</td>
                    </tr>
                </table>
            @endforeach
        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>3.6 Historial de observaciones reportadas al proyecto en los hitos
                evaluados</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th rowspan="2" width="">Hito</th>
                    <th rowspan="2" width="">Observaciones encontradas</th>
                    <th colspan="3" width="">Observaciones encontradas</th>
                    <th rowspan="2" width="">Estado</th>
                    <th rowspan="2" width="">Fecha</th>
                </tr>
                <tr class="tabla-titulo">
                    <th width="">Técnico</th>
                    <th width="">Financiero</th>
                    <th width="">Administrativo</th>
                </tr>
                @foreach($observacionEvaluada as $index=>$e)
                    <tr class="tabla-contenido">
                        <td style="text-align: center;">{{$e->iNumeroHito}}</td>
                        <td style="text-align: justify;">{{$e->cRecomendacion}}</td>
                        @php
                            $t = '';
                            $f = '';
                            $a = '';
                        switch ($e->iTipoObservacionId){
                            case 1:
                                $t = 'X';
                                break;
                            case 2:
                                $f = 'X';
                                break;
                            case 3:
                                $a = 'X';
                                break;
                        }
                        @endphp
                        <td style="text-align: center;">{{$t}}</td>
                        <td style="text-align: center;">{{$f}}</td>
                        <td style="text-align: center;">{{$a}}</td>
                        <td style="text-align: justify;">{{$e->cEstadoObservacion}}</td>
                        <td style="text-align: justify;">{{$e->dtFechaActa}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>3.7 Equipo Técnico del proyecto</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th width="">Apellidos y Nombres</th>
                    <th width="">Cargo</th>
                    <th width="">Profesión</th>
                    <th width="40px">% de Dedicación</th>
                </tr>
                @foreach($miembro as $index=>$f)
                    <tr class="tabla-contenido">
                        <td style="text-align: justify;">{{$f->cPersDescripcion}}</td>
                        <td style="text-align: justify;">{{$f->cTipoMiembroDescripcion}}</td>
                        <td style="text-align: justify;">{{$f->cGrado}}</td>
                        <td style="text-align: right;">{{$f->nDedicacionPorcentaje}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>

<table>
    <tr style="">
        <th class="titulo2"><strong>4. Informe de Avance Financiero </strong></th>
    </tr>
</table>

        <div class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th width="30%">ITEM FINANCIABLE</th>
                    <th width="30%">DOCUMENTO DE GESTIÓN</th>
                    <th width="30%">OBJETIVO LOGRADO</th>
                    <th width="10%">MONTO</th>
                </tr>
                @php $rubroAnt=0;  $i = 1; @endphp
                @foreach($avFinanciero as $index=>$g)
                    @if($rubroAnt !== $g->iRubroId)
                        @php $rubroAnt = $g->iRubroId; $i = 1; @endphp
                        <tr class="tabla-contenido">
                            <td style="text-align: justify;" colspan="4"><strong>
                                    {{$g->cRubroDescripcion}}</strong></td>
                        </tr>
                    @endif
                    <tr class="tabla-contenido">
                        <td style="text-align: justify;">{{$i++}}. {{ $g->cAccion}}</td>
                        <td style="text-align: justify;">{{$g->cDocAprueba}}</td>
                        <td style="text-align: justify;">{{$g->cObjetivo}}</td>
                        <td style="text-align: right;">{{number_format($g->nGasto, 2)}}</td>
                    </tr>
                @endforeach
            </table>

        </div>


<div style="page-break-after:always;"></div>
<table>
    <tr style="">
        <th class="titulo2"><strong>4.1 Resumen de Ejecución Presupuestal</strong></th>
    </tr>
</table>

            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th width="40%">ITEM FINANCIABLES</th>
                    <th width="20%">PRESUPUESTO ASIGNADO (S/)</th>
                    <th width="20%">TOTAL EJECUTADO (S/)</th>
                    <th width="20%">PRESUPUESTO PENDIENTE(S/)</th>
                </tr>
                @php $totalPreAsig=0;  $totalPreEjec = 0; @endphp
                @foreach($resumenEjecPresupuestal as $index=>$h)
                    <tr class="tabla-contenido">
                        @php
                            $totalPreAsig += +$h->totalPresupuesto;
                            $totalPreEjec += +$h->totalGasto;
                        @endphp
                        <td style="text-align: justify;">{{ $h->cRubroDescripcion}}</td>
                        <td style="text-align: right;">{{number_format($h->totalPresupuesto, 2)}}</td>
                        <td style="text-align: right;">{{number_format($h->totalGasto, 2)}}</td>
                        <td style="text-align: right;">{{number_format($h->saldo, 2)}}</td>
                    </tr>
                @endforeach
            </table>
            <br>
            <strong>Avance Presupuestal % = ({{$totalPreEjec}} / {{$totalPreAsig}}) * 100</strong>
            <br>
            @if ($totalPreAsig > 0)
            <strong>Avance Presupuestal % = {{( $totalPreEjec / $totalPreAsig ) * 100}}</strong>
            @endif




<table>
    <tr style="">
        <th class="titulo2"><strong>5. Conclusiones y Recomendaciones</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <ul>
                <li>{{ $dataInfTec[0]->cConclusion }}</li>
                <li>{{ $dataInfTec[0]->cRecomendacion }}</li>
            </ul>

        </td>
    </tr>
    <tr style="">
        <th class="titulo2"><strong>6. Anexos (fotos, tablas comparativas de datos, resultados de pruebas y ensayos,
                analíticas, separatas explicativas, resultados de las actividades realizadas por otros colaboradores,
                etc.)</strong></th>
    </tr>


</table>
</body>

</html>

