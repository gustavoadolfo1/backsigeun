<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> LISTA DE PRESUPUESTO </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
</head>
<style>
    @page {
        margin: 90px 40px;
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


</style>
<body>
<br>
<div id="header">
    <table style="font-size:13px" width="100%">
        <tr>
            <td width="15" style="text-align:left;"><em><img src="./img/logo.png" id="img-logo" style="height:15px; position: relative; float: left; margin-left: 1px; bottom: -10px">
                </em></td>
            <td  style="text-align:left; margin-left: 20px; position: relative"><em> Universidad Nacional de Moquegua</em></td>
            <td  style="text-align:center;"><em></em></td>
            <td  style="text-align:right;"><em></em></td>
        </tr>
    </table>
    <hr style="margin-top:-2px">
</div>
<div id="footer">
    <hr >

    <table style="font-size:13px;margin-top:-10px" width="100%">
        <tr>

            <td  style="text-align:left;"><em>Fecha: <?php echo date("Y-m-d") ?></em></td>
            <td  style="text-align:center;"><em></em></td>
            <td  style="text-align:right;" class="page"><em>Página&nbsp;&nbsp;</em></td>

        </tr>
    </table>

</div>

<img src="./img/logo.png" id="img-logo" style="height:55px; position: relative; float: left; margin-left: 20px;">
<table align="center" style="margin-left: 130px; margin-right:120px; margin-top: -10px;" width="100%">
    <tr style="font-size: 20px; text-align: center">
        <th><strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong></th>
    </tr>
    <tr style="font-size: 13px; text-align: center">
        <th><strong>{{ $data1[0]->cDepenDependeNombre }}</strong>
        </th>
    </tr>
    <tr style="font-size: 13px; text-align: center">
        <th><strong>{{ $data1[0]->cDepenNombre }}</strong>
        </th>
    </tr>
</table>
<br>

@php
    $anyoAnt='';
    $estadoPropuestaAnt='';
@endphp

@foreach($data2 as $index=>$a)
    @if ($a->anyo <> $anyoAnt)

        @if ($anyoAnt <> '')
           <tr style="font-size: 10px; text-align: center">
                <td colspan="4" style="text-align:right; font-size: 11px;"><strong>Total</strong></td>
                <td style="text-align: right;">{{ number_format(($totalSumPresupuesto), 2, '.', '') }}</td>
            </tr>
            </tbody>
            </table>
            <br>
        @endif
        @php
            $nro = 1;
            $totalNroProy = 0;
            $totalSumPresupuesto = 0;
        @endphp
        @if ($a->iEstadoPropuesta <> $estadoPropuestaAnt)
            @switch($a->iEstadoPropuesta)
                @case(0)
                <h3 style="text-align:center"><strong>Presupuesto de Proyectos por Rubros por Año</strong></h3>
                @break

                @case(1)
                <h3 style="text-align:center"><strong>Presupuesto de Propuestas de Proyectos por Rubros por Año</strong></h3>
                @break
            @endswitch

        @endif
        <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
            <thead class="  ">
            <tr style="font-size: 13px; text-align: center">
                <th width="100%" colspan="5" style="text-align:center"><strong>{{$a->anyo}}</strong></th>
            </tr>
            <tr class="card-header" style="font-size:11px;background: #2B2E4A; color:white">
                <th width="5%" style="text-align:center"><strong>N°</strong></th>
                <th width="30%" style="text-align:center"><strong>Tipo de proyecto</strong></th>
                <th width="30%" style="text-align:center"><strong>Resolución que aprueba</strong></th>
                <th width="25%" style="text-align:center"><strong>Rubro</strong></th>
                <th width="10%" style="text-align:center"><strong>Presupuesto</strong></th>
            </tr>
            </thead>
            <tbody>
            <tr style="font-size: 10px">
                <td style="text-align: center">{{$nro}}</td>
                <td style="text-align: left;">{{$a->cTipoProyDescripcion}}</td>
                <td style="text-align: left;">{{$a->cResProyecto}}</td>
                <td style="text-align: left;">{{$a->cRubroDescripcion}}</td>
                <td style="text-align: right;">{{ number_format(($a->sumPresupuesto), 2, '.', '') }}</td>
            </tr>
        @php
            $anyoAnt = $a->anyo;
            $estadoPropuestaAnt =  $a->iEstadoPropuesta ;
        @endphp

    @else
        <tr style="font-size: 10px">
            <td style="text-align: center">{{$nro}}</td>
            <td style="text-align: left;">{{$a->cTipoProyDescripcion}}</td>
            <td style="text-align: left;">{{$a->cResProyecto}}</td>
            <td style="text-align: left;">{{$a->cRubroDescripcion}}</td>
            <td style="text-align: right;">{{ number_format(($a->sumPresupuesto), 2, '.', '') }}</td>
        </tr>
    @endif
    @php
        $nro += 1;
        $totalNroProy += $a->nroProyectos;
        $totalSumPresupuesto += $a->sumPresupuesto;
    @endphp
@endforeach
            <tr style="font-size: 10px; text-align: center">
                <td colspan="4" style="text-align:right; font-size: 11px;"><strong>Total</strong></td>
                <td style="text-align: right;">{{ number_format(($totalSumPresupuesto), 2, '.', '') }}</td>
            </tr>
            </tbody>
        </table>

</body>

</html>
