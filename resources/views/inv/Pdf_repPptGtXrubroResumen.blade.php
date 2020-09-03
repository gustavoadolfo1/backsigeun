<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> REPORTE DE PRESUPUESTO Y GASTO POR RUBRO </title>
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

            <td style="text-align:left;"><em>Fecha: <?php echo date("d-m-Y") ?></em></td>
            <td style="text-align:center;"><em></em></td>
            <td style="text-align:right;" class="page"><em>Página&nbsp;&nbsp;</em></td>

        </tr>
    </table>

</div>

<table align="center" style="margin-top: -10px;" width="100%">
    <tr style="font-size: 14px; text-align: center">
        <th><strong>REPORTE DE PRESUPUESTO Y GASTO POR RUBRO RESUMIDO</strong></th>
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
                    <td><b>Resolución de aprobación </b></td>
                    <td>:</td>
                    <td>{{ $dataProy[0]->cResProyecto }}</td>
                </tr>
                <td><b>Fecha del reporte </b></td>
                <td>:</td>
                <td>@php echo date('d-m-Y H:m:s'); @endphp</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="">
        <th class="titulo1"><strong>2. Presupuesto y Gastos por Rubro</strong></th>
    </tr>
    <tr>
        <td class="contenido1">
            <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <tr class="tabla-titulo">
                    <th>Nº</th>
                    <th width="%">Rubro</th>
                    <th width="">Presupuesto</th>
                    <th width="">Ejecutado</th>
                    <th width="">Saldo</th>
                </tr>
                @foreach($resumen as $index=>$a)
                    <tr class="tabla-contenido">
                        <td>{{($index + 1)}}</td>
                        <td style="text-align: justify;">{{$a->cRubroDescripcion}}</td>
                        <td style="text-align: right;">{{number_format($a->totalPresupuesto, 2)}}</td>
                        <td style="text-align: right;">{{number_format($a->totalGasto, 2)}}</td>
                        <td style="text-align: right;">{{number_format($a->saldo, 2)}}</td>
                    </tr>
                @endforeach
                @foreach($avPresupuestal as $index=>$b)
                    <tr class="tabla-contenido" >
                        <td colspan="5">
                            <div><b>Presupuesto del Proyecto:</b> S/. {{$b->nPresupuestoProyecto}}<br>
                                <b>Asignado a Rubros de Gasto:</b> S/. {{$b->totalPresupuesto}}<br>
                                <b>Presupuesto Disponible:</b> S/. {{$b->saldo}}<br>
                                <b>Presupuesto Ejecutado:</b> S/. {{ $b->totalGasto }} Avance al
                                ({{ $b->avance }}%)
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>
</body>

</html>

