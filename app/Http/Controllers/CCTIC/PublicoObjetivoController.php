<?php

namespace App\Http\Controllers\CCTIC;


use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Model\cctic\PublicoObjetivo;
use App\UraCarrera;
use Illuminate\Support\Facades\DB;



class PublicoObjetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $tiposPublico = PublicoObjetivo::all();
        // $tiposPublico = PublicoObjetivo::query()->get();
        // return response()->json($tiposPublico);
        try {
            $tiposPublico = PublicoObjetivo::query()->get(['iPublicoObjetivoId', 'cPublicoObjetivoDsc', 'cCode']);
            $response = ['validated' => true, 'data' => $tiposPublico];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }

    public function professionalCareers() {
        try {
            $careers = UraCarrera::all()->filter(function ($career) {
                return $career->iProgramasAcadId == 1;
            });

            $response = ['validated' => true, 'data' => $careers];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }
        return response()->json($response, $responseCode);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function byPublicacionId($id)
    {

        try {
            $publico = DB::select('exec [acad].SP_SEL_publico_objetivoByPublicacionId @iPublicacinoId = ?' [$id]);

            if (count($publico) == 0) {
                return response()->json(['validated' => false, 'message' => 'No existe publico obetivo para esta publicacion'], 404);
            }

            $publico = $publico[0];

            $response = ['validated' => true, 'message' => 'Datos obtenidos correctamente', 'data' => $publico];
            $responsecode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'message' => 'Ocurrio un error al obtener el publico objetivo', 'data' => [], 'error' => $e->getMessage()];
            $responsecode = 500;
        }

        return response()->json($response, $responsecode);
    }
}
