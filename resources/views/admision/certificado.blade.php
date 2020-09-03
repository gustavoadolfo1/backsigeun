<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Constancia de ingreso</title>
</head>
<style>
    @page {
        margin-right: 2.5cm;
        margin-left: 2.5cm;
        
    }
    *{
        font-family: Georgia, Helvetica, sans-serif;
        line-height: 30px
    }
</style>
<body>
    <img src="./img/escudo.jpg" style="display:block;position:absolute;width:80%;top:25%;left:10%;opacity: 0.1;z-index:0">
    <div style="display:block;position:relative;">
        <table style="margin:0px;width:100%;z-index:100">
            <tr>
                <td align="center" style="border-bottom:3px solid black">
                    <img src="./img/escudo.jpg" style="height:50px;position:absolute;left:0px;top:0px">
                    <h3 style="margin-top:-10px"> UNIVERSIDAD NACIONAL DE MOQUEGUA<br> CONCURSO DE ADMISIÓN</h3>
                </td>
            </tr>
            <tr>
                <td align="center" valign="middle" style="height:70px">
                    <h3 style="text-align:center">CONSTANCIA DE INGRESO</h3>
                </td>
            </tr>
        </table>
        <h4>EL QUE SUSCRIBE, EL DIRECTOR DE ADMISIÓN DE LA UNIVERSIDAD NACIONAL DE MOQUEGUA</h4>
        <h4 style="text-align:center">HACE CONSTAR :</h4>
        <table style="width:100%">
            <tr>
                <td width="10">Que,</td>
                <th style="border-bottom:2px solid black;text-align:center">{{ $data->nombres }}</th>
            </tr>
        </table>
        <p style="font-size:18px" align="justify">Postulante al examen de admisión {{ $data->cProcAdmDoc }}, logró una vacante de ingreso a la Universidad Nacional de Moquegua, por la modalidad de @if($data->cModalidadCod == '00')<span>EXAMEN ORDINARIO</span>@else<span>EXAMEN EXTRAORDINARIO</span> @endif - UNAM, según consta en la relación de ingresantes a la Escuela Profesional de <b>{{ trim($data->cCarreraDsc) }}.</b></p><br>
        <p style="text-align:right">Se expide el presente para los fines que crea por conveniente.</p><br>
        <p style="text-align:right">Moquegua, {{ $date }}</p><br>


        <table  style="margin:20px auto">
            <tr>
                <td style="height:50px">
                </td>
            </tr>
            <tr>
                <td  align="center" style="border-top:2px solid black;line-height: 10px">
                    <span style="font-size:12px"> MSC. VICTOR DAMIÁN CAHUANA QUISPE</span>    <br>
                    <small>Director de Admisión - UNAM</small>
                </td>
            </tr>
        </table>
    </div>
    
    
</body>
</html>
+"cPaterno": "CASTILLON"
  +"cMaterno": "VILCAPOMA"
  +"cNombre": "ALEX SALVADOR"