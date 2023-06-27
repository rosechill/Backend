<?php

namespace App\Http\Controllers;

use App\Models\instruktur_izin;
use App\Models\jadwal_harian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstrukturIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instruktur_izin = instruktur_izin::with('jadwal_harian', 'instruktur', 'instruktur_pengganti')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data instruktur_izin',
            'data'    => $instruktur_izin
        ], 200);
    }

    public function indexIdInstruktur($id)
    {
        $instruktur_izin = instruktur_izin::where('id_instruktur',$id)->with('jadwal_harian', 'instruktur', 'instruktur_pengganti')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data instruktur_izin',
            'data'    => $instruktur_izin
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
            'id_instruktur_pengganti' => 'required',
            'id_jadwal_harian' => 'required',
            'alasan' => 'required',
            'is_confirm' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $instruktur_izin = instruktur_izin::create([
            'id_instruktur' => $request->id_instruktur,
            'id_instruktur_pengganti' => $request->id_instruktur_pengganti,
            'id_jadwal_harian' => $request->id_jadwal_harian,
            'alasan' => $request->alasan,
            'is_confirm' => $request->is_confirm,
        ]);

        if ($instruktur_izin) {

            return response()->json([
                'success' => true,
                'message' => 'instruktur_izin Created',
                'data'    => $instruktur_izin
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'instruktur_izin Failed to Save',
                'data'    => $instruktur_izin
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
        //find instruktur_izin by ID
        $instruktur_izin = instruktur_izin::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data instruktur_izin',
            'data'    => $instruktur_izin
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
        $instruktur_izin = instruktur_izin::with('jadwal_harian', 'instruktur', 'instruktur_pengganti')->find($id);

        if(is_null($instruktur_izin)){
            return response([
                'message' => 'Data Not Found',
                'data' => null
            ], 404);
        }
        if($instruktur_izin->is_confirm == 0){
            $instruktur_izin->is_confirm = 1;
        }else{
            return response([
                'message' => 'sudah konfirmasi',
                'data' => null
            ], 400);
        }

        $jadwal_harian = jadwal_harian::find($instruktur_izin->id_jadwal_harian);
        $jadwal_harian->update([
            'id_instruktur' => $instruktur_izin->id_instruktur_pengganti,
            'status' => 'menggantikan '.$instruktur_izin->instruktur->nama_instruktur,
        ]);

        if($instruktur_izin->save()){
            return response([
                'message' => 'Update Success',
                'data' => $instruktur_izin
            ], 200);
        }

        return response([
            'message' => 'Update Failed',
            'data' => null
        ], 400);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $instruktur_izin = instruktur_izin::find($id);

        if ($instruktur_izin) {
            //delete instruktur_izin
            $instruktur_izin->delete();

            return response()->json([
                'success' => true,
                'message' => 'instruktur_izin Deleted',
            ], 200);
        }


        //data instruktur_izin not found
        return response()->json([
            'success' => false,
            'message' => 'instruktur_izin Not Found',
        ], 404);
    }
}
