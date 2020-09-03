<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONSTANCIA DE TERCIO SUPERIOR INGRESANTES</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">




  
</head>
<style>
    @page { margin: 0px 0px; }
    #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 0px; background-repeat: no-repeat; }
    #header_logo { position: fixed; left: 100px; top: 40px; right: 0px; height: 0px; background-repeat: no-repeat;opacity: 0.5 }
    #header_h1 { position: fixed; left: 40px; top: 30px; right: 0px; height: 0px; text-align:center; font-size:20px; color:black;opacity: 0.5 }
    #header_h2 { position: fixed; left: 40px; top: 55px; right: 0px; height: 0px; text-align:center; font-size:20px; color:black;opacity: 0.5}
    #header_h3 { position: fixed; left: 40px; top: 75px; right: 0px; height: 0px; text-align:center; font-size:20px; color:black;opacity: 0.5}
    #header_h4 { position: fixed; left: 40px; top: 120px; right: 0px; height: 0px; text-align:center; font-size:11px; color:black;opacity: 0.5}
    
    #footer_r { position: fixed; left: 0px; bottom: -40px; right: 0px; height: 120px; text-align:center; opacity:0.5 }
    #footer_c { position: fixed; left: 80px; bottom: -70px; right: 0px; height: 120px;  }
    #footer_d { position: fixed; left: 0px; bottom: -70px; right: 80px; height: 120px;  }
    #footer_w { position: fixed; left: 80px; bottom: -80px; right: 0px; height: 120px;  }
    
    #footer .page:after { content: counter(page); }
    
</style>
<body> 
 
<div id="header_logo"><img src="img/logo.png" style="width: 90px; height: 70px;"></div>
<div id="header_h1"><strong><p>DIRECCION DE ACTIVIDADES</p></strong></div>
<div id="header_h2"><strong><p>Y</p></strong></div>
<div id="header_h3"><strong><p><i>SERVICIOS ACADEMICOS</i></p></strong></div>
<p style="margin:  108px 80px 0px 80px; text-align: center; line-height: 5px;opacity: 0.5">____________________________________________________________________</p><br>
<div id="header_h4"><p><i>"{{$anio}}"</i></p></div>       
    
        
      
        <div style="margin:  00px 100px 0px 100px">
            <div style=" font-size: 22px;  text-align: center;"><strong><br>&nbsp;CONSTANCIA DE TERCIO SUPERIOR<br>PROMOCION DE INGRESANTES {{$certificado[0]->cSemestreIngreso}}<br>&nbsp;<font size="18px">C.TS.P.IGR.M N° 000{{$nombre[0]->cDocNumDoc}}</font><br>
&nbsp;</strong></div>
        
        </div>


        <div style="margin:  0px 100px 0px 100px">
        

        <br><p align="left" style="line-height:30px; font-size: 14px; text-align: justify;">LA DIRECCION DE ACTIVIDADES Y SERVICIOS ACADÉMICOS DE LA UNIVERSIDAD NACIONAL DE MOQUEGUA HACE CONSTAR QUE:<br> </p>
        <br>
        <center><p style="font-size: 22px; line-height:15px;"><strong>{{$nombreEstudiante}}</strong></p></center>  
        <center><p style="font-size: 18px; line-height:15px;">Con código Nº {{$codigoEstudiante}}</p></center>  
        <br>
        <p align="justify" style="line-height:30px; font-size: 14px" >
         Pertenece al Tercio Superior de la promoción de ingresantes {{$certificado[0]->cSemestreIngreso}}, en la Escuela Profesional de {{$escuelaEstudiante}}; ubicándose en el puesto {{$certificado[0]->iPuesto}} de {{$certificado[0]->iCantidad_Estudiantes}} estudiantes con matricula vigente al Semestre {{$certificado[0]->cSemestreActual}}, con un Promedio Ponderado de <?php echo number_format($certificado[0]->nPromedioPonderado,'4'); ?>, según el ranking general de estudiantes.  
        </p>

           
          
       
            <br>
        <p align="justify" style="line-height:20px; font-size: 14px">
          Se expide la presente a solicitud del interesado, para los fines que considere conveniente.   
        </p>
    </div>
<br>
    <div style="margin:  0px 100px 0px 100px; font-size: 14px">
        
        <p align="right">Moquegua, {{$nombre[0]->cDocFechaDoc}}</p>
        <br>
        
</div>
<div id="footer_r">
<p>_______________________________________________________________________</p>
  <div>
<div id="footer_c">
    <p style="color: #939292; text-align: left; font-size: 9px;" >Prolongación Calle Ancash S/N – Moquegua</p>
  </div> 
  <div id="footer_d">
    <p style="color: #939292; text-align: right; font-size: 9px;" >Tlf. 053-463559</p>
  </div> 
  <div id="footer_w">
    <p style="color: #939292; text-align: left; font-size: 9px;" >www.unam.edu.pe</p>
  </div> 
    
        


    <script src="assets/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>