<?php

namespace App\Http\Resources\CCTIC;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            // 'iPersId' => $this->iPersId,
            'iTipoPersId' => $this->iTipoPersId,
            'iTipoIdentId' => $this->iTipoIdentId,
            // 'cPersDocumento' => $this->cPersDocumento,
            'cPersPaterno' => $this->cPersPaterno,
            'cPersMaterno' => $this->cPersMaterno,
            'cPersNombre' => $this->cPersNombre,
            'cPersSexo' => $this->cPersSexo,
            'dPersNacimiento' => $this->dPersNacimiento,
            // 'iTipoEstCivId' => $this->iTipoEstCivId,
        ];
        return $data;
    }
}
