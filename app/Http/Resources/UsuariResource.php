<?php

namespace App\Http\Resources;

use App\Models\Rol;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuariResource extends JsonResource {
    /**
     * Transform the resource into an array
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $tipus = Rol::where('id', $this->tipus_usuaris_id)->first();
        return [
            'id'            => $this->id,
            'username'      => $this->username,
            'contrasenya'   => $this->contrasenya,
            'nom'           => $this->nom,
            'cognoms'       => $this->cognoms,
            'tipus_usuaris_id'  => $this->rol->id,
            'tipus'         => $tipus->nom
        ];
    }
}
