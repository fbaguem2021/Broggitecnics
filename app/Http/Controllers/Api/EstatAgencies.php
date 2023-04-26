<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartaTrucadaResource;
use App\Models\CartaTrucada;
use App\Models\CartaTrucadaHasAgencies;
use App\Models\EstatAgencia;
use Illuminate\Database\QueryException;

class EstatAgencies extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estats = EstatAgencia::all();
        return response()->json($estats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();

        $cartaHasAgencia = new CartaTrucadaHasAgencies();
        $cartaHasAgencia->cartes_trucades_id = $data['cartaTrucada_id'];
        $cartaHasAgencia->agencies_id = $data['agencia_id'];
        $cartaHasAgencia->estat_agencies_id = 1;

        try {
            $cartaHasAgencia->save();
            $response = ["message" => "succesfully added", "item" => $cartaHasAgencia];
            $code = 201;
        } catch (QueryException $e) {
            $response = ["message" => "error", "errorData" => $e];
            $code = 400;
        }
        return response()->json([$response, $code]);
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
    public function updateState(Request $request, int $cartaTrucadaId, int $agenciaId)
    {
        $data = $request->json()->all();

        try {
            CartaTrucadaHasAgencies::where('cartes_trucades_id', $cartaTrucadaId)
            ->where('agencies_id', $agenciaId)
            ->update(['estat_agencies_id' => $data['new_estat_agencies_id']]);

           $response = ["isError" => false, "updatedCarta" => new CartaTrucadaResource(CartaTrucada::find($cartaTrucadaId))];
           $code = 200;
        } catch (QueryException $e) {
            $response = ["isError" => true, "errorMssg" => $e];
            $code = 400;
        }

        return response()->json([$response, $code]);
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
