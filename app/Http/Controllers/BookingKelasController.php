<?php

namespace App\Http\Controllers;

use App\Models\booking_kelas;
use App\Models\member;
use App\Models\jadwal_harian;
use App\Models\deposit_kelas;
use Carbon\Carbon;
use DB;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BookingKelasController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking_kelas = booking_kelas::with('jadwal_harian.kelas', 'member', 'jadwal_harian.instruktur')->get();
        

        return response()->json([
            'success' => true,
            'message' => 'List Data booking_kelas',
            'data'    => $booking_kelas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_jadwal_harian' => 'required',
            'id_member' => 'required',
        ]);
        
        $cekAlreadyExist = booking_kelas::all();
        foreach ($cekAlreadyExist as $cekAlreadyExist) {
            if ($cekAlreadyExist['id_jadwal_harian'] == $request->id_jadwal_harian &&  $cekAlreadyExist['id_member'] == $request->id_member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah booking)',
                ], 409);
            }
        }


        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //cek apakah dia sudah buat data yang sama
        $cekAlreadyExist = booking_kelas::all();
        foreach ($cekAlreadyExist as $cekAlreadyExist) {
            if ($cekAlreadyExist['id_jadwal_harian'] == $request->id_jadwal_harian &&  $cekAlreadyExist['id_member'] == $request->id_member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah booking :)',
                ], 409);
            }
        }

        //ambil semua element yang diperlukan
        $member = member::find($request->id_member);
        $jadwal_harian = jadwal_harian::with(['jadwal_umum.instruktur', 'jadwal_umum.kelas', 'instruktur'])->find($request->id_jadwal_harian);
        $deposit_kelas = deposit_kelas::where('id_member', $request->id_member)->where('id_kelas', $jadwal_harian->jadwal_umum->kelas->id)->with(['kelas', 'member'])->orderBy('created_at', 'desc')->first();

        //kalo libur ga bisa book`
        if ($jadwal_harian->status == "libur") {
            return response()->json([
                'success' => false,
                'message' => 'Kelas libur!',
            ], 409);
        }
        //buat ijin max h-1
        // $cekDateHMin1 = Carbon::parse($class_runnning_date->date)->subDay()->format('Y-m-d');
        // $dateNow = Carbon::now()->format('Y-m-d');
        // if ($jadwal_harian->tanggal <= $dateNow) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Tidak bisa booking kelas',
        //     ], 409);
        // }

        //memeriksa status aktif untuk member
        // if (!$member || $member->status_membership == 0) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Anda bukan member aktif',
        //         'data'    => $member
        //     ], 409);
        // }
        //memeriksa kuota penuh atau tidak di class_runnning
        if ($jadwal_harian->kapasitas <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas ini sudah penuh',
            ], 409);
        }

        //membuatIddengan format(Xy) X= huruf dan Y = angka
        if (DB::table('booking_kelas')->count() == 0) {
            $id_terakhir = 0;
        } else {
            $id_terakhir = booking_kelas::latest('id')->first()->id;
        }
        $count = $id_terakhir + 1;
        $id_generate = sprintf("%03d", $count);

        //membuat angka dengan format y
        $digitYear = Carbon::parse(now())->format('y');

        //membuat angka dengan format m
        $digitMonth = Carbon::parse(now())->format('m');

        //no  
        $no_booking_kelas = $digitYear . '.' . $digitMonth . '.' . $id_generate;

        //mengecek deposit paket untuk class detail yang sama dengan inputan ?
        if ($deposit_kelas != null && $deposit_kelas->jumlah_deposit_kelas > 0 && $deposit_kelas->expire_date > Carbon::now()->format('Y-m-d')) {
            //buat booking_kelas dan deposit_kelas_history

            $tanggal = Carbon::now();
            $booking_kelas = booking_kelas::create([
                'no_booking_kelas' => $no_booking_kelas,
                'id_jadwal_harian' => $request->id_jadwal_harian,
                'id_member' => $request->id_member,
                'tanggal' => $tanggal,
                'status' => 0,
            ]);

            // $deposit_kelas_history = $booking_kelas->deposit_kelas_history()->create([
            //     'no_deposit_kelas_history' => $no_booking_kelas,
            // ]);
            $booking_kelas_history = $booking_kelas->booking_kelas_history()->create([
                'no_kelas_history' => $no_booking_kelas,
            ]);

            if ($booking_kelas && $booking_kelas_history) {
                $jadwal_harian->update([
                    'kapasitas' => $jadwal_harian->kapasitas - 1
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'booking_kelas paket Created',
                    'data'    => $booking_kelas,
                    'history' => $booking_kelas_history
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'booking_kelas paket Failed to Save',
                    'data'    => $booking_kelas,
                    'history' => $booking_kelas_history
                ], 409);
            }
        } else if ($member->jumlah_deposit_reguler >= $jadwal_harian->jadwal_umum->kelas->harga) {
            //buat booking_kelas_history
            //buat booking_kelas dan booking_kelas_history

            $tanggal = Carbon::now();
            $booking_kelas = booking_kelas::create([
                'no_booking_kelas' => $no_booking_kelas,
                'id_jadwal_harian' => $request->id_jadwal_harian,
                'id_member' => $request->id_member,
                'tanggal' => $tanggal,
                'status' => 0,
            ]);

            $booking_kelas_history = $booking_kelas->booking_kelas_history()->create([
                'no_kelas_history' => $no_booking_kelas,
            ]);

            if ($booking_kelas && $booking_kelas_history) {
                $jadwal_harian->update([
                    'kapasitas' => $jadwal_harian->kapasitas - 1
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'booking_kelas dengan uang Created',
                    'data'    => $booking_kelas,
                    'history' => $booking_kelas_history
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'booking_kelas dengan uang Failed to Save',
                    'data'    => $booking_kelas,
                    'history' => $booking_kelas_history
                ], 409);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki paket kelas atau uang yang cukup',
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find booking_kelas by ID
        $booking_kelas = booking_kelas::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data booking_kelas',
            'data'    => $booking_kelas
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $booking_kelas = booking_kelas::find($id);
        if (!$booking_kelas) {
            //data booking_kelas not found
            return response()->json([
                'success' => false,
                'message' => 'booking_kelas Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'id_jadwal_harian' => 'required',
            'id_member' => 'required',
            'tanggal' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update booking_kelas with new image
        $booking_kelas->update([
            'id_jadwal_harian' => $request->id_jadwal_harian,
            'id_member' => $request->id_member,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'booking_kelas Updated',
            'data'    => $booking_kelas
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking_kelas = booking_kelas::find($id);

        if ($booking_kelas) {
            //delete booking_kelas
            $booking_kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'booking_kelas Deleted',
            ], 200);
        }


        //data booking_kelas not found
        return response()->json([
            'success' => false,
            'message' => 'booking_kelas Not Found',
        ], 404);
    }

    public function presensi($id) 
    {
        $booking_kelas = booking_kelas::find($id);

        $booking_kelas->status = 1;

        $booking_kelas->save();

        // $member = $request->id_member;
        // $findMember = $member->deposit;


        return response()->json([
            'success' => true,
            'message' => 'Presensi Kelas Berhasil',
            'data'    => $booking_kelas
        ], 200);
    }

}