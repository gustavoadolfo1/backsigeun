<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>REPORTE</title>
</head>

<body>
<br>
<br>

<table border="1" cellpadding="0" cellspacing="0"  >
    <thead >
    <tr >
        <th colspan="7" style="text-align:center">UNIVERSIDAD NACIONAL DE MOQUEGUA</th>
    </tr>
    <tr>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="5" >Nº</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="80" >PROYECTO</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="30" >ESCUELA</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="40" >LINEA DE INVESTIGACIÓN</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="20" >RESOLUCIÓN</th>

        <th rowspan="2" style="text-align:center" style="font-size:2" width="40" >CARGO</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="10" >DNI</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="60" >NOMBRES Y APELLIDOS</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="60" >EMAIL</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="20" >TELEFONO</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="70" >DIRECCIÓN</th>
        <th rowspan="2" style="text-align:center" style="font-size:2" width="15" >ESTADO</th>

    </tr>

    </thead>
</table>
<table border="1" cellpadding="0" cellspacing="0"  >
    <tbody>

    <?php
    $x=1;
    for($i=0;$i<count($resumen);$i++){ ?>

    <tr>

        <td style="vertical-align: middle; text-align: center;"><?php echo $x; ?></td>
        <td style="wrap-text: true; vertical-align: middle; text-align: justify;"><?php echo ($resumen[$i]['cNombreProyecto']); ?></td>


        <td  style="wrap-text: true; vertical-align: middle; text-align: justify;">
            <?php echo ($resumen[$i]['cCarrera']); ?>
        </td>


        <td  style="wrap-text: true; vertical-align: middle; text-align: justify;">
            <?php echo ($resumen[$i]['cLinea']); ?>
        </td>

        <td style="wrap-text: true; vertical-align: middle; text-align: center;"><?php echo ($resumen[$i]['cResProyecto']); ?></td>

        <td style="wrap-text: true"><?php
            $cTipoMiembroDescripcion = explode('/', $resumen[$i]['cTipoMiembroDescripcion']);

            for($j=0;$j<count($cTipoMiembroDescripcion)-1;$j++)
            {
                echo $cTipoMiembroDescripcion[$j]."<br />";
            }
            ?>
        </td>


        <td style="wrap-text: true"><?php
            $cPersDocumento = explode(',', $resumen[$i]['cPersDocumento']);

            for($j=0;$j<count($cPersDocumento)-1;$j++)
            {
                echo $cPersDocumento[$j]."<br />";
            }
            ?>
        </td>

        <td style="wrap-text: true"><?php
            $miembro = explode('/', $resumen[$i]['miembro']);

            for($j=0;$j<count($miembro)-1;$j++)
            {
                echo $miembro[$j]."<br />";
            }
            ?>
        </td>

        <td style="wrap-text: true"><?php
            $correo = explode(',', $resumen[$i]['correo']);

            for($j=0;$j<count($correo)-1;$j++)
            {
                echo $correo[$j]."<br />";
            }
            ?>
        </td>

        <td style="wrap-text: true"><?php
            $celular = explode(',', $resumen[$i]['celular']);

            for($j=0;$j<count($celular)-1;$j++)
            {
                echo $celular[$j]."<br />";
            }
            ?>
        </td>

        <td style="wrap-text: true"><?php
            $direccion = explode(',', $resumen[$i]['direccion']);

            for($j=0;$j<count($direccion)-1;$j++)
            {
                echo $direccion[$j]."<br />";
            }
            ?>
        </td>

        <td style="wrap-text: true; vertical-align: middle; text-align: center;"><?php echo ($resumen[$i]['cEstado']); ?></td>



    </tr>

    <?php $x++; } ?>

    </tbody>

</table>

</body>

</html>
