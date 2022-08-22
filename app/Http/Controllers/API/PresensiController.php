<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Auth;
use Carbon\Carbon;
use DB;
use stdClass;
date_default_timezone_set("Asia/Jakarta");

class PresensiController extends Controller
{
    function getPresensis()
    {
        $presensis = Presensi::where('user_id', Auth::user()->id)->get();
        foreach($presensis as $item) {
            if ($item->tanggal == date('Y-m-d')) {
                $item->is_hari_ini = true;
            } else {
                $item->is_hari_ini = false;
            }
            $datetime = Carbon::parse($item->tanggal)->locale('id');
            $masuk = Carbon::parse($item->masuk)->locale('id');
            $pulang = Carbon::parse($item->pulang)->locale('id');

            $datetime->settings(['formatFunction' => 'translatedFormat']);
            $masuk->settings(['formatFunction' => 'translatedFormat']);
            $pulang->settings(['formatFunction' => 'translatedFormat']);
            
            $item->tanggal = $datetime->format('l, j F Y');
            $item->masuk = $masuk->format('H:i');
            $item->pulang = $pulang->format('H:i');
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
        $presensi = Presensi::whereDate('tanggal', '=', date('Y-m-d'))
                        ->first();
        if ($presensi == null) {
            $presensi = Presensi::create([
                'user_id' => Auth::user()->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'tanggal' => date('Y-m-d'),
                'masuk' => date('H:i:s')
            ]);
        } else {
            $data = [
                'pulang' => date('H:i:s')
            ];

            Presensi::whereDate('tanggal', '=', date('Y-m-d'))->update($data);

        }
        $presensi = Presensi::whereDate('tanggal', '=', date('Y-m-d'))
                 ->first();
       
        return response()->json([
            'success' => true,
            'data' => $presensi,
            'message' => 'Sukses simpan'
        ]);
    }
}
