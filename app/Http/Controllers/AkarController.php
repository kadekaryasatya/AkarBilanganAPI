<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AkarBilangan; // Import model

class AkarController extends Controller
{
    public function hitungAkar(Request $request)
    {
        // Validasi input dari pengguna
        $validator = Validator::make($request->all(), [
            'bilangan' => 'required|numeric|min:0',
        ]);
        $id = auth()->user()->id;
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $bilangan = $request->input('bilangan');
        $waktuPemrosesanAwal = microtime(true);

        // Inisialisasi $kuadrat_manual
        $hasil_kuadrat = 0;

        if ($bilangan > 0) {
            // Perhitungan manual akar kuadrat
            $estimasi = $bilangan / 2;
            for ($i = 0; $i < 1000; $i++) {
                $estimasi_baru = 0.5 * ($estimasi + $bilangan / $estimasi);
                if (abs($estimasi_baru - $estimasi) < 1e-6) {
                    $hasil_kuadrat = $estimasi_baru;
                    break;
                }
                $estimasi = $estimasi_baru;
            }
        }

        $waktuPemrosesanAkhir = microtime(true);
        $waktuPemrosesan = round(($waktuPemrosesanAkhir - $waktuPemrosesanAwal) * 1000, 6); // Waktu dalam milidetik dengan 6 desimal
        $waktuPemrosesan = number_format($waktuPemrosesan, 6, '.', ''); // Format bilangan desimal

        // Simpan ke database
        $akarBilangan = new AkarBilangan();
        $akarBilangan->bilangan = $bilangan;
        $akarBilangan->akar =  $hasil_kuadrat;
        $akarBilangan->waktu_pemrosesan = $waktuPemrosesan;
        $akarBilangan->user_id = $id;
        $akarBilangan->save();

        return response()->json([
            'bilangan' => $bilangan,
            'akar' => $hasil_kuadrat,
            'waktu_pemrosesan' => $waktuPemrosesan,
        ]);

    }

    public function getAllData()
    {
        $akarBilangan = AkarBilangan::all();

        return response()->json($akarBilangan);
    }

}
