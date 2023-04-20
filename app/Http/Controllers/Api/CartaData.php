<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartaTrucada;
use App\Models\Comarca;
use App\Models\Expedient;
use App\Models\Incident;
use App\Models\Interlocutor;
use App\Models\Municipi;
use App\Models\Provincia;
use App\Models\TipusIncident;
use App\Models\TipusLocalitzacio;
use App\Models\TipusVies;
use Illuminate\Http\Request;

class CartaData extends Controller
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
            ],
            'cartaLastCodi' => CartaTrucada::latest('id')->pluck('codi_trucada')->first() ? : 'TR230',
            'expedientLatCodi' => Expedient::latest('id')->pluck('codi')->first() ? : 'EX230',

        ];
        return response()->json($data);
    }

    public function checkNumber($number) {
        try {
            $interlocutor = Interlocutor::where('telefon', $number)->first();
            if ($interlocutor) {
                $response = ["match" => true, "interlocutor" => $interlocutor];
            } else {
                $response = ["match" => false];
            }
        } catch (\Throwable $th) {
            $response = ["ERROR" => $th];
        }
        return response()->json($response);
    }

    public function generateNumber() {
        $m = "RESPONSE FROM API GENERATE NUMBER";

        //95% probability
        $prefix = "6";
        $randomNumber = mt_rand(10000000, 99999999);
        $randomPhone = $prefix . $randomNumber;
        return response()->json(["message" => $m, "phone" => $randomPhone]);

        //5% probability
        $interlocutor = Interlocutor::inRandomOrder()->select('telefon')->first();
        $randomTelefon = $interlocutor->telefon;
        // checkNumber($randomTelefon);
       
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
