<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CONSTANCIA DE ESTUDIOS</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">





</head>
<style>
 #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 120px;  }
    #footer .page:after { content: counter(page); }
</style>
<body>

<div style="opacity: 0.5">
            <p style="margin:  0px 80px 0px 80px; position: absolute; left: 20px; top: 0px "><img src="img/logo.png" width="80px" height="70px"  ></p>
            <p style="margin:  0px 80px 0px 80px; text-align: center; line-height: 22px"><strong><font size="20px">DIRECCION DE ACTIVIDADES
            <p style="margin:  0px 80px 0px 80px; text-align: center; line-height: 22px">Y</p>
            <p style="margin:  0px 80px 0px 80px; text-align: center; line-height: 22px">SERVICIOS ACADEMICOS</p>
</font></strong>
            <p style="margin:  0px 0px 0px 0px; text-align: center; line-height: 5px; top:15px">_____________________________________________________________</p><br>
            <p style="margin:  -20px 0px 00px 0px;font-size: 10px; text-align: center;"><i>"{{$anio}}"</i></p>

            </p>
        </div>

        <br>
        @foreach($nombre as $index=>$cons)
        <div style="margin:  0px 80px 0px 80px">
            <div style="border-collapse: separate; border-spacing: 10; border: 1px solid black; border-radius: 13px; line-height: 18px; background: #C8C2C2; font-size: 24px;  text-align: center;"><strong><br>&nbsp;CONSTANCIA DE ESTUDIOS<br>&nbsp;<font size="18px">C.EST. N° 000{{$cons->cDocNumDoc}}</font><br>
&nbsp;</strong></div>

        </div>


        <div style="margin:  0px 80px 0px 80px">


        <br><br><p align="left" style="line-height:15px; font-size: 13px; text-align: justify;">LA DIRECCION DE ACTIVIDADES Y SERVICIOS ACADEMICOS DE LA UNIVERSIDAD NACIONAL DE MOQUEGUA HACE CONSTAR QUE:<br> </p>


        <br>        <p align="justify" style="line-height:20px; font-size: 14px" >
           Don(ña) {{$cons->cNombreEstudiante}}, con código de matricula N° {{$cons->cEstudCodUniv}}, estudiante de la Carrera Profesional de {{$cons->cCarreraDsc}} sede {{$cons->cFilDescripcion}}, ha cursado el {{$cons->cMatricCiclo}} CICLO en el semestre Academico {{$div}} - {{$mod}}.
        </p>

           <br>



        <p align="justify" style="line-height:20px; font-size: 14px">
           Se expide la presente constancia para los fines que se estime conveniente.
        </p>
    </div>
<br><br>
    <div style="margin:  0px 80px 0px 80px; font-size: 13px">

        <p align="right">Moquegua, {{$nombre[0]->cDocFechaDoc}}</p>
        <br>
        @endforeach
</div>
<br><br><br><br>
<div id="footer">
    <p style="color: #939292; text-align: left; font-size: 9px;" >___________________________________________________________________________________________________________________________________________<br>&nbsp;Prolongación Calle Ancash S/N – Moquegua&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tlf. 053-463559 <br>&nbsp;www.unam.edu.pe</p>
  </div>





    <script src="assets/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>
