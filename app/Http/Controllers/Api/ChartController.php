<?php

namespace App\Http\Controllers\Api;

use App\Models\CartaTrucada;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChartResource;



class ChartController extends Controller
{    
    public function index()
    {

        /*
        Devuelve tabla con 3 columnas (nom_del_municipi, municipis_id, num_expedients(incidents) ). 
    No suma si dos expedientes tienen el mismo municipi_id y el mismo num_expedient (estan relacionadas con el mismo incidente), en cambio
    si forman parte del mismo expediente pero estan en municipios distintos, suman en ambos municipios como incidencias:
        
        */


        $result = CartaTrucada::join('municipis as m', 'cartes_trucades.municipis_id', '=', 'm.id')
        ->select('m.nom', 'cartes_trucades.municipis_id', DB::raw('COUNT(DISTINCT cartes_trucades.expedients_id) AS num_expedients'))
        ->groupBy('cartes_trucades.municipis_id')
        ->orderBy('num_expedients', 'desc')
        ->take(10)
        ->get();

return ChartResource::collection($result);


        // $result = DB::table('cartes_trucades as ct')
        //     ->join('municipis as m', 'ct.municipis_id', '=', 'm.id')
        //     ->select('m.nom', 'ct.municipis_id', DB::raw('COUNT(DISTINCT ct.expedients_id) AS num_expedients'))
        //     ->groupBy('ct.municipis_id')
        //     ->orderBy('num_expedients', 'desc')
        //     ->take(10)
        //     ->get();
        
        // return response()->json($result);
    }
}
