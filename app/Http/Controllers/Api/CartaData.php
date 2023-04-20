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
        $m = "RESPONSE FROM API GENERATE NUMBER";
        try {
            $interlocutor = Interlocutor::where('telefon', $number)->first();
            if ($interlocutor) {
                $response = [
                    "message" => $m,
                    "match" => true, 
                    "interlocutor" => $interlocutor
                ];
            } else {
                $response = [
                    "match" => false,
                    "phone" => $number
                ];
            }
        } catch (\Throwable $th) {
            $response = [
                "message" => $m,
                "error" => $th,
                "match" => false,
                "phone" => $number
            ];
        }
        return response()->json($response);
    }

    /**
     * 95% probability to:
     *   Generate a random phone number
     * 5% probabilit to:
     *   Get a existing phone number aka get a random interlocutor   
     * @return void
     */
    public function generateNumber() {
        $m = "RESPONSE FROM API GENERATE NUMBER";
        $probability = mt_rand(1, 100);

        //95% probability
        if ($probability <= 95) {
            $randomPhone = $this->generateRandPhoneNumber();
            $response = (object) [
                "message" => $m,
                "match" => false, 
                "phone" => $randomPhone
            ];
            return response()->json($response);
        } else {
            //5% probability
            try {
                $interlocutor = Interlocutor::inRandomOrder()->first();
                $response = [
                    "message" => $m,
                    "match" => true, 
                    "interlocutor" => $interlocutor
                ];
            } catch (\Throwable $th) {
                $response = [
                    "message" => $m,
                    "error" => $th,
                    "match" => false,
                    "phone" => $this->generateRandPhoneNumber()
                ];
            }
            return response()->json($response);
        }
    }

    private function generateRandPhoneNumber() {
        $prefix = "6";
        $randomPhoneNumber = mt_rand(10000000, 99999999);
        return  $prefix . $randomPhoneNumber;
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
