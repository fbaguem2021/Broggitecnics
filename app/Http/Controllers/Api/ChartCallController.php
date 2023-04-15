<?php

namespace App\Http\Controllers\Api;

use App\Models\CartaTrucada;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChartResource;


class ChartCallController extends Controller
{
    public function index()
    {

        /*
       Devuelve los 10 municipios con más llamadas
        */

        $result = CartaTrucada::join('municipis as m', 'cartes_trucades.municipis_id', '=', 'm.id')
            ->select('m.nom', 'cartes_trucades.municipis_id', DB::raw('COUNT(cartes_trucades.incidents_id) AS num_incidents'))
            ->groupBy('cartes_trucades.municipis_id')
            ->orderBy('num_incidents', 'desc')
            ->take(10)
            ->get();

        return ChartResource::collection($result);
    }
}
