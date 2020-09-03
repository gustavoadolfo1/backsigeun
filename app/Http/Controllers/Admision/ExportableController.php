<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ClasesLibres\Reportes\ReporteExcel;

class ExportableController extends Controller
{
    public static function reporteInscritos($data, $tipo, $leyendas)
    {
        $head = [
            [ 'title' => 'Fecha Inscripción', 'campo' => 'dPreinscripcion', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Apellido Paterno', 'campo' => 'cPaterno', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Apellido Materno', 'campo' => 'cMaterno', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Nombre', 'campo' => 'cNombre', 'width' => '30', 'align' => 'left' ],
            [ 'title' => 'Sexo', 'campo' => 'cSexo', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Dirección', 'campo' => 'cDireccion', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Teléfono', 'campo' => 'cTelefono', 'width' => '35', 'align' => 'left' ],
            [ 'title' => 'Email', 'campo' => 'cEmail', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Carrera', 'campo' => 'carrera', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Sede examen', 'campo' => 'filial', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Colegio', 'campo' => 'colegio', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Departamento', 'campo' => 'departamento', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Provincia', 'campo' => 'provincia', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Distrito', 'campo' => 'distrito', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Modo de preparación', 'campo' => 'cModaPreparacionDsc', 'width' => '35', 'align' => 'left' ],

        ];
        $leyenda = [
            ['title' => 'Sede Examen', 'value' => $leyendas['sedeExamen']],
            ['title' => 'Lugar de Inscripción', 'value' => $leyendas['lugarInscripcion']],
            ['title' => 'Modalidad:', 'value' => $leyendas['modalidad']],
            ['title' => 'Filtro', 'value' => $leyendas['filtro']],
            ['title' => 'Carrera - Filial:', 'value' => $leyendas['carrera']],
            ['title' => 'Sexo:', 'value' => $leyendas['sexo']]
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('horizontal', 'Relación de inscritos proceso de admisión','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function reporteRecaudacionModalidad($data, $proceso, $tipo)
    {
        $head = [
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Nº Postulantes', 'campo' => 'iNumPostulantes', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Total', 'campo' => 'total', 'width' => '15', 'align' => 'left' ],
        ];
        $leyenda = [ ['title' => 'Proceso de admisión', 'value' => $proceso] ];

        $total = 0;
        $totalP = 0;
        foreach ($data as $modalidad) {
            $total += $modalidad->total;
            $totalP += $modalidad->iNumPostulantes;
        }

        $data[] = [ 'cModalDsc' => 'Total', 'iNumPostulantes' => $totalP, 'total' => $total];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Recaudación por modalidad', $leyenda, $head, $data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Recaudación por modalidad','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function reporteRecaudacionModalidadDet($data, $request, $tipo)
    {
        $head = [
            [ 'title' => 'Postulante', 'campo' => 'nombres', 'width' => '60', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Fecha pago', 'campo' => 'fecha_recibo', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Monto', 'campo' => 'ingreso_real', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Datos Verificados', 'campo' => 'bEstadoFinal', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Ingresó', 'campo' => 'bIngresoAdmision', 'width' => '15', 'align' => 'left' ],
        ];
        $leyenda = [ ['title' => 'Modalidad', 'value' => $request['modalidadDsc']] ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Recaudación por modalidad', $leyenda, $head, $data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('horizontal', 'Recaudación por modalidad','Reporte Consulta', $leyenda, $head, $data);
        }
    }

    public static function reporteRecaudacionEscuela($data, $proceso, $tipo)
    {
        $head = [
            [ 'title' => 'Tipo Modalidad', 'campo' => 'cTipoModalidad', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Escuela Profesional', 'campo' => 'cCarreraDsc', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Gestión colegio', 'campo' => 'cGestion', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'PU Examen', 'campo' => 'PreUni_Admision', 'width' => '15', 'align' => 'right' ],
            [ 'title' => 'PU Prospecto', 'campo' => 'PreUni_prospecto', 'width' => '15', 'align' => 'right' ],
            [ 'title' => 'Nº Postulantes', 'campo' => 'iNumPostulantes', 'width' => '15', 'align' => 'right' ],
            [ 'title' => 'Total Examen', 'campo' => 'total_tipo_ingresos', 'width' => '15', 'align' => 'right' ],
            [ 'title' => 'Total Prospecto', 'campo' => 'total_prospecto', 'width' => '15', 'align' => 'right' ],
            [ 'title' => 'Total', 'campo' => 'total', 'width' => '15', 'align' => 'right' ],
        ];
        $leyenda = [ ['title' => 'Proceso de admisión', 'value' => $proceso] ];

        // $total = 0;
        // $totalP = 0;
        // foreach ($data as $modalidad) {
        //     $total += $modalidad->total;
        //     $totalP += $modalidad->iNumPostulantes;
        // }

        // $data[] = [ 'cModalDsc' => 'Total', 'iNumPostulantes' => $totalP, 'total' => $total];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Recaudación por escuela', $leyenda, $head, $data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('horizontal', 'Recaudación por escuela','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function reporteAsistenciaExamen($data, $proceso, $tipo)
    {
        $head = [
            [ 'title' => 'Postulante', 'campo' => 'nombres', 'width' => '60', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Cod. Postulante', 'campo' => 'cCodPostulante', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Hora ingreso', 'campo' => 'dtConsultaExamen', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '60', 'align' => 'left' ],
            [ 'title' => 'Sede Examen', 'campo' => 'cFilialExamen', 'width' => '25', 'align' => 'left' ],
        ];

        $modalidad = ''; $filial = '';
        if (count($data) > 0) {
            $modalidad = $data[0]->cTipoModalidad;
            $filial = $data[0]->cFilialExamen;
        }

        $leyenda = [ 
            ['title' => 'Proceso de admisión', 'value' => $proceso], 
            ['title' => 'Modalidad', 'value' => $modalidad], 
            ['title' => 'Filial', 'value' => $filial], 
        ];

        // $total = 0;
        // $totalP = 0;
        // foreach ($data as $modalidad) {
        //     $total += $modalidad->total;
        //     $totalP += $modalidad->iNumPostulantes;
        // }

        // $data[] = [ 'cModalDsc' => 'Total', 'iNumPostulantes' => $totalP, 'total' => $total];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Asistencia', $leyenda, $head, $data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('horizontal', 'Reporte Asistencia','Reporte Consulta', $leyenda, $head, $data);
        }
    }
}
