<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\jadwal_harian;
use App\Models\jadwal_umum;
use App\Models\instruktur;
use App\Models\kelas;
use App\Models\presensi_instruktur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PresensiInstrukturController extends Controller
{
    public function showSchedule(){
        // $today = Carbon::today();
        $today = '2023-06-1';

        $jadwal = DB::select(
            'SELECT a.* from jadwal_harian a
            join jadwal_umum b
            on a.id = b.id
            join kelas k
            on b.id = k.id
            where a.tanggal = "' .$today. '";'
        );

        return response([
            'message' => 'Retrieve Jadwal dan kelas Success',
            'data' => $jadwal
        ], 200);
    }

    public function store(Request $request, $id_jadwal_harian){
        $storeData = $request->all();

        $jadwal = DB::select(
            'SELECT a.*, i.*, i.jumlah_terlambat, a.jam_mulai from jadwal_harian a
            join jadwal_umum b
            on a.id_jadwal_umum = b.id
            join kelas k
            on b.id_kelas = k.id
            join instruktur i
            on b.id_instruktur = i.id
            where a.id = "' .$id_jadwal_harian. '";'
        );

        // return response([
        //     'message' => 'Data Added',
        //     'data' => $jadwal
        // ], 200);

        $last = DB::table('presensi_instruktur')->latest()->first();
        if($last == null){
            $increment = 1;
        }else{
            $increment = ((int)Str::substr($last->id, 3,3)) + 1;
        }

        if($increment < 10){
            $increment = '00'.$increment;
        }else if($increment < 100){
            $increment = '0'.$increment;
        }

        // $tgl = Carbon::now()->toDateString();
        $tgl = '2023-06-1';
        // $tgl = carbon::today();

        // $jamSekarang = Carbon::now();
        $jamSekarang = Carbon::parse('10:15:00');

        $jamJadwal = Carbon::parse($jadwal[0]->jam_mulai);

        // return response([
        //     'message' => 'Data Added',
        //     'data' => $jamJadwal
        // ], 200);

        if($jamSekarang > $jamJadwal){
            $presensi_instruktur = presensi_instruktur::create([
                'no_presensi_instruktur' => 'PI'.'-'.$increment,
                'id_jadwal_harian' => $id_jadwal_harian,
                'jam_mulai' => $jamSekarang,
                'jam_selesai' => '00:00:00',
                'tgl_presensi' => $tgl,
            ]);

            $waktu_terlambat = $jamSekarang->diff(Carbon::parse($jamJadwal));
            $keterlambatanInstruktur = Carbon::parse($jadwal[0]->jumlah_terlambat);

            $hours = $waktu_terlambat->h;
            $minutes = $waktu_terlambat->i;
            $second = $waktu_terlambat->s;

            $totalKeterlambatan = $keterlambatanInstruktur->addHours($hours)->addMinutes($minutes)->addSeconds($second);
            $hasilKeterlambatan = $totalKeterlambatan->toTimeString();

            DB::table('instruktur')->where('id', $jadwal[0]->id)->update(['jumlah_terlambat'=> $hasilKeterlambatan]);
            
        }else{
            $presensi_instruktur = presensi_instruktur::create([
                'no_presensi_instruktur' => 'PI'.'-'.$increment,
                'id_jadwal_harian' => $id_jadwal_harian,
                'jam_mulai' => $jamSekarang,
                'jam_selesai' => '00:00:00',
                'tgl_presensi' => $tgl,
            ]);
        }

        return response([
            'message' => 'Data Added',
            'data' => $presensi_instruktur
        ], 200);
    }

    public function jamSelesai($id){
        // $jam_selesai = Carbon::now()->toTimeString();
        $jam_selesai = '09:30:00';

        $presensi = presensi_instruktur::find($id);
        $presensi->jam_selesai = $jam_selesai;
        $presensi->save();

        return response([
            'message' => 'Data Added',
            'data' => $presensi
        ], 200);
    }

}