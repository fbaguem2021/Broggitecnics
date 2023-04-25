<?php

namespace App\Http\Controllers\Api;

use App\Models\Expedient;
use App\Models\Comarca;
use App\Models\Municipi;
use App\Models\Provincia;
use Illuminate\Http\Request;
use App\Http\Controllers\Utilitat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpedientResource;
use GuzzleHttp\Handler\Proxy;

class ExpedientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter , $value = null, $direction = null)
    {

        // $query = Expedient::select('expedients.id', 'expedients.codi', 'expedients.estat_expedients_id', 'expedients.created_at', 'expedients.updated_at', DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
        //     ->leftJoin('cartes_trucades', 'cartes_trucades.expedients_id', '=', 'expedients.id')
        //     ->groupBy('expedients.id');

        $query = new Expedient;

        if ($filter == 'all') {

        } else if ($filter == 'orderBy'){
            $query->orderBy($value, $direction);
        } else  if ($filter == 'none'){

        } else if ($filter == 'cartaTrucada') {
            $query = DB::table('expedients')
                ->select('expedients.id','expedients.codi',
                        DB::raw('CONCAT("[",GROUP_CONCAT(DISTINCT \'"\',interlocutors.id,\'"\'),"]") as interlocutors'),
                        DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                        DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as tipus'),
                        DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
                ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
                ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
                ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
                ->leftJoin('interlocutors', 'cartes_trucades.interlocutors_id', '=', 'interlocutors.id')
                // ->where('expedients.id','>=',0)
                ->groupBy('expedients.id','expedients.codi')
                ->orderBy('expedients.id');

            return response()->json($query->get());
        } else{
            $query->where($filter, $value);
        }

        $expedients = $query->paginate(8);

        return ExpedientResource::collection($expedients);
    }

    /**
     * Devuelve un json con la información de los expedientes para la carta de llamada
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $provincia
     * @param  mixed $comarca
     * @param  mixed $municipi
     * @return \Illuminate\Http\JsonResponse
     */
    public function expedients_carta(Request $request, mixed $provincia='', mixed $comarca='', mixed $municipi='') {

        // $filtre = $request->query('municipi','');

        if ($provincia != '') {
            $provincia = Provincia::find($provincia)->nom;
        }
        if ($municipi != '') {
            $municipi = Municipi::find($municipi)->nom;
        }
        if ($comarca != '') {
            $comarca = Comarca::find($comarca)->nom;
        }

        // $municipi  = Utilitat::getValueFromModel([Municipi::class, intval($request->query('municipi',  -1))],'nom','');
        // $provincia = Utilitat::getValueFromModel([Provincia::class, intval($request->query('provincia',  -1))],'nom','');
        // $comarca   = Utilitat::getValueFromModel([Comarca::class, intval($request->query('provincia',  -1))],'nom','');

        // $municipi   = \App\Models\Municipi::find(intval($request->query('municipi',  -1)))->nom  ?: '';
        // $provincia  = \App\Models\Provincia::find(intval($request->query('provincia', -1)))->nom ?: '';
        // $comarca    = \App\Models\Comarca::find(intval($request->query('comarca',   -1)))->nom   ?: '';

            $query = DB::table('expedients')
            ->select('expedients.id','expedients.codi',

                // DB::raw('COUNT(expedients.id) as expedients_count'),
                DB::raw('CONCAT("[",GROUP_CONCAT(DISTINCT \'"\',interlocutors.id,\'"\'),"]") as interlocutors'),
                DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                DB::raw('CONCAT(\'[\', GROUP_CONCAT(DISTINCT CONCAT(\'"\',provincies.nom,",",municipis.nom,",",comarques.nom,\'"\') SEPARATOR ",") , \']\') AS full_loc'),
                DB::raw('GROUP_CONCAT(DISTINCT municipis.nom  ) as municipis'),
                DB::raw('GROUP_CONCAT(DISTINCT provincies.nom ) as provincies'),
                DB::raw('GROUP_CONCAT(DISTINCT comarques.nom  ) as comarques'),
                // DB::raw('CONCAT(\'[\', GROUP_CONCAT(DISTINCT CONCAT(\'["\', provincies.nom,",",municipis.nom,",",comarques.nom,\'"]\')) , \']\') AS full_loc'),
                DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as tipus'),
                DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
            ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
            ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
            ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
            ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
            ->leftJoin('municipis', 'cartes_trucades.municipis_id', 'municipis.id')
            ->leftJoin('comarques','municipis.comarques_id','comarques.id')
            ->leftJoin('interlocutors', 'cartes_trucades.interlocutors_id', '=', 'interlocutors.id')
            // ->where('expedients.id','>=',0)
            ->groupBy('expedients.id','expedients.codi')
            // ->havingRaw('localitzacions like CONCAT("%", ? , "%") ',[$filtre])
            ->havingRaw('provincies LIKE CONCAT("%", ? , "%")',[$provincia])
            ->havingRaw('municipis LIKE CONCAT("%", ? , "%")',[$municipi])
            ->havingRaw('comarques LIKE CONCAT("%", ? , "%")',[$comarca])
            ->orderBy('expedients.id');

        $res = $query->get();

        // return response()->json($query->get());
        return response()->json($res);
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
     * @param  \App\Models\Expedient  $expedient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        request()->request->add([ 'include_cartes' => true]);
        $expedient = Expedient::select('expedients.*', DB::raw('COUNT(cartes_trucades.id) as cartes_count'))
                        ->leftJoin('cartes_trucades', 'cartes_trucades.expedients_id', '=', 'expedients.id')
                        ->where('expedients.id', $id)
                        ->groupBy('expedients.id')
                        ->first();

        return new ExpedientResource($expedient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expedient  $expedient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expedient = Expedient::find($id);

        if (!$expedient) {
            return response()->json(['message' => 'Expedient not found'], 404);
        }

        $expedient->estat_expedients_id = $request->input('estat_expedient_id');
        $expedient->save();

        return response()->json(['message' => 'Expedient updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expedient  $expedient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expedient $expedient)
    {
        //
    }
}
