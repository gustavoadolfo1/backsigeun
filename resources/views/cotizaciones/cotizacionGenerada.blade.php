<!DOCTYPE html>
<html lang="en">
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
            border:2px solid black;
        }
        .bt{
            border-top:2px solid black;
        }
        .br{
            border-right:2px solid black;
        }
        .bb{
            border-bottom:2px solid black;
        }
        .bbl{
            border-bottom:1px solid black;
        }
        .bl{
            border-left:2px solid black;
        }
    </style>
</head>

<body>
    
<script type="text/php">
    $text2 = '{PAGE_NUM} de {PAGE_COUNT}';
    $font = $fontMetrics->get_font( "verdana" , "bold" );
    $pdf->page_text(523, 65, $text2, $font, 9);
</script>
    <table style="width:100%">
        <thead>
            <tr>
                <th colspan="6">Sistema de Logística</th>
                <th>Fecha</th>
                <th>: {{ date('d/m/y') }}</th>
            </tr>
            <tr>
                <th colspan="6">Módulo de Cotizaciones en Linea</th>
                <th>Hora</th>
                <th>: {{ date('H:i') }}</th>
            </tr>
            <tr>
                <th colspan="6">Versión 0.0.1</th>
                <th>Página</th>
                <th>: @php $text @endphp </th>
            </tr>
            <tr>
                <th colspan="8">
                    <h2 class="center" style="font-size:18px">SOLICITUD DE COTIZACIÓN</h2>
                </th>
            </tr>
            <tr>
                <th colspan="2">UNIDAD EJECUTORA</th>
                <td colspan="6">: 001 Universidad Nacional de Moquegua</td>
            </tr>
            <tr>
                <th colspan="2">NRO. IDENTIFICACIÓN</th>
                <td colspan="6">: 001230</td>
            </tr>
            <tr>
                <th colspan="8">&nbsp;</th>
            </tr>
            <tr>
                <th class="bt bl">Señores</th>
                <td class="bt" colspan="4">: {{ $head->cNombre_Proveedor}}</td>
                <th class="bt right">R.U.C.</th>
                <td class="bt br" colspan="2">: {{ $head->cDocumento_Proveedor }}</td>
            </tr>
            <tr>
                <th class="bl">Dirección</th>
                <td class="br" colspan="7">: {{ $head->cDireccion_Proveedor }}</td>
            </tr>
            <tr>
                <th class="bl">Teléfono</th>
                <td colspan="2">: {{ $head->cTelefono_Proveedor }}</td>
                <th class="right">Fax</th>
                <td class="br" colspan="4">:</td>
            </tr>
            <tr>
                <th class="bl">Nro Cons.</th>
                <td colspan="2">: {{ $head->NRO_REQUER }}</td>
                <th class="right">Fecha</th>
                <td>: {{$head->dCotizaFecha }}</td>
                <th  class="right">Documento</th>
                <td class="br" colspan="2">: {{ $head->NRO_PEDIDO }}</td>
            </tr>
            <tr>
                <th class="bl bb">Concepto</th>
                <td colspan="7" class=" bb br">: {{ $head->DOCUMENTO_PEDIDO}}</td>
            </tr>
            <tr>
                <th colspan="8">&nbsp;</th>
            </tr>
            <tr>
                <th class="b center" width="40">CANTIDAD <br> REQUERIDA</th>
                <th class="b center" width="40">UNIDAD MEDIDA</th>
                <th class="b center" colspan="4">DESCRIPCIÓN</th>
                <th class="b center" width="40">PRECIO <br> UNITARIO</th>
                <th class="b center" width="40">PRECIO  <br> TOTAL</th>
            </tr>
        </thead>    
        <tbody class="b" style="max-height:300px">
            @foreach($data as $r)
            <tr>
                <td class="center bb" valign="top">{{ number_format((float)($r->nPedEnLDetCantidad),2) }}</td>
                <td class="center bb" valign="top">{{ $r->cUM_BSO }}</td>
                <td class="bb" colspan="4">
                    <span>{{ $r->cNOMBRE_BSO }}</span><br>
                    @if(count($r->anexos) > 0)
                    <strong>TÉRMINOS DE REFERENCIA</strong>
                    <ul>
                        @foreach($r->anexos as $a)
                        <li>{{ $a->ANEXO_ITEM }}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
                <td class="center bb" valign="top">{{ number_format((float)($r->nCotizaDetPrecioUnitario),2) }}</td>
                <td class="center bb" valign="top">{{ number_format((float)($r->nCotizaDetSubTotal),2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6" class="nb bt" style="border-bottom:none"></td>
                <td class="b center">
                    <strong>Total</strong>
                </td>
                <td  class="center b">
                    {{ number_format((float)$sume , 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        Las cotizaciones deben estar dirigidas a UNIVERSIDAD NACIONAL DE MOQUEGUA <br>
        en la siguiente dirección : PROLONGACIÓN CALLE ANCASH S/N &nbsp;&nbsp; Teléfono : 053461335
    </p>
    <strong>Condiciones de Compra</strong>
    <ul>
        <li><strong>Forma de Pago: </strong><span>{{ $head->cFormaPago }}</span></li>
        <li><strong>Garantía: </strong><span>{{ $head->cGarantia }}</span></li>
        <li><strong>La Cotización debe incluir el I.G.V.</strong></li>
        <li><strong>Plazo de Entrega / Ejecución de Servicio: </strong><span>{{ $head->iPlazoEntregaEjecucionServicio }} días.</span></li>
        <li><strong>Tipo Moneda: </strong><span>{{ $head->cTipoMoneda }}</span></li>
        <li><strong>Validez de cotización: </strong><span>{{ $head->iValidezCotizacion }}</span></li>
        <li><strong>Remitir junto con su cotización la Declaración Jurada y Pacto de Integridad, debidamente firmadas y selladas</strong></li>
        <li><strong>Indica su razón social, domicilio fiscal y número R.U.C.</strong></li>
    </ul>


    <table width="120" style="position:relative;right:20px">
        <thead>
            <tr>
                <th class="center"><span>Atentamente</span></th>
            </tr>
            <tr>
                <th class="bb" height="75px"></th>
            </tr>
            <tr>
                <th class="center">{{ $head->cNombre_Proveedor }}</th>
            </tr>
        </thead>
    </table>

</body>
</html>

