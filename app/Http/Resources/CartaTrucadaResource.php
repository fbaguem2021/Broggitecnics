<?php

namespace App\Http\Resources;

use App\Models\Agencies;
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
            'data_hora' => [
                'data' => $date,
                'hora' => $time
            ],
            'durada' => $this->durada,
            'interlocutor' => $this->interlocutor ? [
                'nom' => $this->interlocutor->nom ?? null,
                'cognom' => $this->interlocutor->cognoms ?? null,
                'telefon' => $this->interlocutor->telefon ?? null,
                'antecedents' => $this->interlocutor->antecedents ?? null,
            ] : null,
            'localitzacio' => [
                'tipus' => $this->tipusLocalitzacio->nom,
                'descripcio' => $this->decripcio_localitzacio ?? null,
                'detall' => $this->detall_localitzacio ?? null,
                'referencies' => $this->altres_ref_localitzacio ?? null,
                'provincia' => $this->provincia ? $this->provincia->nom ?? null : null,
                'comarca' => $this->municipi ? $this->municipi->comarca->nom ?? null : null,
                'municipi' => $this->municipi ? $this->municipi->nom ?? null : null,
            ],
            'incident' => [
                'tipus_incident' => $this->incident->tipusIncidents->nom,
                'codi_incident' => $this->incident->codi,
                'incident' => $this->incident->nom,
                'defincio' => $this->incident->definicio,
                'instruccions' => $this->incident->instruccions
            ],
            'expedient' => [
                'codi' => $this->expedient->codi,
                'estat_expedient' => $this->expedient->estatExpedient->estat
            ],
            'nota_comuna' => $this->nota_comuna,
            'agencies' => collect($this->cartaHasAgencies)->map(function ($agencia) {
                return new AgenciaResource(Agencies::find($agencia['agencies_id']));
            }),
            'operador' => [
                'nom' => $this->usuari->nom,
                'cognom' => $this->usuari->cognoms
            ] 
        ];
    }
}
