<?php


namespace App\ClasesLibres\Generales;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UtilControladores
{
    public static function getAuditoria(){
        return [
            auth()->user()->iCredId,
            null,
            request()->getClientIp(),
            null,
        ];
    }

    public static function mantenimiento( $dataObj, $id, $proc) {
/*
        $dAuditoria = [
            auth()->user()->iCredId,
            null,
            request()->getClientIp(),
            null,
        ];
        */
        $dAuditoria = self::getAuditoria();

        $arrDataIns = array_merge($proc['ins']['params'], $dAuditoria);
        $arrDataUpd = array_merge($proc['upd']['params'], $dAuditoria);

//        echo $proc['ins']['proc'] . ' ' . implode(',', array_fill(0, count($arrDataIns), '?'));
//        echo "\n";
//        echo implode(', ', $arrDataIns);
//        echo "\n";
//
//        echo $proc['upd']['proc'] . ' ' . implode(',', array_fill(0, count($arrDataUpd), '?'));
//        echo "\n";
//        echo implode(', ', $arrDataUpd);
//        echo "\n";
//        DB::enableQueryLog();

        if (!is_object($dataObj)) {
            $res = DB::select($proc['del']['proc'] . ' ?', $proc['del']['params'] );
        } else {
            if (isset($dataObj->{$id}) && !in_array($dataObj->{$id}, [null, -1])) {
                $res = DB::select($proc['upd']['proc'] . ' ' . implode(',', array_fill(0, count( $arrDataUpd ), '?')) , $arrDataUpd );
            } else {
                $res = DB::select($proc['ins']['proc'] . ' ' . implode(',', array_fill(0, count($arrDataIns), '?')) , $arrDataIns );
            }
        }
//        print_r(DB::getQueryLog()); // Show results of log
        return collect($res);
    }

    /**
     * Acciones basicas de MANTENIMIENTO usando <b>request() de Laravel</b>
     *
     * @param $id string        ID Principal (o identificador de la tabla
     * @param $procIns array    Detalle del INSERT [<b>Proc</b>, <b>dataArray</b>]
     * @param $procUpd array    Detalle del UPDATE [<b>Proc</b>, <b>dataArray</b>]
     * @param $procDel string    Proc del DELETE. Como id para eliminar se usa request()->id.
     *
     * @return \Illuminate\Support\Collection coleccion de datos afectados;
     */
    public static function mantenimientoReq( $id, $procIns, $procUpd, $procDel): \Illuminate\Support\Collection {

        $dAuditoria = self::getAuditoria();

        $arrDataIns = array_merge($procIns[1], $dAuditoria);
        $arrDataUpd = array_merge($procUpd[1], $dAuditoria);
        $arrDataDel = array_merge([request()->id], $dAuditoria);

        if (isset(request()->id)){
            $res = DB::select($procDel . ' ' . implode(',', array_fill(0, count( $arrDataDel ), '?')) , $arrDataDel );
        }
        else {
            if (isset(request()->{$id}) && !in_array(request()->{$id}, [null, -1])) {
                $res = DB::select($procUpd[0] . ' ' . implode(',', array_fill(0, count( $arrDataUpd ), '?')) , $arrDataUpd );
            } else {
                $res = DB::select($procIns[0] . ' ' . implode(',', array_fill(0, count($arrDataIns), '?')) , $arrDataIns );
            }
        }
        return collect($res);
    }

    public static function consultasSimples($procConId, $procSinId) {
        // abort(500, json_encode(request()->toArray()));
        if (isset(request()->id)){
            $respuesta = DB::select($procConId, [request()->id]);
        }
        else {
            $respuesta = DB::select($procSinId);
        }
        return $respuesta;
    }

    public static function respuestasSimple($tipo, $respuesta, $multiple = false){

        if ($multiple) {
            $todoOK = true;
            foreach ($respuesta as $rpt) {
                // abort(503, 'Error: Error de Procedimiento ('.$tipo.') '. json_encode($rpt[0]->iResult));
                if ($todoOK && $rpt[0]->iResult == 0){
                    $todoOK = false;
                }
            }
            if ($todoOK) {
                $jsonResponse = [
                    'error' => false,
                    'msg' => 'Se guardo Correctamente',
                    'data' => $respuesta
                ];
            }
            else {
                $jsonResponse = [
                    'error' => true,
                    'msg' => 'Existen algunos errores',
                    'data' => $respuesta,
                ];
            }
        }
        else {
            if (isset($respuesta[0]->iResult)) {
                if ($respuesta[0]->iResult > 0) {
                    $jsonResponse = [
                        'error' => false,
                        'msg' => 'Se guardo Correctamente',
                        'data' => $respuesta
                    ];
                }
                else {
                    abort(503, 'Error: Error de Procedimiento ('.$tipo.') '. json_encode($respuesta));
                }
            } else {
                abort(503, 'Error: Error de Sistema ('.$tipo.')');
            }
        }



        return $jsonResponse;
    }


    /**
     * Mueve un array de ubicaciones temporales (haciendo uso del componente global-file-upload)
     * a una carpeta final dentro de public/storage
     *
     * <b>Si todo esta bien retorna un array convertido a string en formato JSON  de las nuevas ubicaciones para guardar en DB</b>
     *
     * @param array  $arrayArchivos PATH de archivos temporales (retornados del componente global-file-upload)
     * @param string $carpetaDestino Carpeta final en el que se guardara (debe terminar con "/")
     *
     * @return array|false|string
     *
     */
    public static function moverArchivo(array $arrayArchivos, string $carpetaDestino){
        $procAdjunto = null;
        try {
            foreach ($arrayArchivos as $file){
                $pos = strpos($file, $carpetaDestino);
                if ($pos === false) {
                    $file = str_replace('storage/', '', $file);
                    $nuevaUbicacion = $carpetaDestino . basename($file);
                    Storage::disk('public')->move($file, $nuevaUbicacion);
                }
                else {
                    $nuevaUbicacion = $file;
                }

                $procAdjunto[] = $nuevaUbicacion;
                // abort(503, $arch);
            }
            return json_encode($procAdjunto);
        } catch (Exception $e) {
            return 'NADA';
            abort(500, $e->getMessage());
            //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        }
        return $procAdjunto;

    }
}
