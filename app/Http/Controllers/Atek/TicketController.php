<?php

namespace App\Http\Controllers\Atek;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Metro\ApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    function genTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sale_or_no' => 'required'
        ]);

        if ($validator->fails()) {

            return response([
                'status' => false,
                'message' => 'Failed to authenticate !',
                'error' => $validator->errors()
            ]);

        }

        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $request->input('sale_or_no'))
            ->first();

        if (is_null($order)) {

            return response([
                'status' => false,
                'message' => 'Failed to get Order',
                'error' => 'Order does not exist !'
            ]);

        }

        $productId = $order->product_id;

        if ($productId == env('PRODUCT_SJT')) return $this->genSJTTicket($order);
        else if ($productId == env('PRODUCT_RJT')) return $this->genRJTTicket($order);
        else if ($productId == env('PRODUCT_SV')) return $this->genSVTicket($order);
        else if ($productId == env('PRODUCT_TP')) return $this->genTPTicket($order);

        return response([
            'status' => false,
            'message' => 'unknown error!',
            'error' => 'Please contact admin'
        ]);

    }

    // SJT
    function genSJTTicket($order)
    {
        $api = new ApiController();
        $response = $api->genSjtRjtTicket($order, "");

        if ($response == null) {
            return response([
                'status' => false,
                'message' => 'Failed to connect with mmopl',
                'error' => 'Please check your internet connection !'
            ]);
        }

        $Response = json_decode($response, false);

        if ($Response->status == "BSE") {
            return response([
                'status' => false,
                'message' => 'Failed to generate ticket',
                'error' => $Response->error
            ]);
        }

        DB::table('sjt_ms_booking')->insert([

            'txn_date' => Carbon::createFromTimestamp($Response->data->travelDate)->toDateTimeString(),
            'mm_ms_acc_id' => $Response->data->transactionId,
            'sale_or_no' => $order->sale_or_no,
            'ms_qr_no' => $Response->data->masterTxnId,
            'ms_qr_exp' => Carbon::createFromTimestamp($Response->data->masterExpiry)->toDateTimeString(),
            'op_type_id' => $Response->data->operatorId,
            'src_stn_id' => $order->src_stn_id,
            'des_stn_id' => $order->des_stn_id,
            'unit' => $order->unit,
            'unit_price' => ($order->sale_amt) / $order->unit,
            'total_price' => $order->sale_amt,
            'media_type_id' => $order->media_type_id,
            'product_id' => $order->product_id,
            'pass_id' => $order->pass_id,
            'travel_date' => Carbon::createFromTimestamp($Response->data->travelDate)->toDateTimeString(),

        ]);

        foreach ($Response->data->trips as $trip) {

            DB::table('sjt_sl_booking')->insert([

                'txn_date' => Carbon::createFromTimestamp($Response->data->travelDate)->toDateTimeString(),
                'mm_sl_acc_id' => $trip->transactionId,
                'mm_ms_acc_id' => $Response->data->transactionId,
                'sl_qr_no' => $trip->qrCodeId,
                'sl_qr_exp' => Carbon::createFromTimestamp($trip->expiryTime)->toDateTimeString(),
                'qr_dir' => $Response->data->qrType,
                'qr_data' => $trip->qrCodeData

            ]);

        }

        return response([
            'status' => true,
            'message' => 'Ticket generated successfully',
            'data' => $Response->data->masterTxnId
        ]);

    }

    // RJT
    function genRJTTicket($order)
    {
        $api = new ApiController();
        $response = $api->genSjtRjtTicket($order, "");

        if ($response == null) {
            return response([
                'status' => false,
                'message' => 'Failed to connect with mmopl',
                'error' => 'Please check your internet connection !'
            ]);
        }

        $Response = json_decode($response, false);

        if ($Response->status == "BSE") {
            return response([
                'status' => false,
                'message' => 'Failed to generate ticket',
                'error' => $Response->error
            ]);
        }

        DB::table('rjt_ms_booking')->insert([

            'txn_date' => Carbon::createFromTimestamp($Response->data->travelDate)->toDateTimeString(),
            'mm_ms_acc_id' => $Response->data->transactionId,
            'sale_or_no' => $order->sale_or_no,
            'ms_qr_no' => $Response->data->masterTxnId,
            'ms_qr_exp' => Carbon::createFromTimestamp($Response->data->masterExpiry)->toDateTimeString(),
            'op_type_id' => $Response->data->operatorId,
            'src_stn_id' => $order->src_stn_id,
            'des_stn_id' => $order->des_stn_id,
            'unit' => $order->unit,
            'unit_price' => ($order->sale_amt) / $order->unit,
            'total_price' => $order->sale_amt,
            'media_type_id' => $order->media_type_id,
            'product_id' => $order->product_id,
            'pass_id' => $order->pass_id,
            'travel_date' => Carbon::createFromTimestamp($Response->data->travelDate)->toDateTimeString(),

        ]);

        foreach ($Response->data->trips as $trip) {

            DB::table('rjt_sl_booking')->insert([

                'txn_date' => Carbon::createFromTimestamp($Response->data->travelDate)->toDateTimeString(),
                'mm_sl_acc_id' => $trip->transactionId,
                'mm_ms_acc_id' => $Response->data->transactionId,
                'sl_qr_no' => $trip->qrCodeId,
                'sl_qr_exp' => Carbon::createFromTimestamp($trip->expiryTime)->toDateTimeString(),
                'qr_dir' => $Response->data->qrType,
                'qr_data' => $trip->qrCodeData

            ]);

        }

        return response([
            'status' => true,
            'message' => 'Ticket generated successfully',
            'data' => $Response->data->masterTxnId
        ]);

    }




}
