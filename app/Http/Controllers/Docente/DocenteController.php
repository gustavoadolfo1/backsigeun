<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Docente\Docente;
use App\Packages\Maatwebsite\Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstudiantesExport;
use PHPExcel;
use PHPExcel_Shared_Font;
use App\Mail\CorreoEjemplo;
use Illuminate\Support\Facades\Response;
use DB;

class DocenteController extends Controller
{
    /*
     * Obtiene los Cursos de un Docente
     */
    public function CursosDocente($ciclo, $id)
    {

        $cursos = \DB::select('EXEC ura.Sp_SEL_CursosxDocente_copy1 ?, ?', [$ciclo, $id]);

        return response()->json($cursos);
    }
    /*
     * ENVIA CORREO ELECTRONICO
     */
    public function enviarDatosCorreo()
    {
        $to_email = 'hilmerjose.floresmamani@gmail.com';

        \Mail::to($to_email)->send(new CorreoEjemplo);
        return "El correo electrónico ha sido enviado con éxito";
    }
    /*
        <?php

        namespace App\Http\Controllers;

        use Illuminate\Http\Request;
        use Redirect,Response,DB,Config;
        use Mail;
        class EmailController extends Controller
        {
            public function sendEmail()
            {
            $user = auth()->user();
            Mail::to($user)->send(new MailNotify($user));

            if (Mail::failures()) {
                return response()->Fail('Sorry! Please try again latter');
            }else{
                return response()->success('Great! Successfully send in your mail');
                }
            }
        }
    */

    /*
     * Obtiene los datos de la carga academica y datos del Docente
     */
    public function obtenerDatosCargaHorariaDocente($id, $ciclo)
    {

        // $cargadocente = \DB::select('exec ura.Sp_DOCE_SEL_cursosXiDocenteIdXiControlCicloAcad ?,?', array($id,$ciclo));
        $cargadocente = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Racionalizaciones_Prueba ?, ?', array($ciclo, $id));
        return response()->json($cargadocente);
    }

    public function obtenerDatosCargaHorariaDocente2($id, $ciclo)
    {
        $cargadocente = collect(\DB::select('exec ura.Sp_DOCE_SEL_cursosXiDocenteIdXiControlCicloAcad ?,?', array($id, $ciclo)));
        $cargadocenteUnidades = collect(\DB::select('EXEC [ura].[Sp_DOCE_SEL_Notas_DASA_MuestraNumero_Unidades_Cerradas_Docente__iDocenteiD] ?', [$cargadocente->first()->iDocenteId]));
        $cargadocenteExtras = collect(\DB::select('EXEC [ura].[Sp_SEL_CursosxDocente] ?, ?', [$ciclo, $id]));
        /*return response()->json([
            'cardadocente' => $cargadocente,
            'unidades' => $cargadocenteUnidades,
            'extras' => $cargadocenteExtras,
        ]);*/
        foreach ($cargadocente as $curso) {
            $d = $cargadocenteUnidades->where('Codigo_Curso', trim($curso->cCurricCursoCod));
            $d2 = $cargadocenteExtras->where('cCargaHCurso', trim($curso->cCurricCursoCod));

            /*return response()->json([
                'a' => trim($curso->cCurricCursoCod),
                'd' => [$d, $cargadocenteUnidades],
                'd2' => [$d2, $cargadocenteExtras]
            ]);*/

            $curso->UnidadesTotal = 0;
            $curso->UnidadesCerradas = 0;
            if ($d->count() > 0) {
                $dataExtra = $d->first();
                $curso->UnidadesTotal = $dataExtra->Total_Unidades;
                $curso->UnidadesCerradas = $dataExtra->Unidades_Cerradas;
            }

            $curso->Estado = 'N/A';
            if ($d2->count() > 0) {
                $dataExtra2 = $d2->first();
                $curso->Estado = $dataExtra2->Estado;
            }

            // return response()->json($dataExtra);

        }
        return response()->json($cargadocente);
    }


    /*
     * Obtiene informacion de contacto de un Docente
     */
    public function obtenerDatosContacto($codigo)
    {
        try {
            $data = \DB::select('exec [ura].[Sp_DOCE_SEL_DatosDocente] ?', array($codigo));
            $codeResponse = 200;
        } catch (\Exception $e) {

            $data = ['mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json($data, $codeResponse);
    }

    /*
     * edita informacion de contacto de un Docente
     */
    public function editarDatosContacto(Request $request)
    {

        # code...EXEC [ura].[Sp_ESTUD_UPD_datosContacto] @_cEstudCorreo varchar(200), @_cEstudTelef varchar(50), @cClave varchar(20), @cEstudCodUniv varchar(20), @iFilId int=0, @iCarreraId int
        $this->validate(
            $request,
            [
                //'password' => 'required| min:6| required_with:password_confirmation| regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/| confirmed',

                //'correo' => 'required',
                //'telefono' => 'required',
                'clave' => 'required|min:6',
                //'clave2' => 'required|min:6|same:clave|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@]).*$/',
                'clave2' => 'required|min:6|same:clave|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/',

                //'clave2' => 'required|min:6|same:clave',
                'cDoceDni' => 'required',
                //'filId' => 'required',
                //'carreraId' => 'required',
            ],
            [
                //'correo.required' => 'Hubo un problema al obtener el ID de Proforma.',
                //'telefono.required' => 'Hubo un problema al obtener el código del estudiante',
                'clave.required' => 'No pudo recordar su Clave.',
                'cDoceDni.required' => 'No se pudo identificar el DNI',
                //'filId.required' => 'Hubo un problema al obtener información de los cursos.',
                //'carreraId.required' => 'Hubo un problema al obtener información de los conceptos.',
                'clave.required' => 'Nueva contraseña es obligatorio',
                'clave.min' => 'Nueva contraseña debe ser de al menos :min caracteres',
                'clave2.required' => 'Repite contraseña es obligatorio',
                'clave2.min' => 'Repita contraseña debe ser de al menos :min caracteres',
                'clave2.same' => 'Nueva Contraseña y Repita Contraseña no coinciden.',
                //'clave2.regex' => 'La contraseña debe de contener al menos una Mayusculas (A - Z), Minusculas (a - z), Numeros (0 - 9) y No alfanumérico (por ejemplo:!, $, # O%)',
                'clave2.regex' => 'La contraseña debe de contener al menos una Mayusculas (A - Z), Minusculas (a - z), Numeros (0 - 9)',
                //'clave2.confirmed' => 'Confirmar contraseña.',

            ]
        );

        $parametros = [
            //$request->correo ?? NULL,
            //$request->telefono ?? NULL,
            $request->clave ?? NULL,
            $request->cDoceDni ?? NULL,
            //$request->filId ?? NULL,
            //$request->carreraId ?? NULL,
            //auth()->user()->cCredUsuario,
            'user',
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        //dd($parametros);

        try {
            $queryResult = \DB::select('exec [ura].[Sp_DOCE_UPD_DatosDocente]  ?, ?, ?, ?, ?, ?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Se Guardo su Contraseña correctamente.', 'queryResult' => $queryResult[0]];

            $codeResponse = 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }


    /**
     *
     *
     *
     *
     *
     */

    public function asistenciaCabecera($cDoceDni, $iControlCicloAcad, $iCarreraId, $cCurricCursoCod, $iCurricId, $iSeccionId)
    {

        $cursos = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Cabecera ?,?,?,?,?,?', array($cDoceDni, $iControlCicloAcad, $iCarreraId, $cCurricCursoCod, $iCurricId, $iSeccionId));

        return response()->json($cursos);
    }

    public function asistenciaList($cDoceDni, $iControlCicloAcad, $iCarreraId, $cCurricCursoCod, $cFechaAsis, $iSeccionId)
    {
        $cursos = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado ?,?,?,?,?,?', array($cDoceDni, $iControlCicloAcad, $iCarreraId, $cCurricCursoCod, $cFechaAsis, $iSeccionId));

        return response()->json($cursos);
    }

    public function listadoEstudiantes($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId)
    {
        $list = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls_copy1 ?,?,?,?,?,?,?', array($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId));
        /*  foreach ($list as $key => $value) {
            \print_r($value->Codigo_Estudiante);
        } */
        //dump($list);
        return response()->json($list);
    }

    public function listadoFechas($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId)
    {

        //$list = \DB::select('select * from ura.asistencias where iControlCicloAcad='.$iControlCicloAcad.'and iFilId='.$iFilId.'and iCarreraId='.$iCarreraId.'and iCurricId='.$iCurricId.'and cCurricCursoCod="'.$cCurricCursoCod.'" and iSeccionId='.$iSeccionId.'and iDocenteId='.$iDocenteId.'and iEstadoAsis=1');
        $list = \DB::SELECT('exec ura.Sp_DOCE_SEL_Asistencia_MuestraConteo_AsistentesXFaltantes ?,?,?,?,?,?,?', array($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId));



        /*  foreach ($list as $key => $value) {
            \print_r($value->Codigo_Estudiante);
        } */
        //dump($list);
        return response()->json($list);
    }
    public function exportlistEstudiantes($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId)
    {
        $list = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls ?,?,?,?,?,?,?', array($iControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId));

        //return (new EstudiantesExport(collect($list)))->download('estudiantes-list.xlsx');

    }

    public function exportlistEstudiantesXls($ControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId)
    {

        $cursos = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls_copy1 ?,?,?,?,?,?,?', array($ControlCicloAcad, $iFilId, $iCarreraId, $iCurricId, $cCurricCursoCod, $iSeccionId, $iDocenteId));

        Excel::create('Listado de Asistencia', function ($excel) use ($cursos) {

            $excel->sheet('Asistencia', function ($sheet) use ($cursos) {
                //$cursos = \DB::select('exec ura.Sp_DOCE_SEL_Asistencia_Listado_Xls ?,?,?,?,?,?', array(20192,1,2,'GP-413',1,324));
                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:K1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C3', 'DOCENTE');
                $sheet->setCellValue('C4', 'CARRERA');
                $sheet->setCellValue('C5', 'CURSO');
                $sheet->setCellValue('C6', 'CICLO');
                $sheet->setCellValue('C7', 'SECCION');

                $sheet->setCellValue('I4', 'SEMESTRE');
                $sheet->setCellValue('I5', 'CODIGO CURSO');
                $sheet->setCellValue('I6', 'PLAN');


                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);

                $sheet->getStyle('I4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('I6')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);



                $sheet->mergeCells("C3:D3");
                $sheet->mergeCells("C4:D4");
                $sheet->mergeCells("C5:D5");
                $sheet->mergeCells("C6:D6");
                $sheet->mergeCells("C7:D7");

                $sheet->mergeCells("C4:D4");
                $sheet->mergeCells("I5:J5");
                $sheet->mergeCells("I6:J6");

                $sheet->setCellValue('E3', $cursos[0]->Nombre_Docente);
                $sheet->setCellValue('E4', $cursos[0]->Carrera);
                $sheet->setCellValue('E5', $cursos[0]->Nombre_Curso);
                $sheet->setCellValue('E6', $cursos[0]->Ciclo_Letra);
                $sheet->setCellValue('E7', $cursos[0]->Seccion_Letra);

                $sheet->setCellValue('K4', $cursos[0]->Ciclo_Academico);
                $sheet->setCellValue('K5', $cursos[0]->Codigo_Curso);
                $sheet->setCellValue('K6', $cursos[0]->Plan_Curso);


                $sheet->cells('K4', function ($cells) {

                    $cells->setAlignment('left');
                });
                $sheet->cells('K6', function ($cells) {

                    $cells->setAlignment('left');
                });

                $sheet->mergeCells("E3:H3");
                $sheet->mergeCells("E4:H4");
                $sheet->mergeCells("E5:H5");
                $sheet->mergeCells("E6:H6");
                $sheet->mergeCells("E7:H7");


                $data = json_decode(json_encode($cursos), true);

                $sheet->setCellValue('C9', 'N°');
                $sheet->setCellValue('D9', 'CODIGO');
                $sheet->setCellValue('E9', 'APELLIDOS');
                $sheet->setCellValue('H9', 'NOMBRES');

                $sheet->getStyle('C9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('D9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('E9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                $sheet->getStyle('H9')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);


                $sheet->mergeCells("E9:G9");
                $sheet->mergeCells("H9:J9");

                foreach ($cursos as $key => $value) {
                    $x = ($key + 10);
                    $c = "C" . $x;
                    $d = "D" . $x;

                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;
                    $h = "H" . $x;
                    $j = "J" . $x;

                    $sheet->setCellValue($c, $key + 1);
                    $sheet->setCellValue($d, $value->Codigo_Estudiante);
                    $sheet->setCellValue($e, $value->Apellidos);
                    $sheet->mergeCells($e . ":" . $g);
                    $sheet->setCellValue($h, $value->Nombres);
                    $sheet->mergeCells($h . ":" . $j);

                    # code...
                }

                //$sheet->fromArray($data,'A5');
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }

    public function estudianteList(Request $request)
    {
        $query = Docente::where();
    }

    public function HorarioDocente($idDocente, $ciclo)
    {
        $respuesta = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad ?, ?', array($idDocente, $ciclo));
        return response()->json($respuesta);
    }

    public function exportHorarioDocenteXls($idDocente, $ciclo)
    {
        PHPExcel_Shared_Font::setTrueTypeFontPath('C:/Windows/Fonts/');
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
        $respuesta = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad ?, ?', array($idDocente, $ciclo));
        Excel::create('Horario', function ($excel) use ($respuesta, $ciclo) {

            $excel->sheet('Asistencia', function ($sheet) use ($respuesta, $ciclo) {

                $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                $sheet->mergeCells("B1:O1");
                $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                $sheet->setCellValue('C3', 'DOCENTE');


                $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);


                $sheet->setCellValue('B6', 'SEMESTRE ' . $ciclo);
                $sheet->getStyle('B6')->getFont()->setName('Tahoma')->setBold(true)->setSize(12);

                $sheet->setCellValue('B5', '     ');
                $sheet->setCellValue('E5', '                    ');
                $sheet->setCellValue('F5', '     ');
                $sheet->setCellValue('G5', '     ');


                $sheet->mergeCells("B6:N6");

                $sheet->cells('B6:N6', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });

                $sheet->cells('B1:O1', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });

                $sheet->setCellValue('D3', $respuesta[0]->cPersNombre . ' ' . $respuesta[0]->cPersPaterno . ' ' . $respuesta[0]->cPersMaterno);


                $sheet->setCellValue('B7', 'N°');
                $sheet->setCellValue('C7', 'CODIGO');
                $sheet->setCellValue('D7', 'NOMBRE DEL CURSO');
                $sheet->setCellValue('E7', 'SECCION');
                $sheet->setCellValue('F7', 'SEDE');

                $sheet->setCellValue('H7', 'DIA');
                $sheet->setCellValue('H8', 'LUNES');
                $sheet->setCellValue('I8', 'MARTES');
                $sheet->setCellValue('J8', 'MIERCOLES');
                $sheet->setCellValue('K8', 'JUEVES');
                $sheet->setCellValue('L8', 'VIERNES');
                $sheet->setCellValue('M8', 'SABADO');
                $sheet->setCellValue('N8', 'DOMINGO');


                $sheet->mergeCells("B7:B8");
                $sheet->mergeCells("C7:C8");
                $sheet->mergeCells("D7:D8");
                $sheet->mergeCells("E7:E8");

                $sheet->mergeCells("F7:G8");

                $sheet->mergeCells("H7:N7");

                $sheet->cells('B7:N8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontSize(11);
                });

                $sheet->cells('B9:N130', function ($cells) {

                    $cells->setFontSize(8);
                    $cells->setAlignment('center');
                });
                foreach ($respuesta as $key => $value) {


                    $x = ($key + 9);

                    $b = "B" . $x;
                    $c = "C" . $x;
                    $d = "D" . $x;
                    $e = "E" . $x;
                    $f = "F" . $x;

                    $g = "G" . $x;

                    $h = "H" . $x;
                    $i = "I" . $x;

                    $j = "J" . $x;
                    $k = "K" . $x;

                    $l = "L" . $x;
                    $m = "M" . $x;
                    $n = "N" . $x;

                    $sheet->setCellValue($b, $key + 1);

                    $sheet->setCellValue($c, $value->cCurricCursoCod);
                    $sheet->setCellValue($d, $value->cCurricCursoDsc);
                    $sheet->setCellValue($e, $value->cSeccionDsc);
                    $sheet->setCellValue($f, $value->cFilDescripcion);
                    $sheet->mergeCells($f . ":" . $g);


                    $sheet->setCellValue($h, $value->lunes);
                    $sheet->setCellValue($i, $value->martes);
                    $sheet->setCellValue($j, $value->miercoles);
                    $sheet->setCellValue($k, $value->jueves);
                    $sheet->setCellValue($l, $value->viernes);
                    $sheet->setCellValue($m, $value->sabado);
                    $sheet->setCellValue($n, $value->domingo);



                    # code...
                }


                //$sheet->fromArray($data,'A5');
                $sheet->setOrientation('landscape');
                $sheet->getStyle('A1:P130')->getAlignment()->setWrapText(true);
                foreach (range('B', 'P') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            });
        })->download('xlsx');
    }


    public function exportlistAsistenciaExcel(
        $ControlCicloAcad,
        $iFilId,
        $iCarreraId,
        $iCurricId,
        $cCurricCursoCod,
        $iSeccionId,
        $iDocenteId
    ) {

        $respuesta = \DB::select('EXEC [ura].[Sp_DOCE_SEL_Asistencia_MuestraResumen_AsistentesXFaltantes_Porcentajes] ?,?,?,?,?,?,?', array(
            $ControlCicloAcad,
            $iFilId,
            $iCarreraId,
            $iCurricId,
            $cCurricCursoCod,
            $iSeccionId,
            $iDocenteId
        ));



        Excel::create(
            'Asistencia de Estudiantes',
            function ($excel) use ($respuesta, $cCurricCursoCod) {

                $excel->sheet(
                    'Curso ' . $cCurricCursoCod,
                    function ($sheet) use ($respuesta) {

                        $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                        $sheet->mergeCells("B1:O1");
                        $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                        $sheet->setCellValue('C3', 'DNI');
                        $sheet->setCellValue('C4', 'DOCENTE');
                        $sheet->setCellValue('C5', 'CICLO ACADEMICO');

                        $sheet->setCellValue('G4', 'FECHA INICIO');
                        $sheet->setCellValue('G5', 'FECHA FIN');


                        $sheet->setCellValue('C7', 'HISTORIAL DE ASISTENCIA ');

                        $sheet->setCellValue('C10', 'N° ');
                        $sheet->setCellValue('D10', 'CODIGO ');
                        $sheet->setCellValue('F10', 'APELLIDOS ');
                        $sheet->setCellValue('H10', 'NOMBRES ');
                        $sheet->setCellValue('K10', 'ASISTENCIA ');

                        $sheet->setCellValue('K11', 'A ');
                        $sheet->setCellValue('L11', 'F ');
                        $sheet->setCellValue('M11', '% A');
                        $sheet->setCellValue('N11', '% F ');


                        $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('G4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('G5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);



                        $sheet->mergeCells("C7:N7");
                        $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(11);

                        $sheet->cells('B1:O1', function ($cells) {

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('C7:N7', function ($cells) {
                            $cells->setBackground('#1299c5');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });
                        /* $sheet->cells('C8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        /*  $sheet->cells('F8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        /* $sheet->cells('I8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->cells('L8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        /* $sheet->cells('I9', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->cells('L9', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        $sheet->cells('C10:N11', function ($cells) {

                            $cells->setAlignment('center');
                        });
                        $sheet->cells('C9:H9', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("D8:E8");

                        $sheet->mergeCells("C9:H9");

                        $sheet->mergeCells("C10:C11");
                        $sheet->mergeCells("D10:E11");
                        $sheet->mergeCells("F10:G11");
                        $sheet->mergeCells("H10:J11");

                        $sheet->mergeCells("G8:H8");
                        $sheet->cells('G8:H8', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("J8:K8");
                        $sheet->cells('J8:K8', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("J9:K9");
                        $sheet->cells('J9:K9', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("M8:N8");
                        $sheet->cells('M8:N8', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("M9:N9");
                        $sheet->cells('M9:N9', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("K10:N10");

                        $sheet->cells('C10:J11', function ($cells) {
                            $cells->setBackground('#1299c5');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });
                        $sheet->cells('K10:N10', function ($cells) {
                            $cells->setBackground('#1299c5');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });
                        //$sheet->setCellValue($c,$value->Dni);
                        $sheet->setCellValue('D3', $respuesta[0]->Dni);
                        $sheet->mergeCells("D3:E3");
                        $sheet->setCellValue('D4', $respuesta[0]->Docente);
                        $sheet->mergeCells("D4:E4");
                        $sheet->setCellValue('D5', $respuesta[0]->Ciclo_Academico);
                        $sheet->setCellValue('H4', $respuesta[0]->Fecha_Inicio);
                        $sheet->setCellValue('H5', $respuesta[0]->Fecha_Fin);


                        foreach ($respuesta as $key => $value) {


                            $x = ($key + 12);


                            $c = "C" . $x;

                            $d = "D" . $x;
                            $e = "E" . $x;

                            $f = "F" . $x;
                            $g = "G" . $x;

                            $h = "H" . $x;
                            $i = "I" . $x;
                            $j = "J" . $x;

                            $k = "K" . $x;

                            $l = "L" . $x;
                            $m = "M" . $x;
                            $n = "N" . $x;

                            $sheet->setCellValue($c, $key + 1);

                            $sheet->setCellValue($d, $value->Codigo_Estudiante);
                            $sheet->mergeCells($d . ":" . $e);

                            $sheet->setCellValue($f, $value->Apellidos);
                            $sheet->mergeCells($f . ":" . $g);


                            $sheet->setCellValue($h, $value->Nombres);
                            $sheet->mergeCells($h . ":" . $j);

                            $sheet->setCellValue($k, $value->Numero_Asistencias);
                            $sheet->setCellValue($l, $value->Numero_Faltas);
                            $sheet->setCellValue($m, $value->P_Asistencias);
                            $sheet->setCellValue($n, $value->P_Faltas);



                            # code...
                        }


                        $sheet->setShowGridlines(false);
                        //$sheet->fromArray($data,'A5');
                        $sheet->setOrientation('landscape');
                    }

                );
            }
        )->download('xlsx');
    }

    public function exportlistAsistenciaPdf(
        $iControlCicloAcad,
        $iFilId,
        $iCarreraId,
        $iCurricId,
        $cCurricCursoCod,
        $iSeccionId,
        $iDocenteId
    ) {

        $respuesta = \DB::select('EXEC [ura].[Sp_DOCE_SEL_Asistencia_MuestraResumen_AsistentesXFaltantes_Porcentajes] ?,?,?,?,?,?,?', array(
            $iControlCicloAcad,
            $iFilId,
            $iCarreraId,
            $iCurricId,
            $cCurricCursoCod,
            $iSeccionId,
            $iDocenteId
        ));



        Excel::create(
            'Asistencia de Estudiantes',
            function ($excel) use ($respuesta, $cCurricCursoCod) {

                $excel->sheet(
                    'Curso ' . $cCurricCursoCod,
                    function ($sheet) use ($respuesta) {

                        $sheet->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA');
                        $sheet->mergeCells("B1:O1");
                        $sheet->getStyle('B1')->getFont()->setName('Tahoma')->setBold(true)->setSize(22);
                        $sheet->setCellValue('C3', 'DNI');
                        $sheet->setCellValue('C4', 'DOCENTE');
                        $sheet->setCellValue('C5', 'CICLO ACADEMICO');

                        $sheet->setCellValue('G4', 'FECHA INICIO');
                        $sheet->setCellValue('G5', 'FECHA FIN');


                        $sheet->setCellValue('C7', 'HISTORIAL DE ASISTENCIA ');

                        $sheet->setCellValue('C10', 'N° ');
                        $sheet->setCellValue('D10', 'CODIGO ');
                        $sheet->setCellValue('F10', 'APELLIDOS ');
                        $sheet->setCellValue('H10', 'NOMBRES ');
                        $sheet->setCellValue('K10', 'ASISTENCIA ');

                        $sheet->setCellValue('K11', 'A ');
                        $sheet->setCellValue('L11', 'F ');
                        $sheet->setCellValue('M11', '% A');
                        $sheet->setCellValue('N11', '% F ');


                        $sheet->getStyle('C3')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('C4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('C5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('G4')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);
                        $sheet->getStyle('G5')->getFont()->setName('Tahoma')->setBold(true)->setSize(10);



                        $sheet->mergeCells("C7:N7");
                        $sheet->getStyle('C7')->getFont()->setName('Tahoma')->setBold(true)->setSize(11);

                        $sheet->cells('B1:O1', function ($cells) {

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('C7:N7', function ($cells) {
                            $cells->setBackground('#1299c5');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });
                        /* $sheet->cells('C8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        /*  $sheet->cells('F8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        /* $sheet->cells('I8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->cells('L8', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        /* $sheet->cells('I9', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                });
                $sheet->cells('L9', function ($cells) {
                    $cells->setBackground('#1299c5');
                    $cells->setAlignment('center');
                    $cells->setFontColor('#ffffff');
                }); */
                        $sheet->cells('C10:N11', function ($cells) {

                            $cells->setAlignment('center');
                        });
                        $sheet->cells('C9:H9', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("D8:E8");

                        $sheet->mergeCells("C9:H9");

                        $sheet->mergeCells("C10:C11");
                        $sheet->mergeCells("D10:E11");
                        $sheet->mergeCells("F10:G11");
                        $sheet->mergeCells("H10:J11");

                        $sheet->mergeCells("G8:H8");
                        $sheet->cells('G8:H8', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("J8:K8");
                        $sheet->cells('J8:K8', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("J9:K9");
                        $sheet->cells('J9:K9', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("M8:N8");
                        $sheet->cells('M8:N8', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("M9:N9");
                        $sheet->cells('M9:N9', function ($cells) {
                            $cells->setBackground('#ffffff');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });

                        $sheet->mergeCells("K10:N10");

                        $sheet->cells('C10:J11', function ($cells) {
                            $cells->setBackground('#1299c5');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });
                        $sheet->cells('K10:N10', function ($cells) {
                            $cells->setBackground('#1299c5');
                            $cells->setAlignment('center');
                            $cells->setFontColor('#ffffff');
                        });
                        //$sheet->setCellValue($c,$value->Dni);
                        $sheet->setCellValue('D3', $respuesta[0]->Dni);
                        $sheet->mergeCells("D3:E3");
                        $sheet->setCellValue('D4', $respuesta[0]->Docente);
                        $sheet->mergeCells("D4:E4");
                        $sheet->setCellValue('D5', $respuesta[0]->Ciclo_Academico);
                        $sheet->setCellValue('H4', $respuesta[0]->Fecha_Inicio);
                        $sheet->setCellValue('H5', $respuesta[0]->Fecha_Fin);


                        foreach ($respuesta as $key => $value) {


                            $x = ($key + 12);


                            $c = "C" . $x;

                            $d = "D" . $x;
                            $e = "E" . $x;

                            $f = "F" . $x;
                            $g = "G" . $x;

                            $h = "H" . $x;
                            $i = "I" . $x;
                            $j = "J" . $x;

                            $k = "K" . $x;

                            $l = "L" . $x;
                            $m = "M" . $x;
                            $n = "N" . $x;

                            $sheet->setCellValue($c, $key + 1);

                            $sheet->setCellValue($d, $value->Codigo_Estudiante);
                            $sheet->mergeCells($d . ":" . $e);

                            $sheet->setCellValue($f, $value->Apellidos);
                            $sheet->mergeCells($f . ":" . $g);


                            $sheet->setCellValue($h, $value->Nombres);
                            $sheet->mergeCells($h . ":" . $j);

                            $sheet->setCellValue($k, $value->Numero_Asistencias);
                            $sheet->setCellValue($l, $value->Numero_Faltas);
                            $sheet->setCellValue($m, $value->P_Asistencias);
                            $sheet->setCellValue($n, $value->P_Faltas);



                            # code...
                        }


                        $sheet->setShowGridlines(false);
                        //$sheet->fromArray($data,'A5');
                        $sheet->setOrientation('landscape');
                    }

                );
            }
        )->download('pdf');
    }


    public function NotasEstudiante(Request $request)
    {
        return $request[0]; // [1];
    }



    public function InsertarAsistenciaCurso(Request $request)
    {
        $this->validate(
            $request,
            [
                'cDoceDni' => 'required',
                'iControlCicloAcad' => 'required|integer',
                'iCarreraId' => 'required|integer',
                'iCurricId' => 'required|integer',
                'cCurricCursoCod' => 'required',
                'cFechaAsis' => 'required',
                'iSeccionId' => 'required|integer',
            ],
            [
                'cDoceDni.required' => 'DNI del Docente requerido',
                'iControlCicloAcad.required' => 'Código del Ciclo Académico requerido',
                'iCarreraId.required' => 'Código de Carrera Profesional requerido',
                'iCurricId.required' => 'Currícula del Curso requerida',
                'cCurricCursoCod.required' => 'Código del Curso requerido.',
                'cFechaAsis.required' => 'Fecha de Asistencia requerida.',
                'iSeccionId.required' => 'Sección del Curso requerida.',
            ]
        );

        $parametros = [
            $request->cDoceDni,
            $request->iControlCicloAcad,
            $request->iCarreraId,
            $request->iCurricId,
            $request->cCurricCursoCod,
            $request->cFechaAsis,
            $request->iSeccionId,
        ];

        try {
            $data = \DB::select('exec ura.Sp_DOCE_INS_Asistencia_Genera_ListadoAsistencia ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se guardó la asistencia exitosamente.'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];

            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    /* public function insertarUnidadesCurso(Request $request){
        $this->validate(
            $request,
            [
                '$iDocenteId' => 'required|integer',
                '$iControlCicloAcad' => 'required|integer',
                '$iCurricId' => 'required|integer',
                '$iFilId' => 'required|integer',
                '$iCarreraId' => 'required|integer',
                '$cCurricCursoCod' => 'required',
                '$iSeccionId' => 'required|integer',
                '$iNumUnidades' => 'required|integer',
            ],
            [
                'iDocenteId.required' => 'ID del docente requerido.',
                'iControlCicloAcad.required' => 'Ciclo Académico requerido.',
                'iCurricId.required' => 'ID de Currícula requerido.',
                'iFilId.required' => 'ID de Filial requerido.',
                'iCarreraId.required' => 'ID de Carrera Profesional requerido.',
                'cCurricCursoCod.required' => 'Código del Curso requerido.',
                'iSeccionId.required' => 'ID de la Sección requerida.',
                'iNumUnidades.required' => 'Número de Unidades requerido, entre 2 y 4 unidades.',
            ]
        );

        $parametros = [
            $request->iDocenteId,
            $request->iControlCicloAcad,
            $request->iCurricId,
            $request->iFilId,
            $request->iCarreraId,
            $request->cCurricCursoCod,
            $request->iSeccionId,
            $request->iNumUnidades,
        ];

        try {
            $data = \DB::select('exec ura.Sp_DOCE_INS_Notas_Genera_ListadoUnidades ?, ?, ?, ?, ?, ?, ?, ?', $parametros);

            $response = ['validated' => true, 'mensaje' => 'Se registró el número de unidades.'];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode()];

            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    } */

    public function insertarUnidadesCurso(Request $request)
    {
        $this->validate(
            $request,
            [
                'iDocenteId'        => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId'         => 'required',
                'iFilId'            => 'required',
                'iCarreraId'        => 'required',
                'cCurricCursoCod'   => 'required',
                'iSeccionId'        => 'required',
                'iNumUnidades'      => 'required|numeric|min:1|max:4|regex:/^.*(?=.*[2-4])(?=.*[\d]).*$/',
            ],
            [
                'iDocenteId.required'        => 'ID del docente requerido',
                'iControlCicloAcad.required' => 'Ciclo académico requerido.',
                'iCurricId.required'         => 'ID de currícula requerido',
                'iFilId.required'            => 'ID de filial requerido.',
                'iCarreraId.required'        => 'ID de carrera requerido',
                'cCurricCursoCod.required'   => 'Código del curso requerido',
                'iSeccionId.required'        => 'Sección requerida',
                'iNumUnidades.required'      => 'Número de Unidad requerido',
                'iNumUnidades.numeric'       => 'Unidad debe ser numerico',
                'iNumUnidades.min'           => 'Debe tener como minimo 2 unidades',
                'iNumUnidades.max'           => 'Debe tener como máximo 4 unidades',
                'iNumUnidades.regex'         => 'Debe tener como minimo 2 o 4 unidades',

            ]
        );
        $ip = $request->getClientIp();
        $parametros = [
            $request->iDocenteId        ?? NULL,
            $request->iControlCicloAcad ?? NULL,
            $request->iCurricId         ?? NULL,
            $request->iFilId            ?? NULL,
            $request->iCarreraId        ?? NULL,
            $request->cCurricCursoCod   ?? NULL,
            $request->iSeccionId        ?? NULL,
            $request->iNumUnidades      ?? NULL,
            //$notas->pro ?? NULL,
            //$notas->con ?? NULL,
            //$notas->act ?? NULL,
            //$notas->ct  ?? NULL,

            auth()->user()->cCredUsuario, //'user',
            //date
            'equipo',
            $ip, //$request->server->get('REMOTE_ADDR'),
            'N',
            'mac'
        ];
        try {
            $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Notas_Genera_ListadoUnidades] ?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            $response = ['validated' => true, 'mensaje' => 'Las unidades se guardaron correctamente.', 'result' => $data];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        return response()->json($response, $codeResponse);
    }

    public function obtenerUnidadesCurso($iDocenteId, $iControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId)
    {

        try {
            $unidad = \DB::select('EXEC [ura].[Sp_DOCE_SEL_Notas_MuestraUnidadesXCurso] ?,?,?,?,?,?,?', array($iDocenteId, $iControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId));

            $response = ['validated' => true, 'mensaje' => 'Las unidades se guardaron correctamente.', 'result' => $unidad];
            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($unidad);

        //return response()->json(['unidad' => $unidad, 'res' => $response]);
    }

    public function cambiarFechaParcial($iNotasId, $fecha)
    {

        $respuesta = \DB::update('update ura.notas set dFechaExamen = ? WHERE iNotasId = ?;', array($fecha, $iNotasId));
        return response()->json($respuesta);
    }

    public function listTotalestudiante($ciclo, $id, $curso)
    {
        $respuesta = \DB::table('ura.asistencias')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->where('cCurricCursoCod', $curso)
            ->where('iEstadoAsis', '1')
            ->count();

        $TF = \DB::table('ura.asistencias')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->where('cCurricCursoCod', $curso)
            ->count();

        return Response::json(['respuesta' => $respuesta, 'TF' => $TF]);
    }
}
