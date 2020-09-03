<!DOCTYPE html>
<html lang="es">
    <head>
        <link type="image/x-icon" rel="shortcut icon" href="" />
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <title>
            Detraccione masivas
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
                    <h3>Detracciones Masivas </h3>
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
                <th>RUC</th>  
                <th>PROVEEDOR</th> 
                <th>NRO CTA. CTE.</th>
                <th>C.B.S</th>
                <th>C.T.0</th>  
                <th>AÑO</th>  
                <th>N° SIAF</th>  
                <th>N° CHEQUE</th>
                <th>IMPORTE</th>
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
            <td style="text-align:center"><?php echo $d->cRuc_Persona?></td>
            <td style="text-align:left"><?php echo $d->cNombre_Persona?></td>
            <td style="text-align:center"><?php echo $d->cDetraccProveedCtaCte?></td>
            <td style="text-align:center"><?php echo $d->cBienServSujDetraccCodigo?></td>
            <td style="text-align:center"><?php echo $d->cTipoOperacSujDetraccCodigo?></td>
            <td style="text-align:center"><?php echo $d->Ano_eje?></td>
            <td style="text-align:center"><?php echo $d->Expediente?></td>
            <td style="text-align:center"><?php echo $d->cDetraccProveedCheque?></td>
            <td style="text-align:right"><?php echo number_format($d->nDetraccProveedImporte, 2, '.', '')?></td>
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