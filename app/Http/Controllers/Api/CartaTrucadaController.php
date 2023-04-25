<?php

namespace App\Http\Controllers\Api;

use App\Models\CartaTrucada;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartaTrucadaResource;

class CartaTrucadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CartaTrucadaResource::collection(CartaTrucada::all());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carta = new CartaTrucada();
        // $carta->codi_trucada = $request
        // $carta->data_hora_trucada = $request
        // $carta->durada = $request
        // $carta->interlocutors_id = $request
        // $carta->telefon = $request
        // $carta->nom = $request
        // $carta->cognoms = $request
        // $carta->nota_comuna = $request
        // $carta->tipus_localitzacions_id =$request
        // $carta->descripcio_localitzacio = $request
        // $carta->detall_localitzacio = $request
        // //text de altres referencies
        // $carta->altres_ref_localitzacio = $request
        // //id del municipio
        // $carta->municipis_id = $request
        // //id de la provincia
        // $carta->provincies_id = $request
        // //Id de los incidentes
        // $carta->incidents_id = $request
        // //Si ya existe un expediente se hace insert de la id, si no existe, PRIMERO SE HACE INSERT DEL EXPEDIENTE
        // $carta->expedients_id = $request
        //ID user que crea la carta
        $carta->usuaris_id = Auth::user()->id;


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CartaTrucada  $cartaTrucada
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new CartaTrucadaResource(CartaTrucada::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CartaTrucada  $cartaTrucada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CartaTrucada $cartaTrucada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CartaTrucada  $cartaTrucada
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartaTrucada $cartaTrucada)
    {
        //
    }
}
