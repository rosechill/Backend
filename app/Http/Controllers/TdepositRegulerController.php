<?php

namespace App\Http\Controllers;

use App\Models\tdeposit_reguler;
use App\Models\member;
use App\Models\promo_reguler;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TdepositRegulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tdeposit_reguler = tdeposit_reguler::with('member','pegawai','promo_reguler')->get();

        return response()->json([       
            'success' => true,
            'message' => 'List Data tdeposit_reguler',
            'data'    => $tdeposit_reguler
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
            'id_member' => 'required',
            'id_pegawai' => 'required',
            'jumlah_deposit' => 'required',
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

        //membuatIddengan format(Xy) X= huruf dan Y = angka
        $count = DB::table('tdeposit_reguler')->count() + 1;
        $id_generate = sprintf("%03d", $count);

        //membuat angka dengan format y
        $digitYear = Carbon::parse(now())->format('y');

        //membuat angka dengan format m
        $digitMonth = Carbon::parse(now())->format('m');

        //no aktivasi_history
        $no_struk_deposit_reguler = $digitYear . '.' . $digitMonth . '.' . $id_generate;

        // get date time now
        $tanggal_transaksi = Carbon::now();

        //setdefault when no promo
        $id_promo_reguler = null;
        $bonus = 0;
        $sisa_deposit = $member->jumlah_deposit_reguler;
        $total_deposit = $sisa_deposit + $request->jumlah_deposit + $bonus;
        
        //check what promo gofit have
        $promo_reguler = promo_reguler::all();
        foreach ($promo_reguler as $promo_reguler) {
            if ($member->jumlah_deposit_reguler >= $promo_reguler['min_deposit']) {
                if ($request->jumlah_deposit_reguler >= $promo_reguler['min_topup']) {
                    $id_promo_reguler = $promo_reguler['id'];
                    $bonus = $promo_reguler['bonus_uang'];
                    $sisa_deposit = $member->jumlah_deposit_reguler;
                    $total_deposit = $sisa_deposit + $request->jumlah_deposit + $bonus;
                }
            }
        }

        $tdeposit_reguler = tdeposit_reguler::create([
            'no_struk_deposit_reguler' => $no_struk_deposit_reguler,
            'id_promo_reguler' => $id_promo_reguler,
            'id_member' => $request->id_member,
            'id_pegawai' => $request->id_pegawai,
            'tanggal_transaksi' => $tanggal_transaksi,
            'jumlah_deposit' => $request->jumlah_deposit,
            'bonus' => $bonus,
            'sisa_deposit' => $sisa_deposit,
            'total_deposit' => $total_deposit
        ]);

        if ($tdeposit_reguler) {
            $member->update([
                'jumlah_deposit_reguler' => $total_deposit
            ]);
            return response()->json([
                'success' => true,
                'message' => 'tdeposit_reguler Created and member updated successfully',
                'data'    => $tdeposit_reguler,
                'member'  => $member
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'tdeposit_reguler Failed to Save',
                'data'    => $tdeposit_reguler
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
        //find tdeposit_reguler by ID
        $tdeposit_reguler = tdeposit_reguler::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data tdeposit_reguler',
            'data'    => $tdeposit_reguler
        ], 200);
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $tdeposit_reguler = tdeposit_reguler::find($id);
    //     if (!$tdeposit_reguler) {
    //         //data tdeposit_reguler not found
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'tdeposit_reguler Not Found',
    //         ], 404);
    //     }
    //     //validate form
    //     $validator = Validator::make($request->all(), [
    //         'id_member' => 'required',
    //         'id_pegawai' => 'required',
    //         'tanggal_transaksi' => 'required',
    //         'jumlah_deposit' => 'required',
    //         'bonus' => 'required',
    //         'sisa_deposit' => 'required',
    //         'total_deposit' => 'required'
    //     ]);

    //     //response error validation
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     //update tdeposit_reguler with new image
    //     $tdeposit_reguler->update([
    //         'no_struk_deposit_reguler$no_struk_deposit_reguler' => $request->no_struk_deposit_reguler$no_struk_deposit_reguler,
    //         'id_promo_reguler' => $request->id_promo_reguler,
    //         'id_member' => $request->id_member,
    //         'id_pegawai' => $request->id_pegawai,
    //         'tanggal_transaksi' => $request->tanggal_transaksi,
    //         'jumlah_deposit' => $request->jumlah_deposit,
    //         'bonus' => $request->bonus,
    //         'sisa_deposit' => $request->sisa_deposit,
    //         'total_deposit' => $request->total_deposit
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'tdeposit_reguler Updated',
    //         'data'    => $tdeposit_reguler
    //     ], 200);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tdeposit_reguler = tdeposit_reguler::find($id);

        if ($tdeposit_reguler) {
            //delete tdeposit_reguler
            $tdeposit_reguler->delete();

            return response()->json([
                'success' => true,
                'message' => 'tdeposit_reguler Deleted',
            ], 200);
        }


        //data tdeposit_reguler not found
        return response()->json([
            'success' => false,
            'message' => 'tdeposit_reguler Not Found',
        ], 404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function generate_tdeposit_regulerCard($id)
    // {
    //     $tdeposit_reguler = tdeposit_reguler::find($id);
    //     if (!$tdeposit_reguler) {
    //         //data tdeposit_reguler not found
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'tdeposit_reguler Not Found',
    //         ], 404);
    //     }

    //     $member = member::find($tdeposit_reguler->id_member);
    //     $pegawai = member::find($tdeposit_reguler->id_pegawai);

    //     $data = [
    //         'tdeposit_reguler' => $tdeposit_reguler,
    //         'member' => $member,
    //         'pegawai' => $pegawai,
    //     ];

    //     $pdf = Pdf::loadview('tdeposit_regulerCard', $data);

    //     return $pdf->download('tdeposit_reguler_Card_' . $member->name . '.pdf');
    // }
}
