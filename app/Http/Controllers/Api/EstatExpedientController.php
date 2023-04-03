<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\EstatExpedient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EstatExpedientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estats = EstatExpedient::select('estat_expedients.id', 'estat_expedients.estat', DB::raw('COUNT(expedients.id) AS count'))
        ->leftJoin('expedients', 'estat_expedients.id', '=', 'expedients.estat_expedients_id')
        ->groupBy('estat_expedients.id', 'estat_expedients.estat')
        ->orderBy('estat_expedients.id', 'asc')
        ->get();


        return response()->json($estats, 200);
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
     * @param  \App\Models\EstatExpedient  $estatExpedient
     * @return \Illuminate\Http\Response
     */
    public function show(EstatExpedient $estatExpedient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstatExpedient  $estatExpedient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EstatExpedient $estatExpedient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstatExpedient  $estatExpedient
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstatExpedient $estatExpedient)
    {
        //
    }
}
