<?php namespace App\Packages\Maatwebsite\Maatwebsite\Excel\Files;

interface ImportHandler {

    /**
     * Handle the import
     * @param $file
     * @return mixed
     */
    public function handle($file);

} 