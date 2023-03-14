<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsiarioController extends Controller
{
    public function showLogin(){
        return view('auth.login');
    }
    public function login(Request $request){
        $username = $request->input('username');
        $pass= $request->input('passowrd1');

        $user= Usuario::where('username',$username)->first();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUsiarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsiarioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usiario  $usiario
     * @return \Illuminate\Http\Response
     */
    public function show(Usiario $usiario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Usiario  $usiario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usiario $usiario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUsiarioRequest  $request
     * @param  \App\Models\Usiario  $usiario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsiarioRequest $request, Usiario $usiario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usiario  $usiario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usiario $usiario)
    {
        //
    }
}
