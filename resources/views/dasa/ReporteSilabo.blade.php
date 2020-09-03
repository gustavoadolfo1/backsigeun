<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> REPORTE LLENADO SILABO </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
    
</head>
<style>
    
   
   
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

<td  style="text-align:right;" width="50%"><em>Escuela Profesional de {{$silabo[0]->cCarreraCarn}} - {{$silabo[0]->cFilDescripcion}} </em></td>

</tr>
</table>
        
        <br>
    
    <center><h3 style="text-align:center"><strong>REPORTE DETALLADO - LLENADO DEL SÍLABO DOCENTE</strong></h3></center> 
    <table  width="100%"  cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                <tr style="text-align: center; background: #2B2E4A; color:white">
                    <th style="width: 3%; font-size:9px">N°</th>
                    <th style="width: 14%; font-size:9px">DOCENTE</th>
                    <th style="width: 6%; font-size:9px">CURSO</th>
                    <th style="width: 3%; font-size:9px">SECCION</th>
                    <th style="width: 10%; font-size:9px">SECCIÓN 1<br>Datos Generales</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 2<br>Sumilla</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 3<br>Competencias</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 4<br>Secuencia</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 5<br>Unidades</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 6<br>Metodología</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 7<br>Evaluación</th>
                    <th style="width: 8%; font-size:9px">SECCIÓN 8<br>Bibliografía</th>
                    <th style="width: 8%; font-size:9px">CUMPLIMIENTO %</th>
                </tr>

                
                  @foreach($docente as $index=>$doc)
                  <tr>
                  <td style="text-align: center; font-size:9px"  ><strong>{{ $index + 1 }}</strong></td>

                 

                  <td style="text-align: left; font-size:9px" >
                  <?php $x=0;?>
                 
                  @foreach($silabo as $index=>$sil)
                  @if($x==0)
                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->cNombre_Docente }}<br>
                  <?php $x++; ?>
                  @endif
                  @endif
                  @endforeach
                  
                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->cCargaHCurso }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->cSeccionDsc }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion1 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion2 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion3 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion4 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion5 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion6 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion7 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->seccion8 }}<br>
                  @endif

                  @endforeach

                  </td>

                  <td style="text-align: center; font-size:9px" >
                  @foreach($silabo as $index=>$sil)

                  @if($doc->iDocenteId == $sil->iDocenteId)
                  
                  {{ $sil->cumplimiento }} %<br>
                  @endif

                  @endforeach

                  </td>

                  
                 
                      
                  </tr>
                  
                  @endforeach
                 
                </thead>

              </table>
              <br>
    <center><h3 style="text-align:center"><strong>REPORTE RESUMEN - LLENADO DEL SÍLABO DOCENTE</strong></h3></center> 
    <table  width="100%"  cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                <tr style="text-align: center; background: #2B2E4A; color:white">
                    <th style="width: 5%; font-size:12px">#</th>
                    <th style="width: 65%; font-size:12px">DESCRIPCION</th>
                    
                    <th style="width: 15%; font-size:12px">TOTAL SÍLABOS</th>
                    <th style="width: 15%; font-size:12px">PORCENTAJE %</th>
                    
                </tr>
                </thead>
              <tr>
              <td style="text-align: center;">1</td>
              <td>TOTAL DE SILABOS COMPLETADO</td>
              
              <td style="text-align: center;">{{$T_cursos_c}}</td>
              <td style="text-align: center;">{{$pc}} %</td>
              </tr>
              <tr>
              <td style="text-align: center;">2</td>
              <td >TOTAL DE SILABOS EN PROCESO</td>
             
              <td style="text-align: center;">{{$T_cursos_p}}</td>
              <td style="text-align: center;">{{$pp}} %</td>
              </tr>
              <tr>
              <td style="text-align: center;">3</td>
              <td>TOTAL DE SILABOS VACIO</td>
              
              <td style="text-align: center;">{{$T_cursos_v}}</td>
              <td style="text-align: center;">{{$pv}} %</td>
              </tr>
              

              <tr style="text-align: center; background: #2B2E4A; color:white">
             
              <td colspan="2">TOTAL </td>
              <td>{{$T_cursos}}</td>
              <td>100 %</td>
              </tr>
                
                

              </table>

</body>
</html>