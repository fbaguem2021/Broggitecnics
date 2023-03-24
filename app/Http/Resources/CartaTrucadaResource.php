<?php

namespace App\Http\Resources;

use App\Models\Interlocutor;
use Illuminate\Http\Resources\Json\JsonResource;


class CartaTrucadaResource extends JsonResource 
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $dataHoraTrucada = $this->data_hora_trucada;
        $parts = explode(" ", $dataHoraTrucada);
        $date = $parts[0];
        $time = $parts[1];
        return 
        [
            'id' => $this->id,
            'codi_trucada' => $this->codi_trucada,
            'data_hora_trucada' => [
               'data' => $date,
               'hora' => $time
            ],
            'durada' => $this->durada,
            'interlocutor' => [
                'nom' => $this->interlocutor->nom,
                'cognom' => $this->interlocutor->cognom,
                'telefon' => $this->interlocutor->telefon,
                'antecedents' => $this->interlocutor->antecedents,
            ],
            'localitzacio' => [
                'tipus' => $this->tipusLocalitzacio->nom,
                'descripcio' => $this->decripcio_localitzacio,
                'detall' => $this->detall_localitzacio,
                'referencies' => $this->altres_ref_localitzacio,
                'provincia' => $this->provincia->nom,
                'comarca' => $this->municipi->comarca->nom,
                'municipi' => $this->municipi->nom,
                
            ],
            'incident' => [
                'tipus_incident' => $this->incident->tipusIncident->nom,
                'codi_incident' => $this->incident->codi,
                'incident' => $this->incident->nom,
                'descripcio' => $this->incident->descripcio,
                'instruccions' => $this->incident->instruccions
            ],
            'expedient' => [
                'codi' => $this->expedient->codi,
                'estat_expedient' => $this->expedient->estatExpedient->estat
            ],
            'nota_comuna' => $this->nota_comuna,
            'operador' => [
                'nom' => $this->usuari->nom,
                'cognom' => $this->usuari->cognom
            ] 
        ];
    }
}
