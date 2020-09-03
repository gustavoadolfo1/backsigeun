<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONSTANCIA DE ORDEN DE MERITO</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">




  
</head>
<style>
  #footer_r { position: fixed; left: 0px; bottom: -40px; right: 0px; height: 120px; text-align:center; opacity:0.5 }
    #footer_c { position: fixed; left: 80px; bottom: -70px; right: 0px; height: 120px;  }
    #footer_d { position: fixed; left: 0px; bottom: -70px; right: 80px; height: 120px;  }
    #footer_w { position: fixed; left: 80px; bottom: -80px; right: 0px; height: 120px;  }
</style>
<body> 
  
<div style="opacity: 0.5">
            <p style="margin:  0px 80px 0px 80px; position: absolute; left: 20px; top: -20px "><img src="img/logo.png" width="90px" height="70px"  ></p>
            <p style="margin:  0px 80px 0px 80px; text-align: center; line-height: 22px"><strong><font size="20px">DIRECCION DE ACTIVIDADES 
            <p style="margin:  0px 80px 0px 80px; text-align: center; line-height: 22px">Y</p>
            <p style="margin:  0px 80px 0px 80px; text-align: center; line-height: 22px">SERVICIOS ACADEMICOS</p>
</font></strong>
            <p style="margin:  0px 0px 0px 0px; text-align: center; line-height: 5px">_____________________________________________________________</p><br>
            <p style="margin:  -20px 0px 00px 0px;font-size: 10px; text-align: center;"><i>"{{$anio}}"</i></p>  
                
            </p>
        </div>

        
      
        <div style="margin:  0px 80px 0px 80px">
            <div style=" font-size: 22px;  text-align: center;"><strong><br>&nbsp;CONSTANCIA DE ORDEN DE MERITO<br>&nbsp;<font size="18px">C.O.M N° {{$nombre[0]->cDocNumDoc}}</font><br>
&nbsp;</strong></div>
        
        </div>


        <div style="margin:  20px 80px 0px 80px">
        

        <br><p align="left" style="line-height:15px; font-size: 13px; text-align: justify;">LA DIRECCION DE ACTIVIDADES Y SERVICIOS ACADÉMICOS DE LA UNIVERSIDAD NACIONAL DE MOQUEGUA HACE CONSTAR QUE:<br> </p>
        
        <center><p style="font-size: 22px"><strong>{{$nombreEstudiante}}</strong></p></center>  
         
        <br>
        <p align="justify" style="line-height:20px; font-size: 14px" >
         Se ubicó en el puesto {{$certificado[0]->iPuesto}} de {{$certificado[0]->iCantidad_Estudiantes}} estudiantes matriculados, con Promedio Ponderado <?php echo number_format($certificado[0]->nPromedioPonderado,'4'); ?>, correspondiente al Ciclo {{$certificado[0]->cCiclo}} en el Semestre Académico {{$certificado[0]->cSemestre}} , de la Escuela Profesional de {{$escuelaEstudiante}}. 
        </p>

           
          
       
            <br>
        <p align="justify" style="line-height:20px; font-size: 13px">
          Se expide la presente a solicitud del interesado, para los fines que considere conveniente.    
        </p>
    </div>

    <div style="margin:  0px 80px 0px 80px; font-size: 13px">
        
        <p align="right">Moquegua, {{$nombre[0]->cDocFechaDoc}}.</p>
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