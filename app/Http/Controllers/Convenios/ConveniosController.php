<?php

namespace App\Http\Controllers\Convenios;

use App\ClasesLibres\TramiteDocumentario\PdfCreator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use TCPDF_FONTS;

class ConveniosController extends Controller
{
    public function guardarDataArchivo(Request $request)
    {
        $data = $request->get('data');
        $data = json_decode(($data));
        $arrkeyArchivo = $data->controlArchivo;
        $carpeta = '/' . $data->carpeta;
        $prefijo = $data->prefijo;
        $sufijo = $data->sufijo;
        //return response()->json(($data->idProyecto) );
        if (is_array($arrkeyArchivo)){
            foreach ($arrkeyArchivo as $keyArchivo) {
                if ($request->hasFile($keyArchivo)) {
                    $archivo = $request->file($keyArchivo);

                    $nuevoNombreArchivo = ($prefijo ?? '') . (str_Replace('.' . $archivo->getClientOriginalExtension(), '', $archivo->getClientOriginalName())) . '-' . time() . ($sufijo ? '-' . $sufijo : '') . '.' . $archivo->getClientOriginalExtension();
                    $rutaArchivo[$keyArchivo] = ('storage/' . $archivo->storePubliclyAs('inv' . $carpeta, $nuevoNombreArchivo));
                } else {
                    $rutaArchivo[$keyArchivo] =$data->$keyArchivo;
                    //   abort(503, 'No se adjuntaron archivos'); quitar
                }
            }
        }else{

            if ($request->hasFile($arrkeyArchivo)) {
                $archivo = $request->file($arrkeyArchivo);

                $nuevoNombreArchivo = ($prefijo ?? '') . (str_Replace('.' . $archivo->getClientOriginalExtension(), '', $archivo->getClientOriginalName())) . '-' . time() . ($sufijo ? '-' . $sufijo : '') . '.' . $archivo->getClientOriginalExtension();
                $rutaArchivo = ('storage/' . $archivo->storePubliclyAs('inv' . $carpeta, $nuevoNombreArchivo));
            } else {
                //   abort(503, 'No se adjuntaron archivos'); quitar
            }
        }

        //return response()->json(($rutaArchivo) );

        $req = $request->get('tipo');

        $data = json_decode(json_encode($data));
        if ((is_object($data)) && (auth()->user()->iCredId != $data->auditoria->credencial_id)) {
            return response()->json(['error' => true, 'msg' => 'Usuario NO AUTENTICADO' . '#' . auth()->user()->iCredId . '#$' . $data->auditoria->credencial_id . '$']);
        }
        $respuesta = null;

        switch ($req) {



            case 'mantenimiento_conveniosmil':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
                     //   $respuesta = DB::select('EXEC con.DEL_convenios ?', $data);
                       // $respuesta = DB::select('EXEC con.DEL_convenios ?', $data );
                    } else {
                        $dataGuardar = [
                            $data->idTipoConvenio,
                            $data->idTipoEntidad,
                            $data->iEntidadId,
                            $data->idArea,
                            $data->fechainicio, //Carbon::parse($data->fechaInicio)->format('Y-m-d H:i:s'),
                            $data->fechafin, //Carbon::parse($data->fechaFin)->format('Y-m-d H:i:s'),

                            $data->objetivo,
                            $data->observaciones,
                            $rutaArchivo['cDoc'],

                            $data->idResolucion,
                            $data->idEncargado,
                            $data->idTipoEncargado,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ]; //return response()->json($dataGuardar);
                        if ($data->idConvenio) {
                            array_unshift($dataGuardar, $data->idConvenio);
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC con.UPD_Convenios ?,     ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC con.Sp_CONVENIOS_INS_Convenios_Insertar_Convenio     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,     ?, ?, ?, ?', $dataGuardar);
                            // return 'nuevo';
                        }
                    }
                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;

        }

        return response()->json($jsonResponse);
    }

    public function leerDataAnonimo(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');

        $respuesta = null;
        switch ($req) {
            case 'credenciales':
                // return response()->json(['base' => env('APP_NAME')]);
                $respuesta = collect(DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', $data));

                if ($respuesta->count() > 0) {

                    $credencial = $respuesta->first();
                    $respuestaPerfiles = collect(DB::select('seg.Sp_SEL_credenciales_perfilesXiCredId ?', [$credencial->iCredId]));
                    $credencial->perfiles = $respuestaPerfiles;

                    $respuesta = $credencial;
                }

                break;

            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;

        }
        return response()->json($respuesta);
    }

    public function leerData(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');

        $respuesta = null;
        switch ($req) {

            /****************modulo convenios****************************/
            case 'data_conveniosxvencer':
                $respuesta = DB::select('EXEC con. ? ', $data);
                break;

            case 'buscar_convenios':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Buscar_Convenios ? ', $data);
                break;
            case 'data_conveniosvencidos':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Muestra_Convenios_Vencidos ', $data);
                break;


            case 'data_tipoencargados':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Tipos_Encargado ', $data);
                break;
/***************************/
            case 'data_convenios':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Muestra_Convenios_buscar ? ', $data);
                break;
            case 'data_conveniosmil':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Muestra_ConveniosXParametros ?,?,?,?,? ', $data);
                break;

  /*************************/
            case 'data_convenios_porvencer':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_ConveniosXVencer ', $data);
                break;
            case 'data_tipo_convenios':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Lista_TipoConvenios ?', $data);
                break;
            case 'data_resoluciones':
               $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Lista_Resoluciones ', $data);
                break;
            case 'data_encargados':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Listado_Encargados_Convenio  ', $data);
                break;
            case 'data_entidades':
                  $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Lista_Entidades ', $data);
                break;
            case 'data_areas':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Lista_Areas ', $data);
                break;
            case 'data_tipoentidades':
                $respuesta = DB::select('EXEC con.Sp_CONVENIOS_SEL_Convenios_Lista_TipoEntidades ', $data);
                break;
            case 'data_anyo':
                $respuesta = DB::select("EXEC con.Sp_SEL_yearsXiYearId ?", $data);
                break;
            case 'data_personas_encargados':
                $respuesta = DB::select("EXEC con.Sp_SEL_personas_encargadas ?, ? ,?", $data);
                break;
            case 'data_personas':
                $respuesta = DB::select("EXEC con.Sp_SEL_personasXiTipoPersIdXcDocumento_cDescripcion ? ,?", $data);
                break;
            case 'tipo_persona':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_personas');
                break;
            /******************************gustavo*******************************************/

            case 'tipo_identificacion':
                $respuesta = DB::select('EXEC con.Sp_SEL_tipo_Identificaciones');
                break;

            case 'data_oficinas_usuario':
                $respuesta = DB::select("EXEC seg.Sp_SEL_credenciales_dependenciasXiCredId ?", $data);
                break;

            case 'data_credencial':
                if (!isset($data[0])) {
                    $data[0] = auth()->user()->iCredId;
                }
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', $data);
                break;

            case 'data_reniec':
                $respuesta = DB::select('EXEC grl.Sp_SEL_reniecXcReniecDni ?', $data);
                break;


            case 'credenciales':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXcCampoBusqueda ?', $data);
                break;

            case 'conceptos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_conceptosXiEntIdXcCodigo_cNombre 1, ?', $data);
                $respuesta = collect($respuesta);
                foreach ($respuesta as $concept) {
                    $datReq = DB::select("EXEC grl.Sp_SEL_conceptos_requisitosXiConcepId ?", [$concept->iConcepId]);
                    $concept->requisitos = collect($datReq)->sortBy('iConcepReqNumero');
                    //dd($datReq);
                }
                break;

            case 'tipo_conceptos':
                $respuesta = DB::select('EXEC grl.Sp_SEL_tipo_Conceptos');
                break;

            case 'verificarCambioContraseña':
                $respuesta = DB::select('EXEC seg.Sp_SEL_credencialesXiCredId ?', [auth()->user()->iCredId]);
                // return response()->json(['res' => [sha1($data[0]), $respuesta[0]->password]]);
                $retorno = ['cambiado' => true];
                if (sha1(auth()->user()->cCredUsuario) == $respuesta[0]->password) {
                    $retorno = ['cambiado' => false];
                }

                $respuesta = $retorno;

                break;




            // CONSULTAS GENERALES

            case 'consulta_existencia':
                /**
                 * Buscar si existe un valor en una tabla y campo especifico
                 *
                 * $data = array('tabla', 'campo', 'valor');
                 */
                $respuesta = DB::select('EXECUTE grl.Sp_SEL_Verificar_Existe_Campo ?, ?, ?', $data);

                if ($respuesta[0]->iResult == 0) {
                    $respuesta = ['error' => false, 'msg' => ''];
                } else {
                    $respuesta = ['error' => true, 'msg' => 'Ya se encuentra registrado'];
                }

                // dd($respuesta[0]->iResult);
                break;


        }
        //dd($data);
        //dd(DB::getQueryLog());

        return response()->json($respuesta);
    }

    public function guardarData(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');
        $data = json_decode(json_encode($data));
        if ((is_object($data)) && (auth()->user()->iCredId != $data->auditoria->credencial_id)) {
            return response()->json(['error' => true, 'msg' => 'Usuario NO AUTENTICADOoo' . '#' . auth()->user()->iCredId . '#$' . $data->auditoria->credencial_id . '$']);
        }
        $respuesta = null;
        switch ($req) {
            /********************************************gustavo*/
            case 'mantenimiento_persona1':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                    //    $respuesta = DB::select('EXEC inv.Sp_DEL_miembro ?', $data );
                    }
                    else {
                        $dataGuardar1 = [
                            $data->idTipoPersona,
                            $data->idTipoIdentidad,
                            $data->numeroDocumento,//codigo ruc o codigo de universidad extranjera

                            $data->apellidoPaterno,
                            $data->apellidoMaterno,
                            $data->nombres,
                            $data->sexo,
                            $data->fechaNacimiento??null,

                            $data->razonSocial??null,
                            $data->razonSocialCorto??null,
                            $data->razonSocialSigla??null,
                            $data->representanteLegal??null,
                            $data->idNacionalidad??null,


                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];

                        if ($data->iPersId) {

                            array_unshift($dataGuardar1, $data->iPersId);
                            // return response()->json($dataGuardar1);
                            $respuesta = DB::select('EXEC inv.Sp_UPD_personas ?,   ?, ?, ?,     ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?', $dataGuardar1 );

                        } else {
                             //return response()->json($dataGuardar1);
                            $respuesta = DB::select('EXEC con.Sp_INS_personas ?, ?, ?,    ?,?,? ,?, ?,  ?,?,?,?,      ?, ?, ?, ?', $dataGuardar1 );

                        }
                        switch ($data->opTipoPersona??null){

                            case 'entidad':
                                $dataMiembro = [
                                    ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),

                                    $data->idTipoEntidad,
                                    $data->idArea,

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];
                                if ($data->iEntidadId) {
                                    array_unshift($dataMiembro, $data->iEntidadId);
                                  //  $respuesta2 = DB::select('EXEC inv.Sp_UPD_miembro ?, ?, ?,     ?, ?, ?, ?', $dataMiembro );
                                } else {
                                 //    return response()->json($dataMiembro);
                                    $respuesta2 = DB::select('EXEC con.Sp_CONVENIOS_INS_Convenios_Insertar_Entidad_NacionalLocal ?,?,?,    ?, ?, ?, ?', $dataMiembro);

                                }
                                break;
                            case 'encargado':
                                $dataMiembro = [
                                    ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),


                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];
                                if ($data->iPersEncargadoId) {
                                    array_unshift($dataMiembro, $data->iPersEncargadoId);
                                    //  $respuesta2 = DB::select('EXEC inv.Sp_UPD_miembro ?, ?, ?,     ?, ?, ?, ?', $dataMiembro );
                                } else {
                                    //    return response()->json($dataMiembro);
                                    $respuesta2 = DB::select('EXEC con.Sp_INS_encargado ?,    ?, ?, ?, ?', $dataMiembro);

                                }
                                break;
                        }


                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_persona':
                // return response()->json($data);
                DB::beginTransaction();
                try{
                    if (!is_object($data)) {
                  //      $respuesta = DB::select('EXEC inv.Sp_DEL_miembro ?', $data );
                    }
                    else {
                        $dataGuardar1 = [
                            $data->idTipoPersona,
                            $data->idTipoIdentidad,
                            $data->numeroDocumento,

                            $data->apellidoPaterno,
                            $data->apellidoMaterno,
                            $data->nombres,
                            $data->sexo,
                            $data->fechaNacimiento??null,

                            $data->razonSocial??null,
                            $data->razonSocialCorto??null,
                            $data->razonSocialSigla??null,
                            $data->representanteLegal??null,
                            $data->idNacionalidad??null,


                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip??null,
                            null,
                        ];

                        if ($data->iPersId) {

                            array_unshift($dataGuardar1, $data->iPersId);
                            // return response()->json($dataGuardar1);
                           $respuesta = DB::select('EXEC inv.Sp_UPD_personas ?,   ?, ?, ?,     ?, ?, ?, ?, ?,   ?, ?, ?, ?, ?,   ?, ?, ?, ?', $dataGuardar1 );

                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC inv.Sp_INS_personas ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,      ?, ?, ?, ?', $dataGuardar1 );

                        }



                        switch ($data->opTipoPersona??null){

                            case 'encargado':
                                $dataMiembro = [
                                    ($respuesta[0]->iPersId ? $respuesta[0]->iPersId : $data->iPersId),

                                    auth()->user()->iCredId,
                                    null,
                                    $data->auditoria->ip??null,
                                    null,
                                ];
                                if ($data->iEncargadoId) {
                                    array_unshift($dataMiembro, $data->iEncargadoId);
                                //    $respuesta2 = DB::select('EXEC inv.Sp_UPD_miembro ?, ?, ?,     ?, ?, ?, ?', $dataMiembro );
                                } else {
                                    // return response()->json($dataGuardar);
                                    $respuesta2 = DB::select('EXEC con.Sp_INS_encargado ?,      ?, ?, ?, ?', $dataMiembro);

                                }

                                // $respuesta2 = DB::select('EXEC inv.Sp_INS_miembro ?, ?,     ?, ?, ?, ?', $dataMiembro);
                                break;

                        }


                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }


                    DB::commit();
                }
                catch(\Exception $e){
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_conveniosmil':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if ($data->idConvenio && $data->accionBd == 'borrar') {

                        $dataE = [
                            $data->idConvenio,

                            auth()->user()->iCredId,
                            null,
                            $auditoriaIp ?? null,
                            null,
                        ];
                        $respuesta = DB::select('EXEC con.DEL_convenios ?', $dataE );
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_resolucion_eliminar':
                //  return response()->json($data);
                DB::beginTransaction();
                try {
                    if ($data->idResolucion && $data->accionBd == 'borrar') {
                        $dataE = [
                            $data->idResolucion,
                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null
                        ];
                        $respuesta = DB::select('EXEC con.Sp_CONVENIOS_DEL_Convenios_Deshabilitar_Resolucion ?,    ?, ?, ?, ?', $dataE);
                    }
                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
            case 'mantenimiento_resolucion':
                // return response()->json($data);
                DB::beginTransaction();
                try {
                    if (!is_object($data)) {
             //         $respuesta = DB::select('EXEC con.Sp_CONVENIOS_DEL_Convenios_Deshabilitar_Resolucion ?,?,?,?, ?', $data);
                    } else {
                        $dataGuardar = [
                            $data->numero,
                            $data->descripcion,

                            auth()->user()->iCredId,
                            null,
                            $data->auditoria->ip ?? null,
                            null,
                        ];

                        if ($data->idResolucion) {
                            array_unshift($dataGuardar, $data->idResolucion);
                            //return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC con.Sp_CONVENIOS_UPD_Convenios_Actualizar_Resolucion ?,    ?, ?,        ?, ?, ?, ?', $dataGuardar);
                            // return 'editar';
                        } else {
                            // return response()->json($dataGuardar);
                            $respuesta = DB::select('EXEC con.Sp_CONVENIOS_INS_Convenios_Insertar_Resolucion ?,?,      ?, ?, ?, ?', $dataGuardar);
                            // return 'nuevo';
                        }
                    }

                    if ($respuesta[0]->iResult) {
                        $jsonResponse = [
                            'error' => false,
                            'msg' => 'Se guardo Correctamente',
                            'data' => $respuesta
                        ];
                    } else {
                        $jsonResponse = [
                            'error' => true,
                            'msg' => 'Error de Sistema. Comuníquelo al administrador',
                            'data' => $respuesta
                        ];
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    $jsonResponse = $this->returnError($e);
                    DB::rollback();
                }
                // return response()->json($data);
                break;
             //   return response()->json($jsonResponse);
        }

        return response()->json($jsonResponse);
    }






    function makeNested($source)
    {

        $newData = collect();
        foreach ($source as &$or) {
            if (is_null($or->iDepenDependeId)) {
                $newData->add($or);
            } else {
                $pid = $or->iDepenDependeId;
                $dPadre = $source->where('iDepenId', $pid)->first();
                if ($dPadre) {
                    if (!isset($dPadre->hijos)) {
                        $source->where('iDepenId', $pid)->first()->hijos = collect();
                    }
                    $source->where('iDepenId', $pid)->first()->hijos->add($or);
                }

            }
        }

        return $newData;
        $nested = array();

        foreach ($source as &$s) {

            if (is_null($s->iDepenDependeId)) {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
            } else {
                $pid = $s->iDepenDependeId;
                if (isset($source[$pid])) {
                    // If the parent ID exists in the source array
                    // we add it to the 'children' array of the parent after initializing it.

                    if (!isset($source[$pid]['children'])) {
                        $source[$pid]['children'] = array();
                    }

                    $source[$pid]['children'][] = &$s;
                }
            }
        }
        return $nested;
    }

    private function returnError($e)
    {
        $msgResuelto = '';
        if (isset($e->errorInfo)) {
            $msgResuelto = substr($e->errorInfo[2], 54); //'No se guardaron datos SQL, ERROR: ' . $e->getMessage(),
        }

        $jsonResponse = [
            'error' => true,
            'msg' => $msgResuelto,
            //'mensaje' => substr($e->errorInfo[2], 54), 'code' => $e->getCode(),
            'errorLaravel' => $e->getMessage(),
            'data' => null
        ];
        return $jsonResponse;
    }

    public function genPDF(Request $request)
    {

    }

    public function generarPDF(Request $request)
    {
        $req = $request->get('tipo');
        $data = $request->get('data');

        $data = json_decode(json_encode($data));

        $pdf = new PdfCreator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        header("Access-Control-Allow-Origin: *");

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Antonio Salas');
        $pdf->SetTitle('Reportes UNAM');
        $pdf->SetSubject('Tramites - UNAM');
        $pdf->SetKeywords('UNAM, Moquegua, Ilo, EPISI');

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 5, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        $ptserif_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/PTSerif/PTSerif-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_bold = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Bold.ttf'), 'TrueTypeUnicode', '', 96);
        $roboto_regular = TCPDF_FONTS::addTTFfont(public_path('pdf_src/fuentes/Roboto/Roboto-Regular.ttf'), 'TrueTypeUnicode', '', 96);


        $dataTramite = null;
        switch ($req) {
            case 'por_recepcionar':
                $pdf->dependenciaPadre = "SECRETARÍA GENERAL";
                $setDep = true;
                //$pdf->dependencia = ;

                $dependenciasPendientes = DB::select('EXEC tram.Sp_SEL_dependencias_tramites_pendientes_por_recepcionarXiDepenEmisorId ?', $data);
                // dd($dependenciasPendientes);

                $htmlStyles = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    th {
        background-color: #a9a9a9;
        font-weight: bold;
        text-align: center;
    }
    td {
        font-size: 85%;
        height: 70px;
    }

    .negrita {
        font-weight: bold;
    }
    .centrado {
        text-align: center;
    }
</style>
EOF;

                $anchoColumnas = [
                    'REG' => 40,
                    'FEC' => 70,
                    'TDOC' => 155,
                    'TDOC_T' => 75,
                    'TDOC_N' => 80,
                    'REM' => 120,
                    'FOL' => 30,
                    'ASU' => 180,
                    'PROV_DOC' => 70,
                    'DEST' => 70,
                    'FEC_SAL' => 70,
                    'FIRM' => 100,
                    'OBS' => 50,
                ];


                $htmlListaTramites['header'] = '<thead>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REG'] . '">N° REG</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FEC'] . '">FEC. RECEPCION</th>';
                $htmlListaTramites['header'] .= '<th colspan="2" width="' . $anchoColumnas['TDOC'] . '">DOCUMENTO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['REM'] . '">REMITENTE</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FOL'] . '">FOL.</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['ASU'] . '">ASUNTO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['PROV_DOC'] . '">PROVEIDO <br> DOC. SALIDA </th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['DEST'] . '">DESTINO</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FEC_SAL'] . '">FEC. SALIDA</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['FIRM'] . '">FIRMA</th>';
                $htmlListaTramites['header'] .= '<th rowspan="2" width="' . $anchoColumnas['OBS'] . '">OBS</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '<tr>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_T'] . '">TIPO</th>';
                $htmlListaTramites['header'] .= '<th width="' . $anchoColumnas['TDOC_N'] . '">NUMERO</th>';
                $htmlListaTramites['header'] .= '</tr>';
                $htmlListaTramites['header'] .= '</thead>';


                $htmlListaTramites['body'] = '<tbody>';

                foreach ($dependenciasPendientes as $dep) {
                    /*
                     *
  +"iDepenReceptorId": "3"
  +"cDepenNombre": "ORGANO DE CONTROL INSTITUCIONAL"
  +"iCantidad_Tramites": "1"
                     */

                    if (!is_null($dep->iDepenReceptorId)) {
                        $data[1] = $dep->iDepenReceptorId;
                        $tramDepen = DB::select('EXEC tram.Sp_SEL_tramites_pendientes_por_recepcionarXiDepenEmisorIdXiDepenReceptorId ?, ?', $data);

                        foreach ($tramDepen as $tram) {
                            // dd($tram);
                            if ($setDep) {
                                $pdf->dependencia = $tram->cDepenEmisorNombre;
                                $setDep = false;
                            }

                            $htmlListaTramites['body'] .= '<tr>';
                            $htmlListaTramites['body'] .= '<td valign="middle" class="negrita centrado" width="' . $anchoColumnas['REG'] . '">' . $tram->iTramNumRegistro . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC'] . '">' . Carbon::parse($tram->dtTramFechaDocumento)->format('d/m/Y H:i') . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_T'] . '">' . $tram->cTipoDocDescripcion . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['TDOC_N'] . '">' . preg_replace('/(.*-\d{4})-(.*)/', '$1<br>$2', str_replace($tram->cTipoDocDescripcion . ' ', '', $tram->cTramDocumentoTramite)) . '</td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['REM'] . '">' . $tram->cAbrev_Emisor . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FOL'] . '">' . $tram->iTramFolios . '</td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['ASU'] . '">' . $tram->cTramAsunto . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['PROV_DOC'] . '">' . $tram->cTramMovObsEnvio . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['DEST'] . '">' . $tram->cDepenReceptorAbrev . '</td>';
                            $htmlListaTramites['body'] .= '<td class="centrado" width="' . $anchoColumnas['FEC_SAL'] . '">' . Carbon::parse($tram->dtTramMovFechaHoraEnvio)->format('d/m/Y') . '</td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['FIRM'] . '"></td>';
                            $htmlListaTramites['body'] .= '<td width="' . $anchoColumnas['OBS'] . '"></td>';
                            $htmlListaTramites['body'] .= '</tr>';

                        }
                    }


                }
                $htmlListaTramites['body'] .= '</tbody>';

                $pdf->SetFont($roboto_regular, '', 9, '', 'default', true);
                $htmlPrintTable = $htmlStyles;
                $htmlPrintTable .= '<table cellspacing="0" cellpadding="3" border="1">' . $htmlListaTramites['header'] . $htmlListaTramites['body'] . '</table>';


                //dd($dependenciasPendientes);

                //


                $pdf->AddPage('L', 'A4');
                $htmlFooter = '<p style="font-size: 11px; text-align: justify;">Reporte generado por el Módulo de Tramite Documentario.<br>';
                $htmlFooter .= 'SIGEUN</p>';

                $pdf->addHtmlFooter = $htmlFooter;
                $pdf->writeHTML($htmlPrintTable, true, false, false, true, '');

                break;
        }


        // print colored table
        // $pdf->ColoredTable($header, $data,$funcion);
        ob_end_clean();
        // ---------------------------------------------------------
        $pdf->Output('unam-SIGEUN.pdf', 'I');
        //return $pdf;

    }


    public function prueba()
    {
        $url = "https://ws5.pide.gob.pe/Rest/Reniec/Consultar?nuDniConsulta=43177406&nuDniUsuario=41395590&nuRucUsuario=20449347448&password=41395590";
        $result = file_get_contents($url, false);
        dd($result);
    }

    public function actualizarDatosReniec($FN)
    {
        return 'ffffff';

    }
}
