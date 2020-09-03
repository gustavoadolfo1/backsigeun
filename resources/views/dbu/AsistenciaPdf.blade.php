<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> LISTA DE ASISTENCIA </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
    
</head>

<body>
    <img src="./img/logo.png" id="img-logo" style="height:55px; position: relative; float: left; margin-left: 20px;">
    <table align="center" style="margin-left: 130px; margin-right:120px; margin-top: -10px;" width="100%">
        <tr style="font-size: 20px; text-align: center">
            <th><strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong></th>
        </tr>
        
        <tr  style="font-size: 13px; text-align: center">
            <th><strong>DIRECCI&Oacute;N DE BIENESTAR UNIVERSITARIO</strong>
            </th>
        </tr>
        <tr style="font-size: 13px; text-align: center; margin-top:10px">
            <th style="padding-left: 155px; padding-top: 15px"></th>
        </tr>
        <tr  style="font-size: 13px; text-align: center">
            <td></td>
        </tr>
    </table>
    <br>
    <center><h3 style="text-align:center"><strong>LISTA DE ASISTENCIA DE ESTUDIANTES DEL COMEDOR UNIVERSITARIO</strong></h3></center>

    <p><strong>FECHA: </strong>{{$fecha}}</p>
    
    <table  width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                  <tr class="card-header"  style="font-size:11px;background: #2B2E4A; color:white" >
                    <th width="5%"  style="text-align:center" ><strong>N°</strong> </th>
                    <th width="10%" style="text-align:center"><strong>CÓDIGO</strong> </th>
                    <th width="35%" style="text-align:center"><strong>NOMBRES Y APELLIDOS</strong> </th>
                    <th width="25%"  style="text-align:center"><strong>ESCUELA PROFESIONAL</strong> </th>
                    <th width="10%"  style="text-align:center"><strong>ASISTENCIA</strong> </th>
                    
                  </tr>
                  @foreach($asistencia as $index=>$a)

                  <tr style="font-size: 11px">
                  <td style="text-align:center;">{{$index+1}}</td>
                  <td style="text-align:center;">{{$a->cEstudCodUniv }}</td>
                  <td>{{$a->cEstudNombres }} {{$a->cEstudApellidos }}</td>
                  <td>{{$a->cCarreraCarn }}</td>
                  <td style="text-align:center;">{{$a->asistencia }}</td>
                  

                  </tr>
                  @endforeach
                 
                  



                

                </thead>

              </table>
    <br>
    
    <br>
    <p style="line-height:5px">_____________________________</p>
    <p style="font-size:9px; margin-top:-10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lic. Marlene V. Cajaña Quispe</p>
    
   
</body>

</html>