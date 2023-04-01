<?php

namespace App\Http\Controllers;

use App\Models\Expedient;
use Illuminate\Routing\Controller;
use App\Http\Resources\ExpedientResource;

class ExpedientsController extends Controller
{
    public function index()
    {
        $reopenModal = true;
        return view('pages.expedients', compact('reopenModal'));
    }

    public function show($id) {
        request()->request->add([ 'include_cartes' => true]);

        $expedient = Expedient::find($id);

        return view('pages.showExpedient', compact('expedient'));
    }
}
