<?php

namespace App\Http\Resources;

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
        return 
        [
            'id' => $this->id,
            'codi_expedient' => $this->codi,
            'estat_expedient' => [
                'id' => $this->estatExpedient->id,
                'nom' => $this->estatExpedient->estat
            ]
        ];
    }
}
