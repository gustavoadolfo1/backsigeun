<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CERTIFICADO DE ESTUDIOS</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

  
</head>
<style>
 #footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 120px;  }
    #footer .page:after { content: counter(page); }
</style>
<body> 
<p style="position: absolute;top: 50px; left:90%"><strong>N°&nbsp;0{{$cod}}</strong></<p>

     <table border="" align="center">
        <tr align="center">
             <th valign="bottom" ><strong><font size="20px">UNIVERSIDAD NACIONAL DE MOQUEGUA</font></strong>

             </th>
        </tr>
        
        <tr  style="font-size: 11px; text-align: center;">
            <th valign="TOP"><strong>VICEPRESIDENCIA ACADÉMICA<br>DIRECCIÓN DE ACTIVIDADES Y SERVICIOS ACADÉMICOS
                </strong>
            </th>
        </tr>
        <tr  style="font-size: 30px; font-family: 'Arial Black' ; text-align: center;">
            <th ><br>CERTIFICADO DE ESTUDIOS
                
            </th>
        </tr>
        
        
        </table>
       <br>
        <div align="justify">
        <p style="font-size: 13px; text-align: justify;">LA DIRECCIÓN DE ACTIVIDADES Y SERVICIOS ACADÉMICOS DE LA UNIVERSIDAD NACIONAL DE MOQUEGUA CERTIFICA:</p>
        <p style="font-size: 13px; text-align: justify;">Que don(ña):&nbsp;<strong style="text-decoration: underline;">&nbsp;&nbsp;{{$nombreEstudiante}}&nbsp;&nbsp;</strong>, con Código: &nbsp;<strong style="text-decoration: underline;">&nbsp;&nbsp;{{$codigoEstudiante}}&nbsp;&nbsp;.</strong><br>
            Ha cursado las asignaturas que abajo se indican en la escuela profesional de:&nbsp;<strong style="text-decoration: underline;">&nbsp;&nbsp;{{$escuelaEstudiante}}&nbsp;&nbsp;</strong>,<br> Sede/Lugar:&nbsp;<strong style="text-decoration: underline;">&nbsp;&nbsp;{{$sedeEstudiante}}&nbsp;&nbsp;</strong><br>Habiendo obtenido las calificaciones siguientes:</p>
        </div>
    
        <table border="1" style="font-family: Calibri Light; font-size:13px; border: black 1px solid;" width="100%">
                
                <thead>
                    <tr style="background-color: #F9F9F9;font-size: 13px; text-align: center;  ">
                        <td width="50%" style="border: black 1px solid;"><strong>&nbsp;ASIGNATURA&nbsp;</strong></td>
                        <td width="15%" style="border: black 1px solid;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;CREDITOS&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                        <td width="20%" style="border: black 1px solid;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;CALIFICATIVOS&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                        
                        <td width="15%" style="border: black 1px solid;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;SEMESTRE&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                       
                    </tr>
                </thead>
                <tbody>
                       <?php $ciclo=1; $c="0"; $sump=0; $sumc=0; $ce=1; $el=""; ?>
                        @foreach($certificado as $index=>$cert)
                            
                            @if($cert->cMatricDetCicloCurso==$c.$ciclo && $cert->cNombre_Ciclos !="CURSOS ELECTIVOS")
                            <tr style="font-size: 12px; text-align: center;  ">
                            <td  style=" text-align: left;  border: white 1px solid;"><strong>{{$cert->cNombre_Ciclos}}<strong></td>
                            <td  style=" text-align: left;  border: white 1px solid;"></td>
                            <td  style=" text-align: left;  border: white 1px solid;"></td>
                            <td  style=" text-align: left;  border: white 1px solid;"></td>
                            
                            <?php $ciclo++; ?>
                            </tr>
                            @endif
                            @if($cert->cMatricDetCicloCurso==$ciclo && $cert->cNombre_Ciclos =="CICLO 10")
                            <tr style="font-size: 12px; text-align: center; ">
                            <td  style=" text-align: left; border: white 1px solid;"><strong>{{$cert->cNombre_Ciclos}}<strong></td>
                            <td  style=" text-align: left; border: white 1px solid;"></td>
                            <td  style=" text-align: left; border: white 1px solid;"></td>
                            <td  style=" text-align: left; border: white 1px solid;"></td>
                            <?php $ciclo++; ?>
                            </tr>
                            @endif
                            @if($ce==1 && $cert->cNombre_Ciclos =="CURSOS ELECTIVOS")
                            <tr style="font-size: 12px; text-align: center; ">
                            <td  style=" text-align: left; border: white 1px solid; "><br></td>
                            <td  style=" text-align: left; border: white 1px solid; "><br></td>
                            <td  style=" text-align: left; border: white 1px solid; "><br></td>
                            <td  style=" text-align: left; border: white 1px solid; "><br></td>
                            <?php $ciclo++; $ce++; $el=" (EL)"; ?>
                            </tr>
                            @endif
                            <tr style="font-size: 11px; text-align: left;  ">
                            <td style=" text-align: left; border: white 1px solid; ">{{$cert->cCurricCursoDsc}}&nbsp;{{$el}}</td>
                            <td style=" text-align: center; border: white 1px solid; ">{{$cert->iMatricDetCredCurso}}</td>
                            <td style=" text-align: center; border: white 1px solid; ">({{$cert->nMatricDetNotaCurso}})&nbsp;{{$cert->cNumero_a_Letra}}</td>
                            <td style=" text-align: center; border: white 1px solid; ">{{$cert->cControlCicloAcademico}}</td>
                            </tr>
                           <?php $sumc =  $sumc + ($cert->nMatricDetNotaCurso * $cert->iMatricDetCredCurso ); $sump = $sump + $cert->iMatricDetCredCurso;?>
                        @endforeach
                        <tr style="border: black 1px solid;">
                        <td style="text-align:center" colspan="2"><strong> TOTAL DE CREDITOS: <?php echo $sump; ?></strong></td>
                        <td style="text-align:center" colspan="2"><strong> PROMEDIO PONDERADO: <?php  $pp = $sumc/$sump; echo number_format($pp,'2'); ?></strong></td>
                        </tr>
                        
                       
            
        </table>
    <p style="font-size:9px">Así consta en las actas de evaluación que obran en la Dirección de Actividades y Servicios Académicos</p>
                 
    <p style="font-size:13px; text-align:right">Moquegua, {{$fecha}}</p>
<br>
<div id="footer">
    <p style="color: #939292; text-align: left; font-size: 9px;" >___________________________________________________________________________________________________________________________________________<br><strong>NOTA:</strong><br>(EL) Curso Electivo<br>Las enmendaduras invalidan el certificado<br>NOTA APROBATORIA DE 11 A 20 PUNTOS </p>
  </div>   
   
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>  


</body>

</html>