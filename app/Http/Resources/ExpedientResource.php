<?php

namespace App\Http\Resources;

use App\Models\CartaTrucada;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpedientResource extends JsonResource {
    public static $wrap = null;
    /**
     * Transform the resource into an array
     *
     * @param Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $cartes = CartaTrucada::where('expedients_id', $this->id)->get();
        $count = $cartes->count();

        return [
            'id' => $this->id,
            'codi_expedient' => $this->codi,
            'estat_expedient' => $this->estatExpedient->estat,
            'numero_cartes' => $count,
            'cartes_trucada' => CartaTrucadaResource::collection($cartes)
        ];
    }
}
