<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> POSTULANTES COMEDOR UNIVERSITARIO </title>
    
    
</head>
<style>
#footer {
  position: fixed;
  bottom: 0px;
  left: 0px;
  right: 0px;
 
  height: 0px;
}

#footer .page:after {

  content: counter(page);
}
</style>
<body>
<?php $totalsi=0;   ?>
    @foreach($carrera_sede as $index=>$car)
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
            <th><strong>UNIDAD DE ASISTENCIA SOCIAL</strong>
            </th>
        </tr>
       
        <tr  style="font-size: 13px; text-align: center">
            <td></td>
        </tr>
    </table>
    <br>
    <center><h4 style="text-align:center"><strong>RELACION DE POSTULANTES AL SERVICIO DEL COMEDOR UNIVERSITARIO</strong></h4></center>
    
    <strong>FECHA: </strong><br>
    <strong>HORA INICIO: </strong><br>
    <strong>HORA FIN: </strong><br>
    <strong>RESPONSABLE: </strong>
    <?php $si=0; $no=0; ?>
    <h5>{{$car->cCarreraCarn}} - {{$car->cFilDescripcion}}</h5>
   
    <table  width="100%"  cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                  <tr class=""  style="font-size:10px;background: #2B2E4A; color:white" >
                    <th width="5%"  style="text-align:center" ><strong>N°</strong> </th>
                    <th width="10%" style="text-align:center"><strong>CÓDIGO</strong> </th>
                    <th width="10%" style="text-align:center"><strong>DNI</strong> </th>
                    <th width="40%" style="text-align:center"><strong>APELLIDOS Y NOMBRES</strong> </th>
                    <th width="10%" style="text-align:center"><strong>CELULAR</strong> </th>
                    <th width="10%"  style="text-align:center"><strong>FECHA POSTULACION</strong> </th>
                    <th width="15%" style="text-align:center"><strong>FIRMA</strong> </th>
                   
                    
                  </tr>
                  <?php $i=0; ?>
                  @foreach($data as $index=>$ficha)
                 
                  @if($car->iCarreraId == $ficha->iCarreraId && $car->iFilId == $ficha->iFilId)
                  <?php $i=$i+1; ?>
                  <tr class=""  style="font-size:10px;">
                    <td width="5%"  style="text-align:center">{{$i}}</td>
                    <td width="10%" style="text-align:center">{{$ficha->cEstudCodUniv}}</td>
                    <td width="10%" style="text-align:center">{{$ficha->cEstudDni_x}}</td>
                    <td width="40%" style="text-align:justify">{{$ficha->cEstudApellidos_x}} {{$ficha->cEstudNombres_x}}</td>
                    <td width="10%"  style="text-align:center">{{$ficha->cEstudTelef}}</td>
                    <td width="10%"  style="text-align:center">{{$ficha->dtPresentacion}}</td>
                    <td width="15%"  style="text-align:center"></td>

                    
                  </tr>
                  @endif
                  @endforeach
                 
                </thead>

              </table>
              
    <br>
  
    <?php $totalsi = $totalsi + $i;  ?>
   
    <div style="page-break-after:always;"></div>
    @endforeach
    <p>RESUMEN</P>
    <label>TOTAL DE POSTULANTES: {{$totalsi}}</label>  
  
    
   
</body>

</html>