<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> REPORTE DE PRESUPUESTO POR RUBRO </title>
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


<br>

<table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
    <tr class="tabla-titulo">
        <th>Nº</th>
        <th width="%">Nombre Proyecto</th>
        <th width="">Avance Tecnico</th>
        <th width="">Avance Financiero</th>
    </tr>
    @foreach($resumen as $index=>$a)
        <tr class="tabla-contenido">
            <td>{{($index + 1)}}</td>
            <td style="text-align: justify;" >
                <div style="margin-left: 10px; margin-right: 10px">
                    {{$a->cNombreProyecto}}
                </div>
            </td>
            <td style="text-align: right;">{{number_format($a->porcentajeAvanceTecnico, 2)}}</td>
            <td style="text-align: right;">{{number_format($a->porcentajeAvanceFinaciero, 2)}}</td>
        </tr>
    @endforeach
</table>

</html>

