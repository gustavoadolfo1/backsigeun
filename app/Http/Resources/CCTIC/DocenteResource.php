<?php

namespace App\Http\Resources\CCTIC;

use Illuminate\Http\Resources\Json\JsonResource;

class DocenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $personas = new PersonasResource($this->personas);

        $data = [
            'id' => $this->iDocenteId,
            'perId' => $this->iPersId,
            'acedemicProgram' => $this->iProgramasAcadId,
            'dedicationId' => $this->iDedicId,
            'academicDegreeId' => $this->iGradoAcadId,
            'active' => $this->bDocenteActivo,
            'academicDegreeDesc' => $this->cDescGradoAcad,
            'ncellphone' => $this->cDocenteCel,
            'email' => $this->cDocenteCorreoElec,
            'ntel' => $this->cDocenteTel,
            'address' => $this->cDocenteDirec,
            'cDocenteDoc' => $this->cDocenteDoc,
            'iTipoIdentId' => $personas->iTipoIdentId,
            'cPersPaterno' => $personas->cPersPaterno,
            'cPersMaterno' => $personas->cPersMaterno,
            'cPersNombre' => $personas->cPersNombre,
            'cPersSexo' => $personas->cPersSexo,
            'dPersNacimiento' => $personas->dPersNacimiento,
        ];

        // $personas = new PersonasResource ($this->personas);

        return $data;
    }
}
