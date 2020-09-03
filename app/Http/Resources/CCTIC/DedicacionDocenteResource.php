<?php

namespace App\Http\Resources\CCTIC;

use Illuminate\Http\Resources\Json\JsonResource;

class DedicacionDocenteResource extends JsonResource
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
            'id' => $this->iDedicId,
            'description' => $this->cDedicDsc,
        ];

        return $data;
    }
}
