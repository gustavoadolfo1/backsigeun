<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class UploadController extends Controller
{
    protected $manager;

    /*  public function __construct()
    {
        parent::__construct();

        $this->manager = app('uploader');
    } */

    /**
     * Response the folder info.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->manager->folderInfo($request->get('folder'));

        return $this->response->json(['data' => $data]);
    }

    /**
     * Upload the file for file manager.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadForManager(Request $request)
    {
        $file = $request->file('file');

        $fileName = $request->get('name')
            ? $request->get('name') . '.' . explode('/', $file->getClientMimeType())[1]
            : $file->getClientOriginalName();

        $path = Str::finish($request->get('folder'), '/');

        if ($this->manager->checkFile($path . $fileName)) {
            return $this->response->withBadRequest('This File exists.');
        }

        $result = $this->manager->store($file, $path, $fileName);

        return $this->response->json($result);
    }

    /**
     * Generic file upload method.
     *
     * @param ImageRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fileUpload(ImageRequest $request)
    {
        $strategy = $request->get('strategy', 'file');

        if (!$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'error' => 'no file found.',
            ]);
        }

        $path = $strategy . '/' . date('Y') . '/' . date('m') . '/' . date('d');

        $result = $this->manager->store($request->file('image'), $path);

        return $this->response->json($result);
    }

    /**
     * Create the folder.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFolder(Request $request)
    {
        $folder = $request->get('folder');

        $data = $this->manager->createFolder($folder);

        return $this->response->json(['data' => $data]);
    }

    /**
     * Delete the folder.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');

        $folder = $request->get('folder') . '/' . $del_folder;

        $data = $this->manager->deleteFolder($folder);

        if (!$data) {
            return $this->response->withForbidden('The directory must be empty to delete it.');
        }

        return $this->response->json(['data' => $data]);
    }

    /**
     * Delete the file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile(Request $request)
    {
        $path = $request->get('path');

        $data = $this->manager->deleteFile($path);

        return $this->response->json(['data' => $data]);
    }
    public function b64Ext($base64_image, $full = null)
    {
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"
        preg_match("/^data:image\/(.*);base64/i", $base64_image, $img_extension);
        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        return ($full) ?  $img_extension[0] : $img_extension[1];
    }

    public function b64Image($base64_image)
    {
        // Obtener el String base-64 de los datos
        $image_service_str = substr($base64_image, strpos($base64_image, ",") + 1);
        // Decodificar ese string y devolver los datos de la imagen
        $image = base64_decode($image_service_str);
        // Retornamos el string decodificado
        return $image;
    }

    public function image(Request $request)
    {

        if ($request->hasFile('image')) {
            $filename = "tmp" . '.jpg';
            $filePath = 'biblioteca/portadas/';
            $archivo = $request->file('image');
            $archivo->storePubliclyAs($filePath, $filename);
            //Storage::disk('base64')->put($filePath, $filename);

            /* $resultData = DB::table('bib.bienes')
                ->where('iGrupoBienesId', $request->dni)
                ->update(['cPortada' => $filename]); */

            return response()->json(['file' => $filePath . $filename]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'no file found.',
            ]);
        }
        //return response()->json($filePath);
        //}
    }

    public function imageB64($filename)
    {
        //Obtener la imagen del disco creado anteriormente de acuerdo al nombre de
        // la imagen solicitada
        $file = Storage::disk('base64')->get($filename);
        // Retornar una respuesta de tipo 200 con el archivo de la Imagen
        return new Response($file, 200);
    }
}
