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
        $presensis_array = [];
        $tanggals = DB::SELECT("SELECT DATE(waktu) as tanggal FROM `presensis` GROUP BY DATE(waktu) ORDER BY DATE(waktu) DESC");

        foreach($tanggals as $item) {
            $presensis = Presensi::select('id', 'keterangan', 'waktu')
                            ->whereDate('waktu', $item->tanggal)->get();
            foreach($presensis as $p) {
                $datetime = Carbon::parse($p->waktu)->locale('id');

                $datetime->settings(['formatFunction' => 'translatedFormat']);
                
                $p->waktu = $datetime->format('H:i');
            }
            $datetime = Carbon::parse($item->tanggal)->locale('id');

            $datetime->settings(['formatFunction' => 'translatedFormat']);
            
            $obj = new stdClass();
            $obj->tanggal = $datetime->format('l, j F Y');
            $obj->presensis = $presensis;
            if (date('Y-m-d') == $item->tanggal) {
                $obj->is_hari_ini = true;
            } else {
                $obj->is_hari_ini = false;
            }
            
            array_push($presensis_array, $obj);
        }

        return response()->json([
            'success' => true,
            'data' => $presensis_array,
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
