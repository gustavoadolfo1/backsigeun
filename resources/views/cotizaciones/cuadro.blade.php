<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
            width:100%;
            border-collapse: collapse;
        }
        td{
            padding: 3px 6px;
        }
        th{
            padding: 3px 6px;
        }
        .center{
            text-align: center;
        }
        .right{
            text-align: right;
        }
        .nb{
            border:none
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
    </style>
</head>

<body>
    
    <script type="text/php">
        $text2 = '{PAGE_NUM} de {PAGE_COUNT}';
        $font = $fontMetrics->get_font( "verdana" , "bold" );
        $pdf->page_text(523, 65, $text2, $font, 9);
    </script>
    @foreach($res as $keyt=>$data)
    <table class="table mb-4" >
        <thead class="thead-dark">
            <tr>
                <th colspan="{{(count($data) * 2) + 4 }}" align="center" height="60">
                    <img src="img/logo.png"  style="display:block;position:absolute;top:0;left:0;height:80px;width:120px">
                    <h1 style="font-size:18px;">UNIVERSIDAD NACIONAL DE MOQUEGUA</h1> <br>
                    <h5>Cuadro Comparativo</h5>
                </th>
            </tr>
            <tr>
                <th class="b" colspan="4"  align="center">
                    <h3>ARTÍCULOS</h3>
                </th>
                <th class="b" colspan="{{(count($data) * 2)}}" align="center">
                    <h3>EVALUACIÓN</h3>
                </th>
            </tr>
            <tr>
                <th class="b" rowspan="3" align="center">N°</th>
                <th class="b" width="250" rowspan="3" align="center">DESCRIPCIÓN</th>
                <th class="b" rowspan="3" align="center">UNIDAD</th>
                <th class="b" rowspan="3" align="center">CANTIDAD</th>
                @foreach($data as $key=>$one)
                    <th class="br" colspan="2" align="center">
                        <small style="font-size:8px">({{ $one->npro }})</small>
                    </th>
                @endforeach
                
            </tr>
            <tr>
                @foreach($data as $key=>$one)
                    <th class="br" colspan="2" align="center">
                        {{ $one->cNombre_Proveedor }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach($data as $key=>$one)
                    <th class="b" align="center">UNITARIO</th>
                    <th class="b" align="center">TOTAL</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="b">
            @foreach($data[0]->detalles as $key=>$detalle)
            <tr>
                <td class="b" align="center">{{ $key + 1 }}</td>
                <td class="b">{{ $detalle->cNOMBRE_BSO }}</td>
                <td class="b" align="center">{{ $detalle->cUM_BSO }}</td>
                <td class="b" align="center">{{ number_format((float)$detalle->nPedEnLDetCantidad , 2)  }}</td>
                @foreach($data as $kex=>$detalle)
                    <td class="b" align="center">
                        <small>S/. {{ number_format((float)$detalle->detalles[$key]->nCotizaDetPrecioUnitario , 2) }}</small>
                        
                    </td>
                    <td class="b" align="center">
                        @if($detalle->detalles[$key]->bCotizaDetBuenaPro == 1)
                           <br>
                        @endif
                        <small>S/. {{ number_format((float)$detalle->detalles[$key]->nCotizaDetSubTotal , 2) }}</small>
                        <br>
                        @if($detalle->detalles[$key]->bCotizaDetBuenaPro == 1)
                            <img src="img/check.svg" width="18" style="margin-top:0px">
                        @endif
                        
                    </td>
                @endforeach
            </tr> 
            @endforeach
        </tbody>
    </table>
    @if($keyt < count($res))
        <div style="page-break-after:always;"></div>
    @endif
    @endforeach
</body>
</html>

                                        