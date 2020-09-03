<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> SILABO </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
    
</head>
<style>
    @page { margin: 90px 40px; }
    #header { position: fixed; left: 0px; top: -60px; right: 0px; height: 60px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -150px; right: 0px; height: 120px;  text-align: center;}
    #footer .page:after { content: counter(page); }
    body {
      font-family: 'Times New Roman', Times, serif;  }
    
  </style>
<body>
<div id="header">
<table style="font-size:13px" width="100%">
<tr>
<td  style="text-align:left;" width="50%">
<img src="./img/logo.png"  style="height:100vh; position: relative; float: left; ">
<em>&nbsp;Universidad Nacional de Moquegua</em>
</td>

<td  style="text-align:right;" width="50%"><em>Escuela Profesional de {{$carrera[0]->cCarreraCarn}}</em></td>

</tr>
</table>
          <hr style="border: 1px solid #8E8D94;">  
        </div>
        <div id="footer">
        <hr style="border: 1px solid #8E8D94;">  
    
    <table style="font-size:13px;margin-top:-10px" width="100%">
<tr>
<td  style="text-align:left;" width="50%">

<em>&nbsp;Docente: {{$silabus[0]->cSilActAutor}}</em>
</td>

<td  style="text-align:right;" width="50%" class="page"><em>Página&nbsp;&nbsp;</em></td>

<!--
<td  style="text-align:left;"><em>Fecha: </em></td>
<td  style="text-align:center;"><em>{{$silabus[0]->cSilActNomCurso}}</em></td>
<td  style="text-align:right;" class="page"><em>Página&nbsp;&nbsp;</em></td>
-->
</tr>
</table>
         
  </div>
        <p style="text-align:center; font-size:24px;  font-family: 'Times New Roman', Times, serif; "><strong>{{$silabus[0]->cSilActNomCurso}}</strong></p>
        
        
        <p style="text-align:left; font-size:12px; margin-top:20px"><strong>1.- DATOS BÁSICOS</strong></p>
        
    <table style="font-size:10px; margin: 20px 16px 20px 16px;" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Modalidad</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActModal}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Ciclo</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActCiclo}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Curso</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActNomCurso}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Version</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActVersion}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Código</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActCodCurso}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Área Curricular</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActAreaCur}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Tipo de Asignatura</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActTipAsig}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Tipología Especial</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActTipEsp}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Nivel</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActNivel}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Considera, alude</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActConsidera}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Créditos</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActCred}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Horas de Teoría</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActHrsT}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Horas de Práctica</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActHrsP}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Horas Virtuales</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActHrsV}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Pre Requisitos</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActPreRequi}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Autor(es)</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActAutor}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Revisado y aprobado</strong></td>
    <td  width="70%" style="font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActRevisa}}</td>
    </tr>
    </table>
    
    <p style="text-align:left; font-size:12px; margin: 00px 19px 25px 19px; "><strong>IDENTIFICACIÓN ACADÉMICA DEL DOCENTE</strong></p>
    <table style="font-size:10px; margin: -20px 16px 20px 16px;" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="40%">&nbsp;&nbsp;<strong>Nombres y Apellidos </strong></td>
    <td  width="70%" style="text-align:justify;font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActAutor}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="40%">&nbsp;&nbsp;<strong>Condición y categoría </strong></td>
    <td  width="60%" style="text-align:justify;font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActConCat}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="40%">&nbsp;&nbsp;<strong>Especialidad en relación a la asignatura </strong></td>
    <td  width="60%" style="text-align:justify;font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cSilActEspecial}}</td>
    </tr>
    </table>
    <p style="text-align:left; font-size:12px; margin: 0px 19px 25px 19px; "><strong>AMBIENTE DONDE SE REALIZA EL APRENDIZAJE</strong></p>
    <table style="font-size:10px; margin: -20px 16px 40px 16px;" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="60%">&nbsp;&nbsp;<strong>AULA, Taller, laboratorio, según corresponda </strong></td>
    <td  width="40%" style="text-align:justify;font-size:10px;">&nbsp;&nbsp;{{$silabus[0]->cAulaCiclo}}</td>
    </tr>
    
    </table>
    <p style="text-align:left; font-size:12px; margin-top:-20px"><strong>2.- SUMILLA</strong></p>
    <p style="text-align:justify; font-size:10px; margin: 20px 19px 40px 19px; ">{{$silabus[0]->cSilActSumilla}}</p>
    

  <p style="text-align:left; font-size:12px; margin-top:-20px"><strong>3.-  COMPETENCIA ELEMENTO DE COMPETENCIA</strong></p>
    <table style="font-size:10px; margin: 20px 16px 40px 16px; text-align:center;" width="100%" border="1" cellspacing="0" celspadding="10">
    
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="50%"><strong>Competencia </strong></td>
    <td style="background-color:#DBDFDB; height:20px" width="50%"><strong>Elemento de competencia </strong></td>
    
    </tr>
    <tr >
    <td style=" height:20px;text-align:justify; font-size:10px; padding-right: 20px; padding-left: 20px;" width="50%">
    
    @foreach($actual as $index=>$comp)
    <li><label>{{$comp->cDesComAct}}.</label></li><br>
    @endforeach
    
    
    </td>

    <td style=" height:20px;text-align:justify; font-size:11px; padding-right: 20px; padding-left: 20px;" width="50%">
 
    @foreach($elemento as $index=>$elem)
    <li><label  >{{$elem->cDesComEle}}.</label></li><br>
    @endforeach
    
    </td>
    
    </tr>
    <tr>
    <td colspan="2" style="background-color:#DBDFDB; height:20px" width="100%"><strong>Conocimientos y comprensión esenciales </strong></td>
    
    </tr>
    <tr>
    <td colspan="2" style="height:20px;text-align:justify; font-size:11px; padding-right: 20px; padding-left: 20px;" width="100%">
   
    @foreach($conocimiento as $index=>$con)
    <li><label  >{{$con->cDesComCon}}.</label></li><br>
    @endforeach
    
    </td>
    
    </tr>
    
    </table>
    <p style="text-align:left; font-size:12px; margin: -20px 0px 30px 0px;"><strong>4.- SECUENCIA DE APRENDIZAJE</strong></p>
    <p style="text-align:left; font-size:10px; margin: -20px 20px 20px 20px;"><strong>Unidades</strong></p>
    
    @foreach($unidad as $index=>$uni)
    
    <p style="text-align:left; font-size:10px; margin: -20px 20px 20px 20px;"><label style="text-align:left; font-size:10px; margin-left:30px">{{$index+1}}.- {{$uni->cDesUniSilAct}}</label></strong></p>
    @endforeach


    <?php $c=1; ?>
    <p style="text-align:left; font-size:12px; "><strong>5.- UNIDADES DE APRENDIZAJE</strong></p>
    @foreach($unidad as $index=>$uni)
    <p style="text-align:left; font-size:10px; margin: 20px 20px 30px 20px;"><strong>{{$index+1}}º UNIDAD TEMÁTICA: {{$uni->cDesUniSilAct}}</strong></p>
    
    <table style="font-size:10px; margin: -20px 16px 40px 16px; text-align:center" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; " ><strong>SEMANA </strong></td>
    <td style="background-color:#DBDFDB; " ><strong>CONOCIMIENTOS Y COMPRENSIÓN ESENCIALES</strong></td>
    <td style="background-color:#DBDFDB; " ><strong>RESULTADOS DE APRENDIZAJE</strong></td>
    <td style="background-color:#DBDFDB; "><strong>MATERIALES / AULA VIRTUAL</strong></td>
    
    </tr>
    
    @foreach($unidad_detalle as $index=>$sec)
    @if($sec->iUniSilActId == $uni->iUniSilActId)
    <tr style="font-size:10px;">
    <td style=" text-align:center " width="5%">{{$c}}</td>
    <td style=" text-align:justify; padding-right: 5px; padding-left: 5px;" width="30%">
    <?php  $resultado = explode(". ", $sec->cDesConoAct); 
    for($j=0;$j<count($resultado);$j++){
      echo "-".$resultado[$j]."<br>";
    }
    
    ?>
    </td>
    <td style=" text-align:justify; padding-right: 5px; padding-left: 5px;" width="30%">
    
    
    <?php  $resultado = explode(". ", $sec->cDesResuAct); 
    for($j=0;$j<count($resultado);$j++){
      echo "-".$resultado[$j]."<br>";
    }
    
    ?>
    
    </td>
    <td style=" text-align:justify; padding-right: 5px; padding-left: 5px;" width="35%">
    
    
    
    <?php  $resultado = explode(". ", $sec->cDesMateAct); 
    for($j=0;$j<count($resultado);$j++){
      echo "-".$resultado[$j]."<br>";
    }
    
    ?>
    
    </td>
    </tr>
    <?php $c++ ?>
    @endif
    @endforeach
    </table>
    @endforeach
    
    <p style="text-align:left; font-size:12px; margin-top:-20px"><strong>6.- METODOLOGÍA</strong></p>
    <table style="font-size:10px; margin: 20px 16px 40px 16px; text-align:justify" width="100%" border="0" cellspacing="0" celspadding="0">
    @foreach($metodologia as $index=>$meto)
    <tr>
    <td tyle="text-align:justify; font-size:10px;" width="100%"><strong>{{$meto->cDesTipMeto}}: </strong>{{$meto->cDesMetoAct}}
    <br><br></td>
   </tr>
   @endforeach
    
    </table>

    
    <p style="text-align:left; font-size:12px; margin-top:-20px"><strong>7 .- EVALUACIÓN DEL APRENDIZAJE</strong></p>
    <table style="font-size:10px; margin: 20px 16px 40px 16px; text-align:center" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; " width="10%"><strong>Tipo </strong></td>
    <td style="background-color:#DBDFDB; " width="25%"><strong>Resultados de Aprendizaje</strong></td>
    <td style="background-color:#DBDFDB; " width="30%"><strong>Formas de evidenciar los aprendizajes</strong></td>
    <td style="background-color:#DBDFDB; " width="25%"><strong>Instrumento de evaluación</strong></td>
    <td style="background-color:#DBDFDB; " width="10%"><strong>Ponderación</strong></td>
    
    </tr>
    @foreach($evaluacion as $index=>$eva)
    <tr>
    <td style="background-color:#DBDFDB; height:15px" width="10%" >{{$eva->cDesTipEva}}</td>
    <td style=" height:15px;text-align:justify;padding-right: 5px; padding-left: 5px; " width="27%">
    <?php  $resultado = explode(". ", $eva->cDesEvaRes); 
    for($j=0;$j<count($resultado);$j++){
      echo "-".$resultado[$j]."<br>";
    }
    
    ?>
    </td>
    <td style=" height:15px;text-align:justify; padding-right: 5px; padding-left: 5px;" width="26%">
    <?php  $evidencia = explode(". ", $eva->cDesEvaEvi); 
    for($j=0;$j<count($evidencia);$j++){
      echo "-".$evidencia[$j]."<br>";
    }
    
    ?>
    </td>
    <td style=" height:15px;text-align:justify; padding-right: 5px; padding-left: 5px;" width="27%">
    <?php  $instrumento = explode(". ", $eva->cDesEvaIns); 
    for($j=0;$j<count($instrumento);$j++){
      echo "-".$instrumento[$j]."<br>";
    }
    
    ?>
    </td>
    <td style=" height:15px;text-align:center" width="10%">{{$eva->iPonTipEva}} </td>
    
    </tr>
    @endforeach
    
    
    </table>
    <p style="text-align:left; font-size:12px; margin-top:-20px"><strong>8 .- BIBLIOGRAFÍA</strong></p>
    <table style="font-size:10px; margin: 20px 16px 40px 16px; text-align:center;" width="100%" border="0" cellspacing="0" celspadding="0">
    <tr>
    <td style=" height:20px;text-align:justify; font-size:10px; padding-right: 20px; padding-left: 20px;" width="50%">
    

    @foreach($bibliografia as $index=>$bib)
     <li><label style="text-align:justify; font-size:10px; ">{{$bib->cAutBiblioAct}} ({{$bib->iAnioBiblioAct}}) <em>{{$bib->cTitBiblioAct}}.</em>&nbsp;{{$bib->cPaiBiblioAct}}  </label></li><br>
    
    @endforeach
    </td>
    </tr>
    </table>
</body>
</html>