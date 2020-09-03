<!DOCTYPE html>
<html lang="es">
    <head>
        <link type="image/x-icon" rel="shortcut icon" href="" />
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title>
            Cartas Fianza
        </title>
        <style type="text/css">
        body {
         background-color: #fff;
         margin: 1px;
         font-family: Lucida Grande, Verdana, Sans-serif;
         font-size: 11px;
         color: #4F5155;
        }
        @page { margin: 50px 10px 60px 10px; }
        #header { 
            position: fixed; 
            left: 0px; 
            top: -40px;
            right: 0px; 
            height: 80px; 
            border:1px solid;
            border-color:silver;
            border-radius:10px            
        }
        #cuerpo { 
            position: relative;
            left: 0px; 
            right: 0px; 
            top: 48px;
            bottom: -150px;
            background-color: white; 
            padding:0px
        }

        #footer { 
            position: fixed; 
            left: 0px; 
            bottom: -45px; 
            right: 0px; 
            height: 40px; 
            background-color: white;
            border:1px solid;
            border-color:silver;
            border-radius:10px;
            text-align:center;
        }
        #footer .page:after { 
          /*  content: counter(page, upper-roman); */
            content: counter(page); 
        }
        .table{
        /*text-align: justify; */
        font-size:11px;
        border-collapse: collapse;
        width:100%;
        margin:10px auto;
        margin-bottom: 20px ;
        font-family: Arial, Helvetica, sans-serif;
    }
    .table th td {
        border: 0px solid black;
    }
    .table thead th{
        background: #d3deea;
        text-align: center;
        font-size:11px;
        padding: 4px 6px;
        border:1px solid;
        border-color:silver;
        
    }
    .table thead td{    
        font-size:11px;
        padding: 4px;
    }
    .table tbody td{
        font-weight: 100;
        font-size:10px;
        padding: 6px;
        border:1px solid;
        border-color:silver;
    }
    </style>
    </head>
    <body>
    <!--header para cada pagina-->
    <div id="header">
        <table style="border:0px solid;width:100%" >
            <tr>
                <td width="20%" style="text-align:center">
                    <img src="./img/logo.png" width="70" height="50">
                </td>  
                <td width="60%" style="text-align:center">
                    <h2>MÓDULO DE TESORERÍA</h2>
                    <h3>Cartas Fianza</h3>
                </td>
                <td width="20%">
                </td>
            </tr>
        </table>
    </div>
    <!--footer para cada pagina-->
    <div id="footer" >
    <p class="page"></p>
    </div>    
    <div id="cuerpo">
    <table class="table">
        <thead>
            <tr>
                <th>NRO</th>  
                <!--<th>COD.</th>  -->
                <th>N°CARTA FIANZA</th>  
                <th>FECHA CUSTODIA</th> 
                <th>PERSONA/CONSORCIO</th>
                <th>MODALIDAD CONTRATO</th>
                <th>CLASE</th>  
                <th>BANCO</th>  
                <th>TIPO</th>  
                <th>F.VENCIMIENTO</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if($list){
            $i=0;
            foreach ($list as $key => $d) {          
        ?>
            <tr>
                <td style="text-align:center"><?php echo ++$i?></td>
                <!--<td style="text-align:center"><?php echo $d->iCartaFianzaId?></td>-->
                <td style="text-align:center"><?php echo $d->cCartaFianzaNumero?></td>
                <td style="text-align:center"><?php echo $d->dCartaFianzaCustodia?></td>
                <td>
                    <b>ruc: </b><?php echo $d->cRuc_Persona?><br>
                    <b>nombre: </b><?php echo $d->cNombre_Persona?><br>
                    <b>consorcio: </b><?php echo $d->cCartaFianzaNombreConsorcio?><br>
                </td>
                <td>
                    <b>numero: </b><?php echo $d->cCartaFianzaNumeroModalidadContrato?><br>
                    <b>modalidad: </b><?php echo $d->cModalidadContratoNombre?><br>               
                </td>
                <td><?php echo $d->cClaseCartaFianzaNombre?></td>
                <td><?php echo $d->cBancoNombre?></td>
                <td><?php echo $d->cTipoCartaFianzaNombre?></td>
                <td style="text-align:center"><?php echo $d->dCartaFianzaDetVencimiento?></td>
                <td><?php echo $d->cEstadoCartaFianzaNombre?></td>                
            </tr>
        <?php
            }
        }else{
        ?>
            <tr>
                <td colspan="11">No se encontraron resultados</td>
            </tr>
        <?php
        }
        ?>       
        </tbody>
    </table>
    </div>

    </body>
</html>