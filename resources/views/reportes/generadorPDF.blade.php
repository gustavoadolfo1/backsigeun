<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>{{ $title }} - {{ $ofi }} </title>
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
    
    
    @if(count($header) > 0)
    @endIf
    <table class="table">
        <thead>
            <tr style="font-size: 20px; text-align: center">
                <td colspan="{{ count($head) + 1 }}" style="border:none">
                    <img src="./img/logo.png" style="height:55px; position: absolute; float: left; margin-left: 20px;">
                    <h2>UNIVERSIDAD NACIONAL DE MOQUEGUA</h2>
                </td>
            </tr>
            <tr style="font-size: 13px; text-align: center">
                <td colspan="{{ count($head) + 1 }}" style="border:none">
                    <h4 class="text-center">{{ $ofi }}</h4>
                </td>
            </tr>

            @foreach($header as $key1=>$ob)
                <tr>
                @foreach($ob as $key2=>$dt)
                    @if(count($ob) == 1 )
                        @if($key2 == 0)
                        <th colspan="{{ $dimenciones[$key2] + 1 }}">{{ $dt['title'] }}</th>
                        @else
                        <th colspan="{{ $dimenciones[$key2] }}">{{ $dt['title'] }}</th>
                        @endif
                        <td colspan="{{ $dimenciones[4] }}">{{ $dt['value'] }}</td>
                    @else
                        @if($key2 == 0)
                        <th colspan="{{ $dt['colspan'][0] + 1 }}">{{ $dt['title'] }}</th>
                        @else
                        <th colspan="{{ $dt['colspan'][0] }}">{{ $dt['title'] }}</th>
                        @endif
                        <td colspan="{{ $dt['colspan'][1] }}"> {{ $dt['value'] }}</td>
                    @endif
                @endforeach
                </tr>
            @endforeach
            <tr>
                <td colspan="{{ count($head) + 1 }}" style="height:20px;border-width:0px"></td>
            </tr>
            <tr>
                <th  colspan="{{ count($head) + 1 }}">
                    <h3>{{ $title }}</h3>
                </th>
            </tr>
            <tr>
                <th width="10" style="width:5px">N°</th>
                @foreach($head as $t)
                <th>{{ $t['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key=>$dd)
            <tr>
                <td align="center" style="width:3px">{{ $key + 1  }}</td>
                @foreach($head as $ts)
                <td align="{{ $ts['align'] }}" >{{ $dd[$ts['campo']] }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

   
</body>
</body>
</html>