<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpedientsController extends Controller
{
    public function index()
    {
        $reopenModal = false;
        return view('pages.expedients', compact('reopenModal'));
    }
}
