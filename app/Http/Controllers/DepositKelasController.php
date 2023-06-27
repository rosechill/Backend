<?php

namespace App\Http\Controllers;

use App\Models\deposit_kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deposit_kelas = deposit_kelas::with('member','kelas')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data deposit_kelas',
            'data'    => $deposit_kelas
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
            'id_member' => 'required',
            'jumlah_deposit_kelas' => 'required',
            'expire_date' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $deposit_kelas = deposit_kelas::create([
            'id_kelas' => $request->id_kelas,
            'id_member' => $request->id_member,
            'jumlah_deposit_kelas' => $request->jumlah_deposit_kelas,
            'expire_date' => $request->expire_date,
        ]);

        if ($deposit_kelas) {

            return response()->json([
                'success' => true,
                'message' => 'deposit_kelas Created',
                'data'    => $deposit_kelas
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'deposit_kelas Failed to Save',
                'data'    => $deposit_kelas
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
        //find deposit_kelas by ID
        $deposit_kelas = deposit_kelas::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data deposit_kelas',
            'data'    => $deposit_kelas
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
        $deposit_kelas = deposit_kelas::find($id);
        if (!$deposit_kelas) {
            //data deposit_kelas not found
            return response()->json([
                'success' => false,
                'message' => 'deposit_kelas Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'id_member' => 'required',
            'jumlah_deposit_kelas' => 'required',
            'expire_date' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update deposit_kelas with new image
        $deposit_kelas->update([
            'id_kelas' => $request->id_kelas,
            'id_member' => $request->id_member,
            'jumlah_deposit_kelas' => $request->jumlah_deposit_kelas,
            'expire_date' => $request->expire_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'deposit_kelas Updated',
            'data'    => $deposit_kelas
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
        $deposit_kelas = deposit_kelas::find($id);

        if ($deposit_kelas) {
            //delete deposit_kelas
            $deposit_kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'deposit_kelas Deleted',
            ], 200);
        }


        //data deposit_kelas not found
        return response()->json([
            'success' => false,
            'message' => 'deposit_kelas Not Found',
        ], 404);
    }
}