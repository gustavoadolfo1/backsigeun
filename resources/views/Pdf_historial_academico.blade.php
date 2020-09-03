<!DOCTYPE html>
<html>

<head>
    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>HISTOTIAL ACADEMICO</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

</head>
<style>
    @page { margin: 20px 50px; }
    #header { position: fixed; left: 0px; top: -60px; right: 0px; height: 60px; background-color: #F6F6F6;  }
    #footer { position: fixed; left: 0px;  bottom: -150px; right: 0px; height: 120px;  text-align: center;}
    #footer .page:after { content: counter(page); }

    
  </style>
<body>  
        <table border="" align="center">
        <tr align="center" >
            <th rowspan="5" valign="TOP"><img src="./img/logo.png" width="50" height="40"  "></th>
            <th  rowspan="5" ></th>

            <th colspan="3" ><strong><font size="22px">UNIVERSIDAD NACIONAL DE MOQUEGUA</font></strong></th>
        </tr>
        
        <tr  style="font-size: 10px; text-align: center;">
            <th colspan="3" valign="TOP"><strong>VICEPRESIDENCIA ACAD&Eacute;MICA<br>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS<br>
             </strong>
            </th>
        </tr>
        <tr style="text-align: center;" align="center">
        <th></th>
        <th><?php echo DNS1D::getBarcodeHTML($codigoEstudiante, "EAN13")?></th>
        <th></th>
        
        </tr>
        <tr>
        <th></th>
        <th valign="TOP"> {{$codigoEstudiante}}</th>
        <th></th>
        </tr>
           </table>  
           
       
        
        @foreach($nombre as $index=>$cons)
        <p align="left"><strong>HISTORIAL ACAD&Eacute;MICO</strong></p>
        <div>
            <table border="1" style="font-family: Calibri Light" width="100%">
                <thead>
                    <tr  style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;CODIGO/DNI&nbsp;&nbsp;</strong></th>
                        <td width="260px">&nbsp;&nbsp;{{$cons->cEstudCodUniv}}&nbsp;&nbsp;/&nbsp;&nbsp;{{$cons->cDocumentoEstudiante}}</td>
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;SEDE/LUGAR&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;{{$cons->cFilDescripcion}}&nbsp;&nbsp;</td>
                        
                    </tr>
                    <tr  style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;ESTUDIANTE&nbsp;&nbsp;</strong></th>
                        <td width="260px">&nbsp;&nbsp;{{$cons->cNombreEstudiante}}&nbsp;&nbsp;</td>
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;CURRICULA&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;{{$curricula}}&nbsp;&nbsp;</td>
                        
                    </tr>
                    <tr  style="font-size: 11px; ">
                        <th style="background-color: #F9F9F9" ><strong>&nbsp;&nbsp;CARRERA PROFESIONAL&nbsp;&nbsp;</strong></th>
                        <td width="260px">&nbsp;&nbsp;{{$cons->cCarreraDsc}}&nbsp;&nbsp;</td>
                        <th style="background-color: #F9F9F9"><strong>&nbsp;&nbsp;REGIMEN&nbsp;&nbsp;</strong></th>
                        <td width="100px">&nbsp;&nbsp;FLEXIBLE&nbsp;&nbsp;</td>
                        
                    </tr>
                </thead>
                
            </table>
        </div>
        <br>
        @endforeach
        <p align="left"><strong>DETALLE CURSOS</strong></p>
        <div>
            <table border="1" width="100%" >
                <tbody>
                    <tr style="background-color: #F9F9F9;font-size: 10px; text-align: center; ">
                        <td width="6%"><strong>&nbsp;CICLO&nbsp;</strong></td>
                        <td width="10%"><strong>&nbsp;CODIGO&nbsp;</strong></td>
                        <td width="36%"><strong>CURSO</strong></td>
                        <td width="4%"><strong>&nbsp;CRED&nbsp;</strong></td>
                        <td width="11%"><strong>&nbsp;NOTA/SEM&nbsp;</strong></td>
                        <td width="11%"><strong>&nbsp;NOTA/SEM&nbsp;</strong></td>
                        <td width="11%"><strong>&nbsp;NOTA/SEM&nbsp;</strong></td>
                        <td width="11%"><strong>&nbsp;NOTA/SEM&nbsp;</strong></td>
                       
                    </tr>
                </tbody>
                
                <tbody>
                <?php 
                for($i=0;$i<$no ;$i++){
                    ?>
                    <tr >
                    
                    <?php
                    for($j=0; $j<8 ; $j++){
                        if($j==2){
                            ?>
                            <td width="44%"  style="font-size: 9px;text-align: left;">&nbsp;<?php echo $historial[$i][$j]; ?></td>
                            <?php
                                    }
                        else {
                        ?>
                        <td width="8%" style="font-size: 9px; text-align: center;">&nbsp;<?php echo $historial[$i][$j]; ?></td>
                <?php
                        }
                    } ?>

                    </tr>
                <?php
                }    
                 ?>   
                   
                
            
            <tr style="border: white 1px solid; " >
            <td style="border: white 1px solid; "><br></td>
            <td style="border: white 1px solid;"><br></td>
            <td style="border: white 1px solid;"><br></td>
            <td style="border: white 1px solid;"><br></td>
            <td style="border: white 1px solid;"><br></td>
            <td style="border: white 1px solid;"><br></td>
            <td style="border: white 1px solid;"><br></td>
            <td style="border: white 1px solid;"><br></td>
            </tr>
            <?php
            for($i=$no;$i<$nplan ;$i++){
                    ?>
                    <tr  >
                    
                    <?php
                    for($j=0; $j<8 ; $j++){
                        if($j==2){
                            ?>
                            <td width="44%" style="font-size: 9px; text-align: left;">&nbsp;<?php echo $historial[$i][$j]; ?></td>
                            <?php
                                    }
                        else {
                        ?>
                        <td width="8%" style="font-size: 9px; text-align: center;">&nbsp; <?php echo $historial[$i][$j]; ?></td>
                <?php
                        }
                    } ?>

                    </tr>
                <?php
                }   
            ?>
            </tbody>
            </table>

        </div>
    <p style="font-size: 9px">CREDITOS APROBADOS: {{ $nc }}</p>
<br><br>
    <p align="left" style="font-size: 11px">____________________________________________________
        <br>
        DIRECCION DE ACTIVIDADES Y SERVICIOS ACADEMICOS
        <br>UNIDAD DE REGISTRO CENTRAL
        <br>
        <br>
        <br>
        URC&nbsp;/&nbsp;{{$fecha}}
    </p>
   <br> 
    
  
    
   
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>


</body>

</html>