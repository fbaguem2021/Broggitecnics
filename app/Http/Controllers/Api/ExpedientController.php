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
use Illuminate\Database\QueryException;
use App\Http\Resources\ExpedientResource;
use GuzzleHttp\Handler\Proxy;

class ExpedientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter)
    {
        try {
            if ($filter == 'none') {
                $query = Expedient::select('expedients.id', 'expedients.codi', 'expedients.estat_expedients_id', 'expedients.created_at', 'expedients.updated_at',
                DB::raw('COUNT(cartes_trucades.id) as cartes_count'),
                DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as incidents'))
                ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
                ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
                ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
                ->where('expedients.id','>=',0)
                ->groupBy('expedients.id')
                ->groupBy('expedients.codi')
                ->groupBy('expedients.estat_expedients_id')
                ->groupBy('expedients.created_at')
                ->groupBy('expedients.updated_at');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json($query->get());
    }

    public function indexGestio($filter , $value = null, $direction = null) {
        try {
            $query = Expedient::select('expedients.id', 'expedients.codi', 'expedients.estat_expedients_id', 'expedients.created_at', 'expedients.updated_at',
                        DB::raw('COUNT(cartes_trucades.id) as cartes_count'),
                        DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                        DB::raw('GROUP_CONCAT(DISTINCT tipus_incidents.nom) as incidents'))
                        ->leftJoin('cartes_trucades', 'expedients.id', '=', 'cartes_trucades.expedients_id')
                        ->leftJoin('incidents', 'cartes_trucades.incidents_id', '=', 'incidents.id')
                        ->leftJoin('tipus_incidents', 'incidents.tipus_incidents_id', '=', 'tipus_incidents.id')
                        ->leftJoin('provincies', 'cartes_trucades.provincies_id', '=', 'provincies.id')
                        ->where('expedients.id','>=',0)
                        ->groupBy('expedients.id')
                        ->groupBy('expedients.codi')
                        ->groupBy('expedients.estat_expedients_id')
                        ->groupBy('expedients.created_at')
                        ->groupBy('expedients.updated_at');

            if ($filter) {
                switch ($filter) {
                    case 'all':
                        $query->orderByDesc('updated_at');
                        break;
                    case 'estat_expedients_id':
                        $query->where('expedients.estat_expedients_id', '=', $value);
                        break;
                    case 'codi':
                        $query->where('expedients.codi', 'LIKE' ,"%$value%");
                        break;
                    case 'loc':
                        $value = strtolower($value);
                        $query->havingRaw(" LOWER(GROUP_CONCAT(DISTINCT provincies.nom)) LIKE '%$value%' ")
                               ->orderByDesc('updated_at');
                        break;
                    case 'inc':
                        $value = strtolower($value);
                        $query->havingRaw(" LOWER(GROUP_CONCAT(DISTINCT tipus_incidents.nom)) LIKE '%$value%' ")
                               ->orderByDesc('updated_at');
                        break;
                    case 'cartes_count':
                        $value = strtolower($value);
                        $operator = '';
                        $number = '';
                    
                        if (substr($value, 0, 1) === '<' || substr($value, 0, 1) === '>') {
                            $operator = substr($value, 0, 1);
                            $value = substr($value, 1);
                            if (substr($value, 0, 1) === '=') {
                                $operator .= '=';
                                $value = substr($value, 1);
                            }
                        }

                        $number = (int) $value;
                    
                        if ($operator !== '') {
                            $query->havingRaw("COUNT(cartes_trucades.id) $operator $number");
                        } else {
                            $query->havingRaw("LOWER(COUNT(cartes_trucades.id)) LIKE '%$value%'");
                        }
                        break;
                    case 'orderBy':
                        $query->orderBy($value, $direction);
                        break;
                }

            }


        } catch (\Throwable $th) {
            //throw $th;
        }

        $expedients = $query->get();


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
        if ($provincia != '') {
            $provincia = Provincia::find($provincia)->nom;
        }
        if ($municipi != '') {
            $municipi = Municipi::find($municipi)->nom;
        }
        if ($comarca != '') {
            $comarca = Comarca::find($comarca)->nom;
        }
            $query = DB::table('expedients')
            ->select('expedients.id','expedients.codi',
                DB::raw('expedients.estat_expedients_id as estat_id'),
                // DB::raw('COUNT(expedients.id) as expedients_count'),
                DB::raw('CONCAT("[",GROUP_CONCAT(DISTINCT \'"\',interlocutors.id,\'"\'),"]") as interlocutors'),
                DB::raw('GROUP_CONCAT(DISTINCT provincies.nom) as localitzacions'),
                DB::raw('CONCAT(\'[\', GROUP_CONCAT(CONCAT(\'"P: \',provincies.nom,", C: ",comarques.nom,", M: ",municipis.nom,\'"\') SEPARATOR ",") , \']\') AS full_loc'),
                DB::raw('CONCAT(\'[\', GROUP_CONCAT(CONCAT(\'"\',tipus_incidents.nom,": ",incidents.nom,\'"\') SEPARATOR ",") , \']\') AS full_inc'),
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
            ->groupBy('expedients.id','expedients.codi','expedients.estat_expedients_id')
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
        $data = $request->input('data');
        $expedient = new Expedient();
        $expedient->codi = $data['codi'];
        $expedient->estat_expedients_id = $data['estat_id'];

        try {
            $expedient->save();
            $idExp = $expedient->id;
            $response = \response()->json(["idExpedient"=>$idExp], 201);;
        } catch (QueryException $ex) {
            $mensaje = Utilitat::errorMessage($ex);
            $response = \response()->json(["error" => $mensaje], 400);
        }
        return $response;
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
                        ->groupBy('expedients.codi')
                        ->groupBy('expedients.estat_expedients_id')
                        ->groupBy('expedients.created_at')
                        ->groupBy('expedients.updated_at')
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
