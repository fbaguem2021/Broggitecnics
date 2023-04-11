<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartaFormDataResource;
use App\Models\Comarca;
use App\Models\Incident;
use App\Models\Municipi;
use App\Models\Provincia;
use App\Models\TipusIncident;
use App\Models\TipusLocalitzacio;
use App\Models\TipusVies;
use Illuminate\Http\Request;

class CartaFormData extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'localitzacio' => [
                'comarques' => Comarca::all(),
                'provincies' => Provincia::all(),
                'municipis' => Municipi::all(),
                'tipusLoc' => TipusLocalitzacio::all(),
                'tipusVies' => TipusVies::all()
            ],
            'incident' => [
                'tipusIncident' => TipusIncident::all(),
                'incidents' => Incident::orderBy('nom')->get()
            ]
        ];

        return response()->json($data);
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
    public function show($id)
    {
        //
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
