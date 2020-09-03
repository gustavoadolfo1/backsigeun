<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Docente\Docente;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstudiantesExport;
use PHPExcel;
use PHPExcel_Shared_Font;
use App\Mail\CorreoEjemplo;
use Illuminate\Support\Facades\Response;

class RacionalizacionDocenteController extends Controller
{
    /*
     * Obtiene los Cursos de un Docente
     */
    public function DatosDocente($ciclo, $id)
    {

        $racionalizacion = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Racionalizaciones_Prueba ?, ?', array($ciclo, $id));


        foreach ($racionalizacion as $index => $raz) {
            if (($raz->cCargaHtipoCurso) == 'DIRIGIDO') {
                $t = $raz->iCurricDetHrsTcurso;
                $p = $raz->iCurricDetHrsPCurso;

                $raz->iCurricDetHrsTcurso = round(($raz->iCurricDetHrsTcurso) / 2);

                if ($p % 2 == 0) {
                    $raz->iCurricDetHrsPCurso = ($raz->iCurricDetHrsPCurso) / 2;
                } else {
                    $raz->iCurricDetHrsPCurso = (round(($raz->iCurricDetHrsPCurso) / 2)) - 1;
                    $raz->iCurricDetHrsTcurso = round($raz->iCurricDetHrsTcurso);
                    $raz->cHrsTotal = round($raz->cHrsTotal / 2);
                }
            }
        }


        return response()->json($racionalizacion);
    }

    public function HorarioDocente($ciclo, $id)
    {

        $pers = \DB::table('ura.docentes')
            ->where('iPersId', $id)
            ->get();

        $HNLv = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $pers[0]->iDocenteId)
            //->where('cRacionalDeclara', 1)
            ->get();

        if (isset($HNLv[0]->iRacionalId)) {
            $HNL = \DB::table('ura.horarios_carga_lectiva_no_lectiva')
                ->where('iRacionalId', $HNLv[0]->iRacionalId)
                ->count();
            $HNLFOR = \DB::table('ura.horarios_carga_lectiva_no_lectiva')
                ->join('ura.actividades_racional', 'ura.horarios_carga_lectiva_no_lectiva.iActividadesId', '=', 'ura.actividades_racional.iActividadesId')
                ->join('ura.aulas', 'ura.horarios_carga_lectiva_no_lectiva.iAulaCod', '=', 'ura.aulas.iAulaCod')
                ->select('ura.horarios_carga_lectiva_no_lectiva.*', 'ura.actividades_racional.cDesActividades', 'ura.aulas.cAulasDesc')
                ->where('ura.horarios_carga_lectiva_no_lectiva.iRacionalId', $HNLv[0]->iRacionalId)
                ->get();
        } else {
            $HNL = 0;
        }

        if ($HNL == 0) {


            $horario = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad_Prueba ?, ?', array($id, $ciclo));

            $ObtenerHora = \DB::table('ura.horarios_configuracion')
                ->where('iCarreraId', $horario[0]->iCarreraId)
                ->where('iFilId', $horario[0]->iFilId)
                ->get();
            $OH = explode(':', $ObtenerHora[0]->tHoraConfHini);
            $jj = ($OH[0] * 60) + $OH[1];

            $OF = explode(':', $ObtenerHora[0]->tHoraConfHfin);
            $ff = ($OF[0] * 60) + $OF[1];



            $x = 0;
            for ($i = $jj; $i <= $ff; $i = $i + 50) {
                $prueba = $i / 60;
                $drew = explode(".", $prueba);
                $k = $drew[0];
                $l = $i % 60;
                if ($k < 10) {
                    $ee = "0";
                } else {
                    $ee = "";
                }

                if ($l == 0) {
                    $dia = $ee . $k . ":00";
                    $dff = $ee . $k . ":50";
                } else {
                    $dia = $ee . $k . ":" . $l;

                    if (($k + 1) < 10) {
                        $eff = 0;
                    } else {
                        $eff = "";
                    }

                    if (((50 + $l) - 60) == 0) {
                        $dff = $eff . ($k + 1) . ":00";
                    } else {

                        $dff = $eff . ($k + 1) . ":" . ((50 + $l) - 60);
                    }
                }

                $array[$x]['hora'] = $dia;
                $array[$x]['hora_ff'] = $dff;
                $ss1 = 1;
                foreach ($horario as $key => $ho) {

                    if ($ho->lunes_inicio != "-") {

                        $dd = explode(':', $ho->lunes_inicio);
                        $df = explode(':', $ho->lunes_fin);



                        if ($dia < $df[0] . ":" . $df[1] && $dia >= $dd[0] . ":" . $dd[1]) {
                            $dr = (((($df[0] * 60) + $df[1]) - (($dd[0] * 60) + $dd[1])) / 50) + 1;
                            $ss1 = $dr;
                            $array[$x]['Pinto1'] = 1;
                            $array[$x]['curso1'] = $ho->cCurricCursoDsc . ' - ' . $ho->lunes_ubicacion;
                            $array[$x]['iHorariosId1'] = $ho->iHorariosId;
                        }
                    }


                    if ($ho->martes_inicio != "-") {
                        $dd = explode(':', $ho->martes_inicio);
                        $df = explode(':', $ho->martes_fin);

                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto2'] = 1;
                            $array[$x]['curso2'] = $ho->cCurricCursoDsc . ' - ' . $ho->martes_ubicacion;
                            $array[$x]['iHorariosId2'] = $ho->iHorariosId;
                        }
                    }
                    if ($ho->miercoles_inicio != "-") {
                        $dd = explode(':', $ho->miercoles_inicio);
                        $df = explode(':', $ho->miercoles_fin);

                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto3'] = 1;
                            $array[$x]['curso3'] = $ho->cCurricCursoDsc . ' - ' . $ho->miercoles_ubicacion;
                            $array[$x]['iHorariosId3'] = $ho->iHorariosId;
                        }
                    }
                    if ($ho->jueves_inicio != "-") {
                        $dd = explode(':', $ho->jueves_inicio);
                        $df = explode(':', $ho->jueves_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto4'] = 1;
                            $array[$x]['curso4'] = $ho->cCurricCursoDsc . ' - ' . $ho->jueves_ubicacion;
                            $array[$x]['iHorariosId4'] = $ho->iHorariosId;
                        }
                    }
                    if ($ho->viernes_inicio != "-") {
                        $dd = explode(':', $ho->viernes_inicio);
                        $df = explode(':', $ho->viernes_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto5'] = 1;
                            $array[$x]['curso5'] = $ho->cCurricCursoDsc . ' - ' . $ho->viernes_ubicacion;
                            $array[$x]['iHorariosId5'] = $ho->iHorariosId;
                        }
                    }
                    if ($ho->sabado_inicio != "-") {
                        $dd = explode(':', $ho->sabado_inicio);
                        $df = explode(':', $ho->sabado_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto6'] = 1;
                            $array[$x]['curso6'] = $ho->cCurricCursoDsc . ' - ' . $ho->sabado_ubicacion;
                            $array[$x]['iHorariosId6'] = $ho->iHorariosId;
                        }
                    }
                    if ($ho->domingo_inicio != "-") {
                        $dd = explode(':', $ho->domingo_inicio);
                        $df = explode(':', $ho->domingo_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto7'] = 1;
                            $array[$x]['curso7'] = $ho->cCurricCursoDsc . ' - ' . $ho->domingo_ubicacion;
                            $array[$x]['iHorariosId7'] = $ho->iHorariosId;
                        }
                    }
                }
                $array[$x]['ss1'] = $ss1;
                $x++;
            }


            /*  for($i=0; $i<$x;$i++)
                        {
                            $y=0;
                            if(isset($array[$i]['curso1']))
                            {

                            for ($j=0; $j < $x; $j++) {

                                if( (isset($array[$j]['curso1']) )){
                               if($array[$i]['curso1'] == $array[$j]['curso1'])
                                {
                                    $y++;

                                    if($y==1 ){$array[$i]['curso1'] = $array[$j]['curso1']; }
                                    else { $array[$i]['curso1']=''; }
                                }

                                 }
                            }

                            }

                        }

        */
        } else {


            $horario = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad_Prueba ?, ?', array($id, $ciclo));
            $ObtenerHora = \DB::table('ura.horarios_configuracion')
                ->where('iCarreraId', $horario[0]->iCarreraId)
                ->where('iFilId', $horario[0]->iFilId)
                ->get();
            $OH = explode(':', $ObtenerHora[0]->tHoraConfHini);
            $jj = ($OH[0] * 60) + $OH[1];

            $OF = explode(':', $ObtenerHora[0]->tHoraConfHfin);
            $ff = ($OF[0] * 60) + $OF[1];


            $x = 0;
            for ($i = $jj; $i <= $ff; $i = $i + 50) {
                $prueba = $i / 60;
                $drew = explode(".", $prueba);
                $k = $drew[0];
                $l = $i % 60;
                if ($k < 10) {
                    $ee = "0";
                } else {
                    $ee = "";
                }

                if ($l == 0) {
                    $dia = $ee . $k . ":00";
                    $dff = $ee . $k . ":50";
                } else {
                    $dia = $ee . $k . ":" . $l;

                    if (($k + 1) < 10) {
                        $eff = 0;
                    } else {
                        $eff = "";
                    }

                    if (((50 + $l) - 60) == 0) {
                        $dff = $eff . ($k + 1) . ":00";
                    } else {

                        $dff = $eff . ($k + 1) . ":" . ((50 + $l) - 60);
                    }
                }

                $array[$x]['hora'] = $dia;
                $array[$x]['hora_ff'] = $dff;
                $ss1 = 1;
                foreach ($horario as $key => $ho) {

                    if ($ho->lunes_inicio != "-") {

                        $dd = explode(':', $ho->lunes_inicio);
                        $df = explode(':', $ho->lunes_fin);



                        if ($dia < $df[0] . ":" . $df[1] && $dia >= $dd[0] . ":" . $dd[1]) {
                            $dr = (((($df[0] * 60) + $df[1]) - (($dd[0] * 60) + $dd[1])) / 50) + 1;
                            $ss1 = $dr;
                            $array[$x]['Pinto1'] = 1;
                            $array[$x]['curso1'] = $ho->cCurricCursoDsc . ' - ' . $ho->lunes_ubicacion;
                        }
                    }


                    if ($ho->martes_inicio != "-") {
                        $dd = explode(':', $ho->martes_inicio);
                        $df = explode(':', $ho->martes_fin);

                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto2'] = 1;
                            $array[$x]['curso2'] = $ho->cCurricCursoDsc . ' - ' . $ho->martes_ubicacion;
                        }
                    }
                    if ($ho->miercoles_inicio != "-") {
                        $dd = explode(':', $ho->miercoles_inicio);
                        $df = explode(':', $ho->miercoles_fin);

                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto3'] = 1;
                            $array[$x]['curso3'] = $ho->cCurricCursoDsc . ' - ' . $ho->miercoles_ubicacion;
                        }
                    }
                    if ($ho->jueves_inicio != "-") {
                        $dd = explode(':', $ho->jueves_inicio);
                        $df = explode(':', $ho->jueves_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto4'] = 1;
                            $array[$x]['curso4'] = $ho->cCurricCursoDsc . ' - ' . $ho->jueves_ubicacion;
                        }
                    }
                    if ($ho->viernes_inicio != "-") {
                        $dd = explode(':', $ho->viernes_inicio);
                        $df = explode(':', $ho->viernes_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto5'] = 1;
                            $array[$x]['curso5'] = $ho->cCurricCursoDsc . ' - ' . $ho->viernes_ubicacion;
                        }
                    }
                    if ($ho->sabado_inicio != "-") {
                        $dd = explode(':', $ho->sabado_inicio);
                        $df = explode(':', $ho->sabado_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto6'] = 1;
                            $array[$x]['curso6'] = $ho->cCurricCursoDsc . ' - ' . $ho->sabado_ubicacion;
                        }
                    }
                    if ($ho->domingo_inicio != "-") {
                        $dd = explode(':', $ho->domingo_inicio);
                        $df = explode(':', $ho->domingo_fin);
                        if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                            $array[$x]['Pinto7'] = 1;
                            $array[$x]['curso7'] = $ho->cCurricCursoDsc . ' - ' . $ho->domingo_ubicacion;
                        }
                    }
                }
                $array[$x]['ss1'] = $ss1;
                $x++;
            }

            for ($fd = 0; $fd < $HNL; $fd++) {


                $hhh = explode(":", $HNLFOR[$fd]->tHoraCarNoLecInicio);


                for ($xd = 0; $xd < $x; $xd++) {

                    if (($array[$xd]['hora']) == ($hhh[0] . ":" . $hhh[1])) {
                        $dv = $HNLFOR[$fd]->iDiaSemId;
                        $array[$xd]['Pinto' . $dv] = 1;
                        $array[$xd]['curso' . $dv] =  $HNLFOR[$fd]->cDesActividades . ' - ' . $HNLFOR[$fd]->cAulasDesc;
                    }
                }
            }
        }




        return response()->json($array);
    }
    function ActividadesDocente()
    {

        $actividades = \DB::table('ura.actividades_racional')->get();
        return response()->json($actividades);
    }

    function descargaFormato1Pdf($ciclo, $id)
    {

        $pers = \DB::table('ura.docentes')
            ->where('iDocenteId', $id)
            ->get();


        $racionalizacion = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Racionalizaciones_Prueba ?, ?', array($ciclo, $pers[0]->iPersId));

        $Rac = \DB::table('ura.racionalizaciones')
            ->where('iDocenteId', $id)
            ->get();

        $CE = \DB::table('ura.detalle_carga_lectiva')
            ->where('iRacionalId', $Rac[0]->iRacionalId)
            ->get();

        $Carreras = \DB::table('ura.carreras')

            ->get();

        $CNE = \DB::table('ura.detalle_carga_no_lectiva')
            ->join('ura.actividades_racional', 'ura.detalle_carga_no_lectiva.iActividadesId', '=', 'ura.actividades_racional.iActividadesId')
            ->join('ura.aulas', 'ura.detalle_carga_no_lectiva.iAulaCod', '=', 'ura.aulas.iAulaCod')
            ->select('ura.detalle_carga_no_lectiva.*', 'ura.actividades_racional.cDesActividades', 'ura.aulas.cAulasDesc')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->get();





        $pdf = \PDF::loadView('docente.PdfFormato1', compact(['racionalizacion', 'CE', 'CNE', 'Rac', 'Carreras']));


        return $pdf->stream();
    }
    function descargaFormato1APdf($ciclo, $id)
    {

        $pers = \DB::table('ura.docentes')
            ->where('iDocenteId', $id)
            ->get();

        $HNLv = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId',  $id)
            //->where('cRacionalDeclara', 1)
            ->get();

        if (isset($HNLv[0]->iRacionalId)) {
            $HNL = \DB::table('ura.horarios_carga_lectiva_no_lectiva')
                ->where('iRacionalId', $HNLv[0]->iRacionalId)
                ->count();
            $HNLFOR = \DB::table('ura.horarios_carga_lectiva_no_lectiva')
                ->join('ura.actividades_racional', 'ura.horarios_carga_lectiva_no_lectiva.iActividadesId', '=', 'ura.actividades_racional.iActividadesId')
                ->join('ura.aulas', 'ura.horarios_carga_lectiva_no_lectiva.iAulaCod', '=', 'ura.aulas.iAulaCod')
                ->select('ura.horarios_carga_lectiva_no_lectiva.*', 'ura.actividades_racional.cDesActividades', 'ura.aulas.cAulasDesc')
                ->where('ura.horarios_carga_lectiva_no_lectiva.iRacionalId', $HNLv[0]->iRacionalId)
                ->get();
        }



        $horario = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad_Prueba ?, ?', array($pers[0]->iPersId, $ciclo));
        $ObtenerHora = \DB::table('ura.horarios_configuracion')
            ->where('iCarreraId', $horario[0]->iCarreraId)
            ->where('iFilId', $horario[0]->iFilId)
            ->get();
        $OH = explode(':', $ObtenerHora[0]->tHoraConfHini);
        $jj = ($OH[0] * 60) + $OH[1];

        $OF = explode(':', $ObtenerHora[0]->tHoraConfHfin);
        $ff = ($OF[0] * 60) + $OF[1];


        $x = 0;
        for ($i = $jj; $i <= $ff; $i = $i + 50) {
            $prueba = $i / 60;
            $drew = explode(".", $prueba);
            $k = $drew[0];
            $l = $i % 60;
            if ($k < 10) {
                $ee = "0";
            } else {
                $ee = "";
            }

            if ($l == 0) {
                $dia = $ee . $k . ":00";
                $dff = $ee . $k . ":50";
            } else {
                $dia = $ee . $k . ":" . $l;

                if (($k + 1) < 10) {
                    $eff = 0;
                } else {
                    $eff = "";
                }

                if (((50 + $l) - 60) == 0) {
                    $dff = $eff . ($k + 1) . ":00";
                } else {

                    $dff = $eff . ($k + 1) . ":" . ((50 + $l) - 60);
                }
            }

            $array[$x]['hora'] = $dia;
            $array[$x]['hora_ff'] = $dff;
            $ss1 = 1;
            foreach ($horario as $key => $ho) {

                if ($ho->lunes_inicio != "-") {

                    $dd = explode(':', $ho->lunes_inicio);
                    $df = explode(':', $ho->lunes_fin);



                    if ($dia < $df[0] . ":" . $df[1] && $dia >= $dd[0] . ":" . $dd[1]) {
                        $dr = (((($df[0] * 60) + $df[1]) - (($dd[0] * 60) + $dd[1])) / 50) + 1;
                        $ss1 = $dr;
                        $array[$x]['Pinto1'] = 1;
                        $array[$x]['curso1'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic1'] = $ho->lunes_ubicacion;
                    }
                }


                if ($ho->martes_inicio != "-") {
                    $dd = explode(':', $ho->martes_inicio);
                    $df = explode(':', $ho->martes_fin);

                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto2'] = 1;
                        $array[$x]['curso2'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic2'] = $ho->martes_ubicacion;
                    }
                }
                if ($ho->miercoles_inicio != "-") {
                    $dd = explode(':', $ho->miercoles_inicio);
                    $df = explode(':', $ho->miercoles_fin);

                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto3'] = 1;
                        $array[$x]['curso3'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic3'] = $ho->miercoles_ubicacion;
                    }
                }
                if ($ho->jueves_inicio != "-") {
                    $dd = explode(':', $ho->jueves_inicio);
                    $df = explode(':', $ho->jueves_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto4'] = 1;
                        $array[$x]['curso4'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic4'] = $ho->jueves_ubicacion;
                    }
                }
                if ($ho->viernes_inicio != "-") {
                    $dd = explode(':', $ho->viernes_inicio);
                    $df = explode(':', $ho->viernes_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto5'] = 1;
                        $array[$x]['curso5'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic5'] = $ho->viernes_ubicacion;
                    }
                }
                if ($ho->sabado_inicio != "-") {
                    $dd = explode(':', $ho->sabado_inicio);
                    $df = explode(':', $ho->sabado_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto6'] = 1;
                        $array[$x]['curso6'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic6'] = $ho->sabados_ubicacion;
                    }
                }
                if ($ho->domingo_inicio != "-") {
                    $dd = explode(':', $ho->domingo_inicio);
                    $df = explode(':', $ho->domingo_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto7'] = 1;
                        $array[$x]['curso7'] = $ho->cCurricCursoDsc;
                        $array[$x]['ubic7'] = $ho->domingo_ubicacion;
                    }
                }
            }
            $array[$x]['ss1'] = $ss1;
            $x++;
        }

        for ($fd = 0; $fd < $HNL; $fd++) {


            $hhh = explode(":", $HNLFOR[$fd]->tHoraCarNoLecInicio);


            for ($xd = 0; $xd < $x; $xd++) {

                if (($array[$xd]['hora']) == ($hhh[0] . ":" . $hhh[1])) {
                    $dv = $HNLFOR[$fd]->iDiaSemId;
                    $array[$xd]['Pinto' . $dv] = 1;
                    $array[$xd]['curso' . $dv] =  $HNLFOR[$fd]->cDesActividades;
                    $array[$xd]['ubic' . $dv] = $HNLFOR[$fd]->cAulasDesc;
                }
            }
        }
        $raV = \DB::select('exec ura.Sp_DOCE_SEL_Informacion_Racionalizaciones_Prueba ?, ?', array($ciclo, $pers[0]->iPersId));
        $pdf = \PDF::loadView('docente.PdfFormato1A', compact(['array', 'raV']))->setPaper('A4', 'landscape');
        return $pdf->stream();
    }
    function descargaFormato1BPdf()
    {
        $formato1B = "";
        $pdf = \PDF::loadView('docente.PdfFormato1B', compact(['formato1B']))->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    /*
    GUARDA LA CARGA LECTIVA
    */
    public function guardarCargaLectiva(Request $request)
    {
        foreach ($request->aCargaLectiva as $cargal) {
            $RacionalId = \DB::table('ura.racionalizaciones')
                ->where('iControlCicloAcad', $cargal['iControlCicloAcad'])
                ->where('iDocenteId',  $cargal['iDocenteId'])

                ->get();
        }

        $ConsultaCL = \DB::table('ura.detalle_carga_lectiva')
            ->where('iRacionalId',  $RacionalId[0]->iRacionalId)

            ->get();

        $a = 'AAAA';
        if (isset($ConsultaCL[0]->iCargaLecId)) {
            $actualizarCL = \DB::table('ura.detalle_carga_lectiva')
                ->where('iRacionalId',  $RacionalId[0]->iRacionalId)
                ->delete();
        }



        $this->validate(
            $request,
            [],
            []
        );
        try {

            foreach ($request->aCargaLectiva as $cargal) {


                $parametros = array(


                    $RacionalId[0]->iRacionalId,
                    $cargal['iDocenteId'],
                    $cargal['iControlCicloAcad'],
                    $cargal['iCarreraId'],
                    $cargal['iFilId'],
                    $cargal['cFilSigla'],
                    $cargal['iCurricId'],

                    $cargal['cCargaHCurso'],
                    $cargal['cCurricCursoDsc'],
                    $cargal['cSeccionDsc'],
                    $cargal['iTotalMatriculado'],
                    $cargal['nCurricDetCredCurso'],
                    $cargal['iCurricDetHrsTcurso'],
                    $cargal['iCurricDetHrsPCurso'],
                    $cargal['iCurricDetHrsTcurso'] +  $cargal['iCurricDetHrsPCurso'],

                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );
                $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Racional_Carga_Lectiva] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            }

            if ($data[0]->id > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información de la Carga Lectiva exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información de la Carga Lectiva .'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }

        //$response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
        return response()->json($response, $codeResponse);
    }

    /*
    GUARDA LA CARGA NO LECTIVA
    */

    public function guardarCargaNoLectiva(Request $request)
    {
        $iRacionalId = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $request->iControlCicloAcad)
            ->where('iDocenteId', $request->iDocenteId)
            ->where('iCarreraId', $request->iCarreraId)
            ->where('iFilId', $request->iFilId)
            ->get();

        switch ($request->opcion) {
            case 'EDITAR':
                $actualizarCNLectivo = \DB::table('ura.detalle_carga_no_lectiva')
                    ->where('iActividadesId', $request->iActividadesId)
                    ->where('iRacionalId', $iRacionalId[0]->iRacionalId)
                    ->get();

                $actualizar = \DB::table('ura.detalle_carga_no_lectiva')
                    ->where('iActividadesId',  $request->iActividadesId)
                    ->where('iRacionalId', $iRacionalId[0]->iRacionalId)
                    ->update(array('cCargaNoLecDoc' => $request->cCargaNoLecDoc, 'iCargaNoLecHrsTot' => $request->iCargaNoLecHrsTot));



                $actualizarDeclaracion = \DB::table('ura.racionalizaciones')
                    ->where('iRacionalId',  $iRacionalId[0]->iRacionalId)
                    ->update(array('cRacionalDeclara' => $request->cRacionalDeclara));
                $response = ['validated' => true, 'mensaje' => 'Se actualizó la información de la Carga No Lectiva  exitosamente.'];
                $codeResponse = 200;


                break;

            case 'ELIMINAR':
                $eliminar = \DB::table('ura.detalle_carga_no_lectiva')
                    ->where('iActividadesId',  $request->iActividadesId)
                    ->where('iRacionalId', $iRacionalId[0]->iRacionalId)
                    ->delete();

                $eliminarHorario = \DB::table('ura.horarios_carga_lectiva_no_lectiva')
                    ->where('iActividadesId',  $request->iActividadesId)
                    ->where('iRacionalId', $iRacionalId[0]->iRacionalId)
                    ->delete();

                $actualizarDeclaracion = \DB::table('ura.racionalizaciones')
                    ->where('iRacionalId',  $iRacionalId[0]->iRacionalId)
                    ->update(array('cRacionalDeclara' => $request->cRacionalDeclara));
                $response = ['validated' => true, 'mensaje' => 'Se eliminó la información de la Carga No Lectiva  exitosamente.'];
                $codeResponse = 200;
                break;

            case 'AGREGAR':
                $this->validate(
                    $request,
                    [
                        //'iRacionalId' => 'required',
                        //'iDocenteId' => 'required',
                        //'iControlCicloAcad' => 'required',
                        'iActividadesId' => 'required',
                        //'cActividadesOtro' => 'required',
                        //'iCarreraId' => 'required',
                        //'iFilId' => 'required',
                        //'iAulaCod' => 'required',
                        'cCargaNoLecDoc' => 'required',
                        //'iCargaNoLecHrsT' => 'required',
                        //'iCargaNoLecHrsP' => 'required',
                        'iCargaNoLecHrsTot' => 'required',
                    ],
                    [
                        //'iRacionalId.required' => 'ID de racionalización requerido',
                        //'iDocenteId.required' => 'ID de docente requerido',
                        //'iControlCicloAcad.required' => 'Ciclo Académico requerido',
                        'iActividadesId.required' => 'Descripción de actividades requerido',
                        //'cActividadesOtro.required' => 'Descripción de Otras actividades requerido',
                        //'iCarreraId.required' => 'ID de Carrera Profesional requerido',
                        //'iFilId.required' => 'ID de Filial requerido',
                        //'iAulaCod.required' => 'Código de aula requerido',
                        'cCargaNoLecDoc.required' => 'Documento o Resolución requeridos',
                        //'iCargaNoLecHrsT.required' => 'Horas teóricas nolectivas requerido',
                        //'iCargaNoLecHrsP.required' => 'Horas de práctica no lectivas requerido',
                        'iCargaNoLecHrsTot.required' => 'Total de carga no lectiva requerido',
                    ]
                );





                $parametros = [

                    $iRacionalId[0]->iRacionalId,
                    $request->iDocenteId,
                    $request->iControlCicloAcad,
                    //$carganl['idact'],
                    $request->iActividadesId,
                    $request->iCarreraId,
                    $request->iFilId,
                    $request->cFilSigla,
                    $request->iAulaCod,
                    //$carganl['documento'],
                    $request->cCargaNoLecDoc,
                    //$request->iCargaNoLecHrsT,
                    //$request->iCargaNoLecHrsP,
                    1,
                    1,
                    $request->iCargaNoLecHrsTot,
                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                ];

                try {
                    $actualizarDeclaracion = \DB::table('ura.racionalizaciones')
                        ->where('iRacionalId',  $iRacionalId[0]->iRacionalId)
                        ->update(array('cRacionalDeclara' => $request->cRacionalDeclara));

                    $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Racional_Carga_No_Lectiva] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

                    if ($data[0]->id > 0) {
                        $response = ['validated' => true, 'mensaje' => 'Se guardó la información de la Carga No Lectiva  exitosamente.'];
                        $codeResponse = 200;
                    } else {
                        $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información de la Carga No Lectiva.'];
                        $codeResponse = 500;
                    }
                } catch (\QueryException $e) {
                    $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
                    $codeResponse = 500;
                }
                break;
        }



        return response()->json($response, $codeResponse);
    }

    /*
    GUARDA LA CARGA NO LECTIVA
    */
    /*
    public function guardarCargaNoLectiva (Request $request) {

        $this->validate(
            $request, [
                //'iRacionalId' => 'required',
                'iDocenteId' => 'required',
                'iControlCicloAcad' => 'required',
                //'iActividadesId' => 'required',
                'iCarreraId' => 'required',
                'iFilId' => 'required',
                'iAulaCod' => 'required',
                //'cRacionalDeclara' => 'required',

                //'cCargaNoLecDoc' => 'required',
                //'iCargaNoLecHrsT' => 'required',
                //'iCargaNoLecHrsP' => 'required',
                //'iCargaNoLecHrsTot' => 'required',

            ],[
                //'iRacionalId.required' => 'ID de racionalización requerido',
                'iDocenteId.required' => 'ID de docente requerido',
                'iControlCicloAcad.required' => 'Ciclo Académico requerido',
                //'iActividadesId.required' => 'Descripción de actividades requerido',
                'iCarreraId.required' => 'ID de Carrera Profesional requerido',
                'iFilId.required' => 'ID de Filial requerido',
                'iAulaCod.required' => 'Código de aula requerido',
                'cRacionalDeclara' => 'Declaración del Docente'

                //'cCargaNoLecDoc.required' => 'Documento o Resolución requeridos',
                //'iCargaNoLecHrsT.required' => 'Horas teóricas nolectivas requerido',
                //'iCargaNoLecHrsP.required' => 'Horas de práctica no lectivas requerido',
                //'iCargaNoLecHrsTot.required' => 'Total de carga no lectiva requerido',

            ]
        );
        try {

            $iRacionalId = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $request->iControlCicloAcad)
            ->where('iDocenteId', $request->iDocenteId)
            ->where('iCarreraId', $request->iCarreraId)
            ->where('iFilId', $request->iFilId)
            ->get();







            foreach ($request->aCargaNoLectiva as $carganl) {

                $actualizarDeclaracion= \DB::table('ura.racionalizaciones')
                    ->where('iRacionalId',  $iRacionalId[0]->iRacionalId)
                    ->update(array('cRacionalDeclara'=>$request->cRacionalDeclara));

                $parametros =array(


                    $iRacionalId[0]->iRacionalId,
                    $request->iDocenteId ,
                    $request->iControlCicloAcad,
                    $carganl['idact'],
                    $request->iCarreraId,
                    $request->iFilId,
                    $iRacionalId[0]->cFilSigla,
                    $request->iAulaCod,
                    $carganl['documento'],
                    1,
                    1,
                    //$carganl['iCargaNoLecHrsT'],
                    //$carganl['iCargaNoLecHrsP'],
                    $carganl['hora_cantidad'],

                    auth()->user()->cCredUsuario,
                    'equipo',
                    $request->server->get('REMOTE_ADDR'),
                    'mac'
                );

                $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Racional_Carga_No_Lectiva] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
            }

            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información de la Carga No Lectiva exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información de la Carga No Lectiva .'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json( $response, $codeResponse );
    }
    */
    /*
    GUARDA RACIONALIZACIONES
    */
    public function guardarRacionalizaciones(Request $request)
    {

        $ConsultaR = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad',  $request->iControlCicloAcad)
            ->where('iDocenteId', $request->iDocenteId)
            ->get();

        $ConsultaP = \DB::table('ura.docentes')

            ->where('iDocenteId', $request->iDocenteId)
            ->get();


        if (isset($ConsultaR[0]->iRacionalId)) {
            $actualizarDeclaracion = \DB::table('ura.racionalizaciones')
                ->where('iRacionalId',  $ConsultaR[0]->iRacionalId)
                ->update(array('cRacionalLabInst' => $request->cRacionalLabInst, 'cRacionalDedInst' => $request->cRacionalDedInst, 'cRacionalHrsInst' => $request->cRacionalHrsInst, 'cRacionalNomInst' => $request->cRacionalNomInst));
            $actualizarDireccion = \DB::table('grl.persona_tipo_contactos')
                ->where('iPersId',  $ConsultaP[0]->iPersId)
                ->where('iTipoConId',  1)
                ->update(array('cPersTipoConDescripcion' => $request->cPersDireccion));

            $actualizarNacimiento = \DB::table('grl.personas')
                ->where('iPersId',  $ConsultaP[0]->iPersId)
                ->update(array('dPersNacimiento' => $request->cPersNacimiento));
        } else {

            $this->validate(
                $request,
                [
                    'iDocenteId' => 'required',
                    'iControlCicloAcad' => 'required',
                    'iCarreraId' => 'required',
                    'iFilId' => 'required',
                    'cFilSigla' => 'required',
                    'iRacionalHrsLectivas' => 'required',
                    'iRacionalHrsNoLectivas' => 'required',
                    'iRacionalHrsInvestigacion' => 'required',
                    'iRacionalTotalHrs' => 'required',
                    'cRacionalEstado' => 'required',
                    'cRacionalLabInst' => 'required',
                    /*'cRacionalNomInst' => 'required',
                'cRacionalDedInst' => 'required',
                'cRacionalHrsInst' => 'required',*/
                    'cRacionalDeclara' => 'required',
                    'cRacionalEstaLec' => 'required',
                    'cRacionalEstaNoLec' => 'required',
                    'cRacionalEstaRrHh' => 'required',
                    'cRacionalEstaDasa' => 'required',
                ],
                [
                    'iDocenteId.required' => 'ID de docente requerido',
                    'iControlCicloAcad.required' => 'Ciclo Académico requerido',
                    'iCarreraId.required' => 'ID de Carrera Profesional requerido',
                    'iFilId.required' => 'ID de Filial requerido',
                    'cFilSigla' => 'Sigla de Filial requerido',
                    'iRacionalHrsLectivas' => 'Horas Lectivas requerido',
                    'iRacionalHrsNoLectivas' => 'Horas No Lectivas requerido',
                    'iRacionalHrsInvestigacion' => 'Horas Investigacion requerido',
                    'iRacionalTotalHrs' => 'Total de Horas requerido',
                    'cRacionalEstado' => 'Estado requerido',
                    'cRacionalLabInst' => 'Acreditación de Labores requerido',
                    'cRacionalNomInst' => 'Nombre de la Institución requerido',
                    'cRacionalDedInst' => 'Dedicación en Institución requerido',
                    'cRacionalHrsInst' => 'Horas Dedicación en la Institución requerido',
                    'cRacionalDeclara' => 'Declaración requerido',
                    'cRacionalEstaLec' => 'Estado Lectivo requerido',
                    'cRacionalEstaNoLec' => 'Estado No Lectivo requerido',
                    'cRacionalEstaRrHh' => 'Aprobación de Recursos Humanos requerido',
                    'cRacionalEstaDasa' => 'Aprobación de DASA requerido',
                ]
            );

            $parametros = array(
                $request->iDocenteId,
                $request->iControlCicloAcad,
                $request->iCarreraId,
                $request->iFilId,
                $request->cFilSigla,
                $request->iRacionalHrsLectivas,
                $request->iRacionalHrsNoLectivas,
                $request->iRacionalHrsInvestigacion,
                $request->iRacionalTotalHrs,
                $request->cRacionalEstado,
                $request->cRacionalLabInst,
                $request->cRacionalNomInst,
                $request->cRacionalDedInst,
                $request->cRacionalHrsInst,
                $request->cRacionalDeclara,
                $request->cRacionalEstaLec,
                $request->cRacionalEstaNoLec,
                $request->cRacionalEstaRrHh,
                $request->cRacionalEstaDasa,


                auth()->user()->cCredUsuario,
                'equipo',
                $request->server->get('REMOTE_ADDR'),
                'mac'
            );
            try {
                $actualizarDireccion = \DB::table('grl.persona_tipo_contactos')
                    ->where('iPersId',  $ConsultaP[0]->iPersId)
                    ->where('iTipoConId',  1)
                    ->update(array('cPersTipoConDescripcion' => $request->cPersDireccion));

                $actualizarNacimiento = \DB::table('grl.personas')
                    ->where('iPersId',  $ConsultaP[0]->iPersId)
                    ->update(array('dPersNacimiento' => $request->cPersNacimiento));


                $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Racionalizaciones] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

                if ($data[0]->id > 0) {
                    $response = ['validated' => true, 'mensaje' => 'Se guardó la información del formato de Racionalizaciones exitosamente.'];
                    $codeResponse = 200;
                } else {
                    $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información del formato de Racionalizaciones .'];
                    $codeResponse = 500;
                }
            } catch (\QueryException $e) {
                $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
                $codeResponse = 500;
            }
            return response()->json($response, $codeResponse);
        }
    }

    /*
    GUARDA LOS HORARIOS DE LA CARGA NO LECTIVA
    */
    /*
    public function guardarHorarioCargaNoLectiva (Request $request) {
        $this->validate(
            $request, [
                'iRacionalId' => 'required',
                'iDocenteId' => 'required',
                'iControlCicloAcad' => 'required',
                'iActividadesId' => 'required',
                'cActividadesOtro' => 'required',
                'iCarreraId' => 'required',
                'iFilId' => 'required',
                'iAulaCod' => 'required',
                'cCargaNoLecDoc' => 'required',
                'iCargaNoLecHrsT' => 'required',
                'iCargaNoLecHrsP' => 'required',
                'iCargaNoLecHrsTot' => 'required',
            ],[
                'iRacionalId.required' => 'ID de racionalización requerido',
                'iDocenteId.required' => 'ID de docente requerido',
                'iControlCicloAcad.required' => 'Ciclo Académico requerido',
                'iActividadesId.required' => 'Descripción de actividades requerido',
                'cActividadesOtro.required' => 'Descripción de Otras actividades requerido',
                'iCarreraId.required' => 'ID de Carrera Profesional requerido',
                'iFilId.required' => 'ID de Filial requerido',
                'iAulaCod.required' => 'Código de aula requerido',
                'cCargaNoLecDoc.required' => 'Documento o Resolución requeridos',
                'iCargaNoLecHrsT.required' => 'Horas teóricas nolectivas requerido',
                'iCargaNoLecHrsP.required' => 'Horas de práctica no lectivas requerido',
                'iCargaNoLecHrsTot.required' => 'Total de carga no lectiva requerido',
            ]
        );

        $iRacionalId = \DB::table('ura.racionalizaciones')
        ->where('iControlCicloAcad', $request->iControlCicloAcad)
        ->where('iDocenteId', $request->iDocenteId)
        ->where('iCarreraId', $request->iCarreraId)
        ->where('iFilId', $request->iFilId)
        ->get();

        $parametros = [
            $request->iRacionalId ?? NULL,
            $request->iDocenteId ?? NULL,
            $request->iControlCicloAcad ?? NULL,
            $request->iActividadesId ?? NULL,
            $request->cActividadesOtro ?? NULL,
            $request->iCarreraId ?? NULL,
            $request->iFilId ?? NULL,
            $request->iAulaCod ?? NULL,
            $request->cCargaNoLecDoc ?? NULL,
            $request->iCargaNoLecHrsT ?? NULL,
            $request->iCargaNoLecHrsP ?? NULL,
            $request->iCargaNoLecHrsTot ?? NULL,
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        ];

        try {
            $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Racional_Carga_No_Lectiva] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);


            if ($data[0]->id > 0){
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información de la Carga No Lectiva  exitosamente.'];
                $codeResponse = 200;
            }
            else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información de la Carga No Lectiva.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
        return response()->json( $response, $codeResponse );
    }

 */

    public function ListAsistenciaTotal($iDocenteId, $iControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId)
    {

        $AsistenciaTotal = \DB::select('EXEC [ura].[Sp_DOCE_Sel_Asistencia_ListadoGeneral] ?,?,?,?,?,?,?', array(
            $iDocenteId,
            $iControlCicloAcad,
            $iCurricId,
            $iFilId,
            $iCarreraId,
            $cCurricCursoCod,
            $iSeccionId


        ));
        return response()->json($AsistenciaTotal);
    }

    public function CargaNoLectivaDocente($docente, $ciclo)
    {

        $cnl = \DB::select('exec ura.Sp_DOCE_SEL_Racional_Carga_No_Lectiva_xiDocenteId ?, ?', array($docente, $ciclo));

        return response()->json($cnl);
    }
    public function AulasxCarrera($carrera, $filial)
    {

        $aulas = \DB::select('exec ura.Sp_DOCE_SEL_AulasxiCarreraIdxiFilId ?, ?', array($carrera, $filial));

        return response()->json($aulas);
    }
    /*
    public function OficinasxFilial($filial)
    {

        $oficinas = \DB::select('exec ura.Sp_DOCE_SEL_OficinasxiFilId ?, ?', array($filial));

        return response()->json( $oficinas );

    }
*/
    public function DatosR($ciclo, $id)
    {
        //echo $ciclo;
        //echo $id;


        $pers = \DB::table('ura.docentes')
            ->where('iPersId', $id)
            ->get();
        //print_r($pers[0]->iDocenteId);

        $id = $pers[0]->iDocenteId;
        // return;


        $LR = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->count();

        $CE = \DB::table('ura.detalle_carga_lectiva')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->count();

        $CLectivo = \DB::table('ura.detalle_carga_lectiva')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->get();

        $CNE = \DB::table('ura.detalle_carga_no_lectiva')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->count();

        $R = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->get();

        $AR = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->where('cRacionalDeclara', 1)
            ->COUNT();

        $HNLv = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            //->where('cRacionalDeclara', 1)
            ->get();

        if (isset($HNLv[0]->iRacionalId)) {
            $HNL = \DB::table('ura.horarios_carga_lectiva_no_lectiva')
                ->where('iRacionalId', $HNLv[0]->iRacionalId)
                ->count();
        } else {
            $HNL = 0;
        }

        if (isset($HNLv[0]->iRacionalId)) {
            $HNLARCHIVO = \DB::table('ura.detalle_carga_no_lectiva')
                ->where('iRacionalId', $HNLv[0]->iRacionalId)
                ->count();
        } else {
            $HNLARCHIVO = 0;
        }
        $CNLectivo = \DB::table('ura.detalle_carga_no_lectiva')
            ->where('iControlCicloAcad', $ciclo)
            ->where('iDocenteId', $id)
            ->get();

        return Response::json(['LR' => $LR, 'CE' => $CE, 'CNE' => $CNE, 'R' => $R, 'AR' => $AR, 'HNL' => $HNL, 'HNLARCHIVO' => $HNLARCHIVO, 'CLectivo' => $CLectivo, 'CNLectivo' => $CNLectivo]);
    }
    public function insertarCargaLectivaNoLectiva(Request $request)
    {
        $this->validate(
            $request,
            [
                'iHorariosId' => 'required',
                'iRacionalId' => 'required',
                'iAulaCod' => 'required',
                'iCarreraId' => 'required',
                'iFilId' => 'required',
                'iControlCicloAcad' => 'required',
                'iCurricId' => 'required',
                'cCursoCod' => 'required',
                'iSeccionId' => 'required',
                'iActividadesId' => 'required',
                'iDiaSemId' => 'required',
                'tHoraCarNoLecInicio' => 'required',
                'tHoraCarNoLecFin' => 'required',
                'nHoraCarNoLecNumHoras' => 'required',
                'cHoraCarNoLecUsuarioSis' => 'required',
                'cHoraCarNoLecEquipoSis' => 'required',
                'cHoraCarNoLecIpSis' => 'required',
                'cHoraCarNoLecMacSis' => 'required',
            ],
            [
                'iHorariosId.required' => 'ID de Horarios requerido',
                'iRacionalId.required' => 'ID de Racionalizacion requerido',
                'iAulaCod.required' => 'ID de Aula requerido',
                'iCarreraId.required' => 'ID de Carrera requerido',
                'iFilId.required' => 'ID de Filial requerido',
                'iControlCicloAcad.required' => 'Ciclo Académico requerido',
                'iCurricId.required' => 'ID de Currícula requerido',
                'cCursoCod.required' => 'Código del Curso requerido',
                'iSeccionId.required' => 'ID de Sección requerido',
                'iActividadesId.required' => 'ID de Actividades no Lectivas requerido',
                'iDiaSemId.required' => 'Día de la semana requerida',
                'tHoraCarNoLecInicio.required' => 'Hora de Inicio requerido',
                'tHoraCarNoLecFin.required' => 'Hora de Finalización requerido',
                'nHoraCarNoLecNumHoras.required' => 'Total de Horas de Actividad requerido',
                'cHoraCarNoLecUsuarioSis.required' => 'Nombre del Usuario Requerido',
                'cHoraCarNoLecEquipoSis.required' => 'Nombre del Equipo Requerido',
                'cHoraCarNoLecIpSis.required' => 'IP Requerido',
                'cHoraCarNoLecMacSis.required' => 'MAC Requerido',
            ]
        );
        $parametros = array(
            $request->intval(iHorariosId),
            $request->intval(iRacionalId),
            $request->intval(iAulaCod),
            $request->intval(iCarreraId),
            $request->intval(iFilId),
            $request->intval(iControlCicloAcad),
            $request->intval(iCurricId),
            $request->cCursoCod,
            $request->intval(iSeccionId),
            $request->intval(iActividadesId),
            $request->intval(iDiaSemId),
            $request->tHoraCarNoLecInicio,
            $request->tHoraCarNoLecFin,
            $request->intval(nHoraCarNoLecNumHoras),
            auth()->user()->cCredUsuario,
            'equipo',
            $request->server->get('REMOTE_ADDR'),
            'mac'
        );
        try {
            $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Horarios_Carga_Lectiva_No_Lectiva] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);

            if ($data[0]->id > 0) {
                $response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
                $codeResponse = 200;
            } else {
                $response = ['validated' => true, 'mensaje' => 'No se ha podido guardar la información.'];
                $codeResponse = 500;
            }
        } catch (\QueryException $e) {
            $response = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e->getCode()];
            $codeResponse = 500;
        }
        return response()->json($response, $codeResponse);
    }



    public function guardarHorarioCargaNoLectiva(Request $request)
    {

        $RacionalId = \DB::table('ura.racionalizaciones')
            ->where('iControlCicloAcad', $request->iControlCicloAcad)
            ->where('iDocenteId', $request->iDocenteId)
            ->where('iCarreraId', $request->iCarreraId)
            ->where('iFilId', $request->iFilId)
            ->get();

        $pers = \DB::table('ura.docentes')
            ->where('iDocenteId', $request->iDocenteId)
            ->get();


        $horarioV = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad_Prueba ?, ?', array($pers[0]->iPersId, $request->iControlCicloAcad));
        //$horario = \DB::select('exec ura.Sp_DOCE_SEL_horarioClasesXiDocenteIdXiControlCicloAcad_Prueba ?, ?', array($id,$ciclo));
        $ObtenerHora = \DB::table('ura.horarios_configuracion')
            ->where('iCarreraId', $horarioV[0]->iCarreraId)
            ->where('iFilId', $horarioV[0]->iFilId)
            ->get();
        $OH = explode(':', $ObtenerHora[0]->tHoraConfHini);
        $jj = ($OH[0] * 60) + $OH[1];

        $OF = explode(':', $ObtenerHora[0]->tHoraConfHfin);
        $ff = ($OF[0] * 60) + $OF[1];


        $x = 0;
        for ($i = $jj; $i <= $ff; $i = $i + 50) {
            $prueba = $i / 60;
            $drew = explode(".", $prueba);
            $k = $drew[0];
            $l = $i % 60;
            if ($k < 10) {
                $ee = "0";
            } else {
                $ee = "";
            }

            if ($l == 0) {
                $dia = $ee . $k . ":00";
                $dff = $ee . $k . ":50";
            } else {
                $dia = $ee . $k . ":" . $l;

                if (($k + 1) < 10) {
                    $eff = 0;
                } else {
                    $eff = "";
                }

                if (((50 + $l) - 60) == 0) {
                    $dff = $eff . ($k + 1) . ":00";
                } else {

                    $dff = $eff . ($k + 1) . ":" . ((50 + $l) - 60);
                }
            }

            $array[$x]['hora'] = $dia;
            $array[$x]['hora_ff'] = $dff;
            $ss1 = 1;
            foreach ($horarioV as $key => $ho) {

                if ($ho->lunes_inicio != "-") {

                    $dd = explode(':', $ho->lunes_inicio);
                    $df = explode(':', $ho->lunes_fin);



                    if ($dia < $df[0] . ":" . $df[1] && $dia >= $dd[0] . ":" . $dd[1]) {
                        $dr = (((($df[0] * 60) + $df[1]) - (($dd[0] * 60) + $dd[1])) / 50) + 1;
                        $ss1 = $dr;
                        $array[$x]['Pinto1'] = 1;
                        $array[$x]['curso1'] = $ho->cCurricCursoDsc . ' - ' . $ho->lunes_ubicacion;
                        $array[$x]['iHorariosId1'] = $ho->iHorariosId;
                    }
                }


                if ($ho->martes_inicio != "-") {
                    $dd = explode(':', $ho->martes_inicio);
                    $df = explode(':', $ho->martes_fin);

                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto2'] = 1;
                        $array[$x]['curso2'] = $ho->cCurricCursoDsc . ' - ' . $ho->martes_ubicacion;
                        $array[$x]['iHorariosId2'] = $ho->iHorariosId;
                    }
                }
                if ($ho->miercoles_inicio != "-") {
                    $dd = explode(':', $ho->miercoles_inicio);
                    $df = explode(':', $ho->miercoles_fin);

                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto3'] = 1;
                        $array[$x]['curso3'] = $ho->cCurricCursoDsc . ' - ' . $ho->miercoles_ubicacion;
                        $array[$x]['iHorariosId3'] = $ho->iHorariosId;
                    }
                }
                if ($ho->jueves_inicio != "-") {
                    $dd = explode(':', $ho->jueves_inicio);
                    $df = explode(':', $ho->jueves_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto4'] = 1;
                        $array[$x]['curso4'] = $ho->cCurricCursoDsc . ' - ' . $ho->jueves_ubicacion;
                        $array[$x]['iHorariosId4'] = $ho->iHorariosId;
                    }
                }
                if ($ho->viernes_inicio != "-") {
                    $dd = explode(':', $ho->viernes_inicio);
                    $df = explode(':', $ho->viernes_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] &&  $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto5'] = 1;
                        $array[$x]['curso5'] = $ho->cCurricCursoDsc . ' - ' . $ho->viernes_ubicacion;
                        $array[$x]['iHorariosId5'] = $ho->iHorariosId;
                    }
                }
                if ($ho->sabado_inicio != "-") {
                    $dd = explode(':', $ho->sabado_inicio);
                    $df = explode(':', $ho->sabado_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto6'] = 1;
                        $array[$x]['curso6'] = $ho->cCurricCursoDsc . ' - ' . $ho->sabado_ubicacion;
                        $array[$x]['iHorariosId6'] = $ho->iHorariosId;
                    }
                }
                if ($ho->domingo_inicio != "-") {
                    $dd = explode(':', $ho->domingo_inicio);
                    $df = explode(':', $ho->domingo_fin);
                    if ($dia >= $dd[0] . ":" . $dd[1] && $dia < $df[0] . ":" . $df[1]) {
                        $array[$x]['Pinto7'] = 1;
                        $array[$x]['curso7'] = $ho->cCurricCursoDsc . ' - ' . $ho->domingo_ubicacion;
                        $array[$x]['iHorariosId7'] = $ho->iHorariosId;
                    }
                }
            }
            $array[$x]['ss1'] = $ss1;
            $x++;
        }

        for ($l = 0; $l < $x; $l++) {
            $dd = 1;
            while ($dd <= 7) {



                if (isset($array[$l]['curso' . $dd])) {
                } else {
                    if (isset($request->horario[$l]['curso' . $dd])) {
                        $dia = $dd;
                        $hora = $request->horario[$l]['hora'];
                        $horaff = $request->horario[$l]['hora_ff'];
                        //$iHorarioId =$request->horario[$l]['iHorarioId'.$dd];
                        $aula = $request->horario[$l]['idAula' . $dd];
                        if (isset($request->horario[$l]['descAula' . $dd])) {
                            $descAula = $request->horario[$l]['descAula' . $dd];
                        } else {
                            $descAula = '-';
                        }
                        $actividad = $request->horario[$l]['idActividad' . $dd];



                        $parametros = [
                            $RacionalId[0]->iRacionalId ?? NULL,


                            $aula ?? NULL,

                            $request->iCarreraId ?? NULL,
                            $request->iFilId ?? NULL,

                            $request->iControlCicloAcad ?? NULL,

                            $actividad ?? NULL,
                            $dia ?? NULL,

                            $hora ?? NULL,
                            $horaff ?? NULL,
                            $request->nHoraCarNoLecNumHoras ?? NULL,
                            $descAula,
                            auth()->user()->cCredUsuario,
                            'equipo',
                            $request->server->get('REMOTE_ADDR'),
                            'mac'
                        ];
                        $data = \DB::select('EXEC [ura].[Sp_DOCE_INS_Horarios_Carga_Lectiva_No_Lectiva] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $parametros);
                        $response = ['validated' => true, 'mensaje' => 'Se guardó la información de la Carga Lectiva exitosamente.'];
                        $codeResponse = 200;
                    }
                }



                $dd++;
            }
        }




        //$response = ['validated' => true, 'mensaje' => 'Se guardó la información exitosamente.'];
        return response()->json($response, $codeResponse);
    }
}
