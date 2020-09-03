<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>FICHA SOCIOECION&Oacute;MICA</title>
	    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
	</head>
	<style>
		#footer {
			position: fixed;
			bottom: -20px;
			left: 80%;
			right: 0px;
			background-color: #ffffff;
			height: 50px;
		}
	</style>
	<body>

    	<table   style=" margin-top: -10px;" width="100%">
	        <tr style="font-size: 11px; " >
				<th rowspan="2" style="text-align:center" width="20%" ><img src="./img/logo.png" id="img-logo" style="height:60px; position: relative; float: left; margin-left: 20px;"></th>
	            <th style="text-align:center" width="45%"><strong>DIRECCI&Oacute;N DE BIENESTAR UNIVERSITARIO</strong></th>
				<th style="text-align:left"  width="35%">C&Oacute;DIGO DEL ESTUDIANTE: {{$data[0]->cEstudCodUniv}}
					</th>
	        </tr>
	        <tr style="font-size: 11px; text-align: center">
	        	<th><strong>UNIDAD DE SALUD Y ASISTENCIA SOCIAL</strong>
	            </th>
				<th style="text-align:left">CELULAR: {{$data[0]->cEstudTelef}}<th>
	        </tr>
        </table>
        <br>
        <p style=" text-align:center"><strong>FICHA SOCIOECONÓMICA - DECLARACIÓN JURADA</strong></p>
        
	    <table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="8" width="100%">I.&nbsp;&nbsp;&nbsp;&nbsp;<strong>DATOS GENERALES DEL ESTUDIANTE</strong></th>
		    </tr>
	    	<tr>
	    		<th    style="background: #9ca4f0; color:black"><center>DIRECCIÓN ACTUAL </center></th>
				<th  colspan="7"><strong>Tipo de Vía:</strong>
				({{$data[0]->iTipoVia}})
			<?php 
			if($data[0]->iTipoVia==1){echo ' Avenida';}
			if($data[0]->iTipoVia==2){echo ' Jirón';}
			if($data[0]->iTipoVia==3){echo ' Calle';}
			if($data[0]->iTipoVia==4){echo ' Pasaje';}
			if($data[0]->iTipoVia==5){echo ' Carretera';}
			if($data[0]->iTipoVia==6){echo ' Otro '.$data[0]->cTipoViaOtros; }
			?>
				</th>
	    	</tr>
	    	<tr>
	    		<th style="background: #9ca4f0; color:black" width="30%">Nombre de Vía </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="20%">N° de puerta </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Block </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Interior </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Piso </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="5%">Mz. </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Lote </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="5%">Km </th>
	    		
	    	</tr>
			<tr>
			<td>
			{{$data[0]->cDireccionNombreVia}}
			</td>
			<td>
			{{$data[0]->cDireccionNumPuerta}}
			</td>
			<td>
			{{$data[0]->cDireccionBlock}}
			</td>
			<td>
			{{$data[0]->cDireccionInterior}}
			</td>
			<td>
			{{$data[0]->cDireccionPiso}}
			</td>
			<td>
			{{$data[0]->cDireccionMz}}
			</td>
			<td>
			{{$data[0]->cDireccionLt}}
			</td>
			<td>
			{{$data[0]->cDireccionKm}}
			</td>
			</tr>
			<tr>
			<th colspan="2" style="background: #9ca4f0; color:black">Referencia de Ubicación Domiciliaria</th>
			<td colspan="6" >{{$data[0]->cReferenciaDomicilio}}</td>

			</tr>
			</table>


			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th width="10%" style="background: #9ca4f0; color:black">APELLIDO PATERNO </th>
			<td width="20%" >{{$data[0]->cPersPaterno}}</td>
			<th width="10%" style="background: #9ca4f0; color:black">APELLIDO MATERNO </th>
			<td width="20%">{{$data[0]->cPersMaterno}}</td>
			<th width="12%" style="background: #9ca4f0; color:black">NOMBRES </th>
			<td width="28%">{{$data[0]->cPersNombre}}</td>

			</tr>
			</table>
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th width="10%" style="background: #9ca4f0; color:black">D.N.I. </th>
			<td width="20%" >{{$data[0]->cPersDocumento}}</td>
			<th width="20%" style="background: #9ca4f0; color:black">FECHA NACIMIENTO </th>
			<td width="30%">{{$data[0]->cEstudFechaNac}}</td>
			<th width="10%" style="background: #9ca4f0; color:black">SEXO </th>
			<td width="10%">
			<?php 
			if($data[0]->cEstudSexo == 'M' ) {echo 'Masculino';}
			if($data[0]->cEstudSexo == 'F' ) {echo 'Femenino';}
			
			
			
			?>
			</td>

			</tr>
			</table>

			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th width="30%" style="background: #9ca4f0; color:black">ESTADO CIVIL </th>
			<td width="20%" >
			<?php 
			if($data[0]->iEstadoCivil == 1 ) {echo 'Soltero/a';}
			if($data[0]->iEstadoCivil == 2 ) {echo 'Casado/a';}
			if($data[0]->iEstadoCivil == 3 ) {echo 'Conviviente';}
			if($data[0]->iEstadoCivil == 4 ) {echo 'Divorciado/a';}
			if($data[0]->iEstadoCivil == 5 ) {echo 'Viudo/a';}
			
			
			?>
			
			</td>
			
			<th width="40%" style="background: #9ca4f0; color:black">Nro. DE HIJOS DEL ESTUDIANTE </th>
			<td width="10%">
			{{$data[0]->iHijos}}
			
			</td>

			</tr>
			</table>
			

			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th colspan="4" width="100%" style="background: #9ca4f0; color:black;text-align:center">LUGAR DE NACIMIENTO</th>
			</tr>
			<tr>
			<th width="15%" style="background: #9ca4f0; color:black">PAIS</th>
			
			<th width="30%" style="background: #9ca4f0; color:black">DEPARTAMENTO</th>
			
			<th width="25%" style="background: #9ca4f0; color:black">PROVINCIA</th>
		
			<th width="30%" style="background: #9ca4f0; color:black">DISTRITO</th>
			

			</tr>
			<tr>
			<td>{{$data[0]->cPaisNombre}}</td>
			<td>{{$data[0]->cDptoNombre}}</td>
			<td>{{$data[0]->cPrvnNombre}}</td>
			<td>{{$data[0]->cDsttNombre}}</td>
			</tr>
			</table>

			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			
			<tr>
			<th width="50%" style="background: #9ca4f0; color:black">INSTITUCIÓN EDUCATIVA DE PROCEDENCIA</th>
			
			<th width="50%" style="background: #9ca4f0; color:black">NOMBRE DE LA INSTITUCIÓN EDUCATIVA</th>
		

			</tr>
			<tr>
			<td>
			<?php 
			if($data[0]->iColegioTipo == 1 ) {echo 'Particular';}
			if($data[0]->iColegioTipo == 2 ) {echo 'Parroquial';}
			if($data[0]->iColegioTipo == 3 ) {echo 'Estatal';}
			if($data[0]->iColegioTipo == 4 ) {echo 'Militar';}
			if($data[0]->iColegioTipo == 5 ) {echo 'Adventista';}
			if($data[0]->iColegioTipo == 6 ) {echo 'Paraestatal (Privado-Estatal)';}
			
			?>
			</td>
			<td>{{$data[0]->cColegioNombre}}</td>
			
			</tr>
			</table>


	   
	    <br>
		<!--FAMILIAR-->

		<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="8" width="100%">II.&nbsp;&nbsp;&nbsp;&nbsp;<strong>ASPECTO FAMILIAR</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ¿Vive su padre?: 
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php 
				if($data[0]->iPadreVivo==0){echo 'No';}
				if($data[0]->iPadreVivo==1){echo 'Si';}
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;
				¿Vive su madre?: 
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php 
				if($data[0]->iMadreViva==0){echo 'No';}
				if($data[0]->iMadreViva==1){echo 'Si';}
				?>
				&nbsp;&nbsp;
				
				</th>
		    </tr>
	    	<tr>
	    		<th    style="background: #9ca4f0; color:black"><center>2.1.- DIRECCIÓN ACTUAL DEL PADRE </center></th>
				<th  colspan="7"><strong>Tipo de Vía:</strong>
				({{$data[0]->iDireccionPadreTipoVia}})
			<?php 
			if($data[0]->iDireccionPadreTipoVia==1){echo ' Avenida';}
			if($data[0]->iDireccionPadreTipoVia==2){echo ' Jirón';}
			if($data[0]->iDireccionPadreTipoVia==3){echo ' Calle';}
			if($data[0]->iDireccionPadreTipoVia==4){echo ' Pasaje';}
			if($data[0]->iDireccionPadreTipoVia==5){echo ' Carretera';}
			if($data[0]->iDireccionPadreTipoVia==6){echo ' Otro '.$data[0]->cTipoViaOtros; }
			?>
				</th>
	    	</tr>
	    	<tr>
	    		<th style="background: #9ca4f0; color:black" width="30%">Nombre de Vía </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="20%">N° de puerta </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Block </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Interior </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Piso </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="5%">Mz. </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Lote </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="5%">Km </th>
	    		
	    	</tr>
			<tr>
			<td>
			{{$data[0]->cDireccionPadreNombreVia}}
			</td>
			<td>
			{{$data[0]->cDireccionPadreNumPuerta}}
			</td>
			<td>
			{{$data[0]->cDireccionPadreBlock}}
			</td>
			<td>
			{{$data[0]->cDireccionPadreInterior}}
			</td>
			<td>
			{{$data[0]->cDireccionPadrePiso}}
			</td>
			<td>
			{{$data[0]->cDireccionPadreMz}}
			</td>
			<td>
			{{$data[0]->cDireccionPadreLt}}
			</td>
			<td>
			{{$data[0]->cDireccionPadreKm}}
			</td>
			</tr>
			<tr>
			<th colspan="2" style="background: #9ca4f0; color:black">Referencia de Ubicación Domiciliaria</th>
			<td colspan="6" >{{$data[0]->cReferenciaDomicilioPadre}}</td>

			</tr>

			<tr>
	    		<th    style="background: #9ca4f0; color:black"><center>2.2- DIRECCIÓN ACTUAL DE LA MADRE </center></th>
				<th  colspan="7"><strong>Tipo de Vía:</strong>
				({{$data[0]->iDireccionMadreTipoVia}})
			<?php 
			if($data[0]->iDireccionMadreTipoVia==1){echo ' Avenida';}
			if($data[0]->iDireccionMadreTipoVia==2){echo ' Jirón';}
			if($data[0]->iDireccionMadreTipoVia==3){echo ' Calle';}
			if($data[0]->iDireccionMadreTipoVia==4){echo ' Pasaje';}
			if($data[0]->iDireccionMadreTipoVia==5){echo ' Carretera';}
			if($data[0]->iDireccionMadreTipoVia==6){echo ' Otro '.$data[0]->cTipoViaOtros; }
			?>
				</th>
	    	</tr>
	    	<tr>
	    		<th style="background: #9ca4f0; color:black" width="30%">Nombre de Vía </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="20%">N° de puerta </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Block </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Interior </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Piso </th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="5%">Mz. </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Lote </th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="5%">Km </th>
	    		
	    	</tr>
			<tr>
			<td>
			{{$data[0]->cDireccionMadreNombreVia}}
			</td>
			<td>
			{{$data[0]->cDireccionMadreNumPuerta}}
			</td>
			<td>
			{{$data[0]->cDireccionMadreBlock}}
			</td>
			<td>
			{{$data[0]->cDireccionMadreInterior}}
			</td>
			<td>
			{{$data[0]->cDireccionMadrePiso}}
			</td>
			<td>
			{{$data[0]->cDireccionMadreMz}}
			</td>
			<td>
			{{$data[0]->cDireccionMadreLt}}
			</td>
			<td>
			{{$data[0]->cDireccionMadreKm}}
			</td>
			</tr>
			<tr>
			<th colspan="2" style="background: #9ca4f0; color:black">Referencia de Ubicación Domiciliaria</th>
			<td colspan="6" >{{$data[0]->cReferenciaDomicilioMadre}}</td>

			</tr>


			</table>
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th width="50%" style="background: #9ca4f0; color:black">2.3. ESTADO CIVIL DE LOS PADRES:</th>
			<td width="50%" >
			<?php 
			if($data[0]->iPadresEstadoCivil == 1 ) {echo 'Soltero/a';}
			if($data[0]->iPadresEstadoCivil == 2 ) {echo 'Casado/a';}
			if($data[0]->iPadresEstadoCivil == 3 ) {echo 'Conviviente';}
			if($data[0]->iPadresEstadoCivil == 4 ) {echo 'Divorciado/a';}
		
			
			
			?>
			
			</td>
			
			

			</tr>
			</table>
			<br>

			<table style="font-size: 10px" width="100%"  border="1" cellspacing="0" cellpadding="8">
		
	    	<tr>
	    		<th  colspan="7"  style="background: #9ca4f0; color:black">2.4. ESTRUCTURA FAMILIAR (Incluido el Estudiante):</th>
			</tr>
				
		
	    	<tr>
	    		<th style="background: #9ca4f0; color:black" width="30%">Nombres y Apellidos</th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="5%">Edad</th>
	    	
	    		<th style="background: #9ca4f0; color:black" width="10%">Parentesco</th>
	    		
				<th style="background: #9ca4f0; color:black" width="5%">Estado Civil</th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="10%">Grado de Instrucción</th>
	    		
	    		<th style="background: #9ca4f0; color:black" width="20%">Ocupación</th>

				<th style="background: #9ca4f0; color:black" width="20%">Residencia actual</th>
	    		
	    	</tr>
			@foreach($pariente as $index=>$par)

            <tr style="font-size: 9px">
                  <td >
				  {{$par->cParienteNombresyApellidos }}
				  </td>
                  <td >
				  {{$par->iParienteEdad }}
				  </td>
                  <td>{{$par->cParienteParentesco }}</td>
                  <td>{{$par->cParienteEstadoCivil }}</td>
                  <td >{{$par->cParienteGradoInstruccion }}</td>
                  <td>
				  {{$par->cParienteOcupacion }}
				  </td>
				  <td>
				  {{$par->cParienteResidenciaActual }}
				  </td>

            </tr>
                  @endforeach
			</table>



			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th width="50%" style="background: #9ca4f0; color:black">2.5. ¿CON QUIÉN RESIDE ACTUALMENTE?</th>
			<td width="50%" >
			<?php 
			if($data[0]->iResidePadre == 1 ) {echo 'Padre ';}
			if($data[0]->iResideMadre == 1) {echo 'Madre ';}
			if($data[0]->iResideHermanos == 1 ) {echo 'Hermanos ';}
			if($data[0]->iResideConyuge == 1 ) {echo 'Cónyuge ';}
			if($data[0]->iResideHijos == 1 ) {echo 'Hijos ';}
			if($data[0]->iResideOtros == 1 ) {echo 'Otros '.$data[0]->cResideOtros;}
		
			
			
			?>
			
			</td>
			
			

			</tr>
			</table>


			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
			<th width="100%" colspan="3" style="background: #9ca4f0; color:black">2.6. NOMBRE DE LA PERSONA Y PARENTESCO EN CASO DE EMERGENCIA</th>
			</tr>

			<tr>
			<th width="60%" style="background: #9ca4f0; color:black">Nombres y Apellidos</th>
			<th width="20%" style="background: #9ca4f0; color:black">Parentesco</th>
			<th width="20%" style="background: #9ca4f0; color:black">Teléfono fijo o celular</th>
			

			</tr>
			<tr>
			<td>{{$data[0]->cEmergenciaNombre}}</td>
			<td>{{$data[0]->cEmergenciaParentesco}}</td>
			<td>{{$data[0]->cEmergenciaTelefono}}</td>
			</tr>

		
			
			

			</tr>
			</table>

			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="2" width="100%">III.&nbsp;&nbsp;&nbsp;&nbsp;<strong>ASPECTO ECONÓMICO</strong></th>
		    </tr>
	    	<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.1. INGRESO FAMILIAR</th>
				<td  width="70%"   >
				<?php
				if($economico[0]->iIngresoFamiliar==1){ echo 'Menos de S/. 950.00';}
				if($economico[0]->iIngresoFamiliar==2){ echo 'De S/. 951.00 a S/. 1500.00';}
				if($economico[0]->iIngresoFamiliar==3){ echo 'De S/. 1501.00 a S/. 2500.00';}
				if($economico[0]->iIngresoFamiliar==4){ echo 'Más de S/. 2500.00';}
				
				?>
				</td>
				
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.2. DEPENDE ECONÓMICAMENTE DE:</th>
				<td  width="70%"   >
				<?php
				if($economico[0]->iDependeDe==1){ echo 'Sólo Papá';}
				if($economico[0]->iDependeDe==2){ echo 'Sólo Mamá';}
				if($economico[0]->iDependeDe==3){ echo 'Ambos padres';}
				if($economico[0]->iDependeDe==4){ echo 'Hermanos';}
				if($economico[0]->iDependeDe==5){ echo 'Parientes';}
				if($economico[0]->iDependeDe==6){ echo 'De sí mismo';}
				if($economico[0]->iDependeDe==7){ echo 'Hijo(s)';}
				if($economico[0]->iDependeDe==8){ echo 'Otros ( '.$economico[0]->cDependeDeOtros.' )';}
				
				?>


				</td>
				
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.3. APOYO QUE RECIBE ES:</th>
				<td  width="70%"   >
				<?php
				if($economico[0]->iApoyo==1){ echo 'Total';}
				if($economico[0]->iApoyo==2){ echo 'Parcial';}
				if($economico[0]->iApoyo==3){ echo 'Ninguno';}
				?>
				</td>
				
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.4. EL ESTUDIANTE, ¿DESEMPEÑA ALGUNA ACTIVIDAD ECONÓMICA?</th>
				<td  width="70%"   >
				<?php
				$a='';
				if($economico[0]->iActividadEconomicaOcupacion==0){ $a='Impedido';}
				if($economico[0]->iActividadEconomicaOcupacion==1){ $a='Desocupado';}
				if($economico[0]->iActividadEconomicaOcupacion==2){ $a='Trabajador Eventual';}
				if($economico[0]->iActividadEconomicaOcupacion==3){ $a='Obreros de pequeña y mediana empresa';}
				if($economico[0]->iActividadEconomicaOcupacion==4){ $a='Empresario pequeño / profesional independiente';}
				if($economico[0]->iActividadEconomicaOcupacion==5){ $a='Agricultor (menos de 5 Hectáreas)';}
				if($economico[0]->iActividadEconomicaOcupacion==6){ $a='Empleado público / Subalterno FFAA y Policía';}
				if($economico[0]->iActividadEconomicaOcupacion==7){ $a='Obrero de gran empresa';}
				if($economico[0]->iActividadEconomicaOcupacion==8){ $a='Empleado de empresa privada grande / Funcionario del Estado';}
				if($economico[0]->iActividadEconomicaOcupacion==9){ $a='Empresario mediano / Agricultor (entre 5 a 10 Hectáreas)';}
				if($economico[0]->iActividadEconomicaOcupacion==10){ $a='Empresario grande / Comerciante Mayorista';}
				if($economico[0]->iActividadEconomicaOcupacion==11){ $a=$economico[0]->cActividadEconomicaOcupacion;}


				if($economico[0]->iActividadEconomica==0){ echo 'No';}
				if($economico[0]->iActividadEconomica==1){ echo 'Si / '.$a;}
				
				?>
				</td>
				
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.5. INGRESO MENSUAL DEL ESTUDIANTE
				</th>
				<td  width="70%"   >
				<?php
				if($economico[0]->iIngresoEstudiante==1){ echo 'Menos de S/. 475.00';}
				if($economico[0]->iIngresoEstudiante==2){ echo 'De S/. 476.00 a S/. 950.00';}
				if($economico[0]->iIngresoEstudiante==3){ echo 'De S/. 951.00 a S/. 1425.00';}
				if($economico[0]->iIngresoEstudiante==4){ echo 'Más de S/. 1425.00';}
				?>
				</td>
				
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.6. SU LABOR ES:</th>
				<td  width="70%"   >
				<?php
				if($economico[0]->iLabor==1){ echo 'Permanente';}
				if($economico[0]->iLabor==2){ echo 'Sólo fines de semana';}
				if($economico[0]->iLabor==3){ echo 'Esporádica';}
				
				?>
				</td>
				
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;3.7. HORAS DESTINADAS AL TRABAJO QUE REALIZA </th>
				<td  width="70%"   >
				{{$economico[0]->iTrabajoHoras}}
				</td>
				
	    	</tr>
	    
			
			</table>


			<!--aspectos-->
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="3" width="100%">IV.&nbsp;&nbsp;&nbsp;&nbsp;<strong>ASPECTOS DE LA VIVIENDA (Donde actualmente radica el estudiante)</strong></th>
		    </tr>
	    	<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.1. LA VIVIENDA QUE OCUPA SU HOGAR ES:</th>
				<th  width="40%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.2. ¿CUÁNTOS PISOS TIENE LA VIVIENDA QUE OCUPA:</th>
				<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.3. ESTADO DE LA VIVIENDA</th>
				
				
			
				
	    	</tr>
			<tr>
	    		<td>
				<?php
				if($vivienda[0]->iVivienda==1){ echo 'Propia';}
				if($vivienda[0]->iVivienda==2){ echo 'Propia, comprándola a plazos';}
				if($vivienda[0]->iVivienda==3){ echo 'Alquilada';}
				if($vivienda[0]->iVivienda==4){ echo 'Anticresis';}
				if($vivienda[0]->iVivienda==5){ echo 'Cedida por otro hogar';}
				if($vivienda[0]->iVivienda==6){ echo 'Alojado';}
				if($vivienda[0]->iVivienda==7){ echo 'Otro '.$vivienda[0]->cViviendaOtros;}
				
				?>
				</td>
				<td>
				<?php
				if($vivienda[0]->iViviendaNumeroPisos==1){ echo 'Un piso';}
				if($vivienda[0]->iViviendaNumeroPisos==2){ echo 'Dos pisos';}
				if($vivienda[0]->iViviendaNumeroPisos==3){ echo 'Tres pisos';}
				if($vivienda[0]->iViviendaNumeroPisos==4){ echo 'Cuatro pisos';}
				if($vivienda[0]->iViviendaNumeroPisos==5){ echo 'Más de cuatro pisos';}
				
				
				?>
				</td>
				<td>
				<?php
				if($vivienda[0]->iViviendaEstado==1){ echo 'Totalmente construida';}
				if($vivienda[0]->iViviendaEstado==2){ echo 'En construcción';}
				if($vivienda[0]->iViviendaEstado==3){ echo 'Vivienda improvisada';}
				if($vivienda[0]->iViviendaEstado==4){ echo 'Otro '.$vivienda[0]->cViviendaEstadoOtros;}
				
				
				
				?>
				</td>
				
	    	</tr>

			</table>
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
	    		<th  colspan="3" style="background: #9ca4f0; color:black;text-align:center">&nbsp;&nbsp;EL MATERIAL PREDOMINANTE EN:</th>
			
	    	</tr>
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.4. LAS PAREDES EXTERIORES DE LA VIVIENDA ES:</th>
				<th  width="40%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.5. LOS PISOS DE LA VIVIENDA ES:</th>
				<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.6. LOS TECHOS DE LA VIVIENDA ES:</th>
	    	</tr>
			<tr>
	    		<td>
				<?php
				if($vivienda[0]->iViviendaParedes==1){ echo 'Ladrillo revestido';}
				if($vivienda[0]->iViviendaParedes==2){ echo 'Ladrillo no revestido';}
				if($vivienda[0]->iViviendaParedes==3){ echo 'Bloqueta de cemento revestido';}
				if($vivienda[0]->iViviendaParedes==4){ echo 'Bloqueta de cemento no revestido';}
				if($vivienda[0]->iViviendaParedes==5){ echo 'Adobe';}
				if($vivienda[0]->iViviendaParedes==6){ echo 'Quincha (caña con barro)';}
				if($vivienda[0]->iViviendaParedes==7){ echo 'Madera';}
				if($vivienda[0]->iViviendaParedes==8){ echo 'Estera';}
				if($vivienda[0]->iViviendaParedes==9){ echo 'Otro '.$vivienda[0]->cViviendaParedesOtros;}
				
				
				
				?>
				</td>
				<td>
				<?php
				if($vivienda[0]->iViviendaPisos==1){ echo 'Parquet o madera pulida';}
				if($vivienda[0]->iViviendaPisos==2){ echo 'Vinílicos o similares';}
				if($vivienda[0]->iViviendaPisos==3){ echo 'Losetas';}
				if($vivienda[0]->iViviendaPisos==4){ echo 'Cemento';}
				if($vivienda[0]->iViviendaPisos==5){ echo 'Tierra';}
				if($vivienda[0]->iViviendaPisos==6){ echo 'Otro '.$vivienda[0]->cViviendaPisosOtros;}
				
				
				
				?>
				</td>
				<td>
				<?php
				if($vivienda[0]->iViviendaTecho==1){ echo 'Concreto Armado';}
				if($vivienda[0]->iViviendaTecho==2){ echo 'Calamina';}
				if($vivienda[0]->iViviendaTecho==3){ echo 'Fibra de cemento';}
				if($vivienda[0]->iViviendaTecho==4){ echo 'Estera';}
				if($vivienda[0]->iViviendaTecho==5){ echo 'Otro '.$vivienda[0]->cViviendaPisosOtros;}
				
				
				
				?>
				</td>
				
	    	</tr>
			</table>
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.7. TIPO DE VIVIENDA </th>
				<th  width="40%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.8. SIN CONTAR BAÑO, COCINA, PASADIZOS NI GARAJE, ¿CUÁNTOS AMBIENTES EN TOTAL TIENE LA VIVIENDA?</th>
				<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.9. ¿CUÁNTAS HABITACIONES SE USAN EXCLUSIVAMENTE PARA DORMIR?</th>
	    	</tr>
			<tr>
	    		<td>
				<?php
				if($vivienda[0]->iViviendaTipo==1){ echo 'Casa independiente';}
				if($vivienda[0]->iViviendaTipo==2){ echo 'Departamento en edificio';}
				if($vivienda[0]->iViviendaTipo==3){ echo 'Vivienda en quinta';}
				if($vivienda[0]->iViviendaTipo==4){ echo 'Cuarto / habitación';}
				if($vivienda[0]->iViviendaTipo==5){ echo 'Otro '.$vivienda[0]->cViviendaTipoOtros;}
				
				
				
				?>
				</td>
				<td>
				{{$vivienda[0]->iViviendaAmbientes}}
				
				</td>
				<td>
				{{$vivienda[0]->iViviendaHabitacionesDormir}}
				</td>
				
	    	</tr>
			</table>
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
	    		<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.10. EL ABASTECIMIENTO DE AGUA EN SU HOGAR PROCEDE DE:
</th>
				<th  width="40%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.11. EL BAÑO O SERVICIO HIGIÉNICO QUE TIENE SU HOGAR ESTÁ CONECTADO A:
</th>
				<th  width="30%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.12. ¿CUÁL ES EL TIPO DE ALUMBRADO QUE TIENE SU HOGAR? (Puede marcar más de una alternativa)
</th>
	    	</tr>
			<tr>
	    		<td>
				<?php
				if($vivienda[0]->iViviendaAgua==1){ echo 'Red pública dentro de la vivienda';}
				if($vivienda[0]->iViviendaAgua==2){ echo 'Red pública fuera de la vivienda,	pero dentro del edificio';}
				if($vivienda[0]->iViviendaAgua==3){ echo 'Pilón de uso público';}
				if($vivienda[0]->iViviendaAgua==4){ echo 'Camión - Cisterna u otro similar';}
				if($vivienda[0]->iViviendaAgua==5){ echo 'Río, acequia, manantial o similar';}
				if($vivienda[0]->iViviendaAgua==6){ echo 'Otro '.$vivienda[0]->cViviendaAguaOtros;}
				
				
				
				?>
				</td>
				<td>
				<?php
				if($vivienda[0]->iViviendaBano==1){ echo 'Red pública de desagüe';}
				if($vivienda[0]->iViviendaBano==2){ echo 'Letrina o silo';}
			
				if($vivienda[0]->iViviendaBano==3){ echo 'Otro '.$vivienda[0]->cViviendaBanoOtros;}
				
				
				
				?>
				</td>
				<td>
				<?php
				if($vivienda[0]->iElectricidad==1){ echo 'Electricidad: SI<br>';}
				if($vivienda[0]->iElectricidad==0){ echo 'Electricidad: NO<br>';}

				if($vivienda[0]->iMechero==1){ echo 'Mechero: SI<br>';}
				if($vivienda[0]->iMechero==0){ echo 'Mechero: NO<br>';}
				
				if($vivienda[0]->iVela==1){ echo 'Vela: SI<br>';}
				if($vivienda[0]->iVela==0){ echo 'Vela: NO<br>';}

				if($vivienda[0]->iPanelSolar==1){ echo 'Panel Solar: SI<br>';}
				if($vivienda[0]->iPanelSolar==0){ echo 'Panel Solar: NO<br>';}

				if($vivienda[0]->iLuzOtros==1){ echo 'Otros '.$vivienda[0]->cLuzOtrosDsc;}
				
			
				?>
				
				</td>
				
	    	</tr>
			
			</table>

			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr>
	    		<th  width="40%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;4.13. SU HOGAR TIENE:</th>
				<th  width="10%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;OPCION</th>
				
				<th  width="40%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;SU HOGAR TIENE:</th>
				<th  width="10%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;OPCION</th>
	    	</tr>
			<tr>
	    		<td>1. Equipo de sonido </td>
				<td>
				<?php
				if($vivienda[0]->iEquipoSonido==1){ echo 'SI';}
				if($vivienda[0]->iEquipoSonido==0){ echo 'NO';}
				?>
				</td>
				<td>8. Computadora (PC)</td>
				<td>
				<?php
				if($vivienda[0]->iPC==1){ echo 'SI';}
				if($vivienda[0]->iPC==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			<tr>
	    		<td>2. Televisor</td>
				<td>
				<?php
				if($vivienda[0]->iTelevisor==1){ echo 'SI';}
				if($vivienda[0]->iTelevisor==0){ echo 'NO';}
				?>
				</td>
				<td>9. Laptop </td>
				<td>
				<?php
				if($vivienda[0]->iLaptop==1){ echo 'SI';}
				if($vivienda[0]->iLaptop==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			<tr>
	    		<td>3. Servicio de cable</td>
				<td>
				<?php
				if($vivienda[0]->iServicioCable==1){ echo 'SI';}
				if($vivienda[0]->iServicioCable==0){ echo 'NO';}
				?>
				</td>
				<td>10. Servicio de internet</td>
				<td>
				<?php
				if($vivienda[0]->iInternet==1){ echo 'SI';}
				if($vivienda[0]->iInternet==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			<tr>
	    		<td>4. Refrigeradora / congeladora </td>
				<td>
				<?php
				if($vivienda[0]->iRefri==1){ echo 'SI';}
				if($vivienda[0]->iRefri==0){ echo 'NO';}
				?>
				</td>
				<td>11. Tablet</td>
				<td>
				<?php
				if($vivienda[0]->iTablet==1){ echo 'SI';}
				if($vivienda[0]->iTablet==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			<tr>
	    		<td>5. Cocina a gas </td>
				<td>
				<?php
				if($vivienda[0]->iCocinaGas==1){ echo 'SI';}
				if($vivienda[0]->iCocinaGas==0){ echo 'NO';}
				?>
				</td>
				<td>12. Automóvil / camioneta</td>
				<td>
				<?php
				if($vivienda[0]->iAuto==1){ echo 'SI';}
				if($vivienda[0]->iAuto==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			<tr>
	    		<td>6. Teléfono fijo </td>
				<td>
				<?php
				if($vivienda[0]->iTeleFijo==1){ echo 'SI';}
				if($vivienda[0]->iTeleFijo==0){ echo 'NO';}
				?>
				</td>
				<td>13. Moto / mototaxi</td>
				<td>
				<?php
				if($vivienda[0]->iMoto==1){ echo 'SI';}
				if($vivienda[0]->iMoto==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			<tr>
	    		<td>7. Celular</td>
				<td>
				<?php
				if($vivienda[0]->iCelular==1){ echo 'SI';}
				if($vivienda[0]->iCelular==0){ echo 'NO';}
				?>
				</td>
				<td>14. Otro</td>
				<td>
				<?php
				if($vivienda[0]->iOtros==1){ echo 'SI '.$vivienda[0]->cOtrosDsc;}
				if($vivienda[0]->iOtros==0){ echo 'NO';}
				?>
				</td>
	    	</tr>
			
			</table>
<!--ALIMENTACION-->
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="2" width="100%">V.&nbsp;&nbsp;&nbsp;&nbsp;<strong>ALIMENTACIÓN DEL ESTUDIANTE</strong></th>
		    </tr>
	    	<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;5.1. ¿DÓNDE CONSUME SUS ALIMENTOS EL ESTUDIANTE? (DE LUNES A VIERNES)</th>
				
	    	</tr>
			<tr>
	    		<td>
				a) Desayuno
				</td>
				<td>
				<?php
				if($alimentacion[0]->iAlimentosDesayuno==1){ echo 'Hogar';}
				if($alimentacion[0]->iAlimentosDesayuno==2){ echo 'Pensión';}
				if($alimentacion[0]->iAlimentosDesayuno==3){ echo 'Comedor Universitario';}
				if($alimentacion[0]->iAlimentosDesayuno==4){ echo 'Ninguno';}
				if($alimentacion[0]->iAlimentosDesayuno==5){ echo 'Otro '.$alimentacion[0]->cAlimentosDesayunoOtros;}
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				b) Almuerzo
				</td>
				<td>
				<?php
				if($alimentacion[0]->iAlimentosAlmuerzo==1){ echo 'Hogar';}
				if($alimentacion[0]->iAlimentosAlmuerzo==2){ echo 'Pensión';}
				if($alimentacion[0]->iAlimentosAlmuerzo==3){ echo 'Comedor Universitario';}
				if($alimentacion[0]->iAlimentosAlmuerzo==4){ echo 'Ninguno';}
				if($alimentacion[0]->iAlimentosAlmuerzo==5){ echo 'Otro '.$alimentacion[0]->cAlimentosAlmuerzoOtros;}
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				c) Cena
				</td>
				<td>
				<?php
				if($alimentacion[0]->iAlimentosCena==1){ echo 'Hogar';}
				if($alimentacion[0]->iAlimentosCena==2){ echo 'Pensión';}
				if($alimentacion[0]->iAlimentosCena==3){ echo 'Comedor Universitario';}
				if($alimentacion[0]->iAlimentosCena==4){ echo 'Ninguno';}
				if($alimentacion[0]->iAlimentosCena==5){ echo 'Otro '.$alimentacion[0]->cAlimentosCenaOtros;}
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;5.2. ¿TUVO ACCESO AL COMEDOR UNIVERSITARIO</th>
				
	    	</tr>
			<tr>
	    		<td colspan="2">
				<?php
				if($alimentacion[0]->iComedorUso==1){ echo 'SI';}
				if($alimentacion[0]->iComedorUso==0){ echo 'NO';}
				
				
				?>
				</td>
			
				
				
	    	</tr>
			</table>



			<!--DISCAPACIDAD -->
			<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="2" width="100%">VI.&nbsp;&nbsp;&nbsp;&nbsp;<strong>DISCAPACIDAD </strong></th>
		    </tr>
	    	<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;6.1. ¿TIENE UD. LIMITACIONES DE FORMA PERMANENTE PARA?:</th>
				
	    	</tr>
			<tr>
	    		<td>
				1. Moverse o caminar, para usar brazos o piernas
				</td>
				<td>
				<?php
				if($discapacidad[0]->iLimitacionMover==1){ echo 'SI';}
				if($discapacidad[0]->iLimitacionMover==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				2. Ver, aun usando anteojos
				</td>
				<td>
				<?php
				if($discapacidad[0]->iLimitacionVer==1){ echo 'SI';}
				if($discapacidad[0]->iLimitacionVer==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				3. Hablar o comunicarse, aún usando la lengua de señas u otro 
				</td>
				<td>
				<?php
				if($discapacidad[0]->iLimitacionHablar==1){ echo 'SI';}
				if($discapacidad[0]->iLimitacionHablar==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				4. Oír, aun usando audífonos
				</td>
				<td>
				<?php
				if($discapacidad[0]->iLimitacionOir==1){ echo 'SI';}
				if($discapacidad[0]->iLimitacionOir==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				5. Entender o aprender (concentrarse y recordar) 
				</td>
				<td>
				<?php
				if($discapacidad[0]->iLimitacionEntender==1){ echo 'SI';}
				if($discapacidad[0]->iLimitacionEntender==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				6. Relacionarse con los demás, por sus pensamientos sentimientos, emociones o conductas
				</td>
				<td>
				<?php
				if($discapacidad[0]->iLimitacionRelacion==1){ echo 'SI';}
				if($discapacidad[0]->iLimitacionRelacion==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
		
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;6.2. ¿ESTÁ REGISTRADO EN (Puede marcar las dos opciones) </th>
				
	    	</tr>
			<tr>
	    		<td >
				1.- OMAPED: 
				<?php
				if($discapacidad[0]->iOMAPED==1){ echo 'SI';}
				if($discapacidad[0]->iOMAPED==0){ echo 'NO';}
				
				
				?>
				</td>
				<td >
				2.- CONADIS: 
				<?php
				if($discapacidad[0]->iCONADIS==1){ echo 'SI';}
				if($discapacidad[0]->iCONADIS==0){ echo 'NO';}
				
				
				?>
				</td>
			
				
				
	    	</tr>
			</table>
<!--SALUD -->
<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="2" width="100%">VII.&nbsp;&nbsp;&nbsp;&nbsp;<strong>SALUD </strong></th>
		    </tr>
	    	<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;7.1. ¿PADECE DE ALGUNA ENFERMEDAD CRÓNICA (Puede marcar una o más opciones)</th>
				
	    	</tr>
			<tr>
	    		<td>
				Asma
				</td>
				<td>
				<?php
				if($salud[0]->iAsma==1){ echo 'SI';}
				if($salud[0]->iAsma==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Diabetes
				</td>
				<td>
				<?php
				if($salud[0]->iDiabetes==1){ echo 'SI';}
				if($salud[0]->iDiabetes==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Epilepsia
				</td>
				<td>
				<?php
				if($salud[0]->iEpilepsia==1){ echo 'SI';}
				if($salud[0]->iEpilepsia==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Artritis
				</td>
				<td>
				<?php
				if($salud[0]->iArtritis==1){ echo 'SI';}
				if($salud[0]->iArtritis==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Reumatismo
				</td>
				<td>
				<?php
				if($salud[0]->iReumatismo==1){ echo 'SI';}
				if($salud[0]->iReumatismo==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Hipertensión
				</td>
				<td>
				<?php
				if($salud[0]->iHipertension==1){ echo 'SI';}
				if($salud[0]->iHipertension==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Estrés
				</td>
				<td>
				<?php
				if($salud[0]->iEstres==1){ echo 'SI';}
				if($salud[0]->iEstres==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otro
				</td>
				<td>
				<?php
				if($salud[0]->iMalestarOtros==1){ echo 'SI '.$salud[0]->cMalestarOtros;}
				if($salud[0]->iMalestarOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;7.2 ¿PADECE DE ALGÚN TIPO DE ALERGIA?</th>
				
	    	</tr>
			<tr>
	    		<td>
				A medicamentos
				</td>
				<td>
				<?php
				if($salud[0]->iAlergiaMed==1){ echo 'SI '.$salud[0]->cAlergiaMed;}
				if($salud[0]->iAlergiaMed==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				A alimentos
				</td>
				<td>
				<?php
				if($salud[0]->iAlergiaAlim==1){ echo 'SI '.$salud[0]->cAlergiaAlim;}
				if($salud[0]->iAlergiaAlim==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otros
				</td>
				<td>
				<?php
				if($salud[0]->iAlergiaOtros==1){ echo 'SI '.$salud[0]->cAlergiaOtros;}
				if($salud[0]->iAlergiaOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;7.3 SEGURO DE SALUD</th>
				
	    	</tr>
			<tr>
	    		<th width="70%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;EL SISTEMA DE PRESTACIÓN DE SEGURO DE SALUD AL CUAL UD. ESTÁ AFILIADO ACTUALMENTE ES:</th>
				<th width="70%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;¿Quién aporta las cuotas por estar afiliado?</th>
				
	    	</tr>
			<tr>
	    		<td>
				ESSALUD
				</td>
				<td>
				<?php
				$b='';
				if($salud[0]->iSeguroPagoESSALUD==1){ $b = 'Su centro de trabajo';}
				if($salud[0]->iSeguroPagoESSALUD==2){ $b = 'Ud. mismo';}
				if($salud[0]->iSeguroPagoESSALUD==3){ $b = 'Un familiar';}


				if($salud[0]->iSaludSeguroESSALUD==1){ echo 'SI - '.$b;}
				if($salud[0]->iSaludSeguroESSALUD==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Seguro Privado de salud
				</td>
				<td>
				<?php
				$c='';
				if($salud[0]->iSeguroPagoSPS==1){ $c = 'Su centro de trabajo';}
				if($salud[0]->iSeguroPagoSPS==2){ $c = 'Ud. mismo';}
				if($salud[0]->iSeguroPagoSPS==3){ $c = 'Un familiar';}

				if($salud[0]->iSaludSeguroSPS==1){ echo 'SI - '.$c;}
				if($salud[0]->iSaludSeguroSPS==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Entidad Prestadora de salud
				</td>
				<td>
				<?php
				$d='';
				if($salud[0]->iSeguroPagoEPS==1){ $d = 'Su centro de trabajo';}
				if($salud[0]->iSeguroPagoEPS==2){ $d = 'Ud. mismo';}
				if($salud[0]->iSeguroPagoEPS==3){ $d = 'Un familiar';}


				if($salud[0]->iSaludSeguroEPS==1){ echo 'SI - '.$d;}
				if($salud[0]->iSaludSeguroEPS==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Seguro de Fuerzas Armadas/Policiales
				</td>
				<td>
				<?php
				$e='';
				if($salud[0]->iSeguroPagoFFAAPoli==1){ $e = 'Su centro de trabajo';}
				if($salud[0]->iSeguroPagoFFAAPoli==2){ $e = 'Ud. mismo';}
				if($salud[0]->iSeguroPagoFFAAPoli==3){ $e = 'Un familiar';}


				if($salud[0]->iSaludSeguroFFAAPoli==1){ echo 'SI - '.$e;}
				if($salud[0]->iSaludSeguroFFAAPoli==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Seguro Integral de Salud
				</td>
				<td>
				<?php
				$f='';
				if($salud[0]->iSeguroPagoSIS==1){ $f = 'Su centro de trabajo';}
				if($salud[0]->iSeguroPagoSIS==2){ $f = 'Ud. mismo';}
				if($salud[0]->iSeguroPagoSIS==3){ $f = 'Un familiar';}


				if($salud[0]->iSaludSeguroSIS==1){ echo 'SI - '.$f;}
				if($salud[0]->iSaludSeguroSIS==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otro
				</td>
				<td>
				<?php
				$g='';
				if($salud[0]->iSeguroPagoOtros==1){ $g = 'Su centro de trabajo';}
				if($salud[0]->iSeguroPagoOtros==2){ $g = 'Ud. mismo';}
				if($salud[0]->iSeguroPagoOtros==3){ $g = 'Un familiar';}


				if($salud[0]->iSaludSeguroOtros==1){ echo 'SI '.$salud[0]->cSeguroOtros.' - '.$g;}
				if($salud[0]->iSaludSeguroOtros==0){ echo 'NO';}
				
				?>
				</td>
				
				
	    	</tr>
			
			</table>


			<!-- INFORMACIÓN COMPLEMENTARIA
 -->
<br>
			<table style="font-size: 11px" width="100%"  border="1" cellspacing="0" cellpadding="8">
			<tr style="background: #2B2E4A; color:white">
		    	<th colspan="2" width="100%">VIII.&nbsp;&nbsp;&nbsp;&nbsp;<strong> INFORMACIÓN COMPLEMENTARIA</strong></th>
		    </tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black;text-align:center">&nbsp;&nbsp;DEPORTE</th>
				
	    	</tr>
	    	<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.1. ¿QUE DISCIPLINAS DEPORTIVAS PRACTICA? </th>
				
	    	</tr>
			<tr>
	    		<td>
				Fútbol
				</td>
				<td>
				<?php
				if($otro[0]->iFutbol==1){ echo 'SI';}
				if($otro[0]->iFutbol==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Vóley
				</td>
				<td>
				<?php
				if($otro[0]->iVoley==1){ echo 'SI';}
				if($otro[0]->iVoley==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Básquet
				</td>
				<td>
				<?php
				if($otro[0]->iBasquet==1){ echo 'SI';}
				if($otro[0]->iBasquet==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Natación
				</td>
				<td>
				<?php
				if($otro[0]->iNatacion==1){ echo 'SI';}
				if($otro[0]->iNatacion==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otros
				</td>
				<td>
				<?php
				if($otro[0]->iDeporteOtros==1){ echo 'SI '.$otro[0]->cDeporteOtros;}
				if($otro[0]->iDeporteOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.2 ¿HAS PARTICIPADO O PARTICIPAS EN UN CLUB, LIGA, O FEDERACIÓN DEPORTIVA?</th>
				
	    	</tr>
			<tr>
	    		<td colspan="2">
				<?php
				if($otro[0]->iClubDeportivo==1){ echo 'SI '.$otro[0]->cClubDeportivo;}
				if($otro[0]->iClubDeportivo==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
				
	    	</tr>
			
			
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black;text-align:center">&nbsp;&nbsp;CULTURA Y RECREACIÓN</th>
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.3 ¿QUE ACTIVIDAD ARTÍSTICA PRACTICAS?</th>
				
	    	</tr>
			
			<tr>
	    		<td>
				Danza
				</td>
				<td>
				<?php
				if($otro[0]->iDanza==1){ echo 'SI';}
				if($otro[0]->iDanza==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Teatro
				</td>
				<td>
				<?php
				if($otro[0]->iTeatro==1){ echo 'SI';}
				if($otro[0]->iTeatro==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Música
				</td>
				<td>
				<?php
				if($otro[0]->iMusica==1){ echo 'SI';}
				if($otro[0]->iMusica==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otros
				</td>
				<td>
				<?php
				if($otro[0]->iArteOtros==1){ echo 'SI '.$otro[0]->cArteOtros;}
				if($otro[0]->iArteOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.4 ¿HAS FORMADO O FORMAS PARTE DE UN CENTRO ARTÍSTICO O CULTURAL?</th>
				
	    	</tr>
			<tr>
	    		<td  colspan="2">
				<?php
				if($otro[0]->iClubArtistico==1){ echo 'SI '.$otro[0]->cClubArtistico;}
				if($otro[0]->iClubArtistico==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.5 ¿QUÉ RELIGIÓN PROFESAS?</th>
				
	    	</tr>
			<tr>
	    		<td colspan="2">
				<?php
				if($otro[0]->iReligion==1){ echo 'Católica';}
				if($otro[0]->iReligion==2){ echo 'Evangelista';}
				if($otro[0]->iReligion==3){ echo 'Adventista';}
				if($otro[0]->iReligion==4){ echo 'Otro '.$otro[0]->cReligionOtros;}
				
				
				?>
				</td>
			
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.6 ¿QUÉ ACTIVIDADES REALIZAS COMO PASATIEMPO?</th>
				
	    	</tr>
			
			<tr>
	    		<td>
				Cine
				</td>
				<td>
				<?php
				if($otro[0]->iCine==1){ echo 'SI';}
				if($otro[0]->iCine==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Lectura
				</td>
				<td>
				<?php
				if($otro[0]->iLectura==1){ echo 'SI';}
				if($otro[0]->iLectura==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Escuchar Música
				</td>
				<td>
				<?php
				if($otro[0]->iEscucharMusica==1){ echo 'SI';}
				if($otro[0]->iEscucharMusica==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Videojuegos
				</td>
				<td>
				<?php
				if($otro[0]->iVideojuegos==1){ echo 'SI';}
				if($otro[0]->iVideojuegos==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Juegos Online
				</td>
				<td>
				<?php
				if($otro[0]->iJuegosOnline==1){ echo 'SI';}
				if($otro[0]->iJuegosOnline==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Reuniones con Amigos
				</td>
				<td>
				<?php
				if($otro[0]->iReunionesConAmigos==1){ echo 'SI';}
				if($otro[0]->iReunionesConAmigos==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Pasear
				</td>
				<td>
				<?php
				if($otro[0]->iPasear==1){ echo 'SI';}
				if($otro[0]->iPasear==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otros
				</td>
				<td>
				<?php
				if($otro[0]->iPasatiempoOtros==1){ echo 'SI '.$otro[0]->cPasatiempoOtros;}
				if($otro[0]->iPasatiempoOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black;text-align:center">&nbsp;&nbsp;PSICOPEDAGÓGICO</th>
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.7 ¿HAS ASISTIDO ALGUNA VEZ A UNA CONSULTA PSICOLÓGICA?</th>
				
	    	</tr>
			<tr>
			<td  colspan="2">
				<?php
				if($otro[0]->iConsultaPsicologica==1){ echo 'SI '.$otro[0]->cConsultaPsicologica;}
				if($otro[0]->iConsultaPsicologica==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>

			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.8 ¿A QUIÉN ACUDES CUANDO TIENES UN PROBLEMA EMOCIONAL?</th>
				
	    	</tr>
			
			<tr>
	    		<td>
				Padre
				</td>
				<td>
				<?php
				if($otro[0]->iAcudePadre==1){ echo 'SI';}
				if($otro[0]->iAcudePadre==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Madre
				</td>
				<td>
				<?php
				if($otro[0]->iAcudeMadre==1){ echo 'SI';}
				if($otro[0]->iAcudeMadre==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Hermanos
				</td>
				<td>
				<?php
				if($otro[0]->iAcudeMadre==1){ echo 'SI';}
				if($otro[0]->iAcudeMadre==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Amigos
				</td>
				<td>
				<?php
				if($otro[0]->iAcudeMadre==1){ echo 'SI';}
				if($otro[0]->iAcudeMadre==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Tutor
				</td>
				<td>
				<?php
				if($otro[0]->iAcudeTutor==1){ echo 'SI';}
				if($otro[0]->iAcudeTutor==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Psicólogo
				</td>
				<td>
				<?php
				if($otro[0]->iAcudePsicologo==1){ echo 'SI';}
				if($otro[0]->iAcudePsicologo==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otros
				</td>
				<td>
				<?php
				if($otro[0]->iAcudeOtros==1){ echo 'SI '.$otro[0]->cAcudeOtros;}
				if($otro[0]->iAcudeOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			

			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.9 ¿CÓMO CALIFICA UD. SU RELACIÓN CON SUS PADRES O FAMILIARES?</th>
				
	    	</tr>
			<tr>
			<td  colspan="2">
				<?php
			
				if($otro[0]->iRelacionPadresFamiliares==1){ echo 'Excelente';}
				if($otro[0]->iRelacionPadresFamiliares==2){ echo 'Muy Buena';}
				if($otro[0]->iRelacionPadresFamiliares==3){ echo 'Buena';}
				if($otro[0]->iRelacionPadresFamiliares==4){ echo 'Regular';}
				if($otro[0]->iRelacionPadresFamiliares==5){ echo 'Mala';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black;text-align:center">&nbsp;&nbsp;TRANSPORTE</th>
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.10 ¿QUÉ TEMAS TE GUSTARÍA ABORDAR PARA MEJORAR TU DESARROLLO PERSONAL?</th>
				
	    	</tr>
			
			<tr>
	    		<td>
				Inteligencia Emocional
				</td>
				<td>
				<?php
				if($otro[0]->iIntelEmoc==1){ echo 'SI';}
				if($otro[0]->iIntelEmoc==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Habilidades Socioemocionales
				</td>
				<td>
				<?php
				if($otro[0]->iHabSocEmoc==1){ echo 'SI';}
				if($otro[0]->iHabSocEmoc==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Control de las emociones
				</td>
				<td>
				<?php
				if($otro[0]->iControlEmoc==1){ echo 'SI';}
				if($otro[0]->iControlEmoc==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Resilencia
				</td>
				<td>
				<?php
				if($otro[0]->iResilencia==1){ echo 'SI';}
				if($otro[0]->iResilencia==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Autoestima
				</td>
				<td>
				<?php
				if($otro[0]->iAutoestima==1){ echo 'SI';}
				if($otro[0]->iAutoestima==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<td>
				Otros
				</td>
				<td>
				<?php
				if($otro[0]->iDesarrolloOtros==1){ echo 'SI '.$otro[0]->cDesarrolloOtros;}
				if($otro[0]->iDesarrolloOtros==0){ echo 'NO';}
				
				
				?>
				</td>
				
				
	    	</tr>
			
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.11 ¿CUÁL ES EL MEDIO DE TRANSPORTE QUE MÁS UTILIZAS?</th>
				
	    	</tr>
			<tr>
			<td  colspan="2">
				<?php
				
				if($otro[0]->iTransporte==1){ echo 'Taxi';}
				if($otro[0]->iTransporte==2){ echo 'Combi-Microbus';}
				if($otro[0]->iTransporte==3){ echo 'Caminando';}
				if($otro[0]->iTransporte==4){ echo 'Bicicleta';}
				if($otro[0]->iTransporte==5){ echo 'Bus de la UNAM';}
				if($otro[0]->iTransporte==6){ echo 'Automóvil particular';}
				
				
				?>
				</td>
				
				
	    	</tr>

			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.12 ¿CUÁNTO GASTAS APROXIMADAMENTE EN PASAJES PARA ASISTIR A DIARIO A LA UNIVERSIDAD?</th>
				
	    	</tr>
			<tr>
	    	<td  colspan="2">
			<?php
				
				if($otro[0]->iGastoPasaje==1){ echo '30 Soles';}
				if($otro[0]->iGastoPasaje==2){ echo '20 Soles';}
				if($otro[0]->iGastoPasaje==3){ echo '15 Soles';}
				if($otro[0]->iGastoPasaje==4){ echo '10 Soles';}
				if($otro[0]->iGastoPasaje==5){ echo '5 Soles o menos';}
				
				
				
				?>
				</td>
				
				
	    	</tr>
			<tr>
	    		<th colspan="2" width="100%"   style="background: #9ca4f0; color:black">&nbsp;&nbsp;8.13 ¿QUÉ TAN SEGUIDO UTILIZAS EL TRANSPORTE DE LA UNAM?</th>
				
	    	</tr>
			<tr>
			<td  colspan="2">
				<?php
				
				if($otro[0]->iUsoTransporteUNAM==1){ echo 'Diario';}
				if($otro[0]->iUsoTransporteUNAM==2){ echo 'Tres o cuatro veces a la semana';}
				if($otro[0]->iUsoTransporteUNAM==3){ echo 'Una o dos veces a la semana';}
				if($otro[0]->iUsoTransporteUNAM==4){ echo 'Una vez al mes';}
				if($otro[0]->iUsoTransporteUNAM==5){ echo 'Casi nunca';}
				if($otro[0]->iUsoTransporteUNAM==6){ echo 'Nunca';}
				
				
				?>
				</td>
				
	    	</tr>
			</table>

			<br>
	</body>
</html>