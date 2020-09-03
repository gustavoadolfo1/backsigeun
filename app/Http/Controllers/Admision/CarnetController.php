<?php

namespace App\Http\Controllers\Admision;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ClasesLibres\FPDF\FpdfCustom;

class CarnetController extends Controller
{
    public function getCriterios()
    {
        $modalidades = \DB::table('ura.tipos_modalidades as m')->whereIn('iTipoModalidadId', [1, 2])->get();

        $procesoAdmision = \DB::table('adm.proceso_admision')->where('bProcAdmEst', 1)->first();

        // $carreras = \DB::table('adm.carreras_filiales_proceso_admision as cf')
        //                 ->select('c.iCarreraId', 'cCarreraDsc', 'f.iFilId', 'cFilDescripcion')
        //                 ->join('ura.carreras as c', 'cf.iCarreraId', '=', 'c.iCarreraId')
        //                 ->join('grl.filiales as f', 'cf.iFilId', '=', 'f.iFilId')
        //                 ->where('iProgramasAcadId', 1)->where('iProcAdmId', $procesoAdmision->iProcAdmId)
        //                 ->orderBy('cCarreraDsc')->get();

        $carreras = \DB::table('ura.carreras')->where('iProgramasAcadId', 1)->get();

        return response()->json( [ 'modalidades' => $modalidades, 'carreras' => $carreras ] );
    }

    public function getListado($modalidad, $carrera, $filial)
    {
        $postulantes = \DB::select('exec [adm].[Sp_SEL_inscritosCarnet] ?, ?, ?', [$modalidad, $carrera, $filial] );

        return response()->json( $postulantes );
    }


    public function generarCarnets($modalidad, $carrera, $filial)
    {
        $postulantes = \DB::select('exec [adm].[Sp_SEL_inscritosCarnet] ?, ?, ?', [$modalidad, $carrera, $filial] );

        // dd($postulantes);

        if (count($postulantes) < 1) {
            return response()->json('No hay datos');
        }
        
        return $this->generarPDF($postulantes);
    }

    public function imprimirUnCarnet($inscripcionId)
    {
        # code...[adm].[Sp_SEL_inscritosCarnetXiInscripId]

        $postulantes = \DB::select('exec [adm].[Sp_SEL_inscritosCarnetXiInscripId] ' . $inscripcionId );
        
        if (count($postulantes) < 1) {
            return response()->json('No hay datos');
        }
        
        return $this->generarPDF($postulantes);
    }

    public function imprimirCarnetsSeleccionados($ids)
    {
        $ids = explode('-', $ids);
        
        foreach ($ids as $i => $id) {
            $array[]['iInscripIds'] = $id;
        }

        $postulantes = \DB::select("exec [adm].[Sp_SEL_inscritosCarnetXjsoniInscripId] '" . json_encode($array) . "'");
        
        if (count($postulantes) < 1) {
            return response()->json('No hay datos');
        }
        
        return $this->generarPDF($postulantes);
    }

    public function toggleEntrga($inscripId, $bEntrega)
    {
        $queryResult = \DB::select('exec [adm].[Sp_UPD_entregaCarnet] ?, ?, ?', [ $inscripId, $bEntrega, auth()->user()->cCredUsuario ] );

        return response()->json($queryResult);
    }

    public function updCheckImpresionMasivo($modalidad, $carrera, $filial)
    {
        try {
            $queryResult = \DB::select('exec [adm].[Sp_UPD_ImprimioCarnet] ?, ?, ?, ?', [$modalidad, $carrera, $filial, auth()->user()->cCredUsuario] );

            $response = ['validated' => true, 'message' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function updCheckImpresionUnitario($inscripcionId)
    {
        try {
            $queryResult = \DB::select('exec [adm].[Sp_UPD_ImprimioCarnetXiInscripId ] ?, ?', [$inscripcionId, auth()->user()->cCredUsuario] );

            if ($queryResult[0]->iResult == 1) {
                $message = 'Datos actualizados correctamente.';
            } else {
                $message = 'No se pudo actualizar, es posible que el carnet no cuente con una Foto.';
            }

            $response = ['validated' => true, 'message' => $message, 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function updCheckImpresionSelects($ids)
    {
        $ids = explode('-', $ids);
        
        foreach ($ids as $i => $id) {
            $array[]['iInscripIds'] = $id;
        }

        try {
            $queryResult = \DB::select("exec [adm].[Sp_UPD_ImprimioCarnetXjsoniInscripId] '" . json_encode($array) . "', '" . auth()->user()->cCredUsuario . "' ");

            $response = ['validated' => true, 'message' => '', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
        } catch (\Exception $e) {
            $response = ['validated' => true, 'message' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $response, $codeResponse );
    }

    public function generarPDF($data)
    {
        $pdf = new FpdfCustom();
            
        $pdf->SetFont('Arial','',10);
        $postulantesChunked = array_chunk($data, 8);
        
        foreach ($postulantesChunked as $page) {
            $pdf->AddPage();

            $x = 10;
            $barCodeX = 57;
            $fotoY = 20;

            $pdf->Ln(9);

            $pagePostulantesChunked = array_chunk($page, 2);

            foreach ($pagePostulantesChunked as $i => $lineaPostulantes) {

                $countLineaPostulantes = count($lineaPostulantes);

                $imageY = 10;
                $fotoX = 70;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Image('carnet.jpg',$imageY, $x,'86','55','jpg');
                    
                    $foto1 = "storage/adm/fotos/" . ($lineaPostulantes[$i]->cFotografia ?? '0.jpg');
                    if (!file_exists($foto1)){	$foto1="storage/adm/fotos/0.jpg";}

                    $pdf->Image($foto1, $fotoX, $fotoY,'22','26','JPG');
                    
                    $fotoX += 100;
                    $imageY += 100;
                }
                $fotoY += 65;

                // $pdf->Image('carnet.jpg','10', $x,'86','55','jpg');
                // $pdf->Image('carnet.jpg','110', $x,'86','55','jpg');

                // $foto1 = "storage/adm/fotos/" . ($lineaPostulantes[0]->cFotografia ?? '0.jpg');
                // // if (!file_exists($foto1)){ $foto1 = "storage/adm/fotos/0.jpg"; }
                // $foto2 = "storage/adm/fotos/" . ($lineaPostulantes[1]->cFotografia ?? '0.jpg');
                // // if (!file_exists($foto2)){ $foto2 = "storage/adm/fotos/0.jpg"; }

                // $pdf->Image($foto1, 70, $fotoY,'22','26','JPG');
                // $pdf->Image($foto2, 170, $fotoY,'22','26','JPG');

                // $fotoY += 65;

                $pdf->SetFont('Arial','B',11);

                $textX = 40;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Cell($textX, 5, '', '', '', 'L', '');
                    $pdf->Cell(30, 5, $lineaPostulantes[$i]->cCodPostulante, '', '', 'L', '');

                    $textX += 30;
                }
                $pdf->Ln(8);
                
                $pdf->SetFont('Arial','B',10);
                $textX = 13;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Cell($textX, 5, '', '', '', 'L', '');
                    $pdf->Cell(60, 5, utf8_decode($lineaPostulantes[$i]->cApellidos), '', '', 'L', '');

                    $textX += 27;
                }

                // 
                // $pdf->Cell(13,5,'','','','L','');
                // $pdf->Cell(60,5,$lineaPostulantes[0]->cApellidos,'','','L','');
                // $pdf->Cell(40,5,'','','','L','');
                // $pdf->Cell(60,5,$lineaPostulantes[1]->cApellidos,'','','L','');

                $pdf->Ln(5);

                $textX = 13;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Cell($textX, 5, '', '', '', 'L', '');
                    $pdf->Cell(60, 5, utf8_decode($lineaPostulantes[$i]->cPersNombre), '', '', 'L', '');

                    $textX += 27;
                }

                // $pdf->Cell(13,5,'','','','L','');
                // $pdf->Cell(60,5, $lineaPostulantes[0]->cPersNombre,'','','L','');
                // $pdf->Cell(40,5,'','','','L','');
                // $pdf->Cell(60,5, $lineaPostulantes[1]->cPersNombre,'','','L','');

                $pdf->Ln(13);

                $pdf->SetFont('Arial','B',6);
                $textX = 22;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Cell($textX, 5, 'Carrera:', '', '', 'R', '');
                    $pdf->Cell(60,5, trim($lineaPostulantes[$i]->cCarrera) . " (" . $lineaPostulantes[$i]->cFilSigla . ")" , 0,'','L','');

                    $textX += 18;
                }

                // $pdf->SetFont('Arial','B',6);
                // $pdf->Cell(22,5,'Carrera:', 0,'','R','');
                // $pdf->Cell(60,5, trim($lineaPostulantes[0]->cCarrera) . " (" . $lineaPostulantes[0]->cFilSigla . ")" , 0,'','L','');
                // $pdf->Cell(40,5,'Carrera:','','','R','');
                // $pdf->Cell(60,5, trim($lineaPostulantes[1]->cCarrera) . " (" . $lineaPostulantes[1]->cFilSigla . ")" ,'','','L','');

                $pdf->Ln(3);

                $pdf->SetFont('Arial','B',6);
                $textX = 22;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Cell($textX, 5, 'Modalidad:', '', '', 'R', '');
                    $pdf->Cell(60,5, utf8_decode(trim($lineaPostulantes[$i]->cModalidad)), 0,'','L','');

                    $textX += 18;
                }
                // $pdf->Cell(22,5,'Modalidad:','','','R','');
                // $pdf->Cell(60,5, utf8_decode(trim($lineaPostulantes[0]->cModalidad)),'','','L','');
                // $pdf->Cell(40,5,'Modalidad:','','','R','');
                // $pdf->Cell(60,5, utf8_decode(trim($lineaPostulantes[1]->cModalidad)),'','','L','');

                $pdf->Ln(3);

                $textX = 22;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Cell($textX, 5, 'Lugar:', '', '', 'R', '');
                    $pdf->Cell(60,5, utf8_decode($lineaPostulantes[$i]->cLugarExamen), 0,'','L','');

                    $textX += 18;
                }

                // $pdf->Cell(22,5,'Lugar:','','','R','');
                // $pdf->Cell(60,5, $lineaPostulantes[0]->cLugarExamen,'','','L','');
                // $pdf->Cell(40,5,'Lugar:','','','R','');
                // $pdf->Cell(60,5, $lineaPostulantes[1]->cLugarExamen,'','','L','');

                $x += 65;
                $pdf->Ln(33);

                $textX = 25;
                for ($i=0; $i < $countLineaPostulantes; $i++) { 
                    $pdf->Code128($textX,$barCodeX, $lineaPostulantes[$i]->cPersDocumento,35,7);

                    $textX += 100;
                }

                // $pdf->Code128(25,$barCodeX, $lineaPostulantes[0]->cCodPostulante,35,7);
                // $pdf->Code128(125,$barCodeX, $lineaPostulantes[0]->cCodPostulante,35,7);

                $barCodeX += 65;
            }
        }
        return $pdf->Output('S');
    }
}
