<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> LISTA DE CONVENIOS </title>
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
<br>
<div id="header">
    <table style="font-size:13px" width="100%">
        <tr>
            <td width="15" style="text-align:left;"><em><img src="./img/logo.png" id="img-logo" style="height:15px; position: relative; float: left; margin-left: 1px; bottom: -10px">
                </em></td>
            <td style="text-align:left; margin-left: 20px; position: relative"><em> Universidad Nacional de Moquegua</em></td>
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
<!-----------ENCABEZADO------>
<img src="./img/logo.png" id="img-logo" style="height:55px; position: relative; float: left; margin-left: 20px;">
<table align="center" style="margin-left: 130px; margin-right:120px; margin-top: -10px;" width="100%">
    <tr style="font-size: 20px; text-align: center">
        <th><strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong></th>
    </tr>

</table>
<br>

<!------------------------>






        <table width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
            <thead class="  ">
            <tr style="font-size: 13px; text-align: center">
                <th width="100%" colspan="8" style="text-align:center"><strong>{{$a->anyo}}</strong></th>
            </tr>
            <tr class="card-header" style="font-size:11px;background: #2B2E4A; color:white">
                <th width="1%" style="text-align:center"><strong>N°</strong></th>
                <th width="34%" style="text-align:center"><strong>Tipo de Convenio</strong></th>
                <th width="20%" style="text-align:center"><strong>Entidad</strong></th>
                <th width="5%" style="text-align:center"><strong>Resolucion</strong></th>
                <th width="10%" style="text-align:center"><strong>Fecha Incio</strong></th>
                <th width="10%" style="text-align:center"><strong>Fecha Fin</strong></th>
                <th width="10%" style="text-align:center"><strong>Vigencia</strong></th>
                <th width="10%" style="text-align:center"><strong>Objetivo</strong></th>
            </tr>
            </thead>
            <tbody>
            <tr style="font-size: 10px">
                <td style="text-align: center">jjj</td>
                <td style="text-align: left;">{{$a->Tipo_Convenio}}</td>
                <td style="text-align: left;">{{$a->Entidad}}</td>
                <td style="text-align: right;">{{ $a->Resolucion }}</td>
                <td style="text-align: right;">{{ $a->Fecha_Inicio }}</td>
                <td style="text-align: right;">{{ $a->Fecha_Fin }}</td>
                <td style="text-align: right;">{{ $a->Vigencia }}</td>
                <td style="text-align: right;">{{ $a->Objetivo_Convenio }}</td>
            </tr>
            </tbody>

        </table>


</html>
