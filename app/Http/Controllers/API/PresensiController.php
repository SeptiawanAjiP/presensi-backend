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
    function getPresensis()
    {
        $presensis = Presensi::select('presensis.*')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('waktu', 'desc')
                        ->get();
        foreach($presensis as $item) {
            $datetime = Carbon::parse($item->waktu)->locale('id');

            $datetime->settings(['formatFunction' => 'translatedFormat']);
            
            $item->tanggal = $datetime->format('l, j F Y');
            $item->waktu = $datetime->format('H:i');
        }

        return response()->json([
            'success' => true,
            'data' => $presensis,
            'message' => 'Sukses menampilkan data'
        ]);
    }
    function savePresensi(Request $request) 
    {
        $keterangan = "";
        $presensi = Presensi::whereDate('waktu', '=', date('Y-m-d'))
                        ->get();
        if (count($presensi) >= 2) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Hari ini sudah presensi masuk dan pulang'
            ]);
        }
        if (count($presensi) == 0) {
            $keterangan = "JAM MASUK";
        } else if (count($presensi) == 1) {
            $keterangan = "JAM PULANG";
        }
        $presensi_saved = Presensi::create([
            'user_id' => Auth::user()->id,
            'keterangan' => $keterangan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'waktu' => date('Y-m-d H:i:s')
        ]);
        return response()->json([
            'success' => true,
            'data' => $presensi_saved,
            'message' => 'Sukses simpan'
        ]);
    }
}
