<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgenciaResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'localitzacio' => [
                'provincia' => $this->municipi->comarca->provincia->nom,
                'comarca' => $this->municipi->comarca->nom,
                'municipi' => $this->municipi->nom,
                'carrer' => $this->carrer,
                'codi_postal' => $this->codi_postal,
            ],
            'estat' => $this->pivot
        ];
    }
}
