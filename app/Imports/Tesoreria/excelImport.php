<?php

namespace App\Imports\Tesoreria;
use Illuminate\Http\Request;
//use App\TesoreriaCuentasDetraccion;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class ExcelImport implements ToModel,WithHeadingRow
{
    private $formatImport;
    private $params;
    private $mensajes;
   // private $longitud;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct($request){
        $this->formatImport = $request->formatImport;
        $this->params = $request;
    }   
    
    public function model(array $row)
    {
       // print_r($row);
       //echo $row['num'];
       // $dataResult = DB::select('EXEC tre.Sp_INS_UPD_personas_cuentas_XLS ?,?,?,?,?,?,?,?,?,?,?',$parameters);

        $i=0; 
         foreach ($row as $key => $value) {
             //$parameters[$this->titles[$i]] = $value;
             $fileds[$i] = $value;
             $i++;
         }
         unset($fileds[0]);
         unset($fileds[$i-1]);
        ;
        switch($this->formatImport){
            case 'FormatoCuentasAbonoTrabajadores':
                if($fileds[1]!="" && $fileds[2]!="")
                {
                   //print_r( ($fileds));
                    $params = ['1',$this->params->iMotCuentaId,$this->params->iBancoId,$this->params->iPersCuentaEstado,$this->params->iSiExisteModificar,auth()->user()->iCredId,'equipo',$this->params->server->get('REMOTE_ADDR'),'mac'];
                    $parameters=array_merge($params,$fileds);                
                    $dataResult = DB::select('EXEC tre.Sp_INS_UPD_personas_cuentas_XLS ?,?,?,?,?,?,?,?,?,?,?',$parameters);
                   if($dataResult[0]->iResult == 1){
                       //header('Content-type: application/json');
                        response()->json($row['dni']);
                       // echo json_encode($row['dni']);
                    }
                     else{
                         echo  $row['num'].'-'.$dataResult[0]->cMensaje.'<br>';
                     }
                }
            break;            
        }  
        //echo ($this->mensajes);    

        // for ($i=0; $i < 5; $i++) { 
        //     echo $this->mensajes[$i].'<br>';
        // }
    }
    public function headingRow(): int
    {
        return 2;
    }

}