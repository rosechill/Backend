<?php

namespace App\Http\Controllers;

use App\Models\booking_gym;
use App\Models\gym;
use App\Models\booking_gym_history;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
class BookingKelasHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking_kelas_history = booking_kelas_history::with('booking_kelas')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Booking Kelas',
            'data'    => $booking_kelas_history
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
            'no_kelas_history' => 'required',
            'id_booking_kelas' => 'required',
            'tanggal' => 'required',
            'sisa_deposit' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $booking_kelas_history = booking_kelas_history::create([
            'no_kelas_history' => $request->no_kelas_history,
            'id_booking_kelas' => $request->id_booking_kelas,
            'tanggal' => $request->tanggal,
            'sisa_deposit' => $request->sisa_deposit,
        ]);

        if ($booking_kelas_history) {

            return response()->json([
                'success' => true,
                'message' => 'booking_kelas_history Created',
                'data'    => $booking_kelas_history
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'booking_kelas_history Failed to Save',
                'data'    => $booking_kelas_history
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
        //find booking_kelas_history by ID
        $booking_kelas_history = booking_kelas_history::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data booking_kelas_history',
            'data'    => $booking_kelas_history
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
        $booking_kelas_history = booking_kelas_history::find($id);
        if (!$booking_kelas_history) {
            //data booking_kelas_history not found
            return response()->json([
                'success' => false,
                'message' => 'booking_kelas_history Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'no_kelas_history' => 'required',
            'id_booking_kelas' => 'required',
            'tanggal' => 'required',
            'sisa_deposit' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update booking_kelas_history with new image
        $booking_kelas_history->update([
            'no_kelas_history' => $request->no_kelas_history,
            'id_booking_kelas' => $request->id_booking_kelas,
            'tanggal' => $request->tanggal,
            'sisa_deposit' => $request->sisa_deposit,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'booking_kelas_history Updated',
            'data'    => $booking_kelas_history
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
        $booking_kelas_history = booking_kelas_history::find($id);

        if ($booking_kelas_history) {
            //delete booking_kelas_history
            $booking_kelas_history->delete();

            return response()->json([
                'success' => true,
                'message' => 'booking_kelas_history Deleted',
            ], 200);
        }


        //data booking_kelas_history not found
        return response()->json([
            'success' => false,
            'message' => 'booking_kelas_history Not Found',
        ], 404);
    }
}