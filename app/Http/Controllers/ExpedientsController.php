<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpedientsController extends Controller
{
    public function index()
    {
        $reopenModal = true;
        return view('pages.expedients', compact('reopenModal'));
    }
}
