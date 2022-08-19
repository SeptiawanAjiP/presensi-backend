<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $users = User::get();
        return view('user', [
            'users' =>$users
        ]);
    }

    function create()
    {
        return view('create-user');
    }

    function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        // return redirect()->route('user');
        return redirect()->action('App\Http\Controllers\UserController@index');
    }
}
