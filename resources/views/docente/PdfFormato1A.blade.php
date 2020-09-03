<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> FORMATO 1A </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
    
</head>
<style>
    @page { margin: 20px 40px; }
    

    
  </style>
<body>
    <img src="./img/logo.png" id="img-logo" style="height:60px; position: relative; float: left; margin-left: 20px;">
    <table align="center" style="margin-left: 80px; margin-right:120px; margin-top: 5px;" width="100%">
        <tr >
            <th width="25%" style="font-size: 8px; text-align: left"><strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong></th>
            <th width="50%"></th>
            <th  width="25%" style="font-size: 10px; text-align: left">FORMATO N° 2</th>
        </tr>
        <tr style="font-size: 8px; text-align: left">
            <th width="25%"><strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
            </th>
            <th width="50%"></th>
            <th width="25%"></th>
        </tr>
        <tr  style="font-size: 8px; text-align: left">
            <th width="25%"><strong>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS</strong>
            </th>
            <th width="50%"> </th>
            <th width="25%"></th>
        </tr>
        <tr  style="font-size: 16px; text-align: center">
            <th width="25%">
            </th>
            <th width="50%"><strong>CONTROL DE ACTIVIDADES DOCENTE</strong></th>
            <th width="25%"></th>
        </tr>
       
    </table>
    
   <table width="100%" style="border: 1px solid black; font-size:11px">
   <tr>
   <td width="20%">ESCUELA PROFESIONAL</td>
   @if(isset($raV[0]->cCarreraAdscrito))
   <td width="40%" style="border-bottom:1px dotted black; text-decoration:none;">{{$raV[0]->cCarreraAdscrito}}</td>
   @else
   <td width="40%" style="border-bottom:1px dotted black; text-decoration:none;"></td>
   @endif
  
   <td width="20%">CATEGORIA</td>
   @if(isset($raV[0]->cDocenteCategoria))
   <td width="40%"  style="border-bottom:1px dotted black; text-decoration:none;">{{$raV[0]->cDocenteCategoria}}</td>
   @else
   <td width="40%" style="border-bottom:1px dotted black; text-decoration:none;"></td>
   @endif
   </tr>
   <tr>
   <td width="20%">NOMBRE DEL DOCENTE</td>
   
   <td width="40%"  style="border-bottom:1px dotted black; text-decoration:none;">{{$raV[0]->cPersPaterno}} {{$raV[0]->cPersMaterno}}, {{$raV[0]->cPersNombre}}</td>
   <td width="20%">DEDICACIÓN</td>
   @if(isset($raV[0]->cDocenteDedic))
   <td width="40%"  style="border-bottom:1px dotted black; text-decoration:none;">{{$raV[0]->cDocenteDedic}}</td>
   @else
   <td width="40%" style="border-bottom:1px dotted black; text-decoration:none;"></td>
   @endif
   </tr>
   <tr>
   <td width="20%">CONDICION</td>
   @if(isset($raV[0]->cDocenteCondicion))
   <td width="40%"  style="border-bottom:1px dotted black; text-decoration:none;">{{$raV[0]->cDocenteCondicion}}</td>
   @else
   <td width="40%" style="border-bottom:1px dotted black; text-decoration:none;"></td>
   @endif
   <td width="20%"  style="border-bottom:1px dotted black; text-decoration:none;"></td>
   <td width="40%"  style="border-bottom:1px dotted black; text-decoration:none;"></td>
   </tr>
   </table>
    <p style=" font-size:10px"><strong>DETALLE DE LA CARGA LECTIVA Y NO LECTIVA</strong></p>
    <table style="border: 1px solid black; 
  border-collapse:collapse; " border="1" >
                      <thead >
                          <tr style="font-size: 10px; text-align:center">
                              <th rowspan="3" width="40">Horas</th>
                              <th colspan="14"><strong>HORARIO DE CLASES</strong></th>
                          </tr>
                          <tr class="text-center" style="font-size: 9px; text-align:center">
                              
                              <th colspan="2"   >Lunes</th>
                              <th colspan="2"   >Martes</th>
                              <th colspan="2"   >Miercoles</th>
                              <th colspan="2"   >Jueves</th>
                              <th colspan="2"   >Viernes</th>
                              <th colspan="2"   >Sabado</th>
                              <th colspan="2"   >Domingo</th>
                          </tr>
                          <tr class="text-center" style="font-size: 8px">
                            
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Ubicación&nbsp;&nbsp;</th>
                            <th style="font-size: 8px">&nbsp;&nbsp;Asignatura/Actividad&nbsp;&nbsp;</th>
                            
                        </tr>
                        @foreach($array as $index=>$a)
                        <tr class="text-center" style="font-size: 8px; text-align:center">
                            <th height="11" width="9" style="font-size: 7px">&nbsp;&nbsp;{{$a['hora']}} - {{$a['hora_ff']}} &nbsp;&nbsp;</th>
                            @if(isset($a['ubic1']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic1']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif
                            @if(isset($a['curso1']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso1']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['ubic2']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic2']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['curso2']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso2']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['ubic3']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic3']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['curso3']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso3']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['ubic4']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic4']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif
                            @if(isset($a['curso4']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso4']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['ubic5']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic5']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif
                            @if(isset($a['curso5']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso5']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['ubic6']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic6']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif
                            @if(isset($a['curso6']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso6']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif

                            @if(isset($a['ubic7']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['ubic7']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif
                            @if(isset($a['curso7']))
                            <th style="font-size: 7px">&nbsp;&nbsp;{{$a['curso7']}}&nbsp;&nbsp;</th>
                            @else
                            <th style="font-size: 7px">&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            @endif
                           
                            
                        </tr>
                        @endforeach

                      </thead>
                  </table>
</body>
</body>
</html>