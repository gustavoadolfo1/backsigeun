<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anexo 7</title>

    <style>
        body {
            font-size: 16px;
        }
        ol > li {
            margin-bottom: 15px;
            line-height: 1.3;
        }
        .center {
            text-align: center;
        }
        .info {
            line-height: 2;
        }
    </style>
</head>
<body>
    <p class="center"><b>ANEXO Nº7</b></p>
    <p class="center"><b>DECLARACIÓN JURADA DE DATOS DEL PROVEEDOR Y CUMPLIMIENTO DE LOS REQUERIMIENTOS TÉCNICOS MÍNIMOS</b></p>
    <p class="info">
        Yo, <b><i>{{ $data->cPersRazonSocialNombre }}</i></b>, con RUC Nº <b><i>{{ $data->cPersDocumento }}</i></b>, con domicilio en <b><i>{{ $data->cDireccion }}</i></b>, teléfono Nº <b><i>{{ $data->cTelefonoMovil }}</i></b>, con correo electrónico <b><i>{{ $data->cCorreoElectronico }}</i></b>.
        <br>
        Debidamente representado por <b><i>{{ $data->cPersRepresentateLegal ?? '___________________________________' }}</i></b>, identificado con DNI Nº <b><i>{{ $data->cPersRepresLegDocumento ?? '______________________' }}</i></b>, según poder inscrito en <b><i>{{ $data->cPersRepresLegInscritoEn ?? '______________________'  }}</i></b>.
    </p>
    <p>
        Declaro bajo juramento que:
        <ol>
            <li>No tener impedimento para poder participar en el procedimiento de selección, para contratar en el Estado, de acuerdo al artículo 11° de la Ley Nº 30225.</li>
            <li>Conoce, acepta y se somete a los términos de referencia y especificaciones técnicas del área usuaria.</li>
            <li>Soy responsable de la veracidad de los documentos e información que presento para efectos de sustentar mi cotización.</li>
            <li>Me comprometo a mantener todas las consideraciones ofrecidas en mi cotización durante la presente adjudicación sin proceso.</li>
            <li>Conozco las sanciones contenidas en la Ley de contrataciones del Estado y su reglamento, así como en la Ley Nº 27444, Ley de Procedimiento Administrativo General, y en la presente Directiva.</li>
            <li>Cumplo en su totalidad los alcances, las condiciones existentes, las especificaciones técnicas, características y términos de referencia del bien o servicio ofertado.</li>
            <li>Autorizo a la notificación de la Orden de Compra y/o Servicio al correo electrónico consignado en mi cotización y presente documento.</li>
        </ol>
    </p>
    <br>
    <p>
        Moquegua, {{ $date }}
    </p>
    <br><br><br><br><br>
    
    <p class="center">..............................................................................</p>
    <p class="center">Firma y Sello del Representante Legal</p>
 
    
</body>
</html>