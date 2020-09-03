<?php

namespace App\Exports\Tesoreria;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Support\Facades\DB;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
class ExcelExport implements FromArray,WithHeadings,ShouldAutoSize,WithEvents,WithCustomStartCell
{
    private $nameReport;
    private $ids;
    private $titles;
    private $position;
    private $fields;
    public function __construct($nameReport,$ids){
        $this->fechaReporte = date('Y-m-d');
        $this->nameReport = $nameReport;
        $this->ids = $ids;
 
        $i=1;
        foreach (range('A', 'Z') as $char) {
            $this->fields[$i] = $char;
            $i++;
        }
        $this->cabecera($this->nameReport,$this->fields);
    }

    public function cabecera($nameReport,$fields){
        switch($nameReport){
            case 'comprobantePago_ReporteC':
                $this->nombreReporte = "REPORTE DE COMPROBANTES DE PAGO Y DOCUMENTO COMPROMISO";
                $this->titles = ['N°','AÑO','N°SIAF','N°CERTIF.','F.F','T.R','COD.','COMPROMISO','NUMERO','SEC.FUNC.','META','CLASIF','NOMBRE CLASIFICADOR','MONTO','COD.','DOCUMENTO','NUMERO','FECHA','RUC','PROVEEDOR','T.G.','NOTA'];
                $this->position = $this->fields[count($this->titles)];
            break;             
            case 'tablasBase_CuentasDetraccion':
                $this->nombreReporte = "CUENTAS DE DETRACCIÓN";
                $this->titles = ['N°','IDPERSONA','RUC','CTA.CTE.DETRACCIÓN','CTA.CTE.INTERBANCARIA','NOMBRE','DIRECCIÓN'];
                $this->position = $this->fields[count($this->titles)];
            break;             
            case 'ingresosConsolidados_Registro':
                $this->nombreReporte = "INGRESOS CONSOLIDADOS";
                $this->titles = ['N°','COD','NÚMERO RECIBO','FECHA RECIBO','TIPO','FILIAL  ','N° PAPELETA DEPÓSITO','MONTO  ','OBS ','AÑO SIAF','EXPEDIENTE'];
                $this->position = $this->fields[count($this->titles)];
            break; 
            case 'depositoAbono_CuentasDA_Registro':
                $this->nombreReporte = "CUENTAS DE ABONO DE LOS TRABAJADORES";
                $this->titles = ['N°','N°DOC','TRABAJADOR','BANCO','N° CTA.','TIPO CTA.','MOTIVO  '];
                $this->position = $this->fields[count($this->titles)];
            break;           
            default:
                $this->nombreReporte = "";
                $this->titles=[];
                $this->position='A';
            break;
            }        
    }

    public function array():array
    {
        $data=[];
        $i = 0;
        switch($this->nameReport)
        {
            case 'comprobantePago_ReporteC':
                $parameters = [1,$this->ids];
                $dataResult = DB::select('EXEC tre.Sp_SEL_consulta_expedientes_girados_documentocompromisoXcCodigoCadena ?,?',$parameters);
                foreach ($dataResult as $list) {
                    $data[] = array(
                        ++$i,
                        $list->Ano_eje,$list->Expediente,$list->cCertificado,$list->Fuente_financ,$list->Tipo_recurso,$list->cCod_doc_Compromiso,$list->cClase_Compromiso,$list->cNum_doc_Compromiso,$list->Sec_func,$list->cNombre_Meta,$list->Clasificador,$list->Nombre_Clasificador,$list->Monto,$list->Cod_doc_Girado,$list->Nombre_doc_Girado,$list->Numero_doc_Girado,$list->fecha_doc_Girado,$list->Ruc,$list->Proveedor_Nombre,$list->Tipo_giro,$list->Notas                    
                    );
                }
            break;
            case 'tablasBase_CuentasDetraccion':
                $parameters = [$this->ids];
                $dataResult = DB::select('EXEC Siaf.Sp_Siaf_SEL_PersonaXcCodigoCadena ?',$parameters);
                foreach ($dataResult as $list) {
                    $data[] = array(
                        ++$i,$list->iIdPersona,$list->cRuc,$list->cCtaCteDetraccion,$list->Cci,$list->cNombre,$list->cDireccion,                        
                    );
                }
            break; 
            case 'ingresosConsolidados_Registro':
                $parameters = [$this->ids];
                $dataResult = DB::select('EXEC tre.Sp_SEL_recibosXcCodigoCadena ?',$parameters);
                foreach ($dataResult as $list) {
                    $data[] = array(
                        ++$i,$list->iRecId,$list->dRecFecha,$list->iRecNumero,$list->cTipoDocDescripcion,$list->cFilDescripcion,$list->cRecNroDoc,$list->nRecMonto,$list->cRecObs,$list->Ano_eje,$list->Expediente,
                    );
                }
            break;  
            case 'depositoAbono_CuentasDA_Registro':
                $parameters = [$this->ids];
                $dataResult = DB::select('EXEC tre.Sp_SEL_personas_cuentasXcCodigoCadena ?',$parameters);
                foreach ($dataResult as $list) {
                    $data[] = array(
                        ++$i,$list->cPersonaDocumento,$list->cPersonaNombre,$list->cBancoNombre,$list->cPersCuentaNumero,$list->cTipoCuentaNombre,$list->cMotPlanNombre
                    );
                }
            break;  
            default:
                $data=[array('no se encontro modelo de reporte en excel, verifique en app/Export/*****.php')];
            break;

        }
        return $data;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return $this->titles;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A4:'.$this->position.'4'; // All headers
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],                        
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'dee2e6'],
                    ],
                ];            
                $event->sheet->getDelegate()->setCellValue('A1', 'ENTIDAD:')->getStyle('A1')->getFont()->setSize(10);
                $event->sheet->getDelegate()->setCellValue('A2', 'REPORTE:')->getStyle('A2')->getFont()->setSize(10);
                $event->sheet->getDelegate()->setCellValue('A3', 'FECHA:')->getStyle('A3')->getFont()->setSize(10);
                $event->sheet->getDelegate()->mergeCells('B1:'.$this->position.'1');   
                $event->sheet->getDelegate()->mergeCells('B2:'.$this->position.'2');   
                $event->sheet->getDelegate()->mergeCells('B3:'.$this->position.'3');   
                $event->sheet->getDelegate()->setCellValue('B1', 'UNIVERSIDAD NACIONAL DE MOQUEGUA')->getStyle('B1')->getFont()->setSize(12);
                $event->sheet->getDelegate()->setCellValue('B2', $this->nombreReporte)->getStyle('B2')->getFont()->setSize(12);
                $event->sheet->getDelegate()->setCellValue('B3', $this->fechaReporte )->getStyle('B3')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

}