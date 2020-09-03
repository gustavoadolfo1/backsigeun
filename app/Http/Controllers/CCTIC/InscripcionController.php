<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\model\cctic\Inscripcion;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $filePathName = null;
        if ($request->hasFile('file')) {
            $filePathName = $request->file('file')->store('cctic/inscripcion/requisito');
        }

        $dataJson = json_decode(json_encode($request->detalleInscripcion));
        $dataPerson = json_decode(json_encode($request->persona));


//        guardar la persona
//        y obtener el id

        $parametersPersona = [
            1,
            $request->iTipoIdentId,
            $request->cPersDocumento,
            /*Persona Natural*/
            $request->cPersPaterno,
            $request->cPersMaterno,
            $request->cPersNombre,
            $request->cPersSexo, //cPersSexo,
            $request->dPersNacimiento, //dPersNacimiento,
            /*Persona Juridica*/
            null,
            null,
            null,
            null,
            /*Campos de autoria*/
            auth()->user()->iCredId,
            gethostname(),
            $request->getClientIp(),
            strtok(exec('getmac'), ' ')
        ];



        $dataInsert = [
            $request->iPersId,
            $request->iEstudId,
            $request->iPublicoObjetivoId,
            $request->iFilId,
            $request->iProgramAcadId,
            $request->iGruposId,
            $request->bRequisitos,
            $filePathName,
            $request->montoMatricula,
            false,
            auth()->user()->cCredUsuario,
            auth()->user()->iCredId,
            $request->server->get('REMOTE_ADDR'),
            $dataJson,
            $dataPerson,
            $request->iConcepReqIdMatricula,
            $request->iConcepReqIdMensualidad,
        ];


        if (!$request->iPersId) {
            $persona = DB::select('exec [grl].[Sp_INS_personas] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $parametersPersona);
            $dataInsert[0] = $persona[0]->iPersId;
        }

        try {
            $resp = DB::select('exec [acad].SP_INS_inscripcion
                @iPersId = ?,
                @iEstudId = ?,
                @iPublicoObjetivoId = ?,
                @iFilId = ?,
                @iProgramAcadId = ?,
                @iGruposId  = ?,
                @bRequisitos = ?,
                @cPathRequisito = ?,
                @fMontoMatricula = ?,
                @bPagoMatricula = ?,
                @cUsuarioSis = ?,
                @iCredId = ?,
                @cIpSis = ?,
                @jsonDetalle = ?,
                @jsonPersona = ?,
                @ConceptoMatricula = ?,
                @ConceptoMensualidad = ?',
                $dataInsert
            );

            $response = ['validated' => true, 'message' => 'Inscripcion registrada correctamente', 'data' => $resp];
            $responseCode= 200;
        } catch (\Exception $e) {

            $response = ['validated' => true, 'message' => 'No se pudo registrar la inscripcion', 'error' => $e->getMessage(), 'data' => []];
            $responseCode= 500;

        }


        return response()->json($response, $responseCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function byPersdDocumento(Request $request)
    {
        $params = [
          $request->dni,
          $request->iProgramasAcadId,
          $request->iFilId,
        ];

        try {
            $inscripciones  = DB::select('exec [acad].SP_SEL_insripcionesBycPersDocumento
                @cPersDocumento = ?,
                @iProgramasAcadId = ?,
                @iFilId = ?',
                $params
            );

            if (count($inscripciones) == 0) {
                return response()->json(['validated' => true, 'message' => 'No se encontraron registros de esta persona', 'data' => []], 200);
            }

            foreach ($inscripciones as $inscripcion) {
                if (is_null($inscripcion->detalle_inscripcion)) {
                    $inscripcion->detalle_inscripcion = [];
                } else {
                    $inscripcion->detalle_inscripcion = json_decode($inscripcion->detalle_inscripcion);
                }
            }


            $response = ['validated' => true, 'message' => 'Datos obtenidos correctamente', 'data' => $inscripciones];
            $responseCode = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'message' => 'Error al obtener los datos', 'data' => [], 'error' => $e->getMessage()];
            $responseCode = 500;

        }

        return response()->json($response, $responseCode);
    }


    public function byGrupoId($id)
    {
        try {
            $inscripciones = DB::select('exec [acad].SP_SEL_inscripcionesByGrupoId @iGruposId = ?
            ', [$id]);

            $response = ['validated' => true, 'message' => 'Inscripciones obtenidas correctamente', 'data' => $inscripciones];
            $responseCode = 200;

            if (count($inscripciones) == 0) {
                $response = ['validated' => true, 'message' => 'Inscripciones obtenidas correctamente', 'data' => $inscripciones];
                $responseCode = 200;
                return response()->json($response, $responseCode);
            }

            foreach ($inscripciones as $inscripcion) {
                $inscripcion->detalle_inscripcion = json_decode($inscripcion->detalle_inscripcion);
            }

        } catch (\Exception $e) {
            $response =  ['validated' => false, 'message' => 'No se pudo obtener las inscripciones', 'data '=> [], 'error' => $e->getMessage()];

            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }


    public function byDNI(Request $request)
    {
        $params = [
            $request->dni,
            $request->programAcad,
            $request->filial
        ];
        try {

            $inscripciones = DB::select('exec [acad].[Sp_CCTIC_SEL_Panel_Muestra_Inscripciones_DNI] ?, ?, ?', $params);


            foreach ($inscripciones as $preinscripcion) {
                $preinscripcion->unidades = json_decode($preinscripcion->Unidades);
                $preinscripcion->horarios = json_decode($preinscripcion->Horarios);
                $preinscripcion->mensualidades= json_decode($preinscripcion->Mensualidades);
            }

            $response = ['validated' => true, 'data' => $inscripciones, 'message' => 'Inscripciones obtenidas correctamente'];
            $responseCode = 200;

        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['validated' => false, 'data' => [], 'error' => $e->getMessage(), 'message' => 'No se pudo obtener las inscripciones'];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function inscripcionConDetalles(Request $request)
    {
        $params = [
            $request->dni,
            $request->iProgramasAcadId,
            $request->iFilId,

            auth()->user()->cCredUsuario,
            null,
            $request->server->get('REMOTE_ADDR'),
        ];

        try {
            $inscripciones  = DB::select('exec 
                [acad].[Sp_CCTIC_SEL_Inscripciones_Muestra_Detalles] ?, ?, ?, ?, ?, ?', $params
            );

            if (count($inscripciones) == 0) {
                return response()->json(['validated' => true, 'message' => 'No se encontraron registros de esta persona', 'data' => []], 200);
            }

            foreach ($inscripciones as $inscripcion) {
                    $inscripcion->Inscripcion_Detalle = json_decode($inscripcion->Inscripcion_Detalle);
            }


            $response = ['validated' => true, 'message' => 'Datos obtenidos correctamente', 'data' => $inscripciones];
            $responseCode = 200;

        } catch (\Exception $e) {

            $response = ['validated' => true, 'message' => 'Error al obtener los datos', 'data' => [], 'error' => $e->getMessage()];
            $responseCode = 500;

        }

        return response()->json($response, $responseCode);
    }
}
