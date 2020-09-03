<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>SIGEUN</title>
    <link href="img/login.css" type=text/css rel=stylesheet>
    <style type="text/css">
        span {
            font-size: 15px;
        }

        a {
            text-decoration: none;
            color: #0062cc;
            border-bottom: 2px solid #0062cc;

        }

        a:hover {
            text-decoration: none;
            color: #0062cc;
            cursor: pointer;

        }


        a .box :hover {
            background: #452d94;
        }

        .box {
            padding: 2px 0px;
            padding-right: 3px !important;
            padding-left: 3px !important;
        }

        a .box-part {
            /*background:#FFF;*/
            background: #1093c8;
            border-radius: 0;
            padding: 5px 5px;
            margin: 3 0px;

        }

        .box-part {
            /*background:#FFF;*/
            background: #999;
            border-radius: 0;
            padding: 5px 5px;
            margin: 3 0px;

        }

        /*
.box-part :hover{
	background:#452d94;
}
*/
        .box :hover {
            background: #999;
            /* background:#452d94;*/
            /*cursor:pointer;*/
        }

        .text {
            margin: 20px 0px;
        }

        .title {
            color: white;
            line-height: 1pt;
            font-weight: bold;
            font-weight: 900;
        }


        .fa {
            color: #4183D7;
        }

        .link {
            color: black;
            text-decoration: none;
        }
    </style>

</head>

<body>
	<div align="center"><img src="img/logo.png" width="100" alt="" style="position: absolute; top: 5; left: 0;"/></div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> 

<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=Roboto');
*,*:before,*:after{box-sizing:border-box;vertical-align:baseline}
.container{max-width:1000px;margin:0 auto;display:block;position:relative;height:100%;padding:10px}
.hide-by-pass{display:block;margin:0 auto;padding:20px;max-width:950px;position:relative;background:#ffffff;font-family:'Roboto',sans-serif; top:50px;
border-radius: 8px 8px 8px 8px;
-moz-border-radius: 8px 8px 8px 8px;
-webkit-border-radius: 8px 8px 8px 8px;
border: 0px solid #000000;
}
input,button{outline:none!important}
input{border:none!important}
#pass-input-bypass{width:100%;padding:12px 20px;margin:8px 0;display:inline-block;border:1px solid #ccc;border-radius:4px;box-sizing:border-box}
.content-pass-protected{margin-top:1em;margin-bottom:1em;display:block;position:relative;padding:5px 5px}
.btn-center{text-align:center}
#btn_reload{display:inline-block;padding:3px 9px;border:0.1em solid #fb2718;margin:0 1em;border-radius:5px;box-sizing:border-box;text-decoration:none;font-family:'Roboto',sans-serif;font-weight:300;color:#fb2718;background:transparent;cursor:pointer;text-align:center;transition:all 0.2s}
.btn-down{display:inline-block;padding:0.35em 1.2em;border:0.1em solid #555;margin:0;border-radius:0.12em;box-sizing:border-box;text-decoration:none;font-family:'Roboto',sans-serif;font-weight:300;color:#555;text-align:center;transition:all 0.2s}
.btn-down:hover{color:#fff;background-color:#555}
@media all and (max-width:30em){.btn-down{display:block;margin:0.4em auto}}
.title-wrp{color:#e65045;vertical-align:baseline;font-family:'Roboto',sans-serif;font-size:15px;margin:0;white-space:normal;word-break:break-word;padding:0;font-weight:bold;text-transform:uppercase}
.hide-by-pass,#pass-input-bypass{-webkit-box-shadow:0 2px 2px 0 rgba(0,0,0,0.14),0 3px 1px -2px rgba(0,0,0,0.12),0 1px 5px 0 rgba(0,0,0,0.2);box-shadow:0 2px 2px 0 rgba(0,0,0,0.14),0 3px 1px -2px rgba(0,0,0,0.12),0 1px 5px 0 rgba(0,0,0,0.2)}
#not-found,#has-error,#no-error{font-weight:bold;border-radius:5px;width:100%;height:100%;padding:10px}
#not-found{color:#fff;background:#4CAF50!important}
#has-error{color:#fff;background:#E91E63!important}
#no-error{color:#fff;background:#4CAF50!important}
#ticket-by{font-weight:normal;position:relative;display:block;color:#555}
#ticket-content{display:inline-block;position:relative;color:#ff3030;font-weight:bold}

</style>

<div class="container">
   <div class='hide-by-pass dialog-effect-in' pass-protected="true">
       <div class='content-pass-protected'>
        <div class="btn-center">
        		 <div align="center"><img src="img/logo_sigeun.png"  alt=""/></div>
          		 <h2 align="center">SISTEMA INTEGRADO DE GESTIÓN UNIVERSITARIA - SIGEUN</h2>

		     	<div class="row">
		     		<div class="box col-lg-6 col-md-6 col-sm-6 col-xs-12">
		     			<a href="/modulos/moddasa" class="link">
		                <div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">					
							<div class="box-part text-center">
		                        <img src="img/dasa.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> DASA</h5>
								</div>
							 </div>
						</div>	 
						</a>
						<a href="/modulos/modescuela" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/escuelas.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> ESCUELAS</h5>
								</div>
							 </div>
						</div>
						</a>
						<a href="/modulos/moddocente_15_10_2019"  class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/docente.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> DOCENTE</h5>
								</div>
							 </div>
						</div>
						</a>
						<a href="/modulos/modestudiante" class="link">
		 				 <div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/estudiante.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> ESTUDIANTE</h5>
								</div>
							 </div>
						</div>
						</a>
						


		     		</div>	
		     		<div class="box col-lg-6 col-md-6 col-sm-6 col-xs-12">

		     				<a href="/modulos/modtramite" class="link">
						 <div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/tramite_doc.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> TRAMITE</h5>
								</div>
							 </div>
						</div>
						</a>
						<a href="/modulos/modcaja" class="link">
						 <div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/caja.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> CAJA</h5>
								</div>
							 </div>
						</div>
						</a>

						<a href="/modulos/moddbu" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/bienestar.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> DBU</h5>
									</div>
								</div>
						</div>
						</a>
						<a href="/modulos/modlogistica" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/logistica.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> LOGISTICA</h5>
									</div>
								</div>
						</div>
						</a>

						


		     		</div>	
		     			
						 
				</div>
				<div class="row">
					<div class="box col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<a href="/modulos/modpatrimonio" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/patrimonio.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> PATRIMONIO</h5>
									</div>
								</div>
						</div>
						</a>


						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/ceid.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> CEID</h5>
								</div>
							</div>
						</div>

						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/cctic.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> CCTIC's</h5>
									</div>
								</div>
						</div>

						<a href="/modulos/modaulavirtual" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/aula_virtual.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> AULA VIRTUAL</h5>
									</div>
								</div>
						</div>
						</a>


						
					</div>
					<div class="box col-lg-6 col-md-6 col-sm-6 col-xs-12">
					    <a href="/modulos/modadmision" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/cepre.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> ADMISIÓN</h5>
									</div>
								</div>
						</div>
						</a>
						<a href="/modulos/modinvestigacion" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="box-part text-center">
			                        <img src="img/investigacion.png" id="img1" width="80%">
									<div class="title">
										<h5>MODULO<BR> INVESTIGACIÓN</h5>
									</div>
								</div>
						</div>
						</a>
						<a href="/modulos/modbiblioteca" class="link">
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/biblioteca.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> BIBLIOTECA</h5>
								</div>
							</div>
						</div>
						</a>
						<div class="box col-lg-3 col-md-3 col-sm-3 col-xs-6">
							<div class="box-part text-center">
		                        <img src="img/rrhh.png" id="img1" width="80%">
								<div class="title">
									<h5>MODULO<BR> RRHH</h5>
								</div>
							</div>
						</div>
						<a href="/modulos/modccu" class="link">
					</div>
					
					
				</div>

				<div class="row">
					
				</div>

        </div>
    </div>


    <!--
<div id="dialog" class="dialog dialog-effect-in">

  <div class="dialog-front">
    <div class="dialog-content">
    <section id="content">
-->








    <!--
		<table>
			<tr>
				<td>
					<a href="/modulos/moddasa">
						<img src="img/btn_1_.png" id="img1" onmouseover="img1.src='img/btn_1_.png'" onmouseout="img1.src='img/btn_1.png'"></a>
				</td>
				<td>
					<a href="/modulos/modescuela">
						<img src="img/btn_2.png" id="img2"  onmouseover="img2.src='img/btn_2_.png'" onmouseout="img2.src='img/btn_2.png'"></a>
				</td>
				<td>
					<a href="/modulos/moddocente_15_10_2019">
						<img src="img/btn_3.png" id="img3"  onmouseover="img3.src='img/btn_3_.png'" onmouseout="img3.src='img/btn_3.png'"></a>
				</td>
				<td>
					<a href="/modulos/modestudiante">
						<img src="img/btn_4.png" id="img4"  onmouseover="img4.src='img/btn_4_.png'" onmouseout="img4.src='img/btn_4.png'"></a>
				</td>
				<td>
					<a href="/modulos/modtramite">
						<img src="img/btn_5.png" id="img5"  onmouseover="img5.src='img/btn_5_.png'" onmouseout="img5.src='img/btn_5.png'"></a>
				</td>
				<td>
					<a href="/modulos/modcaja">
						<img src="img/btn_6.png" id="img6"  onmouseover="img6.src='img/btn_6_.png'" onmouseout="img6.src='img/btn_6.png'"></a>
				</td>
				<td>
					<a href="/modulos/moddbu">
						<img src="img/btn_7.png" id="img7"  onmouseover="img7.src='img/btn_7_.png'" onmouseout="img7.src='img/btn_7.png'"></a>
				</td>
			</tr>
		</table>
	-->
    <!--
      </section>
    </div>
  </div>

</div>
-->

</html>
