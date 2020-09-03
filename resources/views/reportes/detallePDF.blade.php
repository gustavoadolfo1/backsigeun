<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<header>
    <title>--</title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
    
</header>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    .table{
        text-align: justify; 
        font-size:11px;
        border-collapse: collapse;
        width:100%;
        margin:10px auto;
        margin-bottom: 20px ;
        font-family: Arial, Helvetica, sans-serif;
    }
    .table th, td {
        border: 1px solid black;
    }
    .table thead th{
        background: #8395a7;
        text-align: center;
        font-size:11px;
        padding: 4px 6px;
    }
    .table tfoot th{
        background: #8395a7;
        text-align: center;
        font-size:11px;
        padding: 4px 6px;
    }
    .table thead td{    
        font-size:11px;
        padding: 4px 6px;
    }
    td{    
        font-size:11px;
        padding: 4px 6px;
    }
    .table tbody td{
        font-weight: 100;
        font-size:10px;
        padding: 4px 3px  px;
    }
</style>
<body>
    
    <table class="table">
        <thead>
            <tr style="font-size: 20px; text-align: center">
                <td colspan="7" style="border:none">
                    <img src="./img/logo.png" style="height:55px; position: absolute; float: left; margin-left: 20px;">
                    <h2>UNIVERSIDAD NACIONAL DE MOQUEGUA</h2>
                </td>
            </tr>
            <tr style="font-size: 13px; text-align: center">
                <td colspan="7" style="border:none">
                    <h4 class="text-center">Tramite Documentario</h4>
                </td>
            </tr>
            <tr>
                <th class="align-middle" colspan="2" >&nbsp;Documento</th>
                <td class="align-middle" colspan="1" >{{ $header->cTipoDocDescripcion }}-{{ $header->cTramNumeroDocumento }}-{{ $header->cTramSiglaDocumento }}</td>
                <th class="align-middle" colspan="2" >&nbsp; Nro. Expediente</th>
                <td class="align-middle" colspan="2" >{{ $header->iTramNumRegistro }}</td>
            </tr>
            <tr>
                <th class="align-middle" colspan="2" >&nbsp; Remitente</th>
                <td class="align-middle" colspan="1" >{{ $header->cNombre_Emisor }}</td>
                <th class="align-middle" colspan="2" >&nbsp; Dependencia</th>
                <td class="align-middle" colspan="2" >{{ $header->cDepenEmisorNombre }}</td>
            </tr>
            <tr>
                <th class="align-middle" colspan="2" >&nbsp; Contenido</th>
                <td class="align-middle" colspan="1" >{{ $header->cTramContenido }}</td>
                <th class="align-middle" colspan="2" >&nbsp; Fecha de Creación</th>
                <td class="align-middle" colspan="2" > {{ $headerDate }}
                </td>
            </tr>
            <tr>
                <td colspan="7" style="height:20px;border-width:0px"></td>
            </tr>
            <tr>
                <th  colspan="7">
                    <h3>Seguimiento de Documento</h3>
                </th>
            </tr>
            <tr>
                <th width="10" style="width:5px">N°</th>						
                <th> Fecha y Hora de Recepción </th>
                <th>Dependencia</th>
                <th>Documento</th>
                <th>#Folios</th>
                <th>Asunto</th>
                <th>Tiempo Transcurrido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultado as $key=>$dat)
            <tr>
                <td style="text-align: center;">{{ $key + 1 }}</td>
                <td style="text-align: center;" width="70">{{ $dat->date }}</td>
                <td width="320">
                    <b>Origen: </b>{{ $dat->cDepenEmisorNombre }} <br>
                    <b>Desti.:</b>{{ $dat->cDepenReceptorNombre }} 
                </td>
                <td style="text-align: center">{{ $dat->cTipoDocDescripcion }}-{{ $dat->cTramNumeroDocumento }}-{{$dat->cTramSiglaDocumento }}</td>
                
                <td style="text-align: center;" width="20">{{ $dat->iTramFolios }}</td>
                <td style="text-align: center">{{ $dat->cTramAsunto }}</td>
                <td style="text-align: center;">{{ $dat->ttr }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="height:20px;border-width:0px"></td>
            </tr>
            <tr>
                <th colspan="3">Última Dependencia</th>
                <td colspan="4">{{ $ultimaDep }}</td>
            </tr>
            <tr>
                <th colspan="3">Tiempo transcurrido desde inicio de trámite</th>
                <td colspan="4">{{ $totalDias }}</td>
            </tr>
        </tfoot>
    </table>

   
</body>
</body>
</html>