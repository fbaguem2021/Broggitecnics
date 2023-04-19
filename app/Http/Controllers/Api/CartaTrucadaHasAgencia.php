<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CartaTrucadaHasAgencies;

class CartaTrucadaHasAgencia extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\CartaTrucadaHasAgencies  $cartaTrucadaHasAgencies
     * @return \Illuminate\Http\Response
     */
    public function show(CartaTrucadaHasAgencies $cartaTrucadaHasAgencies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartaTrucadaHasAgencies  $cartaTrucadaHasAgencies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartaTrucadaHasAgencies $cartaTrucadaHasAgencies)
    {
/*         $data = $request->json()->all();

        $cTHasAg = CartaTrucadaHasAgencies::where('cartes_trucades_id', '=', $data['cartes_trucades_id'])
                                                ->where('agencies_id', '=', $data['agencies_id'])
                                                ->first();
        if (!$cTHasAg) {
            $response = response()->json(['error'=> 'CartaTrucadaHasAgencies not found.'], 404);
            return $response;
        }
        $cTHasAg->estat_agencies_id = 3; */
            
        try {
            $cartesTrucadesId = 1; // Replace with the actual value of the cartes_trucades_id
            $agenciesId = 456; // Replace with the actual value of the agencies_id
            $newEstatAgenciesId = 2; // Replace with the new value of the estat_agencies_id

            $cartesTrucadesHasAgencies = CartaTrucadaHasAgencies::where('cartes_trucades_id', 1)
                ->where('agencies_id', 456)
                ->first();

            if ($cartesTrucadesHasAgencies) {
                $cartesTrucadesHasAgencies->estat_agencies_id = $newEstatAgenciesId;
                $cartesTrucadesHasAgencies->save();
            }
            $cartesTrucadesHasAgencies = CartaTrucadaHasAgencies::where('cartes_trucades_id', $cartesTrucadesId)
            ->where('agencies_id', $agenciesId)
            ->first();


        } catch (\Exception $e) {
            $response = response()->json(['error'=> $e->getMessage()], 500);
        }

        return response()->json([dump($cartesTrucadesHasAgencies)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CartaTrucadaHasAgencies  $cartaTrucadaHasAgencies
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartaTrucadaHasAgencies $cartaTrucadaHasAgencies)
    {
        //
    }
}
