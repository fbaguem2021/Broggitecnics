<?php

namespace App\Http\Controllers\Api;

use App\Models\Expedient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpedientResource;

class ExpedientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter , $value = null, $direction = null)
    {
        try {
            $query = Expedient::select('expedients.id', 'expedients.codi', 'expedients.estat_expedients_id', 'expedients.created_at', 'expedients.updated_at', 
                        DB::raw('COUNT(cartes_trucades.id) as cartes_count'),
                        DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                        DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as incidents'))
                        ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                        ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
                        ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
                        ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
                        ->where('expedients.id','>=',0)
                        ->groupBy('expedients.id')
                        ->groupBy('expedients.codi')
                        ->groupBy('expedients.estat_expedients_id')
                        ->groupBy('expedients.created_at')
                        ->groupBy('expedients.updated_at');
        } catch (\Throwable $th) {
            //throw $th;
        }


        
        if ($filter == 'none'){

        } else if ($filter == 'orderBy'){
            $query->orderBy($value, $direction);

        } else if ($filter) {
            switch ($filter) {
                case 'all':
                    break;
                case 'codi':
                    $query->where('expedients.codi', 'like', " '%$value%' ");
                    break;
                case 'loc':
                    $query->havingRaw(" GROUP_CONCAT(DISTINCT provincies.nom) LIKE '%$value%' ");
                    break;
                case 'inc':
                    $query->havingRaw(" GROUP_CONCAT(DISTINCT tipus_incidents.nom) LIKE '%$value%' ");
                    break;
                case 'cartes_count':
                    $query->havingRaw(" COUNT(cartes_trucades.id) LIKE '%$value%' ");
                    break;
            }
            
        }

        $expedients = $query->get();

            
        return ExpedientResource::collection($expedients);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expedient  $expedient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        request()->request->add([ 'include_cartes' => true]);
        $expedient = Expedient::select('expedients.*', DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
                        ->leftJoin('cartes_trucades', 'cartes_trucades.expedients_id', '=', 'expedients.id')
                        ->where('expedients.id', $id)
                        ->groupBy('expedients.id')
                        ->groupBy('expedients.codi')
                        ->groupBy('expedients.estat_expedients_id')
                        ->groupBy('expedients.created_at')
                        ->groupBy('expedients.updated_at')
                        ->first();
                
        return new ExpedientResource($expedient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expedient  $expedient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expedient = Expedient::find($id);

        if (!$expedient) {
            return response()->json(['message' => 'Expedient not found'], 404);
        }

        $expedient->estat_expedients_id = $request->input('estat_expedient_id');
        $expedient->save();

        return response()->json(['message' => 'Expedient updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expedient  $expedient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expedient $expedient)
    {
        //
    }
}
