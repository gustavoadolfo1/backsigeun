<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel; 
use PHPExcel; 
use PHPExcel_IOFactory;
class EgresadosController extends Controller
{

public function cargar_usuarios(Request $request)
    {
        
       $archivo = $request->file('archivo');
       $nombre_original=$archivo->getClientOriginalName();
       $extension=$archivo->getClientOriginalExtension();
       $r1=Storage::disk('archivos')->put($nombre_original,  \File::get($archivo) );
       $ruta  =  storage_path('archivos') ."/". $nombre_original;
       
        //$archivo = "libro1.xlsx";
        $inputFileType = PHPExcel_IOFactory::identify($ruta);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($ruta);
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 1; $row <= $highestRow; $row++){
        if (is_numeric($sheet->getCell("A".$row)->getValue())) {
             # code...
        if ((is_string($sheet->getCell("G".$row)->getValue())) 
            && (is_string($sheet->getCell("K".$row)->getValue())) 
            && (is_numeric($sheet->getCell("I".$row)->getValue())) 
            && (is_string($sheet->getCell("O".$row)->getValue())) 
            && (is_numeric($sheet->getCell("L".$row)->getValue()))
            && ($sheet->getCell("C".$row)->getValue()=="DERECHO") ) {
            # code...
        
            $nombre_apellidos = $sheet->getCell("G".$row)->getValue();
            $nombre_ap = explode(" ", $nombre_apellidos);
            $total_nomb_ap = count($nombre_ap); 
                

            $nombre=$nombre_ap[0]; 
                                
            $apellidos = $nombre_ap[$total_nomb_ap-2]." ".$nombre_ap[$total_nomb_ap-1];
                                   
            $email = $sheet->getCell("K".$row)->getValue(); 
            
            $dni = $sheet->getCell("I".$row)->getValue();

            $users=DB::select('call _users(?,?,?,?,?,?,?,?,?)',   ["0",$nombre,$email,Carbon::now(),bcrypt($dni),"default",Carbon::now(),Carbon::now(),"nuevo"]);
            $obtenerusuario=DB::table('users')->where('email','=',$email)->get();

            foreach ($obtenerusuario as $key => $usuario) {
                    $idusuario=$usuario->id;
                                                            }
            $cod_fac = 7;
            $cod_esc_prof = 9; 
            
            $fecha_ing = "1900-01-01";

            $pe_ing = "";

            $pe_mat = "";

            $fecha_eg = "1900-01-01";

            $pe_eg = "";

            $resolucion = $sheet->getCell("O".$row)->getValue(); 

            $condicion = "SI";

            $lug_nac = "";
            $fecha_nac = "1900-01-01";
            $edad = 0;
            $tiempo_residencia = "";
            $sexo = "";
            $cod_ec = 1;
            $direccion = "";
            $telefono = 0;
            $cel = $sheet->getCell("L".$row)->getValue(); 
            $situacion = "";

            $egresados=DB::select('call _egresado(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',   ["0",$cod_fac,$cod_esc_prof,$fecha_ing,$pe_ing,$pe_mat,$fecha_eg,$pe_eg,$resolucion,$condicion,$dni,$nombre,$apellidos,$lug_nac,$fecha_nac,$edad,$tiempo_residencia,$sexo,$cod_ec,$direccion,$telefono,$cel,$situacion,"",$idusuario,"nuevo"]);                                        

                                                }
                                                    }
                                                        }
        return back();
    }
}