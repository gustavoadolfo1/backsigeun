<?php

namespace App\Exports;

use App\Estudiantes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EstudiantesExport
{
    //use Exportable;

    public function construct_(string $codigo)
    {
        $this->cEstudCodUniv = $codigo;
    }

    public function query()
    {
        return Estudiantes::query()->where('cEstudCodUniv', $this->cEstudCodUniv);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
   /*  public function collection()
    {
        return Estudiantes::all();
    } */

    protected $estudiantes;
    public $view;

    public function __construct($estudiantes = null)
    {
        $this->estudiantes = $estudiantes;
        //$this->view = $view;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->estudiantes ?: Estudiantes::all();
    }

     public function headings(): array
    {
        return [
            'Código Estudiante',
            'Apellidos',
            'Nombres',
        ];
    }
    public function view(): View
    {
        return view($this->view,
            $this->estudiantes ?: Estudiantes::all()
        );
    }
   /*  public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        //$drawing->setPath(public_path('/img/logo.jpg'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('B3');

        return $drawing;
    } */
}
