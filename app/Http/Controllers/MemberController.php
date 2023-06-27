<?php

namespace App\Http\Controllers;

use App\Models\member;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class memberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = member::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data member',
            'data'    => $member
        ], 200);
    }

    public function indexKadaluarsa()
    {
        $date = Carbon::now()->format('Y-m-d');
        $member = member::where('masa_berlaku', $date)->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data member',
            'data'    => $member
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
            'nama_member' => 'required|unique:member',
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
        if(DB::table('member')->count() == 0){
            $id_terakhir = 0;
        }else{
            $id_terakhir = member::latest('id')->first()->id;
        }
        $count = $id_terakhir + 1;
        $id_generate = sprintf("%02d", $count);

        //membuat angka dengan format y
        $digitYear = Carbon::parse(now())->format('y');

        //membuat angka dengan format m
        $digitMonth = Carbon::parse(now())->format('m');

        //membuat password dengan format dmy
        $datePass = Carbon::parse($request->tanggal_lahir)->format('dmY');
        $password = bcrypt($datePass);

        //no member
        $no_member = $digitYear . '.' . $digitMonth . '.' . $id_generate;

        $member = member::create([
            'no_member' => $no_member,
            'nama_member' => $request->nama_member,
            'gender' => $request->gender,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telp' => $request->nomor_telp,
            'alamat' => $request->alamat, 
            'username' => $no_member,
            'password' => $password, 
            'jumlah_deposit_reguler' => null,
            'status_membership' => 0,
            'masa_berlaku' => null,
        ]);

        if ($member) {

            return response()->json([
                'success' => true,
                'message' => 'member Created',
                'data diri member'    => $member,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'member Failed to Save',
                'data diri member'    => $member,
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
        //find member by ID
        $member = member::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data member',
            'data'    => $member
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
        $member = member::find($id);
        if (!$member) {
            //data member not found
            return response()->json([
                'success' => false,
                'message' => 'member Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required',
            'tanggal_lahir' => 'required',
            'nomor_telp' => 'required',
            'alamat' => 'required',            
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update member with new image
        $member->update([
            'nama_member' => $request->nama_member,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telp' => $request->nomor_telp,
            'alamat' => $request->alamat,    
        ]);

        return response()->json([
            'success' => true,
            'message' => 'member Updated',
            'data'    => $member
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
        $member = member::find($id);

        if ($member) {
            //delete member
            $member->update([
                'status_membership' => 0,
                'masa_berlaku' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'member Deleted',
            ], 200);
        }


        //data member not found
        return response()->json([
            'success' => false,
            'message' => 'member Not Found',
        ], 404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateMemberCard($id)
    {
        $member = member::find($id);
        if (!$member) {
            //data member not found
            return response()->json([
                'success' => false,
                'message' => 'member Not Found',
            ], 404);
        }

        $data = [
            'title' => 'GoFit',
            'title2' => 'Member Card',
            'alamat' => 'Jl. Centralpark No. 10, Yogyakarta',
            'member' => $member
        ];
        
        //ada tutor di yt , tapi prosesnya masih panjang
        //butuh composer karena bukan bawaan laravel
        
        // $pdf = Pdf::loadview('memberCard', $data);

        // return $pdf->download('Member_Card_' . $member->name . '.pdf');
    }   


     /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request, $id)
    {
        $member = member::find($id);

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'newPassword' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($member) {
            $newPassword = bcrypt($request->newPassword);
            $member->update([
                'password' => $newPassword
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
                'member' => $member,
            ], 200);
            }else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password failed to change',
                ], 409);
            }
       }


}
