<?php

namespace App\Libraries\COM;

class CrystalReportConnector  
{
    private $crApp;
    
    /**
     * Crea una nueva instancia para la conexión con Crystal Report
     *
     * @return void
     */
    public function __construct()
    {
        //------ Create a new COM Object of Crytal Reports XI ------

        //$ObjectFactory= new \COM("CrystalReports11.ObjectFactory.1") or die ("Error on load"); // call COM port
        //$crapp = $ObjectFactory-> CreateObject("CrystalDesignRunTime.Application.11");

        $this->crapp = new \COM("CrystalRuntime.Application.9");
        //COM("CrystalRuntime.Application.9") or die ("Error on load");

        dd($this->crapp);
    }

    public function generarReporte($reporte, $paramemters)
    {
        $reporte = "D:\\rpt_SIGEUN\\" . $reporte;

        $reporte = $this->crApp->OpenReport($reporte, 1);
        $reporte->Database->Tables(1)->SetLogOnInfo(env('DB_HOST'), env('SIGEUN'), env('DB_USERNAME'), env('DB_PASSWORD'));
        //$reporte->FormulaSyntax = false;
        $reporte->EnableParameterPrompting = 0;
        $reporte->DiscardSavedData;

        $reporte->ParameterFields(1)->AddCurrentValue(1); 
        $reporte->ParameterFields->Item(1)->SetCurrentValue(1);

        $reporte->ParameterFields(2)->AddCurrentValue(1); 
        $reporte->ParameterFields->Item(2)->SetCurrentValue(1);

        $reporte->ReadRecords(); 

        $reporte->ExportOptions->DiskFileName = "D:\\rpt_SIGEUN\\reporte.pdf";
        $reporte->ExportOptions->PDFExportAllPages = true; 
        $reporte->ExportOptions->DestinationType = 1;
        $reporte->ExportOptions->FormatType = 31;
        $reporte->Export(false); 
    }
}
