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

        $query = Expedient::select('expedients.*', DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
            ->leftJoin('cartes_trucades', 'cartes_trucades.expedients_id', '=', 'expedients.id')
            ->groupBy('expedients.id');

        if ($filter == 'all') {
            
        } else if ($filter == 'orderBy'){
            $expedients = $query->orderBy($value, $direction);
        } else  if ($filter == 'none'){
            $expedients = [];
        }
        else{
            $expedients = $query->where($filter, $value);
        }

        $expedients = $query->paginate(8);

            
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
        return new ExpedientResource(Expedient::find($id));
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
