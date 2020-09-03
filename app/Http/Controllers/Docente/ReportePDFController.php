<?php

namespace App\Http\Controllers\Docente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Tram\TramitesController;

class ReportePDFController extends Controller
{
    public function pdfActaDocente($cargaId, $cicloAcad)
    {
        $acta = \DB::select('exec [ura].[Sp_DASA_SEL_actasDocente] ?,?', array($cargaId, $cicloAcad));

        $pdf = \PDF::loadView('docente.PdfActa', compact(['acta', 'cicloAcad']));
        return $pdf->stream();
    }

    public function pdfRegEvaDocente($iDocenteId, $iControlCicloAcad, $iCurricId, $iFilId, $iCarreraId, $cCurricCursoCod, $iSeccionId)
    {

        DB::beginTransaction();


        try {
            $eva = \DB::select(
                'exec ura.Sp_DOCE_SEL_Notas_Muestra_PromedioFinal ?,?,?,?,?,?,?',
                [
                    $iDocenteId,
                    $iControlCicloAcad,
                    $iCurricId,
                    $iFilId,
                    $iCarreraId,
                    $cCurricCursoCod,
                    $iSeccionId
                ]
            );

            $pdf = \PDF::loadView('docente.PdfRegEva', compact('eva'))->setPaper('A4', 'landscape');
            return $pdf->stream();

            /*
                    if ($result[0]->iResult > 0) {
                        $responseJson = ['error' => false, 'mensaje' => 'El registro se guardo correctamente.'];
                    } else {
                        $responseJson = ['error' => true, 'mensaje' => 'No se ha podido guardar el registro.'];
                    } */

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            report($e);
            $responseJson = $errorJson->returnError($e);
        }
    }
    public function pdfRegistroDocente(Request $request)
    {
        // return response()->json($request->all());
        //  exec [ura].[Sp_DOCE_SEL_Notas_Muestra_PromedioFinal]  @iDocenteId int, @iControlCicloAcad int, @iCurricId int, @iFilId int, @iCarreraId int, @cCurricCursoCod varchar(15), @iSeccionId int
        $acta = \DB::select('exec [ura].[Sp_DOCE_SEL_Notas_Muestra_PromedioFinal] ?,?, ?,?, ?,?, ?', [
            $request->iDocenteId,
            $request->iControlCicloAcad,
            $request->iCurricId,
            $request->iFilId,
            $request->iCarreraId,
            $request->cCurricCursoCod,
            $request->iSeccionId,

        ]);
        return response()->json($acta);

        //$pdf = \PDF::loadView('docente.PdfActa', compact(['acta','cicloAcad']));
        //return $pdf->stream();

    }
    public function pdfRegistroDocente2(Request $request)
    {
        $data = $request->params;
        // return response()->json($data);
        $acta = \DB::select('exec [ura].[Sp_DOCE_SEL_Notas_Muestra_PromedioFinal] ?,?, ?,?, ?,?, ?', [
            $data['iDocenteId'],
            $data['iControlCicloAcad'],
            $data['iCurricId'],
            $data['iFilId'],
            $data['iCarreraId'],
            $data['cCurricCursoCod'],
            $data['iSeccionId'],

        ]);
        return response()->json($acta);

        //$pdf = \PDF::loadView('docente.PdfActa', compact(['acta','cicloAcad']));
        //return $pdf->stream();

    }
}
