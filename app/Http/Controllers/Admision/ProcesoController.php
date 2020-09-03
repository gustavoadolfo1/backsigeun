<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class ProcesoController extends Controller
{
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'proceso' => 'required',
                'iCicloControl' => 'required|max:5|min:5|unique:sqlsrv.adm.proceso_admision',
                'fechaExtraordinario' => 'required',
                'fechaOrdinario' => 'required',
            ],
            [
                'iCicloControl.unique' => 'El nombre de proceso de admisón debe ser único.',
            ]
        );

        DB::beginTransaction();
        
        try {
            DB::table('adm.proceso_admision')->insert([
                'cProcAdmDoc' => $request->proceso, 
                'iCicloControl' => $request->iCicloControl,
                'bProcAdmEst' => 0,
                'bOrdinario' => 1,
                'bExtraOrdinario' => 1,
                'dProcOrdinario' => date('Y-m-d', strtotime($request->fechaOrdinario)),
                'dProcExtraOrdinario' => date('Y-m-d', strtotime($request->fechaExtraordinario)), 
            ]);
            
            $sedesFormatted = [];
            foreach ($request->sedes as $sede) {
                $sede['iGrupoControl'] = $request->iCicloControl;
                $sede['cGrupoDsc'] = $request->proceso;
                $sede['iProgramasAcadId'] = 7;
                $sede['bEstadoAcademico'] = false;
                $sede['bEstadoInscripciones'] = false;
                unset ($sede['active'], $sede['cFilDescripcion']);

                $sedesFormatted[] = $sede;
            }

            DB::table('acad.grupos')->insert( $sedesFormatted );

            DB::commit();

            $response = ['validated' => true, 'message' => 'Se creó un nuevo proceso de admisión' ];
            $codeResponse = 200;

        } catch (\Exception $e) {
            DB::rollback();

            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function activar($idProceso)
    {
        DB::beginTransaction();
        
        try {

            DB::table('adm.proceso_admision')->update(['bProcAdmEst' => false]);
            DB::table('adm.proceso_admision')->where('iProcAdmId', $idProceso)->update(['bProcAdmEst' => true]);

            $proceso = DB::table('adm.proceso_admision')->where('iProcAdmId', $idProceso)->first();

            DB::table('acad.grupos')->update(['bEstadoAcademico' => false, 'bEstadoInscripciones' => false]);
            DB::table('acad.grupos')->where('iGrupoControl', $proceso->iCicloControl)->update(['bEstadoAcademico' => true, 'bEstadoInscripciones' => true]);

            DB::commit();
            $response = ['validated' => true, 'message' => 'Se activó el proceso de admisión' ];
            $codeResponse = 200;
        } catch (\Exception $e) {
            DB::rollback();
            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }
}
