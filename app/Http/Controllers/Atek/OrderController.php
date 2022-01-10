<?php

namespace App\Http\Controllers\Atek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    function genOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pax_mobile'    => 'required|max:10|min:10',
            'pax_name'    => 'required',
            'pax_email'    => 'required',
            'unit'          => 'required',

            'sale_amt'      => 'required',
            'product_id'    => 'required',
            'pass_id'       => 'required',
            'app_id'        => 'required'
        ]);

        if (!$validator->fails()) {

            $productID      = $request->input('product_id');
            $passId         = $request->input('pass_id');
            $mediaTypeId    = env('MEDIA_TYPE_ID_MOBILE');
            $saleOrderNo    = $this->genOrderNo($productID, $passId);

            DB::table('sale_order')->insert([

                'sale_or_no'    => $saleOrderNo,
                'txn_date'      => now(),
                'pax_mobile'    => $request->input('pax_mobile'),
                'pax_name'      => $request->input('pax_email'),
                'pax_email'     => $request->input('pax_mobile'),
                'src_stn_id'    => $request->input('src_stn_id'),
                'des_stn_id'    => $request->input('des_stn_id'),
                'unit'          => $request->input('unit'),
                'sale_amt'      => $request->input('sale_amt'),
                'media_type_id' => $mediaTypeId,
                'product_id'    => $productID,
                'pass_id'       => $passId,
                'app_id'        => $request->input('app_id'),

            ]);

            return response([
                'status' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'sale_or_no'    => $saleOrderNo,
                ]
            ]);

        }

        return response([
            'status' => false,
            'message' => 'Failed to create order',
            'error' => $validator->errors()
        ]);

    }

    public function getOrder($order_no)
    {

    }

    public function genOrderNo($product_id, $pass_id)
    {
        return "AK"."1".$product_id.$pass_id.strtoupper(dechex(time()));
    }

}
