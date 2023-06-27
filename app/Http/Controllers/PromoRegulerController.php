<?php

namespace App\Http\Controllers;

use App\Models\promo_reguler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoRegulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promo_reguler = promo_reguler::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data promo_reguler',
            'data'    => $promo_reguler
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
            'min_deposit' => 'required',
            'min_topup' => 'required',
            'bonus_uang' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $promo_reguler = promo_reguler::create([
            'min_deposit' => $request->min_deposit,
            'min_topup' => $request->min_topup,
            'bonus_uang' => $request->bonus_uang,
        ]);

        if ($promo_reguler) {

            return response()->json([
                'success' => true,
                'message' => 'promo_reguler Created',
                'data'    => $promo_reguler
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'promo_reguler Failed to Save',
                'data'    => $promo_reguler
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
        //find promo_reguler by ID
        $promo_reguler = promo_reguler::find($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data promo_reguler',
            'data'    => $promo_reguler
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
        $promo_reguler = promo_reguler::find($id);
        if (!$promo_reguler) {
            //data promo_reguler not found
            return response()->json([
                'success' => false,
                'message' => 'promo_reguler Not Found',
            ], 404);
        }
        //validate form
        $validator = Validator::make($request->all(), [
            'min_deposit' => 'required',
            'min_topup' => 'required',
            'bonus_uang' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //update promo_reguler with new image
        $promo_reguler->update([
            'min_deposit' => $request->min_deposit,
            'min_topup' => $request->min_topup,
            'bonus_uang' => $request->bonus_uang,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'promo_reguler Updated',
            'data'    => $promo_reguler
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
        $promo_reguler = promo_reguler::find($id);

        if ($promo_reguler) {
            //delete promo_reguler
            $promo_reguler->delete();

            return response()->json([
                'success' => true,
                'message' => 'promo_reguler Deleted',
            ], 200);
        }


        //data promo_reguler not found
        return response()->json([
            'success' => false,
            'message' => 'promo_reguler Not Found',
        ], 404);
    }
}
