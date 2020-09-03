<?php
namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class ProgramasAcademicosController extends Controller
{
    // get data
    public function index()
    {
        try {
            $planes = DB::select('[acad].[Sp_SEL_programas_academicos]');
            $response = ['validated' => true, 'data' => $planes];
            $responseCode = 200;
        } catch (\Exception $e) {
            $response = ['validated' => false, 'data' => [], 'message' => $e->getMessage()];
            $responseCode = 500;
        }

        return response()->json($response, $responseCode);
    }
}
