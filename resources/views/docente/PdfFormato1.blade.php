<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> FORMATO 1 </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">

</head>
<style>
#footer {
  position: fixed;
  bottom: -20px;
  left: 80%;
  right: 0px;
 
  height: 50px;
}

#footer .page:after {

  content: counter(page);
}
</style>
<body>

    <img src="./img/logo.png" id="img-logo" style="height:60px; position: relative; float: left; margin-left: 20px;">
    <table align="right" style="margin-left: 350px; margin-right:120px; margin-top: -10px;">
        <tr style="font-size: 13px; text-align: right">

            <th><strong>COMISIÓN ORGANIZADORA</strong></th>
        </tr>
        <tr style="font-size: 13px; text-align: right">

            <th><strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
            </th>
        </tr>

    </table>
    <br>
    <p style="font-size:15px; text-align:center" ><strong>FORMATO 1</strong></p>
    <p style=" text-align:center"><strong>RACIONALIZACIÓN ACADÉMICA <?php echo date('Y');?></strong></p>

    <table style="font-size:14px;" border='0' width="100%">
    <tr>
    <th>Docente:</th>
    <?php $nn=''; $nc='';
    if($racionalizacion[0]->cDocenteCondicion=='ORDINARIO') {$nn='checked';}
    if($racionalizacion[0]->cDocenteCondicion=='CONTRATADO') {$nc='checked';}
   ?>
    <td>&nbsp;&nbsp;&nbsp;Nombrado</td>
    <td ><input type="checkbox" style="font-size: 35px;" {{$nn}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style="color:white">&nbsp;&nbsp;&nbsp;Contratado</td>
    <td>&nbsp;&nbsp;&nbsp;Contratado</td>
    <td><input type="checkbox" style="font-size: 35px;" {{$nc}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style="color:white">&nbsp;&nbsp;&nbsp;Contratado</td>
    <td style="color:white"> &nbsp;&nbsp;&nbsp;Contratado</td>


    <tr>
    </table>
    <br>
    <table  width="100%" style="border: 1px solid #2B2E4A" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">1.&nbsp;&nbsp;&nbsp;&nbsp;DATOS DE LA CARRERA PROFESIONAL</th>
    </tr>
    </table>
    <br>
    <table>
    <tr style="font-size:13px;">
    <td><strong>Escuela Profesional&nbsp;&nbsp;<strong></td>
    <td style="border-bottom:1px dotted black;
  text-decoration:none; width:570px">  &nbsp;&nbsp;&nbsp;{{$racionalizacion[0]->cCarreraAdscrito}}</td>
    </tr>
    <tr style="font-size:13px;">
    <td><strong>Especialidad&nbsp;&nbsp;</strong></td>
    <td  style="border-bottom:1px dotted black;
  text-decoration:none; width:570px">&nbsp;&nbsp;&nbsp;{{$racionalizacion[0]->cDocenteTitulo}}</td>
    </tr>
    </table>
    <?php $sn=''; $sc='';
    if($racionalizacion[0]->iControlCicloAcad%2==0) {$sc='checked';}
    else {$sn='checked';} ?>
    <table width="100%">
    <tr style="font-size:13px;">
    <td><strong>Régimen Semestral</strong></td>
    <td>&nbsp;&nbsp;&nbsp;Sem. I</td>
    <td ><input type="checkbox" style="font-size: 35px;" {{$sn}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;Sem. II</td>
    <td ><input type="checkbox" style="font-size: 35px;" {{$sc}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style="color:white">&nbsp;&nbsp;&nbsp;Contratado</td>
    <td style="color:white"> &nbsp;&nbsp;&nbsp;Contratado</td>
    </tr>
    </table>

    <br>
    <table  width="100%" style="border: 1px solid #2B2E4A" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">2.&nbsp;&nbsp;&nbsp;&nbsp;DATOS PERSONALES</th>
    </tr>
    </table>
    <br>

    <br>
    <table width="100%">
    <tr style="font-size:13px;">
    <td><strong>Apellidos&nbsp;&nbsp;</strong></td>
    <td style="border-bottom:1px dotted black;
  text-decoration:none; width:80px">&nbsp;&nbsp;&nbsp;{{$racionalizacion[0]->cPersPaterno}}&nbsp;{{$racionalizacion[0]->cPersMaterno}}</td>
    <td style="text-align:right"><strong>Nombres</strong></td>
    <td colspan="3" style="border-bottom:1px dotted black;
  text-decoration:none; width:80px">&nbsp;&nbsp;&nbsp;{{$racionalizacion[0]->cPersNombre}}</td>
    </tr>
    <tr style="font-size:13px;">
    <td><strong>Domicilio&nbsp;&nbsp;</strong></td>
    <td colspan="5" style="border-bottom:1px dotted black;
  text-decoration:none; width:570px">&nbsp;&nbsp;&nbsp;{{$racionalizacion[0]->cPersDireccion}}</td>
    </tr>
    <tr style="font-size:13px;">
    <td><strong>DNI&nbsp;&nbsp;</strong></td>
    <td style="border-bottom:1px dotted black;
  text-decoration:none; width:50px">&nbsp;&nbsp;&nbsp;{{$racionalizacion[0]->cPersDocumento}}</td>
    <td colspan="2"><strong>Fecha de nacimiento</strong></td>
    <td  colspan="2" style="border-bottom:1px dotted black;
  text-decoration:none; width:50px;">{{$racionalizacion[0]->dPersNacimiento}}</td>
    </tr>
    </table>
    <?php $pr =''; $as=''; $aux=''; $de=''; $tc=''; $tp='';
    if($racionalizacion[0]->cDocenteCategoria == 'PRINCIPAL'){$pr='checked';}
    if($racionalizacion[0]->cDocenteCategoria == 'ASOCIADO'){$as='checked';}
    if($racionalizacion[0]->cDocenteCategoria == 'AUXILIAR'){$aux='checked';}

    if($racionalizacion[0]->cDocenteDedic == 'DEDICACION EXCLUSIVA'){$de='checked';}
    if($racionalizacion[0]->cDocenteDedic == 'TIEMPO COMPLETO'){$tc='checked';}
    if($racionalizacion[0]->cDocenteDedic == 'TIEMPO PARCIAL'){$tp='checked';}?>
    <br>
    <table width="100%">
    <tr style="font-size:13px;">
    <td ><strong>Categoría</strong></td>

    <td >Pr<input type="checkbox" style="font-size: 25px;margin-left:auto; margin-top:-8px;" {{$pr}}/></td>

    <td>As.<input type="checkbox" style="font-size: 25px;margin-left:auto; margin-top:-8px;" {{$as}}/></td>

    <td >Aux.<input type="checkbox" style="font-size: 25px;margin-left:auto; margin-top:-8px;" {{$aux}}/></td>
    <td ><strong>Dedicación</strong></td>

    <td >DE<input type="checkbox" style="font-size: 25px;margin-left:auto; margin-top:-8px;" {{$de}}/></td>

    <td >TC<input type="checkbox" style="font-size: 25px;margin-left:auto; margin-top:-8px;" {{$tc}}/></td>

    <td >TP<input type="checkbox" style="font-size: 25px;margin-left:auto; margin-top:-8px;" {{$tp}}/></td>
    <td ><strong>Hrs</strong></td>
    <td style="border-bottom:1px dotted black;
  text-decoration:none; width:20px"  >{{$racionalizacion[0]->cCargaHDedicHras}}</td>
    </tr>

    </table>
    <br>
    <?php $tpp =''; $mag=''; $doc='';
    if(isset($racionalizacion[0]->cDocenteGradoPost)) {
      if($racionalizacion[0]->cDocenteGradoPost == 'MAGISTER'){$mag='checked';}
      if($racionalizacion[0]->cDocenteGradoPost == 'DOCTOR'){$doc='checked';}
    }
    else {
    if($racionalizacion[0]->cDocenteGradoAcad == 'TITULO PROFESIONAL'){$tpp='checked';}
    
        }

    ?>
    <table width="100%">

    <tr style="font-size:13px;">
    <td colspan="2"><strong>Nivel académico alcanzado:</strong></td>
    <td style="text-align:right">Título Profesional </td>
    <td ><input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$tpp}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style="text-align:right">Magister</td>
    <td ><input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$mag}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td style="text-align:right">Doctorado</td>
    <td ><input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$doc}}/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>

    </table>

    <br>

    <br>
    <table  width="100%" style="border: 1px solid 2B2E4A" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">3.&nbsp;&nbsp;&nbsp;&nbsp;CARGA LECTIVA</th>
    </tr>
    </table>
    <br>
    <table  width="100%" style="border: 1px solid #2B2E4A" cellspacing="0" cellpadding="3" border="1">
                <thead class="  ">
                  <tr class="card-header"  style="font-size:11px;background: #2B2E4A; color:white" >
                    <th width="3%"  rowspan="2" style="text-align:center" ><strong>N° Estud.</strong> </th>
                    <th width="10%"  colspan="6" style="text-align:center"><strong>ASIGNATURA</strong> </th>
                    <th width="10%"  colspan="2" style="text-align:center"><strong>ESCUELA PROFESIONAL</strong> </th>
                    <th width="6%" colspan="2"  style="text-align:center"><strong>SEMESTRE</strong> </th>
                  </tr>

                  <tr style="font-size: 10px; background: #2B2E4A; color:white">
                    <th width="10%" style="text-align: center;height:20" ><strong>Código</strong></th>
                    <th width="15%" style="text-align: center;height:20" ><strong>Nombre</strong></th>
                    <th width="4%" style="text-align: center;height:20" ><strong>CRED</strong></th>
                    <th width="2%" style="text-align: center;height:20" ><strong>HT</strong></th>
                    <th width="2%" style="text-align: center;height:20" ><strong>HP</strong></th>
                    <th width="2%" style="text-align: center;height:20" ><strong>TH</strong></th>
                     <th width="1%" style="text-align: center;height:20" ><strong>Sec.</strong></th>
                    <th width="15%" style="text-align: center;height:20" ><strong>Nombre</strong></th>

                    <th width="3%" style="text-align: center;height:20" ><strong>I</strong></th>
                    <th width="3%" style="text-align: center;height:20" ><strong>II</strong></th>


                  </tr>
                  <?php $sce=0; ?>
                  @foreach($CE as $index=>$a)
                  <tr style="font-size: 9px">
                    <td  style="text-align: center;height:10" >{{$a->iCargaLecEstu}}</td>
                    <td style="text-align: center;height:10" >{{$a->cCursoCod}}</td>
                    <td  style="text-align: justify;height:10" >{{$a->cCursoDes}}</td>
                    <td  style="text-align: center;height:10" >{{$a->iCargaLecCred}}</td>
                    <td style="text-align: center;height:10" >{{$a->iCargaLecHrsT}}</td>
                    <td style="text-align: center;height:10" >{{$a->iCargaLecHrsP}}</td>
                    <td style="text-align: center;height:10" >{{$a->iCargaLecHrsTot}}</td>
                     <td  style="text-align: center;height:10" >{{$a->cCargaLecSec}}</td>
                     @foreach($Carreras as $key=>$ca)
                     @if($a->iCarreraId == $ca->iCarreraId ) 
                    <td style="text-align: justify;height:10" >
                    {{$ca->cCarreraDsc}}
                    
                    </td>
                    @endif
                    @endforeach
                    @if($a->iControlCicloAcad%2==0)
                    <td style="text-align: center;height:10" ></td>
                    <td style="text-align: center;height:10" >X</td>
                    @else
                    <td style="text-align: center;height:10" >X</td>
                    <td style="text-align: center;height:10" ></td>
                    @endif
                  </tr>
                  <?php $sce = $sce + $a->iCargaLecHrsTot; ?>
                  @endforeach



                 <tr  style="font-size:11px;">
                  <th colspan="6" style="text-align:right">TOTAL CARGA LECTIVA</th>
                  <th style="text-align:center"><strong>{{ $sce }}</strong></th>
                  <th colspan="4"></th>

                </tr>

                </thead>

              </table>
    <br>
    <div id="footer">
    <p class="page">Página </p>
</div>
<div style="page-break-after:always;"></div>
    <table  width="100%" style="border: 1px solid black" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">4.&nbsp;&nbsp;&nbsp;&nbsp;CARGA NO LECTIVA</th>
    </tr>
    </table>
    <br>
    <table width="100%" style="border: 1px solid black" cellspacing="0" cellpadding="3" border="1">
                  <thead >
                    <tr style="font-size: 9px; background: #2B2E4A; color:white">
                      <th style="text-align: center"><strong> N° </strong></th>
                      <th ><strong> ACTIVIDAD</strong></th>
                      <th ><strong> ESCUELA PROFESIONAL / PROYECTO / CARGO </strong></th>
                      <th ><strong> RESOLUCIÓN / DOCUMENTO</strong></th>

                      <th style="text-align: center" ><strong> HT </strong></th>


                    </tr>
                    <?php $scne = 0; ?>
                    @foreach($CNE as $index=>$b)

                    <tr style="font-size: 10px">
                      <td style="text-align: center">{{$index+1}}</td>
                      <td >{{$b->cDesActividades}}</td>
                      <td >{{$racionalizacion[0]->cCarreraAdscrito}}</td>
                      <td >{{$b->cCargaNoLecDoc}}</td>

                      <td style="text-align: center" >{{$b->iCargaNoLecHrsTot}}</td>


                    </tr>
                    <?php $scne = $scne +$b->iCargaNoLecHrsTot; ?>
                    @endforeach


                    <tr  style="font-size: 10px" >
                      <th style="text-align:right" colspan="4" ><strong>TOTAL CARGA NO LECTIVA</strong></th>
                      <th style="text-align:center"><strong>{{$scne}}</strong></th>


                    </tr>
                  </thead>
                </table>
    <br>
    <table  width="100%" style="border: 1px solid black" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">5.&nbsp;&nbsp;&nbsp;&nbsp;LABORES EN OTRA INSTITUCIÓN</th>
    </tr>
    </table>
    <br>
    <?php  $sii=''; $noo='';
    if($Rac[0]->cRacionalLabInst=='SI') {$sii='checked';}
    else{$noo='checked';}
    ?>
    <table width="100%"  >
    <tr style="font-size: 13px">
    <td>NO<input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$noo}}/>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>SI<input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$sii}}/>&nbsp;&nbsp;&nbsp;&nbsp;</td>

    <td ><strong>Nombre de la Institución</strong></td>
    <td style="text-align:justify">{{$Rac[0]->cRacionalNomInst}}</td>
    <td ><strong>Hrs</strong></td>
    <td>{{$Rac[0]->cRacionalHrsInst}}</td>
    <tr>
    </table>
    <br>
    <table  width="100%" style="border: 1px solid black" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">6.&nbsp;&nbsp;&nbsp;&nbsp;DECLARACIÓN PERSONAL DEL DOCENTE </th>
    </tr>
    </table>
    <?php $decs='' ; $decn='';
    if($Rac[0]->cRacionalDeclara==1){$decs='checked';}
    else{$decn='checked';}
    ?>
    <p style="text-align:justify; font-size:12px"><em>Hago de su conocimiento que estoy de acuerdo con la Racionalización asignada para el presente año académico.</em></p>
    <div align="center">
    <table  width="100%">
    <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>SI<input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$decs}}/>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>NO<input type="checkbox" style="font-size: 35px;margin-left:auto; margin-top:-15px" {{$decn}}/>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td></td>

    </tr>
    </table>
    </div>
    <br>
    <p style="line-height:15px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____________________________</p>
    <p style="font-size:10px; margin-top:-5px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Director de la Escuela Profesional&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma del Docente</p>

    <br>
    <table  width="100%" style="border: 1px solid black" cellspacing="3" cellpadding="3">
    <tr style="background: #2B2E4A; color:white">
    <th width="100%">7.&nbsp;&nbsp;&nbsp;&nbsp;INFORMES</th>
    </tr>
    </table>
    <br>

    <table  width="100%" style="border: 2px solid black;
  border-collapse:collapse; " border="1">
    <tr>
    <th width="30%"  >
    <p style="font-size:11px; text-align:center">VICEPRESIDENTE ACAD.</p>
    <p style="font-size:11px; text-align:center">(Procede)</p>
    <label style="font-size:11px;">&nbsp;&nbsp;Carga Lectiva&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;</label>
    <br>
    <label style="font-size:11px;">&nbsp;&nbsp;Carga No Lectiva&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;</label>
    <br>
    <p style="font-size:11px; text-align:justify">&nbsp;&nbsp;Moquegua ....., de ................... de 20..</p>
    <br>
    <p style="font-size:11px;">&nbsp;&nbsp;Firma&nbsp;&nbsp;&nbsp;_________________</p>
    <br>
    </th>

    <th width="30%" >
    <p style="font-size:11px; text-align:center">OF. DE RECURSOS HUMANOS</p>
    <br>
    <p style="font-size:11px; text-align:center"></p>
    <label style="font-size:11px;">&nbsp;&nbsp;Existe Plaza&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;</label>
    <br>
    <label style="font-size:11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><label style="font-size:10px;  ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONDICIONADA&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;<label style="font-size:10px; ">&nbsp;&nbsp;&nbsp;</label>
    <br>
    <p style="font-size:11px; text-align:justify">&nbsp;&nbsp;Moquegua ....., de ................... de 20..</p>
    <br>
    <p style="font-size:11px;">&nbsp;&nbsp;Firma&nbsp;&nbsp;&nbsp;_________________</p>
    <br>
    </th>

    <th width="40%" >
    <p style="font-size:11px; text-align:center">DIRECC. ACT. Y SERV. ACAD.</p>
    <p style="font-size:11px; text-align:justify">&nbsp;&nbsp;Con los informes que anteceden se considera:</p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; text-align:center;" >&nbsp;&nbsp;&nbsp;NO PRECEDENTE&nbsp;&nbsp;&nbsp;</label>
    <br>
    <label style="font-size:11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;OBSERVADA&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;&nbsp;<label style="font-size:10px; border: black 1px solid; ">&nbsp;&nbsp;&nbsp;PROCEDENTE&nbsp;&nbsp;&nbsp;</label>
    <br>
    <p style="font-size:11px; text-align:justify">&nbsp;&nbsp;Moquegua ....., de ................... de 20..</p>
    <br>
    <p style="font-size:11px;">&nbsp;&nbsp;Firma&nbsp;&nbsp;&nbsp;_________________</p>
    <br>
    </th>
    </tr>
    </table>
    <br>

</body>
</body>
</html>
