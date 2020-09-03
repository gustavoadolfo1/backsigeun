<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>

    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="format-detection" content="date=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="x-apple-disable-message-reformatting" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Email Template</title>
    <style>
        * {
            margin: 0;
        }

        /* reset link */

        a {
            text-decoration: none;
            color: #000;
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        .container-body {
            background-color: #333545;
            width: 100%;
        }

        .content-container {
            width: 100%;
            background: #fff;
        }

        .header-email {
            height: 6rem;
            background: #fff;
        }


        html,
        body,
        #wrapper {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            border: 0;
        }

        #wrapper td {
            vertical-align: middle;
            text-align: center;
        }

        .image-logo {
            display: block;
            margin: auto;
        }


        /* message content */

        .message-email {
            font-size: 1.2rem;
            background: #fff;
            padding: 1rem;
        }

        .bold-text {
            font-weight: 700;
        }

        .bottom-spacing {
            margin-bottom: 1rem;
        }

        .light {
            font-weight: 300;
        }

        .normal-text {
            font-weight: 400;
        }

        .image {
            width: 100%;
        }

        .button-link {
            padding: 0.5em 1em;
            background: #fff;
            color: rgb(78, 84, 203);
            font-size: 1.1rem;
            margin-left: auto;
        }

        .pre-footer-email {
            padding: 2rem 1rem;
            font-size: 1.1rem;
            font-weight: 300;
            line-height: 1.5;
        }

        .footer-email {
            padding: 1rem;
            /* font-size: 0.7rem; */
            text-align: center;
            background-color: #eee;
            font-weight: 300;
            /* padding-bottom: 100% */
        }

        @media only screen and (min-width: 480px) {
            .content-container {
                max-width: 650px;
                margin-right: auto;
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <div class="container-body">
        <div class="content-container">
            <div class="header-email">
                <table id="wrapper">
                    <tr>
                        <td><img class="image-logo" src="http://www.unam.edu.pe/images/logo210x60.png"
                                alt="logo unam" />
                        </td>
                    </tr>
                </table>
            </div>

            <div class="message-email">
                <p class="bottom-spacing"><span class="bold-text">Hola {{$data->cPersNombre}}. 🖐️</span></p>
                <p class="bottom-spacing light">Te acabas de preinscribir en el curso</p>
                <p class="bottom-spacing light">{{$data->cCarreraDsc}} {{$data->cModProgDsc}} - Ciclo {{$data->cRitmoDsc}}</p>
                <p class="bottom-spacing light">⏰ Horario: <span class="normal-text">{{$data->cConfigDiasDsc}} de
                     {{ Carbon\Carbon::parse($data->horaInicio)->format('H:i:s A')}} a
                    {{ Carbon\Carbon::parse($data->horaFin)->format('H:i:s A') }}</span> </p>

            </div>

            <div class="sidebar-email">
                <img src="https://www.unam.edu.pe/images/ceid.jpg" class="image" alt="">
            </div>

            <div class="pre-footer-email">
                <p> ⚠️Recuerda que cuando el curso se aperture se te enviara nuevamente un correro electronico con
                    los
                    detalles de pago y matricula.</p>
            </div>
            <div class="footer-email">
                Copyright © UNAM Todos los derechos reservados
            </div>
        </div>

    </div>
</body>

</html>
