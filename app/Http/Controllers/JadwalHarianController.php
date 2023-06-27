<?php

namespace App\Http\Controllers;

use App\Models\instruktur;
use App\Models\jadwal_harian;
use App\Models\jadwal_umum;
use App\Models\kelas;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwal_harian = jadwal_harian::with('instruktur','kelas','jadwal_umum')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List Data jadwal harian',
            'data'    => $jadwal_harian
        ], 200);
    }

    public function generateWeek()
    {
        $jadwal_harian_list = jadwal_harian::all();
        $jadwal_umum = jadwal_umum::all();
        // $tanggal = $jadwal_harian_list->tanggal;

        if ($jadwal_harian_list->isEmpty() || $jadwal_harian_list->count() != $jadwal_umum->count()) {
            //delete all dulu
            
            DB::table('jadwal_harian')->delete();

            foreach ($jadwal_umum as $jadwal_umum) {

                //get tanggal sekarang dan cari tanggal di minggu ini
                $now = Carbon::now();
                $StartDate = $now->copy()->startOfWeek();
                $EndDate = $now->copy()->endOfWeek();
                
                if ($jadwal_umum['hari_jadwal'] == 'senin') {
                    $tanggal = $StartDate;
                } else if ($jadwal_umum['hari_jadwal'] == 'selasa') {
                    $tanggal = $StartDate->addDays(1);
                } else if ($jadwal_umum['hari_jadwal'] == 'rabu') {
                    $tanggal = $StartDate->addDays(2);
                } else if ($jadwal_umum['hari_jadwal'] == 'kamis') {
                    $tanggal = $StartDate->addDays(3);
                } else if ($jadwal_umum['hari_jadwal'] == 'jumat') {
                    $tanggal = $StartDate->addDays(4);
                } else if ($jadwal_umum['hari_jadwal'] == 'sabtu') {
                    $tanggal = $StartDate->addDays(5);
                } else if ($jadwal_umum['hari_jadwal'] == 'minggu') {
                    $tanggal = $StartDate->addDays(6);
                }


                $tanggal_fix = Carbon::parse($tanggal)->format('Y-m-d');
                $status = 'Berjalan';
                $hari_jadwal = Carbon::parse($tanggal_fix)->format('l');
                $jadwal_harian = jadwal_harian::firstOrCreate([
                    'id_instruktur' => $jadwal_umum['id_instruktur'],
                    'id_kelas' => $jadwal_umum['id_kelas'],
                    'id_jadwal_umum' => $jadwal_umum['id'],
                    'tanggal' => $tanggal_fix,
                    'kapasitas' => $jadwal_umum['kapasitas'],
                    'hari_jadwal' => $hari_jadwal,
                    'jam_mulai' => $jadwal_umum['jam_mulai'],
                    'jam_selesai' => $jadwal_umum['jam_selesai'],
                    'status' => $status,
                ]);
            }

            $jadwal_harian = jadwal_harian::all();

            if ($jadwal_harian) {

                return response()->json([
                    'success' => true,
                    'message' => 'jadwal_harian scheduled generated successfully',
                    'data'    => $jadwal_harian,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'jadwal_harian Failed to generate scheduled',
                    'data'    => $jadwal_harian,
                ], 409);
            }
        } else {
            foreach ($jadwal_harian_list as $jadwal_harian_list) {
                $tanggal = $jadwal_harian_list->tanggal;
                $hari_jadwal = Carbon::parse($tanggal)->format('l');
                $jadwal_harian_list->update([
                    'tanggal' => Carbon::parse($tanggal)->addDays(7),
                    'hari_jadwal' => $hari_jadwal
                ]);
            }
            $jadwal_harian_list = jadwal_harian::all();
            if ($jadwal_harian_list) {

                return response()->json([
                    'success' => true,
                    'message' => 'jadwal_harian scheduled generated successfully',
                    'data'    => $jadwal_harian_list,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'jadwal_harian Failed to generate scheduled',
                    'data'    => $jadwal_harian_list,
                ], 409);
            }
        }
    }

    /**
     * update when class is not available.
     *
     */
    public function update(Request $request, $id)
    {

        // $jadwal_harian = jadwal_harian::find($id);
        // if (!$jadwal_harian) {
        //     //data jadwal_harian not found
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'jadwal_harian Not Found',
        //     ], 404);
        // }

        // //validate form
        // // $validator = Validator::make($request->all(), [
        // //     'status' => 'required',
        // // ]);

        // // //response error validation
        // // if ($validator->fails()) {
        // //     return response()->json($validator->errors(), 400);
        // // }

        // // $jadwal_harian->update([
        // //     'status' => $request->status,
        // // ]);
        
        // if($jadwal_harian->status == "Berjalan"){
        //     $jadwal_harian->status = "Libur";
        // }else{ 
        //     $jadwal_harian->status = "Berjalan";
        // }

        $jadwal_harian = jadwal_harian::find($id);

        if(is_null($jadwal_harian)){
            return response([
                'message' => 'Data Not Found',
                'data' => null
            ], 404);
        }
        if($jadwal_harian->status == 'Berjalan'){
            $jadwal_harian->status = 'Libur';
        }else{
            $jadwal_harian->status = 'Berjalan';
        }
        

        if($jadwal_harian->save()){
            return response([
                'message' => 'Update Success',
                'data' => $jadwal_harian
            ], 200);
        }

        return response([
            'message' => 'Update Failed',
            'data' => null
        ], 400);
    }

        // if ($jadwal_harian) {

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'jadwal_harian scheduled update status successfully',
        //         'data'    => $jadwal_harian,
        //     ], 201);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'jadwal_harian Failed to update status scheduled',
        //         'data'    => $jadwal_harian,
        //     ], 409);
        // }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find jadwal_harian by ID
        $jadwal_harian = jadwal_harian::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data jadwal_harian',
            'data'    => $jadwal_harian
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
        $jadwal_harian = jadwal_harian::find($id);

        if ($jadwal_harian) {
            //delete jadwal_harian
            $jadwal_harian->delete();

            return response()->json([
                'success' => true,
                'message' => 'jadwal_harian Deleted',
            ], 200);
        }


        //data jadwal_harian not found
        return response()->json([
            'success' => false,
            'message' => 'jadwal_harian Not Found',
        ], 404);
    }
    
}
