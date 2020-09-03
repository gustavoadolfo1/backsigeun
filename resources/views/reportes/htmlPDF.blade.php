<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>{{ $title }} </title>
    <link rel="stylesheet" href=" asset('assets/bootstrap/css/bootstrap.min.css') ">
    
</head>
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
    .table thead td{    
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
    {!! $html !!}
</body>
</body>
</html>