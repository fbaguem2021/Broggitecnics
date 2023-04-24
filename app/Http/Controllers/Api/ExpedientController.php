<?php

namespace App\Http\Controllers\Api;

use App\Models\Expedient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpedientResource;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function expedients_carta(Request $request) {
        $filtre = $request->query('municipi','');
        $municipi   = $request->query('municipi','');
        $provincia  = $request->query('provincia','');
        $comarca    = $request->query('comarca','');

        $query = DB::table('expedients')
            ->select('expedients.id','expedients.codi',

                // DB::raw('COUNT(expedients.id) as expedients_count'),
                DB::raw('CONCAT("[",GROUP_CONCAT(DISTINCT \'"\',interlocutors.id,\'"\'),"]") as interlocutors'),
                DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                DB::raw('CONCAT(\'[\', GROUP_CONCAT(DISTINCT CONCAT(\'"\',provincies.nom,",",municipis.nom,",",comarques.nom,\'"\') SEPARATOR ",") , \']\') AS full_loc'),
                DB::raw('GROUP_CONCAT(DISTINCT municipis.id  ) as municipis'),
                DB::raw('GROUP_CONCAT(DISTINCT provincies.id ) as provincies'),
                DB::raw('GROUP_CONCAT(DISTINCT comarques.id  ) as comarques'),
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
            // ->havingRaw('',[])
            // ->havingRaw('',[])
            // ->havingRaw('',[])
            ->orderBy('expedients.id');

        return response()->json($query->get());
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
