<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index(){

        return view('App.front.index');
    }

    public function register(Request $request){


        $request->validate([
            'name'      => 'required|string|max:255|min:3|alpha',
            'email'     => 'required|string|email|max:255|unique:users',
            'apellido_paterno'=> 'required|string|max:255|min:3|alpha',
            'apellido_materno'=> 'required|string|max:255|min:3|alpha',
            'phone'     => 'required|string|max:8',
            'password'  => 'required|string|min:5',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'apellido_paterno'  => $request->apellido_paterno,
            'apellido_materno'  => $request->apellido_materno,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'user_type'         => $request->user_type ?? 'patient',
        ]);

        Auth::login($user);
        return redirect()->intended('/admin');
        // return redirect()->route('index'); // o adonde quieras llevarlo
    }
}
