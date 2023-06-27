<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\member;
use App\Models\pegawai;
use App\Models\instruktur;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member = member::where('username',$request->username)->first();
        $pegawai = pegawai::where('username',$request->username)->first();
        $instruktur = instruktur::where('username',$request->username)->first();
        
        if($member !=null){
            $loginMember = member::where('username',$request->username)->first();

            if(Hash::check($request->password, $loginMember->password)){
                $member = member::where('username',$request->username)->first();
            }else{
                return response([
                    'message' => 'email atau password salah ',
                    'data' => $member
                ], 404);
            }

            $token = $member->createToken('Authentication Token')->accessToken;
            return response([
                'message' => 'berhasil login sebagai member',
                'data' => $member, 
                'token'=> $token
            ]);
        }else if ($instruktur != null) {
            $loginInstruktur = instruktur::where('username',$request->username)->first();

            if(Hash::check($request->password, $loginInstruktur->password)){
                $instruktur = instruktur::where('username',$request->username)->first();
            }else{
                return response([
                    'message' => 'email atau password salah ',
                    'data' => $instruktur
                ], 404);
            }

            $token = $instruktur->createToken('Authentication Token')->accessToken;
            return response([
                'message' => 'berhasil login sebagai instruktur',
                'data' => $instruktur, 
                'token'=> $token
            ]);
        }else if($pegawai != null){
            $loginPegawai = pegawai::where('username',$request->username)->first();

            if(Hash::check($request->password, $loginPegawai->password)){
                $pegawai = pegawai::where('username',$request->username)->first();
            }else{
                return response([
                    'message' => 'email atau password salah ',
                    'data' => $pegawai
                ], 404);
            }

            $token = $pegawai->createToken('Authentication Token')->accessToken;
            return response([
                'message' => 'berhasil login sebagai pegawai',
                'data' => $pegawai, 
                'token'=> $token
            ]);
        }else{
            return response([
                'message' => 'email atau password salah',
            ], 400);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $member = member::where('username',$request->username)->first();
        $pegawai = pegawai::where('username',$request->username)->first();
        $instruktur = instruktur::where('username',$request->username)->first();
        
        if($pegawai && Hash::check($request->oldPassword, $pegawai->password)){
            $newPassword = bcrypt($request->newPassword);
            $pegawai->update([
                'password' => $newPassword
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diganti',
                'pegawai' => $pegawai,
            ], 200);

        }else if($instruktur && Hash::check($request->oldPassword, $instruktur->password)){
            $newPassword = bcrypt($request->newPassword);
            $instruktur->update([
                'password' => $newPassword
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diganti',
                'instruktur' => $instruktur,
            ], 200);

        }else  if($member && Hash::check($request->oldPassword, $member->password)){
            $newPassword = bcrypt($request->newPassword);
            $member->update([
                'password' => $newPassword
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diganti',
                'member' => $member,
            ], 200);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Password gagal diganti',
            ], 409);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Logout',
        ], 200);
    }

}
