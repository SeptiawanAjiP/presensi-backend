<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $presensis = Presensi::select('presensis.*', 'users.name')
                        ->join('users', 'presensis.user_id', '=', 'users.id')
                        ->get();
        foreach($presensis as $item) {
            $datetime = Carbon::parse($item->waktu)->locale('id');

            $datetime->settings(['formatFunction' => 'translatedFormat']);
            
            $item->waktu = $datetime->format('l, j F Y - H:i a');
        }
        return view('home', [
            'presensis' => $presensis
        ]);
    }
}
