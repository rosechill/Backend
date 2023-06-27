<?php

namespace App\Http\Controllers;

use App\Models\promo_kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promo_kelas = promo_kelas::with('kelas')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data promo_kelas',
            'data'    => $promo_kelas
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
            'id_kelas' => 'required',
            'jumlah_kelas' => 'required',
            'bonus_kelas' => 'required',
            'durasi_aktif' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $promo_kelas = promo_kelas::create([
            'id_kelas' => $request->id_kelas,
            'jumlah_kelas' => $request->jumlah_kelas,
            'bonus_kelas' => $request->bonus_kelas,
            'durasi_aktif' => $request->durasi_aktif,
        ]);

        if ($promo_kelas) {

            return response()->json([
                'success' => true,
                'message' => 'promo_kelas Created',
                'data'    => $promo_kelas
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'promo_kelas Failed to Save',
                'data'    => $promo_kelas
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
        //find promo_kelas by ID
        $promo_kelas = promo_kelas::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data promo_kelas',
            'data'    => $promo_kelas
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
        $promo_kelas = promo_kelas::find($id);
        if (!$promo_kelas) {
            //data promo_kelas not found
            return response()->json([
                'success' => false,
                'message' => 'promo_kelas Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'jumlah_kelas' => 'required',
            'bonus_kelas' => 'required',
            'durasi_aktif' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update promo_kelas with new image
        $promo_kelas->update([
            'id_kelas' => $request->id_kelas,
            'jumlah_kelas' => $request->jumlah_kelas,
            'bonus_kelas' => $request->bonus_kelas,
            'durasi_aktif' => $request->durasi_aktif,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'promo_kelas Updated',
            'data'    => $promo_kelas
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
        $promo_kelas = promo_kelas::find($id);

        if ($promo_kelas) {
            //delete promo_kelas
            $promo_kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'promo_kelas Deleted',
            ], 200);
        }


        //data promo_kelas not found
        return response()->json([
            'success' => false,
            'message' => 'promo_kelas Not Found',
        ], 404);
    }
}