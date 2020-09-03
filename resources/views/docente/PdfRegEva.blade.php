<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title> REGISTRO DE EVALUACIÓN </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">

</head>

<body>
    <img src="./img/logo.png" id="img-logo" style="height:55px; position: relative; float: left; margin-left: 20px;">
    <table align="center" style="margin-left: 130px; margin-right:120px; margin-top: -10px;">
        <tr style="font-size: 20px; text-align: center">
            <th><strong>UNIVERSIDAD NACIONAL DE MOQUEGUA</strong></th>
        </tr>
        <tr style="font-size: 13px; text-align: center">
            <th><strong>VICEPRESIDENCIA ACAD&Eacute;MICA</strong>
            </th>
        </tr>
        <tr  style="font-size: 13px; text-align: center">
            <th><strong>DIRECCI&Oacute;N DE ACTIVIDADES Y SERVICIOS ACAD&Eacute;MICOS</strong>
            </th>
        </tr>
        <tr style="font-size: 13px; text-align: center; margin-top:10px">
            <th style="padding-left: 155px; padding-top: 15px"></th>
        </tr>
        <tr  style="font-size: 13px; text-align: center">
            <td></td>
        </tr>
    </table>
    <br>
    <p align="left" style="margin-top: -10px;"><strong>REGISTRO DE EVALUACIÓN</strong></p>
    <table  border="1"  width="100%" style="text-align: justify; font-size:9px" cellpadding="2" cellspacing="0">
      <tr>
        <td style="background-color: #C3C3CB" width="15%">DNI /DOCENTE</td>
        <td colspan="3" width="50%">{{$eva[0]->Docente}}</td>
        <td style="background-color: #C3C3CB" width="25%">SEDE:</td>
        <td width="10%">{{ $eva[0]->cFilDescripcion }}</td>
      </tr>

      <tr>
        <td style="background-color: #C3C3CB" width="15%">ESCUELA PROFESIONAL:</td>
        <td width="30%">{{ $eva[0]->cCarreraDsc }}</td>
        <td style="background-color: #C3C3CB" width="10%">SEMESTRE:</td>
        <td width="10%">{{ $eva[0]->iControlCicloAcad }}</td>

      </tr>

       <tr>
            <td style="background-color: #C3C3CB" width="15%">CURSO:</td>
            <td width="30%">{{ $eva[0]->cCurricCursoCod }} {{ $eva[0]->cCurricCursoDsc }} {{  }}</td>
            <td style="background-color: #C3C3CB" width="10%">SEDE:</td>
            <td width="10%"></td>
            <td style="background-color: #C3C3CB" width="25%">SECCION:</td>
            <td width="10%"></td>
       </tr>


   </table>
<br>
   <table   border="1" width="660px"  cellpadding="2" cellspacing="0" >

                <thead>
                <tr style="text-align: center; font-size:9px;background-color: #C3C3CB;  ">
                    <th rowspan="2" width="8px">N°</th>
                    <th rowspan="2" width="22px">CODIGO</th>
                    <th rowspan="2" width="40px">APELLIDOS</th>
                    <th rowspan="2" width="40px">NOMBRES</th>
                    <th rowspan="2" width="10px">ASIST.%</th>
                    <th colspan="2"  width="12%"><strong>EVALUACION REGULAR</strong></th>
                    <th colspan="2"  width="12%"><strong>EVALUACIÓN APLAZADOS</strong></th>
                    <th colspan="3"  width="22%"><strong>EVALUACIÓN FINAL</strong></th>
                </tr>
                <tr style="text-align: center;background-color: #C3C3CB; font-size:9px">

                    <th width="12px">N°</th>
                    <th width="20px">LETRAS</th>
                    <th width="10px">N°</th>
                    <th width="20px">LETRAS</th>
                    <th width="12px">N°</th>
                    <th width="20px">LETRAS</th>
                    <th width="20px">APROB./DESAPR.</th>
                    </tr>
                </thead>
                <tbody>

                        @foreach($eva as $index=>$a)
                            <tr style="text-align: center; font-size:8px; border-color: black; solid 2px">
                                <td width="8px" >{{$index+1}}</td>
                                <td width="22px" ></td>
                                <td width="40px" ></td>
                                <td width="40px" ></td>
                                <td width="10px" > %</td>


                                <td width="12px" style="color:blue; border-color: black; solid 2px" ></td>
                                <td width="20px" style="color:blue; border-color: black; solid 2px" ></td>

                                <td width="12px" style="color:red; border-color: black; solid 2px" ></td>
                                <td width="20px" style="color:red; border-color: black; solid 2px" ></td>



                                <td width="10px" style="color:blue; border-color: black; solid 2px"  ></td>
                                <td width="20px" style="color:blue; border-color: black; solid 2px" ></td>

                                <td width="10px" style="color:red; border-color: black; solid 2px" ></td>
                                <td width="20px" style="color:red; border-color: black; solid 2px" ></td>

                                <td width="12px" style="color:blue; border-color: black; solid 2px" ></td>
                                <td width="20px" style="color:blue; border-color: black; solid 2px"  ></td>

                                <td width="12px" style="color:red; border-color: black; solid 2px"></td>
                                <td width="20px" style="color:red; border-color: black; solid 2px"></td>


                                <td width="30px" style="color:blue; border-color: black; solid 2px" ></td>

                                <td width="30px" style="color:red; border-color: black; solid 2px"></td>

                            </tr>
                            @endforeach




        </table>
    <br>
    <br>
    <p style="line-height:5px">_____________________________</p>
    <p style="font-size:9px; margin-top:-10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Docente</p>

    <p style="color: black; text-align: left; font-size: 9px; margin:120px 150px 50px 150px">Nº MATRICULADOS: Nº APROBADOS:  Nº DESAPROBADOS:  Nº
	 SUSTITUTORIO: </p>

</body>
</body>
</html>
