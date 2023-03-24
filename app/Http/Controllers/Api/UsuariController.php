<?php

namespace App\Http\Controllers\Api;

use stdClass;
use App\Models\Usuari;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsuariResource;

class UsuariController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rol = \App\Models\Rol::where('id',1)->get();
        $usuaris = Usuari::/*whereBelongsTo($rol)->*/orderBy('id', 'asc')->paginate(5);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuari  $usuari
     * @return \Illuminate\Http\Response
     */
    public function show(Usuari $usuari)
    {
        //
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
        //
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
