<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <title> ACTA DE NOTAS </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">

</head>
<style>
    @page {
        margin: 15px 15px;
    }
</style>

<style>

    .ss{
        font-size:10px;
        text-align:center
    }
    .nb{
        border:0
    }
    .b{
        border:1px solid black
    }
    #footer .page:after {

        content: counter(page);
    }
    table{
        margin:20px auto;
    }
</style>

<body>
    <!-- <header>
        <img src="./img/logo.png" style="height:40px; position: relative; float: left; margin-left: 40px;" />

    </header> -->

    <main>
        <div>
            <table  width="85%"  cellpadding="1" cellspacing="0">

                <thead>
                    <tr style="font-size: 22px; text-align: center">
                        <th colspan="12">
                            <img src="./img/logo.png" style="dispplay:block;position:absolute;left:60px;height:40px;" />
                            <strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong>
                        </th>
                    </tr>
                    <tr style="font-size: 14px; text-align: center">
                        <th colspan="12"><strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
                        </th>
                    </tr>
                    <tr style="font-size: 14px; text-align: center">
                        <th colspan="12"><strong>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS</strong>
                        </th>
                    </tr>
                    <tr style="font-size: 13px; text-align: center; margin-top:10px">
                        <th colspan="12" style="padding-left: 155px; padding-top: 15px"></th>
                    </tr>
                    <tr style="font-size: 14px; text-align: center;padding:15px 0px">
                        <td colspan="12"><strong>ACTA DE NOTAS</strong> </td>
                    </tr>
                    <tr style="font-size: 13px; text-align: center">
                        <td colspan="12"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="ss b" style="background-color: #C3C3CB">DOCENTE</td>
                        <td colspan="2" class="ss b" style="text-align:left" >&nbsp;{{$acta[0]->cDocente}}</td>
                        <td colspan="3" class="ss b" style="background-color: #C3C3CB">SEMESTRE ACADEMICO:</td>
                        <td colspan="5" class="ss b">&nbsp;{{$cicloAcad}}</td>
                    </tr>

                    <tr>
                        <td  class="ss b" colspan="2" style="background-color: #C3C3CB">CURSO:</td>
                        <td  class="ss b" colspan="2" style="text-align:left">&nbsp;{{$acta[0]->cCurricCursoCod}} - {{$acta[0]->cCurricCursoDsc}}</td>
                        <td  class="ss b" colspan="3" style="background-color: #C3C3CB">CREDITOS:</td>
                        <td  class="ss b" colspan="2">&nbsp;{{$acta[0]->nCurricDetCredCurso}}</td>
                        <td  class="ss b" colspan="2" style="background-color: #C3C3CB">CICLO DE CURSO:</td>
                        <td  class="ss b" colspan="1">&nbsp;{{$acta[0]->cMatricDetCicloCurso}}</td>
                    </tr>
                    
                    <tr>
                        <td  class="ss b" colspan="2" style="background-color: #C3C3CB">CARRERA:</td>
                        <td  class="ss b" colspan="2" style="text-align:left">&nbsp;{{$acta[0]->cCarreraDsc}}<br>&nbsp;PLAN&nbsp;{{$acta[0]->cPlan}}</td>
                        <td  class="ss b" colspan="3" style="background-color: #C3C3CB">SEDE:</td>
                        <td  class="ss b" colspan="2">&nbsp;{{$acta[0]->cFilDescripcion}}</td>
                        <td  class="ss b" colspan="2" style="background-color: #C3C3CB">SECCION:</td>
                        <td  class="ss b" colspan="1">&nbsp;{{$acta[0]->cSeccionDsc}}</td>
                    </tr>
                    <tr>
                        <td colspan="12" style="height:30px"></td>
                    </tr>
                    <tr style="text-align: center; font-size:9px;background-color: #C3C3CB;  ">
                        <th class="b" rowspan="2" width="8px">N°</th>
                        <th class="b" rowspan="2" width="22px">CODIGO</th>
                        <th class="b" rowspan="2">APELLIDOS</th>
                        <th class="b" rowspan="2">NOMBRES</th>
                        <th class="b" rowspan="2" width="10px">ASIST.%</th>
                        <th class="b" colspan="2" width="12px"><strong>EVALUACIÓN REGULAR</strong></th>
                        <th class="b" colspan="2" width="12px"><strong>EVALUACIÓN SUSTITUTORIO</strong></th>
                        <th class="b" colspan="3" width="22px"><strong>EVALUACIÓN FINAL</strong></th>
                    </tr>
                    <tr style="text-align: center;background-color: #C3C3CB; font-size:9px">

                        <th class="b" width="12px">N°</th>
                        <th class="b" width="20px">LETRAS</th>
                        <th class="b" width="10px">N°</th>
                        <th class="b" width="20px">LETRAS</th>
                        <th class="b" width="12px">N°</th>
                        <th class="b" width="20px">LETRAS</th>
                        <th class="b" width="20px">APROB./DESAPR.</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($acta as $index=>$a)
                    <tr style="text-align: center; font-size:8px; border-color: black; solid 2px">
                        <td class="b" width="8px">{{$index+1}}</td>
                        <td class="b">{{$a->cMatricCodUniv}}</td>
                        <td class="b" style="text-align: left;">&nbsp;&nbsp;{{$a->cApellidos}}</td>
                        <td class="b" style="text-align: left;">&nbsp;&nbsp; {{$a->cPersNombre}}</td>
                        <td class="b" width="10px">{{$a->cMatricDetAsist}} %</td>

                        @if(($a->nMatricDetPF)>10)
                        <td class="b" width="12px" style="color:blue; border-color: black; solid 2px">{{$a->nMatricDetPF}}</td>
                        <td class="b" width="20px" style="color:blue; border-color: black; solid 2px">{{$a->cMatricDetLPF}}</td>
                        @else
                        <td class="b" width="12px" style="color:red; border-color: black; solid 2px">{{$a->nMatricDetPF}}</td>
                        <td class="b" width="20px" style="color:red; border-color: black; solid 2px">{{$a->cMatricDetLPF}}</td>
                        @endif

                        @if(($a->nMatricDetAplaz)>10)
                        <td class="b" width="10px" style="color:blue; border-color: black; solid 2px">{{$a->nMatricDetAplaz}}</td>
                        <td class="b" width="20px" style="color:blue; border-color: black; solid 2px">{{$a->cMatricDetLaplaz}}
                        </td>
                        @else
                        <td class="b" width="10px" style="color:red; border-color: black; solid 2px">{{$a->nMatricDetAplaz}}</td>
                        <td class="b" width="20px" style="color:red; border-color: black; solid 2px">{{$a->cMatricDetLaplaz}}</td>
                        @endif

                        @if(($a->nMatricDetEF)>10)
                        <td class="b" width="12px" style="color:blue; border-color: black; solid 2px">{{$a->nMatricDetEF}}</td>
                        <td class="b" width="20px" style="color:blue; border-color: black; solid 2px">{{$a->cMatricDetLEF}}</td>
                        @else
                        <td class="b" width="12px" style="color:red; border-color: black; solid 2px">{{$a->nMatricDetEF}}</td>
                        <td class="b" width="20px" style="color:red; border-color: black; solid 2px">{{$a->cMatricDetLEF}}</td>
                        @endif

                        @if(trim($a->cMatricDetOEF)=="APROBADO")
                        <td class="b" width="30px" style="color:blue; border-color: black; solid 2px">{{$a->cMatricDetOEF}}</td>
                        @else
                        <td class="b" width="30px" style="color:red; border-color: black; solid 2px">{{$a->cMatricDetOEF}}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <div style="text-align: center" style="margin-bottom: -55px;">
            <p style="line-height:4px">_____________________________</p>
            <p style="font-size:8px;">
                Docente
            </p>
        </div>

    </main>
    <footer>
        <div style="text-align: center">

            <p style="color: black; text-align: center; font-size: 9px;">Nº MATRICULADOS: {{$acta[0]->iTotMatriculados}}
                Nº
                APROBADOS: {{$acta[0]->iTotAprobados}} Nº DESAPROBADOS: {{$acta[0]->iTotDesaprobados}} Nº
                SUSTITUTORIO: {{$acta[0]->iTotSustitutorios}} </p>
        </div>
    </footer>
</body>


</html>
