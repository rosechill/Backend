<?php

namespace App\Http\Controllers;

use App\Models\booking_gym;
use App\Models\gym;
use App\Models\member;
use App\Models\booking_gym_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;

class BookingGymController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking_gym = booking_gym::with('gym', 'member')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data booking_gym',
            'data' => $booking_gym
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
            'id_gym' => 'required',
            'id_member' => 'required',
            'tanggal' => 'required'
        ]);

        if (DB::table('booking_gym')->count() == 0) {
            $id_terakhir = 0;
        } else {
            $id_terakhir = booking_gym::latest('id')->first()->id;
        }
        $count = $id_terakhir + 1;
        $id_generate = sprintf("%03d", $count);

        //membuat angka dengan format y
        $digitYear = Carbon::parse(now())->format('y');

        //membuat angka dengan format m
        $digitMonth = Carbon::parse(now())->format('m');

        //no  
        $no_booking_gym = $digitYear . '.' . $digitMonth . '.' . $id_generate;
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $member = $request->id_member;
        $findMember = member::find($member);
        if($findMember -> status_membership == 1){
             // Ambil Kelas untuk kurangin kapasitas
        $gym = $request->id_gym;
        $findGym = gym::find($gym);

        // Untuk kurangin kapastias di gym
        $kapasitas = $findGym->kapasitas - 1;

        // untuk update kapasitas gym di table gym
        $findGym->kapasitas = $kapasitas;
        $findGym->save();

        $booking_gym = booking_gym::create([
            'no_booking_gym' => $no_booking_gym,
            'id_gym' => $request->id_gym,
            'id_member' => $request->id_member,
            'tanggal' => $request->tanggal,
            'status' => 0,
        ]);


        if ($booking_gym) {

            return response()->json([
                'success' => true,
                'message' => 'booking_gym Created',
                'data' => $booking_gym
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'booking_gym Failed to Save',
                'data' => $booking_gym
            ], 409);
        }
    }else{
        return response()->json([
            'success' => false,
            'message' => 'member tidak atif',
            'data' => null
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
        //find booking_gym by ID
        $booking_gym = booking_gym::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data booking_gym',
            'data' => $booking_gym
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
        $booking_gym = booking_gym::find($id);
        if (!$booking_gym) {
            //data booking_gym tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'booking_gym tidak ditemukan',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'id_gym' => 'required',
            'id_member' => 'required',
            'tanggal' => 'required'
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update booking_gym with new image
        $booking_gym->update([
            'id_gym' => $request->id_gym,
            'id_member' => $request->id_member,
            'tanggal' => $request->tanggal
        ]);

        return response()->json([
            'success' => true,
            'message' => 'booking_gym Updated',
            'data' => $booking_gym
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
        $booking_gym = booking_gym::find($id);

        if ($booking_gym) {
            //delete booking_gym
            $booking_gym->delete();

            return response()->json([
                'success' => true,
                'message' => 'booking_gym Deleted',
            ], 200);
        }


        //data booking_gym tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'booking_gym tidak ditemukan',
        ], 404);
    }

    public function presensi($id)
    {
        $booking_gym = booking_gym::find($id);

        $booking_gym->status = 1;

        $booking_gym->save();

        // $member = $request->id_member;
        // $findMember = $member->deposit;


        return response()->json([
            'success' => true,
            'message' => 'Presensi Kelas Berhasil',
            'data' => $booking_gym
        ], 200);
    }
}