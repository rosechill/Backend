<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Http\Resources\PegawaiResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Helper\Table;

class pegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pegawai = pegawai::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data pegawai',
            'data'    => $pegawai
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
            'nama_pegawai' => 'required|unique:pegawai',
            'role' => 'required',
            'gender' => 'required',
            'tanggal_lahir' => 'required',
            'nomor_telp' => 'required',
            'alamat' => 'required',    
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //membuatIddengan format(Xy) X= huruf dan Y = angka
        
        if(DB::table('pegawai')->count() == 0){
            $id_terakhir = 0;
        }else{
            $id_terakhir = pegawai::latest('id')->first()->id;
        }
        $count = $id_terakhir + 1;
        $id_generate = sprintf("%02d", $count);

        //membuat password dengan format dmy
        $datePass = Carbon::parse($request->tanggal_lahir)->format('dmY');
        $password = bcrypt($datePass);

        //no pegawai
        $no_pegawai = 'P' . $id_generate;

        $pegawai = pegawai::create([
            'no_pegawai' => $no_pegawai,
            'nama_pegawai' => $request->nama_pegawai,
            'role' => $request->role,
            'gender' => $request->gender,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telp' => $request->nomor_telp,
            'alamat' => $request->alamat,
            'username' => $no_pegawai,
            'password' => $password,   
        ]);

        if ($pegawai) {
            return response()->json([
                'success' => true,
                'message' => 'pegawai Created',
                'data diri pegawai'    => $pegawai
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'pegawai Failed to Save',
                'data diri pegawai'    => $pegawai
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
        //find pegawai by ID
        $pegawai = pegawai::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data pegawai',
            'data'    => $pegawai
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
        $pegawai = pegawai::find($id);
        if (!$pegawai) {
            //data pegawai not found
            return response()->json([
                'success' => false,
                'message' => 'pegawai Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'nama_pegawai' => 'required',
            'tanggal_lahir' => 'required',
            'nomor_telp' => 'required',
            'alamat' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pegawai->update([
            'nama_pegawai' => $request->nama_pegawai,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telp' => $request->nomor_telp,
            'alamat' => $request->alamat,  
        ]);

        return response()->json([
            'success' => true,
            'message' => 'pegawai Updated',
            'data'    => $pegawai
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
        $pegawai = pegawai::find($id);

        if ($pegawai) {
            //delete pegawai
            $pegawai->delete();

            return response()->json([
                'success' => true,
                'message' => 'pegawai Deleted',
            ], 200);
        }


        //data pegawai not found
        return response()->json([
            'success' => false,
            'message' => 'pegawai Not Found',
        ], 404);
    }
}
