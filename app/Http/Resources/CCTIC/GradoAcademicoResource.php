<?php

namespace App\Http\Resources\CCTIC;

use Illuminate\Http\Resources\Json\JsonResource;


class GradoAcademicoResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->iGradoAcadId,
            'description' => $this->cgradoAcadDesc,
        ];

        return $data;
    }
}
