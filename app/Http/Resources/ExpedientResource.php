<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

        $response = [
            'id' => $this->id,
            'codi_expedient' => $this->codi,
            'modificat' => $this->updated_at,
            'creat' => $this->created_at,
            'estat_expedient_id' => $this->estatExpedient->id,
            'estat_expedient' => $this->estatExpedient->estat,
            'cartes_count' => $this->cartes_count
        ];

        if ($include_cartes) {
            $response['cartes_trucada'] = CartaTrucadaResource::collection(CartaTrucada::where('expedients_id', $this->id)->get());
        }

        return $response;
    }

}