<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Auth;
use Carbon\Carbon;
date_default_timezone_set("Asia/Jakarta");

class PresensiController extends Controller
{
    function savePresensi(Request $request) {
        $presensi = Presensi::create([
            'user_id' => Auth::user()->id,
            'keterangan' => $request->keterangan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'waktu' => date('Y-m-d H:i:s')
        ]);
        return response()->json([
            'success' => true,
            'data' => $presensi,
            'message' => 'Sukses'
        ]);
    }
}
