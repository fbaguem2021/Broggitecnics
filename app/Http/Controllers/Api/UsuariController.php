<?php

namespace App\Http\Controllers\Api;

use stdClass;
use App\Models\Usuari;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UsuariResource;
use Illuminate\Database\QueryException;

class UsuariController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rol = \App\Models\Rol::where('id',1)->get();
        $queryid = $request->query('id', 4);

        $tipo = Usuari::find($queryid)->tipus_usuaris_id;
        $usuaris = Usuari::where('tipus_usuaris_id', $tipo)->orderBy('id', 'asc')->paginate(5);
        // $usuaris = Usuari::orderBy('id', 'asc')->paginate(5);
        return UsuariResource::collection($usuaris);
    }

    /**
     * Browses the resource with the specified parameters
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request) {
        $params = new stdClass();

        foreach (['username','nom','cognoms'] as $filtro) {
            $query_filtro = $request->query($filtro, false);
            if ( $query_filtro == true && $query_filtro != '' ) {
                $params->filtro = $filtro;
                $params->valor = $request->query($filtro)."%";
            }
        }

        $usr = Usuari::where($params->filtro, 'like', $params->valor)->orderBy('id', 'asc')->paginate(5);
        return UsuariResource::collection($usr);
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

        $usr = new Usuari;
        $usr->username          = $data['username'];
        $usr->contrasenya       = bcrypt($data['contrasenya']);
        $usr->nom               = $data['nom'];
        $usr->cognoms           = $data['cognoms'];
        $usr->tipus_usuaris_id  = $data['tipus_usuaris_id'];

        try {
            $usr->save();
            $response = (new UsuariResource($usr))
                        ->response()
                        ->setStatusCode(201);
        } catch (QueryException $ex) {
            $mensaje = "Error al crear usuario";
            $response = response()->json(['error'=>$mensaje, 400]);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function show(Usuari $usuari)
    {
        // $col = UsuariResource::collection(Usuari::where('id', $usuari->id)->get())[0]->id;
        return UsuariResource::collection(Usuari::where('id', $usuari->id)->get())[0];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuari $usuari)
    {
        $pssw = $request->query('password', false);

        $data = $request->json()->all();
        $usr = Usuari::find($usuari->id);
        /*
            $usr->username = $usuari->username;
            if ($pssw) {
                $usr->contrasenya = bcrypt($usuari->contrasenya);
            }
            $usr->nom = $usuari->nom;
            $usr->cognoms = $usuari->cognoms;
            $usr->tipus_usuaris_id = $usuari->tipus_usuaris_id;
        */
        $usr->username = $data['username'];
        if ($pssw) {
            $usr->contrasenya = bcrypt($data['contrasenya']);
        }
        $usr->nom = $data['nom'];
        $usr->cognoms = $data['cognoms'];
        $usr->tipus_usuaris_id = $data['tipus_usuaris_id'];

        try {
            $usr->save();
            $response = (new UsuariResource($usr))
                        ->response()
                        ->setStatusCode(201);
        } catch (QueryException $ex) {
            $mensaje = "Error al crear usuario";
            $response = response()->json(['error'=>$mensaje, 400]);
        }
        // return response()->json(['error'=>'esto es una prueva', 400]);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuari $usuari)
    {
        //
    }
}
