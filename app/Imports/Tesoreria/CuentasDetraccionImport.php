<?php

namespace App\Imports\Tesoreria;
use Illuminate\Http\Request;
//use App\TesoreriaCuentasDetraccion;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class CuentasDetraccionImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        print_r($row);
        $parameters1 = [
            $row['col0'],
            $row['col1'],
            $row['col2'],
            $row['col3'],

        ];
        $dataResult1 = DB::select('EXEC Siaf.Sp_Siaf_INS_Persona_DatosPrincipales ?,?,?,?',$parameters1);
        if($dataResult1[0]->iResult == 1)
        {
            $parameters2 = [
                $row['col0'],
                $row['col1'],
                $row['col4'],            
            ];
            $dataResult2 = DB::select('EXEC Siaf.Sp_Siaf_UPD_Persona_DatosGenerales_cCtaCteDetraccion ?,?,?',$parameters2);
            if($dataResult2[0]->iResult == 1)
            {
                //print_r($dataResult1);
                //echo 'La cuenta para el RUC: '.$row[1].' se agrego'.'<br>';
                response()->json($row['col1']);
            }
            else{
                echo $dataResult2[0]->cMensaje.'<br>';
            }
        }
        else{
            echo $dataResult1[0]->cMensaje.'<br>';
            // $parameters2 = [
            //     $row['col0'],
            //     $row['col1'],
            //     $row['col4'],            
            // ];
            // $dataResult2 = DB::select('EXEC Siaf.Sp_Siaf_UPD_Persona_DatosGenerales_cCtaCteDetraccion ?,?,?',$parameters2);
            
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

}
