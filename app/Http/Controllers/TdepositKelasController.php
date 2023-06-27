<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use App\Models\deposit_kelas;
use App\Models\tdeposit_kelas;
use App\Models\member;
use App\Models\promo_kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TdepositKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tdeposit_kelas = tdeposit_kelas::with('pegawai','promo_kelas','member')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data tdeposit_kelas',
            'data'    => $tdeposit_kelas
        ], 200);
    }

    public function indexKadaluarsa()
    {
        $date = Carbon::now()->format('Y-m-d');
        $tdeposit_kelas = tdeposit_kelas::with('pegawai','promo_kelas','member')->where('expire_date', $date)->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data tdeposit_kelas',
            'data'    => $tdeposit_kelas
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
        /// user pilih kelas dan dicek apakah kelas tersebut ada promo atau tidak
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required', //show class and harga
            'id_member' => 'required', //put id memmber
            'id_pegawai' => 'required', // put id pegawai
            'jumlah_deposit_kelas' => 'required', // put berapa class dibeli
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //kelas find
        $kelas = kelas::find($request->id_kelas);
        if (!$kelas) {
            //data member not found
            return response()->json([
                'success' => false,
                'message' => 'kelas Not Found',
            ], 404);
        }

        //class detail find
        $member = member::find($request->id_member);
        if (!$member) {
            //data member not found
            return response()->json([
                'success' => false,
                'message' => 'member Not Found',
            ], 404);
        }

        //membuatIddengan format(Xy) X= huruf dan Y = angka
        $count = DB::table('tdeposit_kelas')->count() + 1;
        $id_generate = sprintf("%03d", $count);
        //membuat angka dengan format y
        $digitYear = Carbon::parse(now())->format('y');
        //membuat angka dengan format m
        $digitMonth = Carbon::parse(now())->format('m');
        //no aktivasi_history
        $no_struk_deposit_kelas = $digitYear . '.' . $digitMonth . '.' . $id_generate;

        // get date time now
        $tanggal_transaksi = Carbon::now();

        //setdefault when no promo
        $id_promo_kelas = null;
        $jumlah_kelas = $request->jumlah_deposit_kelas;
        $jumlah_deposit_kelas = $request->jumlah_deposit_kelas;
        $bonus_kelas = 0;
        $expire_date = null;
        //check what promo gofit have
        $promo_kelas = promo_kelas::all();
        foreach ($promo_kelas as $promo_kelas) {
            if ($jumlah_kelas == $promo_kelas['jumlah_kelas']) {
                $id_promo_kelas = $promo_kelas['id'];
                $bonus_kelas = $promo_kelas['bonus_kelas'];
                $expire_date_time = Carbon::now()->addMonth($promo_kelas['durasi_aktif']);
                $expire_date = $expire_date_time->toDateString();
                //jumlah package amount kalo ada dapet promo
                $jumlah_deposit_kelas = $jumlah_kelas + $bonus_kelas;
            }
        }


        //total harga base on promo class
        $total_harga = $jumlah_kelas * $kelas->harga;

        $tdeposit_kelas = tdeposit_kelas::create([
            'no_struk_deposit_kelas' => $no_struk_deposit_kelas,
            'id_promo_kelas' => $id_promo_kelas,
            'id_kelas' => $kelas->id,
            'id_member' => $request->id_member,
            'id_pegawai' => $request->id_pegawai,
            'tanggal_transaksi' => $tanggal_transaksi,
            'total_harga' => $total_harga,
            'jumlah_deposit_kelas' => $jumlah_deposit_kelas,
            'expire_date' => $expire_date
        ]);

        if ($tdeposit_kelas) {
            //memasukan deposit ke dalam deposit member
            $deposit_kelas = deposit_kelas::create([
                'id_kelas' => $tdeposit_kelas->id_kelas,
                'id_member' => $tdeposit_kelas->id_member,
                'jumlah_deposit_kelas' => $tdeposit_kelas->jumlah_deposit_kelas,
                'expire_date' => $tdeposit_kelas->expire_date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'tdeposit_kelas Created and add to deposit member successfully',
                'data recipt'    => $tdeposit_kelas,
                'data deposit member'    => $deposit_kelas
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'tdeposit_kelas Failed to Save',
                'data'    => $tdeposit_kelas
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
        //find tdeposit_kelas by ID
        $tdeposit_kelas = tdeposit_kelas::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data tdeposit_kelas',
            'data'    => $tdeposit_kelas
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
        $tdeposit_kelas = tdeposit_kelas::find($id);

        if ($tdeposit_kelas) {
            //delete tdeposit_kelas
            $tdeposit_kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'tdeposit_kelas Deleted',
            ], 200);
        }


        //data tdeposit_kelas not found
        return response()->json([
            'success' => false,
            'message' => 'tdeposit_kelas Not Found',
        ], 404);
    }
}
