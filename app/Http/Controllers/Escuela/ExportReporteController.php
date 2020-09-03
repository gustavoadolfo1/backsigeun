<?php

namespace App\Http\Controllers\Escuela;

use Illuminate\Http\Request;

use App\ClasesLibres\Reportes\ReporteExcel;
use App\Http\Controllers\Controller;
use DB;
use App\Exports\UraGeneralExport;

class ExportReporteController extends Controller
{
    public static function obtenerMatriculadosGeneral($data, $tipo, $request)
    {
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Apellidos, nombres', 'campo' => 'cNombresConcatenado', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Ciclo', 'campo' => 'cMatricCiclo', 'width' => '8', 'align' => 'left' ],
            [ 'title' => 'Nº Matr.', 'campo' => 'iNumMatricula', 'width' => '8', 'align' => 'left' ],
            [ 'title' => 'Semestre de ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Condición', 'campo' => 'cTiposMatDsc', 'width' => '15', 'align' => 'left' ],
        ];

        $request['ciclo'] = $request['ciclo'] == '00' ? 'Todos' : $request['ciclo'];
        $request['nMat'] = $request['nMat'] == 0 ? 'Todos' : $request['nMat'];
        $request['tipoMat'] = $request['tipoMat'] == 0 ? 'Todas' : $request['tipoMat'];

        $leyenda = [
            ['title' => 'Escuela', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre', 'value' => $request['semestre']],
            ['title' => 'Ciclo', 'value' => $request['ciclo']],
            ['title' => 'Nº Matrícula', 'value' => $request['nMat']],
            ['title' => 'Condición', 'value' => $request['tipoMat']],
        ];
        
        $generado = new ReporteExcel;
        if ($tipo == 'excel') {
            return $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $generado->generatePDF('vertical', $data[0]->cCarreraDsc,'Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function obtenerMatriculadosPorCurso($data, $tipo, $request)
    {
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Apellidos, nombres', 'campo' => 'cNombresConcatenado', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Ciclo', 'campo' => 'cMatricCiclo', 'width' => '8', 'align' => 'left' ],
            [ 'title' => 'Curso', 'campo' => 'cCurricCursoDsc', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Sección', 'campo' => 'cSeccionDsc', 'width' => '8', 'align' => 'left' ],
            [ 'title' => 'Nº Matr.', 'campo' => 'iNumMatricula', 'width' => '8', 'align' => 'left' ],
            [ 'title' => 'Ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Condición', 'campo' => 'cTiposMatDsc', 'width' => '15', 'align' => 'left' ],
        ];

        $request['nMat'] = $request['nMat'] == 0 ? 'Todos' : $request['nMat'];

        $leyenda = [
            ['title' => 'Escuela', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre / Plan / Ciclo ', 'value' => $request['semestre'] . ' / ' . $request['plan']['cCurricAnio'] . ' / ' . $request['ciclo'] ],
            ['title' => 'Asignatura', 'value' => $request['curso']['cCurricCursoDsc']],
            ['title' => 'Sección', 'value' => $request['seccion']['cSeccionDsc']],
            ['title' => 'Nº Matr', 'value' => $request['nMat']],
        ];
        
        $generado = new ReporteExcel;
        if ($tipo == 'excel') {
            return $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $generado->generatePDF('horizontal', $data[0]->cCarreraDsc,'Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function obtenerIngresantesMatriculados($data, $tipo, $request)
    {
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Apellidos, nombres', 'campo' => 'cNombresConcatenado', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Condición', 'campo' => 'cTiposMatDsc', 'width' => '15', 'align' => 'left' ],
        ];

        $request['tipoMat'] = $request['tipoMat'] == 0 ? 'Todas' : $request['tipoMat'];

        $leyenda = [
            ['title' => 'Escuela', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre', 'value' => $request['semestre']],
            ['title' => 'Condición', 'value' => $request['tipoMat']],
        ];
        
        $generado = new ReporteExcel;
        if ($tipo == 'excel') {
            return $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $generado->generatePDF('horizontal', $data[0]->cCarreraDsc,'Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public function getFileReporteHorarios(Request $request, $tipo)
    {
        $parametros = [
            $request->iControlCicloAcad, 
            $request->iCarreraId,
            $request->iCurricId, 
            $request->iFilId
        ];

        $datos = \DB::select('exec [ura].[Sp_HORA_SEL_registroHorariosCompletos] ?, ?, ?, ? ', $parametros);
        
        $keys = [ 'cCursoCod', 'cCurricCursoDsc', 'cCurricDetCicloCurso', 'cSeccionDsc', 'num_horas_plan', 'nTotalHoras', 'obs_horario', 'obs_carga_acad'];

        $carrera = DB::table('ura.carreras')->where('iCarreraId', $request->iCarreraId)->first();
        $plan = DB::table('ura.curriculas')->where('iCurricId', $request->iCurricId)->first();
        $filial = DB::table('grl.filiales')->where('iFilId', $request->iFilId)->first();

        $parametros = [ 
            ["Ciclo académico:", $request->iControlCicloAcad], 
            ["Carrera:", $carrera->cCarreraDsc], 
            ["Plan curricular:", $plan->cCurricAnio], 
            ["Filial:", $filial->cFilDescripcion] 
        ];

        $header = [ 'Código', 'Curso', 'Ciclo', 'Sección', 'Total horas', 'Horas asignadas', 'Estado C. horaria', 'Estado C. académica' ];

        $export = new UraGeneralExport($header, $datos, $parametros, $keys, 'REPORTE DE HORARIOS');

        if ($tipo == 'excel') {
            return $export->download('reporte.xlsx');
        } else {
            return $export->download('reporte.pdf', \Maatwebsite\Excel\Excel::MPDF);
        }
    }
}
