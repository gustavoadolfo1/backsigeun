<?php

namespace App\Http\Controllers\Inv;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\UraCurricula;

use App\ClasesLibres\Reportes\ReporteExcel;
use App\ClasesLibres\Reportes\DasaExcelReporte;
use App\ClasesLibres\Reportes\SpreadSheetReporteMaker;

class ExportReporteController extends Controller
{
    //


        public static function reporteExceltrimestral($Tipo, $Anyo)
        {
            // cPersDocumento	cCarreraDsc	cEstudCodUniv	cEstudSemeIngre	cEstudSemeUlti	nombres	curricula	ciclox	promedio	creditos	total
            $data = \DB::select('exec [inv].[Sp_REP_anual_trimestral] ?,?', array($Tipo, $Anyo));

            $head = [
                [ 'title' => 'Tipo de Proyecto', 'campo' => 'cTipoProyDescripcion', 'width' => '15', 'align' => 'left' ],
                [ 'title' => 'Proyecto', 'campo' => 'cNombreProyecto', 'width' => '50', 'align' => 'left' ],
                [ 'title' => 'Director', 'campo' => 'director', 'width' => '15', 'align' => 'left' ],
                [ 'title' => 'Carrera', 'campo' => 'cCarrera', 'width' => '30', 'align' => 'left' ],
                [ 'title' => 'Linea de Investigación', 'campo' => 'cLinea', 'width' => '50', 'align' => 'left' ],
                [ 'title' => 'Resolución', 'campo' => 'cResProyecto', 'width' => '30', 'align' => 'left' ],
                [ 'title' => 'Estado', 'campo' => 'cEstado', 'width' => '20', 'align' => 'left' ],
                [ 'title' => 'Presupuesto Asignado', 'campo' => 'nPresupuestoProyecto', 'width' => '10', 'align' => 'left' ],
                [ 'title' => 'Presupuesto Ejecutado', 'campo' => 'nPresupuestoEjecucion', 'width' => '10', 'align' => 'left' ],
                [ 'title' => 'Presupuesto por Ejecutar', 'campo' => 'saldo', 'width' => '10', 'align' => 'left' ],
                [ 'title' => 'Presupuesto por Ejecutar', 'campo' => 'totalPresupuesto', 'width' => '10', 'align' => 'left' ],

                [ 'title' => 'Avance Económico', 'campo' => 'avance', 'width' => '10', 'align' => 'left' ],
                [ 'title' => 'Equipo de Investigación', 'campo' => 'equipoInv', 'width' => '50', 'align' => 'right' ],

            ];

            $years = [];
            foreach ($data[0] as $key => $value) {
                if(substr($key, 0, 2) > 0) {
                    $head[] = [ 'title' => $key, 'campo' => $key, 'width' => '10', 'align' => 'left' ];
                    $years[] = $key;
                }
            }

            $leyenda = [];



            $generado = new ReporteExcel();
            return $generado->generateExcel('Reporte Destalle Trimestral',$leyenda,$head,$data);

        }
}
