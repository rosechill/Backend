<?php

namespace App\Http\Controllers;

use App\Models\jadwal_umum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwal_umum = jadwal_umum::with('instruktur','kelas','jadwal_harian')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data jadwal_umum',
            'data'    => $jadwal_umum
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
            'id_instruktur' => 'required',
            'id_kelas' => 'required',
            'hari_jadwal' => 'required',
            'jam_mulai' => 'required',        
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //jam harus dalam kontek H:i:s dibuatin string dulu
        $jam_mulai = Carbon::parse($request->jam_mulai)->format('H:i:s');

        //menambahkan 1 jam setelah start class karena emang sejam setelah start class pasti selesai
        $jam_selesai = Carbon::parse($jam_mulai)->addHour();

        //mengeset kapasitas karena max emang 10 saja (nanti kalo ada ikut berarti --)
        $kapasitas = 10;

        //cek apakah jadwal dan instuktur tersebut sudah ada atau belum
        $jadwal_umum_temp = jadwal_umum::all();
        foreach ($jadwal_umum_temp as $jadwal_umum_temp) {
            //intruktur = class = date = jam_mulai 
            if ($jadwal_umum_temp['id_instruktur'] == $request->id_instruktur && $jadwal_umum_temp['id_kelas'] == $request->id_kelas  && $jadwal_umum_temp['jam_mulai'] == $request->jam_mulai && $jadwal_umum_temp['hari_jadwal'] == $request->hari_jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'jadwal yang anda input sudah ada',
                ], 409);
            }
            // instuktur = date = start class
            else if ($jadwal_umum_temp['id_instruktur'] == $request->id_instruktur  && $jadwal_umum_temp['jam_mulai'] == $jam_mulai && $jadwal_umum_temp['hari_jadwal'] == $request->hari_jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'instruktur tersebut sudah ada di jadwal yang anda input',
                ], 409);
            }
        }

        $jadwal_umum = jadwal_umum::firstOrCreate([
            'id_instruktur' => $request->id_instruktur,
            'id_kelas' => $request->id_kelas,
            'hari_jadwal' => $request->hari_jadwal,
            'kapasitas' => $kapasitas,   
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
                 
        ]);

        if ($jadwal_umum) {

            return response()->json([
                'success' => true,
                'message' => 'jadwal_umum Created',
                'data'    => $jadwal_umum,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'jadwal_umum Failed to Save',
                'data'    => $jadwal_umum
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
        //find jadwal_umum by ID
        $jadwal_umum = jadwal_umum::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data jadwal_umum',
            'data'    => $jadwal_umum
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
        $jadwal_umum = jadwal_umum::find($id);
        if (!$jadwal_umum) {
            //data jadwal_umum not found
            return response()->json([
                'success' => false,
                'message' => 'jadwal_umum Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'id_instruktur' => 'required',
            'id_kelas' => 'required',
            'hari_jadwal' => 'required',
            'kapasitas' => 'required', 
            'jam_mulai' => 'required',
                    
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //jam harus dalam kontek H:i:s dibuatin string dulu
        $jam_mulai = Carbon::parse($request->jam_mulai)->format('H:i:s');

        //menambahkan 1 jam setelah start class karena emang sejam setelah start class pasti selesai
        $jam_selesai = Carbon::parse($jam_mulai)->addHour();

        //cek apakah jadwal dan instuktur tersebut sudah ada atau belum
        $jadwal_umum_temp = jadwal_umum::all();
        foreach ($jadwal_umum_temp as $jadwal_umum_temp) {
            //intruktur = class = date = jam_mulai 
            if ($jadwal_umum_temp['id_instruktur'] == $request->id_instruktur && $jadwal_umum_temp['id_kelas'] == $request->id_kelas  && $jadwal_umum_temp['jam_mulai'] == $request->jam_mulai && $jadwal_umum_temp['hari_jadwal'] == $request->hari_jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'jadwal yang anda input sudah ada',
                ], 409);
            }
            // instuktur = date = start class
            else if ($jadwal_umum_temp['id_instruktur'] == $request->id_instruktur  && $jadwal_umum_temp['jam_mulai'] == $jam_mulai && $jadwal_umum_temp['hari_jadwal'] == $request->hari_jadwal) {
                return response()->json([
                    'success' => false,
                    'message' => 'instruktur tersebut sudah ada di jadwal yang anda input',
                ], 409);
            }
        }

        //update jadwal_umum with new image
        $jadwal_umum->update([
            'id_instruktur' => $request->id_instruktur,
            'id_kelas' => $request->id_kelas,
            'hari_jadwal' => $request->hari_jadwal,
            'kapasitas' => $request->kapasitas, 
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,     
        ]);

        return response()->json([
            'success' => true,
            'message' => 'jadwal_umum Updated',
            'data'    => $jadwal_umum
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
        $jadwal_umum = jadwal_umum::find($id);

        if ($jadwal_umum) {
            //delete jadwal_umum
            $jadwal_umum->delete();

            return response()->json([
                'success' => true,
                'message' => 'jadwal_umum Deleted',
            ], 200);
        }


        //data jadwal_umum not found
        return response()->json([
            'success' => false,
            'message' => 'jadwal_umum Not Found',
        ], 404);
    }

    


}
