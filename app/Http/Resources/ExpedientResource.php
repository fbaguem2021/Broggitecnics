<?php

namespace App\Http\Resources;


use App\Models\CartaTrucada;
use App\Models\Expedient;
use Illuminate\Support\Facades\DB;
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
            'localitzacions' => Expedient::select(DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'))
                ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
                ->where('expedients.id', $this->id)
                ->pluck('localitzacions')
                ->first(),
            'incidents' => Expedient::select(DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as incidents'))
                ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
                ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
                ->where('expedients.id', $this->id)
                ->pluck('incidents')
                ->first(),
            'modificat' => $this->updated_at,
            'creat' => $this->created_at,
            'estat_expedient_id' => $this->estatExpedient->id,
            'estat_expedient' => $this->estatExpedient->estat,
            'cartes_count' => $this->cartes_count
        ];


        /* 
        
        */
        /* $query = DB::table('expedients')
                ->select('expedients.id','expedients.codi',
                        DB::raw('CONCAT("[",GROUP_CONCAT(DISTINCT \'"\',interlocutors.id,\'"\'),"]") as interlocutors'),
                        DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                        DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as tipus'),
                        DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
                ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
                ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
                ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
                ->leftJoin('interlocutors', 'cartes_trucades.interlocutors_id', '=', 'interlocutors.id')
                // ->where('expedients.id','>=',0)
                ->groupBy('expedients.id','expedients.codi')
                ->orderBy('expedients.id'); */



        if ($include_cartes) {
            $response['cartes_trucada'] = CartaTrucadaResource::collection(CartaTrucada::where('expedients_id', $this->id)->get());
        }

        return $response;
    }

}