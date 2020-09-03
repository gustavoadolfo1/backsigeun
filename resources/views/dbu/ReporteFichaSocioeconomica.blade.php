<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> FICHA SOCIOECONÓMICA </title>
    
    
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
    <center><h3 style="text-align:center"><strong>FICHA SOCIOECONÓMICA</strong></h3></center>

    <?php $totalsi=0; $totalno=0;  ?>
    @foreach($carrera_sede as $index=>$car)
    <?php $si=0; $no=0; ?>
    <h4>{{$car->cCarreraCarn}} - {{$car->cFilDescripcion}}</h4>
   
    <table  width="100%"  cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                  <tr class=""  style="font-size:11px;background: #2B2E4A; color:white" >
                    <th width="5%"  style="text-align:center" ><strong>N°</strong> </th>
                    <th width="10%" style="text-align:center"><strong>CÓDIGO</strong> </th>
                    <th width="10%" style="text-align:center"><strong>DNI</strong> </th>
                    <th width="40%" style="text-align:center"><strong>APELLIDOS Y NOMBRES</strong> </th>
                     <th width="5%"  style="text-align:center"><strong>ESTADO</strong> </th>
                   
                    
                  </tr>
                  <?php $i=0; ?>
                  @foreach($data as $index=>$ficha)
                 
                  @if($car->iCarreraId == $ficha->iCarreraId && $car->iFilId == $ficha->iFilId)
                  <?php $i=$i+1; ?>
                  <tr class=""  style="font-size:11px;">
                    <th width="5%"  style="text-align:center">{{$i}}</th>
                    <th width="10%" style="text-align:center">{{$ficha->cEstudCodUniv}}</th>
                    <th width="10%" style="text-align:center">{{$ficha->cPersDocumento}}</th>
                    <th width="40%" style="text-align:justify">{{$ficha->cPersPaterno}} {{$ficha->cPersMaterno}} {{$ficha->cPersNombre}}</th>
                     @if($ficha->iEstado == 0)
                    <?php $no = $no + 1; ?>
                    <th width="5%"  style="text-align:center; color:red">INCOMPLETO</th>
                    @else
                    <?php $si = $si + 1; ?>
                    <th width="5%"  style="text-align:center; color:green">COMPLETO</th>
                    @endif
                  </tr>
                  @endif
                  @endforeach
                 
                </thead>

              </table>
              
    <br>
    <label>TOTAL: {{$si + $no}}</label> /
    <label>COMPLETO: {{$si}}</label> /
    <label>INCOMPLETO: {{$no}}</label>
    <?php $totalsi = $totalsi + $si; $totalno = $totalno + $no; ?>
   
    <div style="page-break-after:always;"></div>
    @endforeach
    <p>RESUMEN</P>
    <label>TOTAL: {{$totalsi + $totalno}}</label> /
    <label>COMPLETO: {{$totalsi}}</label> /
    <label>INCOMPLETO: {{$totalno}}</label>
    
   
</body>

</html>