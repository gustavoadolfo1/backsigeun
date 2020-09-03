<?php

namespace App\Http\Controllers\DASA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\UraCurricula;

use App\ClasesLibres\Reportes\ReporteExcel;
use App\ClasesLibres\Reportes\DasaExcelReporte;
use App\ClasesLibres\Reportes\SpreadSheetReporteMaker;

class ExportReporteController extends Controller
{
    public function reporteCargasAcademicas($carreraId, $filid, $ciclo, $plan, $tipo)
    {
        $cargas = \DB::select('exec ura.Sp_HORA_SEL_cargaAcademica ?, ?, ?', array($carreraId, $filid, $ciclo));

        $planes = UraCurricula::all();

        $data = [];

        foreach ($planes as $planCurric) {
            $cargasPlan = [];
            foreach ($cargas as $carga) {
                if ($planCurric->iCurricId == $carga->iCurricId) {
                    $cargasPlan[] = $carga;
                }
            }
            $planCurric->cargas = $cargasPlan;
        }

        foreach ($planes as $dataPlan) {
            if ($dataPlan->iCurricId == $plan) {
                $data = $dataPlan->cargas;
                break;
            }
        }

        if ($tipo == 'excel') {

            $resultado = $data;
            
            $head = [
                [ 'title' => 'Ciclo', 'campo' => 'cCurricDetCicloCurso', 'width' => '15', 'align' => 'center' ],
                [ 'title' => 'Cod. Curso', 'campo' => 'cCurricCursoCod', 'width' => '15', 'align' => 'center' ],
                [ 'title' => 'Curso', 'campo' => 'cCurricCursoDsc', 'width' => '65', 'align' => 'left' ],
                [ 'title' => 'Tipo Apert.', 'campo' => 'cTipoApertura', 'width' => '20', 'align' => 'center' ],
                [ 'title' => 'Sección', 'campo' => 'cSeccionDsc', 'width' => '15', 'align' => 'center' ],
                [ 'title' => 'Docente', 'campo' => 'cDocente', 'width' => '65', 'align' => 'left' ],
                [ 'title' => 'Régimen', 'campo' => 'cCondicionDsc', 'width' => '45', 'align' => 'left' ],
                [ 'title' => 'Documento', 'campo' => 'cCargaHDocApru', 'width' => '45', 'align' => 'left' ],
            ];
            $leyenda = [
                ['title' => 'Escuela Profesional' , 'value' => $resultado[0]->cCarreraDsc],
                ['title' => 'Ciclo académico' , 'value' => $ciclo],
                ['title' => 'Plan curricular' , 'value' => $resultado[0]->cCurricAnio]
            ];

            $generado = new ReporteExcel;
            $generado->generateExcel('Reporte Consulta',$leyenda,$head,$resultado);
        }
    }

    public function matriculadosPorSemestreCarreraFilialCiclo($cicloAcad, $carreraId, $filialId, $ciclo, $tipo)
    {
        if ($ciclo == 'all') {
            $data = \DB::select('exec ura.Sp_SEL_Estudiantes_Carrera_Profesionales_Cantidad_MatriculadosXiSemestre2 ?,?,?', array( $cicloAcad, $carreraId, $filialId  ));
        }
        else 
            $data = \DB::select('exec ura.Sp_SEL_Estudiantes_Carrera_Profesionales_Cantidad_MatriculadosXiSemestre ?,?,?,?', array( $cicloAcad, $carreraId, $filialId, $ciclo  ));
        

        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'cNombre_Estudiante', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '45', 'align' => 'left' ],
            [ 'title' => 'Tipo matrícula', 'campo' => 'cTiposMatDsc', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Semestre Ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Ciclo', 'campo' => 'cMatricCiclo', 'width' => '15', 'align' => 'left' ],
        ];
        $leyenda = [
            ['title' => 'Escuela Profesional' , 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Ciclo académico' , 'value' => $cicloAcad],
            ['title' => 'Ciclo' , 'value' => $ciclo]
        ];

        if ($tipo == 'excel') {
            $generado = new ReporteExcel;
            $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public function matriculadosPorCurso(Request $request, $tipo)
    {
        $data = \DB::select('exec ura.Sp_SEL_EstudiantesXiFilIdXiSemestreXiCarreraIdXiCurricIdXcCurricCursoCodXiSeccionId ?,?,?,?,?,?', array( $request->iFilId, $request->iSemestre, $request->iCarreraId, $request->iCurricId, $request->cCurricCursoCod, $request->iSeccionId  ));
        
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'cNombre_Estudiante', 'width' => '65', 'align' => 'left' ],
        ];
        $leyenda = [
            ['title' => 'Escuela Profesional', 'value' => $request->cCarreraDsc],
            ['title' => 'Ciclo académico', 'value' => $request->iSemestre],
            ['title' => 'Plan', 'value' => $request->cCurricAnio],
            ['title' => 'Curso', 'value' => $request->cCurricCursoDsc . ' - ' . $request->cCargaHCurso],
            ['title' => 'Sede', 'value' => $request->cFilSigla],
            ['title' => 'Sección', 'value' => $request->cSeccionDsc],
            ['title' => 'Créditos', 'value' => $request->nCurricDetCredCurso]
        ];

        if ($tipo == 'excel') {
            $generado = new ReporteExcel;
            $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function ingresantesPorModalidadYSemestreIngreso($data, $tipo)
    {
        $head = [
            [ 'title' => 'Escuela', 'campo' => 'cCarreraDsc', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Codigo', 'campo' => 'cEstudCodUniv', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'cNombre_Estudiante', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Sexo', 'campo' => 'cPersSexo', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '25', 'align' => 'left' ],
            [ 'title' => 'Ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Última Matr.', 'campo' => 'cEstudSemeUlti', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Situación', 'campo' => 'cClasificDsc', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Observación', 'campo' => 'cEstudObservacion', 'width' => '35', 'align' => 'left' ],
        ];
        $leyenda = [
            ['title' => 'Modalidad de ingreso', 'value' => $data[0]->cModalDsc],
            ['title' => 'Semestre de Ingreso', 'value' => $data[0]->cEstudSemeIngre],
        ];

        if ($tipo == 'excel') {
            $generado = new ReporteExcel();
            return $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
    }
    public static function reporteExcelPPA($grupo,$carrera, $ciclo)
    {
        // cPersDocumento	cCarreraDsc	cEstudCodUniv	cEstudSemeIngre	cEstudSemeUlti	nombres	curricula	ciclox	promedio	creditos	total
        $data = \DB::select('exec [ura].[Sp_GRAL_PROC_promedioPonderadoAcumulado] ?,?,?', array($grupo, $carrera, $ciclo));
        $head = [
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Carrera', 'campo' => 'cCarreraDsc', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Codigo Es.', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Ingreso Semes.', 'campo' => 'cEstudSemeIngre', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Ulti. Semes.', 'campo' => 'cEstudSemeUlti', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'nombres', 'width' => '60', 'align' => 'left' ],
            [ 'title' => 'Curricula', 'campo' => 'curricula', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Ciclo Est.', 'campo' => 'ciclo_actual', 'width' => '10', 'align' => 'left' ],
            [ 'title' => '# Cred Semes.', 'campo' => 'ciclox', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Puntaje', 'campo' => 'promedio', 'width' => '12', 'align' => 'left' ],
            [ 'title' => 'Total Cred.', 'campo' => 'creditos', 'width' => '12', 'align' => 'left' ],
            [ 'title' => 'PPA', 'campo' => 'total', 'width' => '12', 'align' => 'left' ],
        ];
        $leyenda = [];
        $generado = new ReporteExcel();
        return $generado->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        
    }
    
    public static function estudiantesASancionar($data, $tipo, $carreraId, $cicloAcad)
    {
        $head = [
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Código', 'campo' => 'cMatricCodUniv', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'nombre', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Plan Curricular', 'campo' => 'cCurricAnio', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Filial', 'campo' => 'cFilSigla', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Última Matr.', 'campo' => 'cEstudSemeUlti', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estado', 'campo' => 'cClasificDsc', 'width' => '20', 'align' => 'left' ],
        ];
        $leyenda = [
            ['title' => 'Escuela Profesional', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre Académico', 'value' => $cicloAcad],
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Estudiantes Con Tercera Matrícula Desaprobada','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function estudiantesCuartaDesaprobada($data, $tipo, $cicloAcad)
    {
        $head = [
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Código', 'campo' => 'cMatricCodUniv', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'nombre', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Plan Curricular', 'campo' => 'cCurricAnio', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Filial', 'campo' => 'cFilSigla', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Modalidad', 'campo' => 'cModalDsc', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Última Matr.', 'campo' => 'cEstudSemeUlti', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estado', 'campo' => 'cClasificDsc', 'width' => '20', 'align' => 'left' ],
        ];
        $leyenda = [
            ['title' => 'Escuela Profesional', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre Académico', 'value' => $cicloAcad],
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Estudiantes Con Cuarta Matrícula Desaprobada','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function getMatriculadosPorNumeroMatricula($data, $tipo, $params)
    {
        $mayor = 0;
        foreach ($data as $escuela) {
            $escuela->ciclos = json_decode($escuela->json, true);
            if (count($escuela->ciclos[0]) > $mayor) {
                $mayor = count($escuela->ciclos[0]);
            }
        }

        //return response()->json( $data );

        $ciclos = [ [ 'romano' => 'I', 'numero' => '01' ], [ 'romano' => 'II', 'numero' => '02' ], [ 'romano' => 'III', 'numero' => '03' ], [ 'romano' => 'IV', 'numero' => '04' ], [ 'romano' => 'V', 'numero' => '05' ], [ 'romano' => 'VI', 'numero' => '06' ], [ 'romano' => 'VII', 'numero' => '07' ], [ 'romano' => 'VIII', 'numero' => '08' ], [ 'romano' => 'IX', 'numero' => '09' ], [ 'romano' => 'X', 'numero' => '10' ], [ 'romano' => 'XI', 'numero' => '11' ], [ 'romano' => 'XII', 'numero' => '12' ] ];

        $ciclosData = [];

        for ($index = 0; $index < $mayor; $index++) {
            $ciclosData[] = $ciclos[$index];
        }

        $totalColumnTotal = 0;
        foreach ($data as $escuela) {
            $total = 0;
            foreach ($ciclosData as $ciclo) {
                $numero = $ciclo['numero'];
                $total += $escuela->$numero != null ? (int)$escuela->$numero : 0; 
            }
            $escuela->total = $total;
            $totalColumnTotal += $total;
        }

        $params = [ 'data' => $data, 'ciclos' => $ciclosData, 'total' => $totalColumnTotal, 'params' => $params ];

        if ($tipo == 'pdf') {
            $pdf = \PDF::loadView('dasa.reportesAcademicos.matriculadosNumMatricula', $params)->setPaper('A4','portrait');

            return $pdf->stream();
        }
        elseif ($tipo == 'excel') {
            $reporter = new DasaExcelReporte();
            return $reporter->matriculadosPorNumMatricula($params);
        }
    }

    public static function getSituacionRacionalizacion($data, $iControlCicloAcad, $tipo)
    {
        $params = [ 'detalles' => $data->detalle, 'resumen' => $data->resumen, 'semestre' => $iControlCicloAcad ];

        if ($tipo == 'pdf') {
            // $pdf = \PDF::loadView('dasa.reportesAcademicos.matriculadosNumMatricula', $params)->setPaper('A4','portrait');

            // return $pdf->stream();
        }
        elseif ($tipo == 'excel') {
            $reporter = new DasaExcelReporte();
            return $reporter->situacionRacionalizacion($params);
        }
    }

    public static function getRelacionEgresadosBachilleresTitulados($data, $tipo, $carreraId, $semestre)
    {
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'cEstudiante', 'width' => '65', 'align' => 'left' ],
            [ 'title' => 'Sexo', 'campo' => 'cPersSexo', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Email', 'campo' => 'cEstudCorreo', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Teléfono', 'campo' => 'cEstudTelef', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Sem. egreso', 'campo' => 'cEstudSemeEgresado', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Bachiller', 'campo' => 'cBachiller', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Fecha Dipl. Bach.', 'campo' => 'FechaBachiller', 'width' => '25', 'align' => 'left' ],
            [ 'title' => 'Título', 'campo' => 'cTitulo', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Fecha Dipl. Titu', 'campo' => 'FechaTitulo', 'width' => '25', 'align' => 'left' ],
        ];
        $leyenda = [
            ['title' => 'Escuela Profesional', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre Académico', 'value' => $semestre == 0 ? 'TODOS' : $semestre ],
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Relación de egresados, bachilleres y titulados','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function getBachilleresOTitulados($data, $tipoGrado, $tipo)
    {
        $head = [
            [ 'title' => 'Escuela Profesional', 'campo' => 'cCarrera', 'width' => '50', 'align' => 'left' ],
            [ 'title' => 'Grado', 'campo' => 'cTipo', 'width' => '20', 'align' => 'left' ],
        ];

        $leyenda = [
            ['title' => 'Grado', 'value' => $tipoGrado == 1 ? 'Bachilleres' : 'Titulados']
        ];
        
        $years = [];
        foreach ($data[0] as $key => $value) {
            if(substr($key, 0, 2) > 0) {
                $head[] = [ 'title' => $key, 'campo' => $key, 'width' => '10', 'align' => 'center' ];
                $years[] = $key;
            }
        }
        $head[] = [ 'title' => 'Total', 'campo' => 'total', 'width' => '10', 'align' => 'center' ];

        $lastRow = (object)(array)$data[0];
        $lastRow->cCarrera = 'TOTAL';

        $totalLastColumn = 0;

        foreach ($data as $i => $row) {
            $totalRow = 0;
            foreach ($years as $year) {
                $row->$year = (int)($row->$year ?? 0);
                $totalRow += $row->$year;
                if ($i != 0) {
                    $lastRow->$year += $row->$year;
                }
            }
            $row->total = $totalRow;
            $totalLastColumn += $totalRow;
        }
        $lastRow->total = $totalLastColumn;
        $data[] = $lastRow;

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Número de graduados','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function getRecojoInfoMINEDU($data, $tipo)
    {
        $header = [
            [ 'title' => 'Año', 'key' => 'cTipoItem', 'width' => '40', 'align' => 'left' ]
        ];

        $params = [];

        $years = [];
        foreach ($data[0]->data[0] as $key => $value) {
            if(substr($key, 0, 2) == 20) {
                $header[] = [ 'title' => $key, 'key' => $key, 'width' => '10', 'align' => 'center' ];
                $years[] = $key;
            }
        }

        $reporter = new SpreadSheetReporteMaker();
        $reporter->generateSheet('Reporte', $params, $header);

        foreach ($data as $escuela) {
            $reporter->renderData($header, "Escuela Profesional: " . $escuela->cCarreraDsc, $escuela->data);
        }
        return $reporter->export();
    }

    public static function getEgresadosDetallado($data, $tipo, $semestre)
    {
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Estudiante', 'campo' => 'cEstudiante', 'width' => '60', 'align' => 'left' ],
            [ 'title' => 'Sexo', 'campo' => 'cPersSexo', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Cred. Apro.', 'campo' => 'cEstudCredEgres', 'width' => '13', 'align' => 'left' ],
            [ 'title' => 'Prom. Pond', 'campo' => 'nEstudPPromEgres', 'width' => '13', 'align' => 'left' ],
            [ 'title' => 'Sem. ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '13', 'align' => 'left' ],
            [ 'title' => 'Sem. egreso', 'campo' => 'cEstudSemeEgresado', 'width' => '13', 'align' => 'left' ],
            [ 'title' => 'Modalidad de ingreso', 'campo' => 'cModalidad', 'width' => '60', 'align' => 'left' ]
        ];
        $leyenda = [
            ['title' => 'Escuela Profesional', 'value' => $data[0]->cCarreraDsc],
            ['title' => 'Semestre Académico', 'value' => $semestre == 0 ? 'TODOS' : $semestre ],
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Relación de egresados detallado','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function getBachilleresOTituladosDetallado($data, $tipoGrado, $year, $tipo)
    {
        $head = [
            [ 'title' => 'Año', 'campo' => 'ANIO_GRAD', 'width' => '10', 'align' => 'center' ],
            [ 'title' => 'Código', 'campo' => 'CODI_UNIV', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Nombres', 'campo' => 'cPersNombre', 'width' => '30', 'align' => 'left' ],
            [ 'title' => 'Apellidos', 'campo' => 'cPaterno', 'width' => '30', 'align' => 'left' ],
            [ 'title' => 'Resolución', 'campo' => 'GRAD_RESO', 'width' => '60', 'align' => 'left' ],
            [ 'title' => 'Fecha Resolución', 'campo' => 'RESO_FECH', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Diploma', 'campo' => 'DIPL_NUME', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Fecha Diploma', 'campo' => 'DIPL_FECH', 'width' => '20', 'align' => 'left' ]
        ];
        $leyenda = [
            ['title' => 'Grado', 'value' => $tipoGrado == 1 ? 'Bachilleres' : 'Titulados'],
            ['title' => 'Escuela Profesiona', 'value' => $data[0]->cCarreraDsc ],
            ['title' => 'Año', 'value' => $year == 0 ? 'TODOS LOS AÑOS' : $year]
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('vertical', 'Relación de egresados detallado','Reporte Consulta',$leyenda,$head,$data);
        }
    }

    public static function getReporteSIRIES($data, $tipo, $semestre)
    {
        $head = [
            [ 'title' => 'Código', 'campo' => 'cEstudCodUniv', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Apellido Paterno', 'campo' => 'cPersPaterno', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Apellido Materno', 'campo' => 'cPersMaterno', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Nombres', 'campo' => 'cPersNombre', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Sexo', 'campo' => 'cPersSexo', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Escuela Profesional', 'campo' => 'cCarreraDsc', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Filial', 'campo' => 'cFilDescripcion', 'width' => '20', 'align' => 'left' ],
            [ 'title' => 'Mod. admisión', 'campo' => 'cModalDsc', 'width' => '45', 'align' => 'left' ],
            [ 'title' => 'S. ingreso', 'campo' => 'cEstudSemeIngre', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'Prim. Matrícula', 'campo' => 'iPrimeraMatricula', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Ult. Matrícula', 'campo' => 'cEstudSemeUlti', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Créd. Aprob.', 'campo' => 'num_cred_aprob', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'PPA', 'campo' => 'nPromedioPPA', 'width' => '10', 'align' => 'left' ],
            [ 'title' => 'DNI', 'campo' => 'cPersDocumento', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Celular', 'campo' => 'cCelular', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Telefono', 'campo' => 'cEstudTelef', 'width' => '15', 'align' => 'left' ],
            [ 'title' => 'Correo personal', 'campo' => 'cCorreoPersonal', 'width' => '40', 'align' => 'left' ],
            [ 'title' => 'Correo institucional', 'campo' => 'cEstudCorreoInstitucional', 'width' => '40', 'align' => 'left' ],
        ];

        $leyenda = [
            ['title' => 'Semestre Académico', 'value' => $semestre == 0 ? 'TODOS' : $semestre ],
        ];

        $reporte = new ReporteExcel;
        if ($tipo == 'excel') {
            return $reporte->generateExcel('Reporte Consulta',$leyenda,$head,$data);
        }
        elseif ($tipo == 'pdf') {
            return $reporte->generatePDF('horizontal', 'Reporte SIRIES','Reporte Consulta',$leyenda,$head,$data);
        }
    }
}
