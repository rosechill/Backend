<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class kelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelas = kelas::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data kelas',
            'data'    => $kelas
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
            'nama_kelas' => 'required',
            'harga' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $kelas = kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'harga' => $request->harga,
        ]);

        if ($kelas) {

            return response()->json([
                'success' => true,
                'message' => 'kelas Dibuat',
                'data'    => $kelas
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'kelas Gagal Disimpan',
                'data'    => $kelas
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
        //find kelas by ID
        $kelas = kelas::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data kelas',
            'data'    => $kelas
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
        $kelas = kelas::find($id);
        if (!$kelas) {
            //data Kelas Tidak Ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Kelas Tidak Ditemukan',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required',
            'harga' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update kelas 
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'harga' => $request->harga,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'kelas Updated',
            'data'    => $kelas
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
        $kelas = kelas::find($id);

        if ($kelas) {
            //delete kelas
            $kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'kelas Dihapus',
            ], 200);
        }


        //data Kelas Tidak Ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Kelas Tidak Ditemukan',
        ], 404);
    }
}
