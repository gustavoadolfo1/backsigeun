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

        <tr  style="font-size: 13px; text-align: center">
            <th><strong>UNIDAD DE SALUD / SERVICIO SALUD</strong>
            </th>
        </tr>
       
        <tr  style="font-size: 13px; text-align: center">
            <td></td>
        </tr>
    </table>
    <br>
    <center><h3 style="text-align:center"><strong>REGISTRO DIARIO DE ATENCIÓN A ESTUDIANTES, PERSONAL DOCENTE Y ADMINISTRATIVO ( {{$fecha}} )</strong></h3></center>

    
    
    <table  width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                  <tr class="card-header"  style="font-size:11px;background: #2B2E4A; color:white" >
                    <th width="5%"  style="text-align:center" ><strong>N°</strong> </th>
                    <th width="5%" style="text-align:center"><strong>HORA</strong> </th>
                    <th width="25%" style="text-align:center"><strong>APELLIDOS Y NOMBRES</strong> </th>
                    <th width="20%" style="text-align:center"><strong>ASUNTO</strong> </th>
                    <th width="25%"  style="text-align:center"><strong>DEPENDENCIA O ESCUELA PROFESIONAL</strong> </th>
                    <th width="10%"  style="text-align:center"><strong>CODIGO / DNI</strong> </th>
                    <th width="10%"  style="text-align:center"><strong>CELULAR</strong> </th>
                   
                    
                  </tr>
                 
                </thead>
                <tbody>
                @foreach($atencion as $index=>$a)
                
                  <tr style="font-size: 10px">
                    <td style="text-align:center">{{$index+1}}</td>
                    <td style="text-align: center;">{{$a->hora_atencion}}</td>
                    <td style="text-align: center;">{{$a->cPersPaterno}} {{$a->cPersMaterno}}, {{$a->cPersNombre}}</td>
                    <td>{{$a->motivo_atencion}}</td>
                    <td>{{$a->dependencia_escuela}}</td>
                    <td style="text-align: center;">{{$a->codigo_dni}}</td>
                    <td></td>
                    
                  </tr>
                  
                  @endforeach
                </tbody>
              </table>
    <br>
    
    
   
</body>

</html>