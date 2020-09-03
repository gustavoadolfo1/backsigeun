<?php

namespace App\Http\Controllers\CCTIC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\cctic\GradoAcademico;
use App\Http\Resources\CCTIC\GradoAcademicoResource;

class GradosAcademicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           $programasAcademicos = GradoAcademico::all();
           return GradoAcademicoResource::collection($programasAcademicos);
    }
}
