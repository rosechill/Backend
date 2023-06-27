<?php

namespace App\Http\Controllers;

use App\Models\taktivasi;
use App\Models\member;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TaktivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taktivasi = taktivasi::with('member','pegawai')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data taktivasi',
            'data'    => $taktivasi
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
        // Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
            'id_pegawai' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $member = member::find($request->id_member);
        if (!$member) {
            //data member not found
            return response()->json([
                'success' => false,
                'message' => 'member Not Found',
            ], 404);
        }
        //kalau member belum aktivasi maka buat baru

        //membuatIddengan format(Xy) X= huruf dan Y = angka
        $count = DB::table('taktivasi')->count() + 1;
        $id_generate = sprintf("%03d", $count);

        //membuat angka dengan format y
        $digitYear = Carbon::parse(now())->format('y');

        //membuat angka dengan format m
        $digitMonth = Carbon::parse(now())->format('m');

        //no taktivasi
        $no_taktivasi = $digitYear . '.' . $digitMonth . '.' . $id_generate;

        //set harga selalu 3 jt
        $harga = 3000000;

        // get date time now
        $tanggal_transaksi = Carbon::now();

        // set expired date 1 year after date time now
        $expired_date = Carbon::parse($tanggal_transaksi)->addYear()->toDateString();

        $taktivasi = taktivasi::create([
            'no_taktivasi' => $no_taktivasi,
            'id_member' => $request->id_member,
            'id_pegawai' => $request->id_pegawai,
            'tanggal_transaksi' => $tanggal_transaksi,
            'harga' => $harga,
            'expire_date' => $expired_date,
        ]);

        if ($taktivasi) {

            $member = member::find($request->id_member);
            $member->update([
                'masa_berlaku' => $expired_date,
                'status_membership' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'taktivasi ditambah',
                'data'    => $taktivasi,
                'data member'    => $member
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'taktivasi gagal dibuat',
                'data'    => $taktivasi
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
        //find taktivasi by ID
        $taktivasi = taktivasi::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data taktivasi',
            'data'    => $taktivasi
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
        $taktivasi = taktivasi::find($id);

        if ($taktivasi) {
            //delete taktivasi
            $taktivasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'taktivasi Deleted',
            ], 200);
        }


        //data taktivasi not found
        return response()->json([
            'success' => false,
            'message' => 'taktivasi Not Found',
        ], 404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function generate_taktivasiCard($id)
    // {
    //     $taktivasi = taktivasi::find($id);
    //     if (!$taktivasi) {
    //         //data taktivasi not found
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'taktivasi Not Found',
    //         ], 404);
    //     }

    //     $member = member::find($taktivasi->id_member);
    //     $pegawai = member::find($taktivasi->id_pegawai);

    //     $data = [
    //         'taktivasi' => $taktivasi,
    //         'member' => $member,
    //         'pegawai' => $pegawai,
    //     ];

    //     $pdf = Pdf::loadview('taktivasiCard', $data);

    //     return $pdf->download('taktivasi_Card_' . $member->name . '.pdf');
    // }
    // function downloadPDF($no_taktivasi, $id_member, $tanggal_transaksi, $expired_date, $id_pegawai, $nama_lengkap, $nama_lengkap){
    //     const pdf = new jsPDF({
    //         orientation: 'landscape',
    //         unit: 'cm',
    //         format: [8, 17]
    //     });

    //     pdf.text('Go Fit', 0.5, 1);
    //     pdf.text("No Struk : "+ $no_taktivasi, 11, 1);
    //     pdf.text('Jl. Centralpark No.10 Yogyakarta', 0.5, 2);
    //     pdf.text("Tanggal : "+ $tanggal_transaksi, 11, 2);
    //     pdf.text('', 0.5, 3);
    //     pdf.text("Member                    : "+ $id_member +" / " + $nama_lengkap, 0.5, 4);
    //     pdf.text("Aktivasi Tahunan      : Rp.3.000.000 ", 0.5, 5);
    //     pdf.text("Masa aktif member   : " + $expired_date, 0.5, 6);
    //     pdf.text("Kasir : " + $id_pegawai + " /" + $nama_lengkap, 11, 7);
    //     pdf.save("Struk Aktivasi-" + $id_member + ".pdf");
    // }
}