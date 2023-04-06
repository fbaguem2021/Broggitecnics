<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipusLocalitzacio;
use Illuminate\Http\Request;

class TipusLocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipusLoc = TipusLocalitzacio::orderBy('id')->get();
        return response()->json($tipusLoc);
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
     * @param  \App\Models\TipusLocalitzacio  $tipusLocalitzacio
     * @return \Illuminate\Http\Response
     */
    public function show(TipusLocalitzacio $tipusLocalitzacio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipusLocalitzacio  $tipusLocalitzacio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipusLocalitzacio $tipusLocalitzacio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipusLocalitzacio  $tipusLocalitzacio
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipusLocalitzacio $tipusLocalitzacio)
    {
        //
    }
}
