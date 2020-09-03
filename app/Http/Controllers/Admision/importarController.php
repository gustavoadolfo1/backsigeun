<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;

class importarController extends Controller
{
    public function dataProcesada(Request $request){
        $data = [
            $request->iCiclo,
            $request->cJson
        ];
        $queryResult = \DB::select("EXEC adm.Sp_UPD_puntaje_inscripcionesXiGrupoControlXcJson $request->iCiclo, '$request->cJson' " , $data);
        return response()->json( $queryResult );
    }

    public function getProcesos(){
        $users = \DB::table('adm.proceso_admision')->get();
        return response()->json($users);
    }
   
    public function getProcesosCertificados($idProceso){
        $queryResult = \DB::select("EXEC [adm].[SP_SEL_ingresantes] ?" , [$idProceso]);
        return response()->json($queryResult);
    }

    public function constaciaIngreso($id){
        $data = \DB::select("select iIngresanteId, concat(pers.cPersPaterno , ' ', pers.cPersMaterno,', ', pers.cPersNombre) as nombres, c.cCarreraDsc,m.cModalidadCod, p.cProcAdmDoc from adm.ingresantes as ing
                                    inner join ura.carreras as c on c.iCarreraId=ing.iCarreraId
                                    inner join grl.personas as pers on ing.iPersId=pers.iPersId
                                    inner join adm.proceso_admision as p on p.iCicloControl = ing.iGrupoControl
                                    left join ura.modalidades as m on m.cModalidadCod=ing.cModalidadCod
                                    where iIngresanteId=".$id);

        setlocale(LC_TIME, 'Spanish');
        $dt = Carbon::now();
        $date = $dt->formatLocalized('%d de %B del %Y');
        //dd($data[0]);
        $pdf = PDF::loadView('admision.certificado', [ 'data' => $data[0], 'date' => $date ])->setPaper('A4');
        return $pdf->stream();
    }

    public function toggleEntregaConstancia($id, $bEntrega)
    {
        $queryResult = \DB::select('exec [adm].[Sp_UPD_EntregoConstanciaXiIngresanteId] ?, ?, ?', [ $id, $bEntrega, auth()->user()->cCredUsuario ] );

        return response()->json($queryResult);
    }

    public function updImpresionConstancia($inscripcionId)
    {
        try {
            $queryResult = \DB::select('exec [adm].[Sp_UPD_ImprimioConstanciaXiIngresanteId] ?, ?', [$inscripcionId, auth()->user()->cCredUsuario] );

            if ($queryResult[0]->iResult == 1) {
                $message = 'Datos actualizados correctamente.';
            } else {
                $message = 'No se pudo actualizar.';
            }

            $response = ['validated' => true, 'message' => $message, 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
