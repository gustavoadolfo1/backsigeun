<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .body{
        display:block;
        position:relative;
        width:100%;
        height:auto;
        font-family:Lucida, sans-serif;
        background-color:#ECEFF1;
        padding:30px 00px;
    }
    .container{
        display:block;
        position:relative;
        width:80%;
        max-width:480px;
        margin:30px auto;
    }
    .card {
        background: #fff;
        border-radius: 3px;
        display: inline-block;
        position: relative;
        padding:0px;
        box-shadow:0 0 11px #090909
    }
    .card-header{
        background:#e0e3ec ;
        display:block;
        position: relative;
        /* border:2px solid black; */
        padding:14px 16px;
        box-shadow:0 0 11px #090909
    }
    .card-body{
        background: white;
        display:block;
        position: relative;
        /* border:2px solid black; */
        padding:16px;
        box-shadow:0 0 11px #090909
    }
    .card-1 {
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    .center {
        text-align:center
    }
    .right{
        display:block;
        position:relative;
        width:280px;
        margin:30px 0px;
        right:0px;
        float:right;
        text-align:center;
    }
    .left{
        margin-top:70px;
    }
</style>
<body>
    <div class="body">
        <div class="container">
            <div class="card card-1">
                <div class="card-header">
                    <p class="center">
                        <img style="display: block;position:relative;margin:20px auto" src="http://www.unam.edu.pe/images/logo210x60.png" alt="" width="210" />
                    </p>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Señores :</strong> <br>
                        <span>{{ $data->cNombre_Proveedor }}</span>
                    </p>
                    <p>Previos saludos cordiales, a través del siguiente se extiende la siguiente notificación: </p>
                    <p>
                        <strong>N° Orden : </strong> <span>{{ $data->NRO_ORDEN}}</span><br>
                        <strong>N° SIAF : </strong> <span>{{ $data->NRO_SIAF}}</span><br>
                    </p>
                    <p>
                        Debiendo cumplir con la misma dentro del plazo establecido, a partir de la presente notificación. 
                    </p>
                    <p>
                        En tal sentido queda usted válidamente notificado para todos los efectos legales.
                    </p>
                    <div class="left">
                        <span>Atentamente:</span><br><br>
                        <small>
                            {{ $data->cNombre_Cotizador }} <br>
                            Oficina de Logística <br>
                            <b>UNIVERSIDAD NACIONAL DE MOQUEGUA</b> 
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
