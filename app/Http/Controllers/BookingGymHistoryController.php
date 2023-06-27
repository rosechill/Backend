<?php

namespace App\Http\Controllers;

use App\Models\booking_gym;
use App\Models\gym;
use App\Models\booking_gym_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingGymHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking_gym_history = booking_gym_history::with('booking_gym')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data booking_gym_history',
            'data'    => $booking_gym_history
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
            'no_booking_gym_history' => 'required',
            'id_gym_booking' => 'required',
            'tanggal' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $booking_gym_history = booking_gym_history::create([
            'no_booking_gym_history' => $request->no_booking_gym_history,
            'id_gym_booking' => $request->id_gym_booking,
            'tanggal' => $request->tanggal,
        ]);

        if ($booking_gym_history) {

            return response()->json([
                'success' => true,
                'message' => 'booking_gym_history Created',
                'data'    => $booking_gym_history
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'booking_gym_history Failed to Save',
                'data'    => $booking_gym_history
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
        //find booking_gym_history by ID
        $booking_gym_history = booking_gym_history::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data booking_gym_history',
            'data'    => $booking_gym_history
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
        $booking_gym_history = booking_gym_history::find($id);
        if (!$booking_gym_history) {
            //data booking_gym_history tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'booking_gym_history tidak ditemukan',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'no_booking_gym_history' => 'required',
            'id_gym_booking' => 'required',
            'tanggal' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update booking_gym_history with new image
        $booking_gym_history->update([
            'no_booking_gym_history' => $request->no_booking_gym_history,
            'id_gym_booking' => $request->id_gym_booking,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'booking_gym_history Updated',
            'data'    => $booking_gym_history
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
        $booking_gym_history = booking_gym_history::find($id);

        if ($booking_gym_history) {
            //delete booking_gym_history
            $booking_gym_history->delete();

            return response()->json([
                'success' => true,
                'message' => 'booking_gym_history Deleted',
            ], 200);
        }


        //data booking_gym_history tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'booking_gym_history tidak ditemukan',
        ], 404);
    }
}