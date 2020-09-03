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

    
  </style>
<body>
<div id="header">
<table style="font-size:13px" width="100%">
<tr>
<td  style="text-align:left;"><em>Universidad Nacional de Moquegua</em></td>
<td  style="text-align:center;"><em>Escuela</em></td>
<td  style="text-align:right;"><em>{{$silabo[0]->cCarreraDsc}}</em></td>

</tr>
</table>
          <hr style="margin-top:-2px">  
        </div>
        <p style="text-align:center; font-size:34px; margin-top:-10px"><strong>{{$silabo[0]->cSilActNomCurso}}</strong></p>
        <p style="text-align:left; font-size:16; margin-top:-10px"><strong>1.- DATOS BÁSICOS</strong></p>
        
    <table style="font-size:14px; margin-top:-20px" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Modalidad</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActModal}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Ciclo</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActModal}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Curso</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActCiclo}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Version</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActVersion}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Código</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActCodCurso}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Área Curricular</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActAreaCur}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Tipo de Asignatura</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActTipAsig}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Tipología Especial</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActTipEsp}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Nivel</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActNivel}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Considera, alude</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActConsidera}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Créditos</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActCred}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Horas de Teoría</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActHrsT}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Horas de Práctica</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActHrsP}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Horas Virtuales</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActHrsV}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Pre Requisitos</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActPreRequi}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Autor(es)</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActAutor}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="30%">&nbsp;&nbsp;<strong>Revisado y aprobado</strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActRevisa}}</td>
    </tr>
    </table>
    
    <p style="text-align:left; font-size:12;"><strong>IDENTIFICACIÓN ACADÉMICA DEL DOCENTE</strong></p>
    <table style="font-size:14px; margin-top:-20px" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="40%">&nbsp;&nbsp;<strong>Nombres y Apellidos </strong></td>
    <td  width="70%">&nbsp;&nbsp;{{$silabo[0]->cSilActAutor}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="40%">&nbsp;&nbsp;<strong>Condición y categoría </strong></td>
    <td  width="60%">&nbsp;&nbsp;{{$silabo[0]->cSilActConCat}}</td>
    </tr>
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="40%">&nbsp;&nbsp;<strong>Especialidad en relación a la asignatura </strong></td>
    <td  width="60%">&nbsp;&nbsp;{{$silabo[0]->cSilActEspecial}}</td>
    </tr>
    </table>
    <p style="text-align:left; font-size:12; "><strong>AMBIENTE DONDE SE REALIZA EL APRENDIZAJE</strong></p>
    <table style="font-size:14px; margin-top:-20px" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="60%">&nbsp;&nbsp;<strong>AULA, Taller, laboratorio, según corresponda </strong></td>
    <td  width="40%">&nbsp;&nbsp;{{$silabo[0]->cAulaCiclo}}</td>
    </tr>
    
    </table>
    <p style="text-align:left; font-size:16; "><strong>2.- SUMILLA</strong></p>
    <p style="text-align:justify; font-size:12; margin-top:-20px ">{{$silabo[0]->cSilActSumilla}}</p>
    <div id="footer">
    <hr >
    
    <table style="font-size:13px;margin-top:-10px" width="100%">
<tr>

<td  style="text-align:left;"><em>Fecha: <?php echo date("Y-m-d") ?></em></td>
<td  style="text-align:center;"><em>{{$silabo[0]->cCarreraDsc}}</em></td>
<td  style="text-align:right;" class="page"><em>Página&nbsp;&nbsp;</em></td>

</tr>
</table>
         
  </div>
  <div style="page-break-after:always;"></div>
  <p style="text-align:left; font-size:16; margin-top:-10px"><strong>3.-  COMPETENCIA ELEMENTO DE COMPETENCIA</strong></p>
  <table style="font-size:14px; margin-top:-20px; text-align:center" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:20px" width="50%"><strong>Competencia </strong></td>
    <td style="background-color:#DBDFDB; height:20px" width="50%"><strong>Elemento de competencia </strong></td>
    
    </tr>
    <tr>
    <td style=" height:20px;text-align:justify; font-size:11px;" width="50%">
    <ul>
    @foreach($competenciaActual as $index=>$comp)
    <li><label>{{$comp->cDesComAct}}.</label></li><br>
    @endforeach
    </ul>
    
    </td>

    <td style=" height:20px;text-align:justify; font-size:11px;" width="50%">
    <ul>
    @foreach($elementoActual as $index=>$elem)
    <li><label  >{{$elem->cDesComEle}}.</label></li><br>
    @endforeach
    </ul>
    </td>
    
    </tr>
    <tr>
    <td colspan="2" style="background-color:#DBDFDB; height:20px" width="50%"><strong>Conocimientos y comprensión esenciales </strong></td>
    
    </tr>
    <tr>
    <td colspan="2" style="height:20px;text-align:justify; font-size:11px;" width="50%">
    <ul>
    @foreach($conocimientoActual as $index=>$con)
    <li><label  >{{$con->cDesComCon}}.</label></li><br>
    @endforeach
    </ul>
    </td>
    
    </tr>
    
    </table>
    <p style="text-align:left; font-size:16;"><strong>4.- SECUENCIA DE APRENDIZAJE</strong></p>
    <label style="text-align:left; font-size:13;"><strong>Unidades: </strong></label><br><br>
    @foreach($unidad as $index=>$uni)
    <label style="text-align:left; font-size:11; margin-left:30px">{{$index+1}}.- {{$uni->cDesUniSilAct}}</label><br>
    
    @endforeach
    
    <p style="text-align:left; font-size:16;"><strong>5.- UNIDADES DE APRENDIZAJE</strong></p>
    @foreach($unidad as $index=>$uni)
    <ul><label style="text-align:left; font-size:11; "><strong>{{$index+1}}º UNIDAD TEMÁTICA: {{$uni->cDesUniSilAct}}</strong></label></ul><br>
    
    
    <table style="font-size:11px; margin-top:-10px; text-align:center" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:15px" width="10%"><strong>SEMANA </strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="40%"><strong>CONOCIMIENTOS Y COMPRENSIÓN ESENCIALES</strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="25%"><strong>RESULTADOS DE APRENDIZAJE</strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="25%"><strong>MATERIALES / AULA VIRTUAL</strong></td>
    
    </tr>
    
    @foreach($detalleSecuencia as $index=>$sec)
    @if($sec->iUniSilActId == $uni->iUniSilActId)
    <tr style="font-size:11px;">
    <td style=" height:40px;text-align:center" width="10%">{{$sec->iSemSilActId}}</td>
    <td style=" height:40px;text-align:justify" width="40%">&nbsp;&nbsp;{{$sec->cDesConoAct}}</td>
    <td style=" height:40px;text-align:justify" width="25%">&nbsp;&nbsp;{{$sec->cDesResuAct}}</td>
    <td style=" height:40px;text-align:justify" width="25%"> &nbsp;&nbsp;{{$sec->cDesMateAct}}</td>
    </tr>
    @endif
    @endforeach
    </table>

    @endforeach
    <p style="text-align:left; font-size:16;"><strong>6.- METODOLOGÍA</strong></p>
    <table style="font-size:14px; margin-top:-20px; text-align:justify" width="100%" border="0" cellspacing="0" celspadding="0">
    @foreach($metodologia as $index=>$meto)
    <tr>
    <td style=" height:20px" width="100%"><strong>{{$meto->cDesTipMeto}}: </strong>{{$meto->cDesMetoAct}}</td>
   </tr>
   @endforeach
    
    </table>

    
    <p style="text-align:left; font-size:16;"><strong>7 .- EVALUACIÓN DEL APRENDIZAJE</strong></p>
    <table style="font-size:11px; margin-top:-20px; text-align:center" width="100%" border="1" cellspacing="0" celspadding="0">
    <tr>
    <td style="background-color:#DBDFDB; height:15px" width="10%"><strong>Tipo </strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="25%"><strong>Resultados de Aprendizaje</strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="30%"><strong>Formas de evidenciar los aprendizajes</strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="25%"><strong>Instrumento de evaluación</strong></td>
    <td style="background-color:#DBDFDB; height:15px" width="10%"><strong>Ponderación</strong></td>
    
    </tr>
    @foreach($evaluacion as $index=>$eva)
    <tr>
    <td style=" height:15px" width="10%">{{$eva->cDesTipEva}}</td>
    <td style=" height:15px" width="25%">{{$eva->cDesEvaRes}}</td>
    <td style=" height:15px" width="30%">{{$eva->cDesEvaEvi}}</td>
    <td style=" height:15px" width="25%"> {{$eva->cDesEvaIns}}</td>
    <td style=" height:15px" width="10%"> {{$eva->iPonTipEvaRes}}</td>
    
    </tr>
    @endforeach
    
    
    </table>
    <p style="text-align:left; font-size:16;"><strong>8 .- BIBLIOGRAFÍA</strong></p>
    @foreach($bibliografia as $index=>$bib)
    <label style="text-align:justify; font-size:13px; "><strong>-</strong>{{$bib->cAutBiblioAct}}. {{$bib->iAnioBiblioAct}}. {{$bib->cTitBiblioAct}} {{$bib->cEdiBiblioAct}} - {{$bib->iAnioBiblioAct}} </label><br>
    @endforeach
</body>
</html>