<?php

namespace App\Http\Controllers\Api;

use App\Models\CartaTrucada;
use App\Models\Interlocutor;
use Illuminate\Http\Request;
use App\Http\Controllers\Utilitat;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Resources\CartaTrucadaResource;
use App\Http\Resources\InterlocutorResource;

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
        $data = $request->input('data');
        $carta = new CartaTrucada();
        $carta->codi_trucada = $data['codi_trucada'];
        $carta->data_hora_trucada = $data['data_hora_trucada'];
        $carta->durada = $data['durada'];
        $carta->interlocutors_id = $data['interlocutors_id'];
        $carta->telefon = $data['telefon'];
        $carta->nom = $data['nom'];
        $carta->cognoms = $data['cognoms'];
        $carta->nota_comuna = $data['nota_comuna'];
        $carta->tipus_localitzacions_id = $data['tipus_localitzacions_id'];
        $carta->descripcio_localitzacio = $data['descripcio_localitzacio'];
        $carta->detall_localitzacio = $data['detall_localitzacio'];
        $carta->altres_ref_localitzacio = $data['altres_ref_localitzacio'];
        $carta->municipis_id = $data['municipis_id'];
        $carta->provincies_id = $data['provincies_id'];
        $carta->incidents_id = $data['incidents_id'];
        $carta->expedients_id = $data['expedients_id'];
        $carta->usuaris_id = $data['usuaris_id'];

        try {
            $carta->save();            
            $response = \response()->json(["S'ha guardat la carta correctament"], 201);;
        } catch (QueryException $ex) {
            $mensaje = Utilitat::errorMessage($ex);
            $response = \response()->json(["error" => $mensaje], 400);
        }
        
        return $response;  
    }

    public function storeInterlocutor(Request $request)
    {
        $data = $request->input('data');
        $interlocutor = new Interlocutor();
        $interlocutor->telefon = $data['telefon'];
        $interlocutor->antecedents = $data['antecedents'];
        $interlocutor->nom = $data['nom'];
        $interlocutor->cognoms = $data['cognoms'];

        try {
            $interlocutor->save();
            // $idInter = Interlocutor::where('telefon', $data['telefon'])->first();
            $idInter = $interlocutor->id; 
            $response = \response()->json(["idInterlocutor"=>$idInter], 201);;
        } catch (QueryException $ex) {
            $mensaje = Utilitat::errorMessage($ex);
            $response = \response()->json(["error" => $mensaje], 400);
        }
        //también se puede añadir al array de la sesión el objeto cicleX con push directamente $request->session()->push('cicles', '$cicleX');
        return $response;  
    }

    public function getIdInterlocutor()
    {
        return CartaTrucadaResource::collection(CartaTrucada::all());

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
