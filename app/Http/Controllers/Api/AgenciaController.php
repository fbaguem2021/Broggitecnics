<?php

namespace App\Http\Controllers\Api;
use App\Models\Agencia;
use Illuminate\Http\Request;
use App\Models\AgenciesPrimaries;
use App\Http\Controllers\Controller;
use App\Http\Resources\AgenciaResource;
use App\Policies\AgenciaPolicy;
use Illuminate\Database\QueryException;


class AgenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agencies = Agencia::all();
        // return AgenciaResource::collection($agencies);
        return AgenciaResource::collection($agencies);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //mostrar agencias según el ID de la agencia primaria
    public function show($id)
    {
        $agencies = Agencia::where('AgenciesPrimaries_id', $id)->get();
        return AgenciaResource::collection($agencies);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
