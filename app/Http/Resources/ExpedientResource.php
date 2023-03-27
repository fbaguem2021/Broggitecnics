<?php

namespace App\Http\Resources;

use App\Models\CartaTrucada;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpedientResource extends JsonResource {

    /**
     * Transform the resource into an array
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $include_cartes = $request->get('include_cartes');
        $count = CartaTrucada::where('expedients_id', $this->id)->count();

        $response = [
            'id' => $this->id,
            'codi_expedient' => $this->codi,
            'estat_expedient_id' => $this->estatExpedient->id,
            'estat_expedient' => $this->estatExpedient->estat,
            'numero_cartes' => $count
        ];

        if ($include_cartes) {
            $response['cartes_trucada'] = CartaTrucadaResource::collection(CartaTrucada::where('expedients_id', $this->id)->get());
        }

        return $response;
    }
}