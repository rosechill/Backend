<?php

namespace App\Http\Controllers;

use App\Models\instruktur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Helper\Table;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instruktur = instruktur::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data instruktur',
            'data'    => $instruktur
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
            'nama_instruktur' => 'required|unique:instruktur',
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
        if(DB::table('instruktur')->count() == 0){
            $id_terakhir = 0;
        }else{
            $id_terakhir = instruktur::latest('id')->first()->id;
        }
        $count = $id_terakhir + 1;
        $id_generate = sprintf("%02d", $count);

        //membuat password dengan format dmy
        $datePass = Carbon::parse($request->tanggal_lahir)->format('dmY');
        $password = bcrypt($datePass);

        //no instruktur
        $no_instruktur = 'I' . $id_generate;

        //inisialisasi jml terlambat
        $jumlah_terlambat = 0;

        $instruktur = instruktur::create([
            'no_instruktur' => $no_instruktur,
            'nama_instruktur' => $request->nama_instruktur,
            'gender' => $request->gender,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telp' => $request->nomor_telp,
            'alamat' => $request->alamat,
            'username' => $no_instruktur,
            'password' => $password,  
            'jumlah_terlambat' => $jumlah_terlambat,
        ]);

        if ($instruktur) {
            return response()->json([
                'success' => true,
                'message' => 'instruktur Created',
                'data diri instruktur'    => $instruktur,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'instruktur Failed to Save',
                'data diri instruktur'    => $instruktur,
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
        //find instruktur by ID
        $instruktur = instruktur::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data instruktur',
            'data'    => $instruktur
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
        $instruktur = instruktur::find($id);
        if (!$instruktur) {
            //data instruktur not found
            return response()->json([
                'success' => false,
                'message' => 'instruktur Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'nama_instruktur' => 'required',
            'alamat' => 'required',
            'nomor_telp' => 'required',
            'tanggal_lahir' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update instruktur with new image
        $instruktur->update([
            'nama_instruktur' => $request->nama_instruktur,
            'alamat' => $request->alamat,
            'nomor_telp' => $request->nomor_telp,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'instruktur Updated',
            'data'    => $instruktur
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
        $instruktur = instruktur::find($id);

        if ($instruktur) {
            //delete instruktur
            $instruktur->delete();

            return response()->json([
                'success' => true,
                'message' => 'instruktur Deleted',
            ], 200);
        }


        //data instruktur not found
        return response()->json([
            'success' => false,
            'message' => 'instruktur Not Found',
        ], 404);
    }

    public function resetTerlambat()
    {
        // $firstDate = Carbon::now()->startOfMonth()->toDateString();
        // $dateNow = Carbon::now()->toDateString();
        
        // if($firstDate == $dateNow){
        //     $instruktur = instruktur::where('jumlah_terlambat', '>',0)->update(['jumlah_terlambat'=>0]);

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Jumlah Terlambat di reset',
        //         'data'    => $instruktur
        //     ], 200);
        // }else{
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Hanya bisa reset di awal bulan',
        //     ], 409);
        // }
        
        //buat penilaian
        $instruktur = instruktur::where('jumlah_terlambat', '>',0)->update(['jumlah_terlambat'=>0]);
    }

    

}
