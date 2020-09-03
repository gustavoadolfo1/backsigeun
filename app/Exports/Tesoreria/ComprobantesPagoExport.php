<?php

// namespace App\Exports\Tesoreria;
// use Illuminate\Http\Request;
// use Maatwebsite\Excel\Concerns\FromArray;
// use Illuminate\Support\Facades\DB;
// //use Maatwebsite\Excel\Concerns\WithHeadingRow;
// class ComprobantesPagoExport implements FromArray
// {
//     private $nameReport;
//     private $ids;
//     public function __construct($nameReport,$ids){
//         $this->nameReport = $nameReport;
//         $this->ids = $ids;
//     }
//     public function array():array
//     {
//         $i = 0;
//         switch($this->nameReport)
//         {
//             case 'rptComprobantesPagoC':
//                 $parameters = [1,$this->ids];
//                 $dataResult = DB::select('EXEC tre.Sp_SEL_consulta_expedientes_girados_documentocompromisoXcCodigoCadena ?,?',$parameters);
//                 $data[] = array('N°','AÑO','N°SIAF','N°CERTIF.','F.F','T.R','COD.','COMPROMISO','NUMERO','SEC.FUNC.','META','CLASIF','NOMBRE CLASIFICADOR','MONTO','COD.','DOCUMENTO','NUMERO','FECHA','RUC','PROVEEDOR','T.G.','NOTA');
//                 foreach ($dataResult as $list) {
//                     $data[] = array(
//                         ++$i,
//                         $list->Ano_eje,$list->Expediente,$list->cCertificado,$list->Fuente_financ,$list->Tipo_recurso,$list->cCod_doc_Compromiso,$list->cClase_Compromiso,$list->cNum_doc_Compromiso,$list->Sec_func,$list->cNombre_Meta,$list->Clasificador,$list->Nombre_Clasificador,$list->Monto,$list->Cod_doc_Girado,$list->Nombre_doc_Girado,$list->Numero_doc_Girado,$list->fecha_doc_Girado,$list->Ruc,$list->Proveedor_Nombre,$list->Tipo_giro,$list->Notas                    
//                     );
//                 }
//             break;
//             case 'rptComprobantesPagoD':
//                 $parameters = [1,$this->ids];
//                 $dataResult = DB::select('EXEC tre.Sp_SEL_consulta_expedientes_columnatotalfaseXcCodigoCadena ?,?',$parameters);
//                 $data[] = array('N°','AÑO','N°SIAF','N°CERTIF.','F.F','T.R','NUMERO','FECHA','META','COMPROMISO','DEVENGADO','GIRADO','PAGADO','RENDIDO');                
//                 foreach ($dataResult as $list) {
//                     $data[] = array(
//                         ++$i,
//                         $list->Ano_eje,
//                         $list->Expediente,
//                         $list->Fuente_financ,
//                         $list->Tipo_recurso,
//                         $list->Numero_doc_Compromiso,
//                         $list->Fecha_doc_Compromiso,
//                         $list->Sec_func,
//                         $list->nCompromiso,
//                         $list->nDevengado,
//                         $list->nGirado,
//                         $list->nPagado,
//                         $list->nRendido,
//                     );
//                 }
//             break;

//         }
//         return $data;
//     }
// }