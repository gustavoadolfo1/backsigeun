
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        *{
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
        table{
            border-collapse: collapse;
            width:100%;
            margin-bottom:20px;
        }
        td{
            padding: 3px 6px;
        }
        th{
            padding: 3px 6px;
        }
        .mt-{

        }
        .foto{
            display:block;
            position:absolute;
            margin-top:0px auto;
            top:0;
            width:2.7cm;
            height:3.5cm;
            border:1px solid black;
        }
        .foto img{
            display:block;
            position:relative;
            margin-left:0.05cm;
            width:2.6cm;
            height:3.4cm;
            margin-top:0.05cm;
        }
        .center{
            text-align
            : center;
        }
        .right{
            text-align: right;
        }
        .nb{
            border:none !important;
        }
        .b{
            border:1px solid black;

        }
        .bt{
            border-top:1px solid black;
        }
        .br{
            border-right:1px solid black;
        }
        .bb{
            border-bottom:1px solid black;
        }
        .bbl{
            border-bottom:1px solid black;
        }
        .bl{
            border-left:1px solid black;
        }
        .bg-color{
            background-color:#c3c3c3;
        }
        .box{
            display:block;
            position:relative;
            width:100%;
            width:100%;
            text-align:center;
            border:2px solid black;
        }
        .box-head{
            text-align:center;
            padding:6px 2px;
        }
        .box-body{
            text-align:center;
            padding:6px 2px;
            border-top:2px solid black;
        }
        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 50px; 
            text-align: center;

        }
        .boxis{
            display:block;
            position:relativo;
            border-top:1px solid black;
            width:100%;
            margin-top:10px;
            height:45px;
        }
    </style>
</head>
<body>
    <table style="margin:0px">
        <thead>
            <tr>
                <td align="center" colspan="12">
                    <img src="./img/escudo.jpg" style="display:block;position:absolute;left:10px;top:10px;height:50px;">
                    <h1 style="font-size:1.5rem"> UNIVERSIDAD NACIONAL DE MOQUEGUA<br> COMISIÓN DE ADMISIÓN</h1>
                </td>
            </tr>
            <tr>
                <th colspan="12">
                    <h4> CONTROL DE ASISTENCIA DE POSTULANTES POR AULA</h4>
                </th>
            </tr>
            <tr>
                <th class="b bg-color" colspan="2">AULA</th>
                <td class="b" colspan="10"> {{ $data[0]->cAulasDesc }} </td>
            </tr>
            <tr>
                <th class="b bg-color" colspan="2">UBICACIÓN</th>
                <td class="b" colspan="2"> {{ $data[0]->iAulaPiso }} piso</td>
                <th class="b bg-color" colspan="2">NRO. POSTULANTES</th>
                <td class="b" colspan="2">{{ $data[0]->iTotalProcesados }}</td>
                <th class="b bg-color" colspan="2">SEDE DE EXAMEN</th>
                <td class="b" colspan="2">{{ $data[0]->cFilialExamen }}</td>
            </tr>
            <tr>
                <td colspan="12">&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key=>$d)
            
                @if(($key + 1) % 2 != 0)
                <tr>
                    <td class="b" align="center">
                        @if($d->cFotografia)
                                    <img src="storage/adm/fotos/{{ $d->cFotografia }}" width="70"  height="85">
                        @else
                        <img src="storage/adm/fotos/0.jpg" alt="" style="width:1.8cm;height:2.1cm;">
                        @endif
                    </td>
                    <td class="b" colspan="4" valign="bottom"  style="font-size:9px">
                        ({{ $d->iOrdenSalon }})&nbsp;{{ $d->cPersDocumento }}<br>
                        <b style="font-size:9px"> {{ $d->cPersPaterno }} {{ $d->cPersMaterno }}</b>, {{ $d->cPersNombre }}<br>
                        <i style="font-size:8px">{{ $d->cCarreraDsc }} ( {{ $d->cFilSiglaCarrera }} )</i> 

                        <div class="boxis">
                            
                        </div>

                    </td>
                    <td class="b" align="center" width="40">
                        
                    </td>
                @endif
                @if(($key + 1) % 2 == 0)
                <td class="b" align="center">
                        @if($d->cFotografia)
                                    <img src="storage/adm/fotos/{{ $d->cFotografia }}" width="70"  height="85">
                        @else
                        <img src="storage/adm/fotos/0.jpg" alt="" style="width:1.8cm;height:2.1cm;">
                        @endif
                    </td>
                    <td class="b" colspan="4" valign="bottom"  style="font-size:9px">
                        ({{ $d->iOrdenSalon }})&nbsp;{{ $d->cPersDocumento }}<br>
                        <b style="font-size:9px">{{ $d->cPersPaterno }} {{ $d->cPersMaterno }} </b>, {{ $d->cPersNombre }}<br>
                        <i style="font-size:8px">{{ $d->cCarreraDsc }} ( {{ $d->cFilSiglaCarrera }} )</i> 

                                <div class="boxis"> </div>
                    </td>
                    <td class="b" align="center" width="40">
                        
                    </td>
                </tr>
                @endif
            @endforeach
            @if( count($data) % 2 != 0 )
                    <td class="bt bl" colspan="6">
                        
                    
                </tr>
                
            @endif
            
        </tbody>
   </table>
</body>
</html>



<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title> 
</head>
<body>
    <div >
        @foreach($data as $key=>$d)
        <div style="display:inline-block;margin-bottom:10px">
            <div style="display:block">
                <table border="1" width="260" height="150" >
                        <tr>
                            <td width="75" rowspan="2" valign="middle" align="center" >
                                @if($d->cFotografia)
                                    <img src="storage/adm/fotos/{{ $d->cFotografia }}" width="70"  height="85">
                                @else
                                <img src="storage/adm/fotos/0.jpg" alt="" style="width:1.8cm;height:2.1cm;">
                                @endif
                                
                            </td>
                            <td width="120"  height="50" style="font-size:9px">
                                &nbsp;<strong>({{ $d->iOrden }})</strong>&nbsp;<span>{{ $d->cPersDocumento }}</span><br>
                                <span> <strong>{{ $d->cPersPaterno }} {{ $d->cPersMaterno }}</strong> , {{ $d->cPersNombre }}</span> <br>
                                <span>{{ $d->cCarreraDsc }} ( {{ $d->cFilSigla }} )</span>
                            </td>   
                            <td  rowspan="2">
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                </table>
            </div>
        </div>
            
            @if($key % 2 != 0)
                <br>
            @endif
        @endforeach
    </div>
    
<footer>
    Universidad Nacional de Moquegua
</footer>
</body>
</html> -->
