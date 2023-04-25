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
            'provincia' => $this->municipi->comarca->provincia->nom ?? NULL,
            'comarca' => $this->municipi->comarca->nom ?? NULL,
            'municipi' => $this->municipi->nom ?? NULL,
            'carrer' => $this->carrer,
            'codi_postal' => $this->codi_postal,
            'estat' => $this->cartaHasAgencies ? $this->cartaHasAgencies[0] ->estatAgencia ?? NULL : NULL,
            'agencies_primaries_id' => $this->agencies_primaries_id
        ];
    }
}
