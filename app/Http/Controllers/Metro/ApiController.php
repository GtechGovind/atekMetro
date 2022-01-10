<?php

namespace App\Http\Controllers\Metro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // SJT RJT
    function genSjtRjtTicket($data, $pgOrderId)
    {
        $OP_TYPE_ID = "1";
        $PG_ID = env('PG_ID');
        $BASE_URL = env("BASE_URL_MMOPL");
        $AUTHORIZATION = env("API_SECRET");

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL/qrcode/issueToken",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => '',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "data": {
                    "fare"                      : "' . $data->sale_amt . '",
                    "source"                    : "' . $data->src_stn_id . '",
                    "destination"               : "' . $data->des_stn_id . '",
                    "tokenType"                 : "' . $data->pass_id . '",
                    "supportType"               : "' . $data->media_type_id . '",
                    "qrType"                    : "' . $data->product_id . '",
                    "operationTypeId"           : "' . $OP_TYPE_ID . '",
                    "operatorId"                : "' . $data->app_id . '",
                    "operatorTransactionId"     : "' . $data->sale_or_no . '",
                    "name"                      : "' . $data->pax_name . '",
                    "email"                     : "' . $data->pax_email . '",
                    "mobile"                    : "' . $data->pax_mobile . '",
                    "activationTime"            : "' . $data->insert_date . '",
                    "trips"                     : "' . $data->unit . '"
                },
                "payment": {
                    "pass_price"                : "' . $data->sale_amt . '",
                    "pgId"                      : "' . $PG_ID . '",
                    "pgOrderId"                 : "' . $pgOrderId . '"
                }
            }',
            CURLOPT_HTTPHEADER => [
                "Authorization:  $AUTHORIZATION",
                'Content-Type:  application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // SV
    function genSVPass($data, $pgOrderId)
    {
        $BASE_URL_MMOPL = env('BASE_URL_MMOPL');
        $API_SECRET = env('API_SECRET');
        $PG_ID = env('PG_ID');

        $OP_TYPE_ID = "1";
        $REG_FEE = "0";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL_MMOPL/qrcode/issuePass",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "data": {
                "fare"                      : "' . $data->sale_amt . '",
                "tokenType"                 : "' . $data->pass_id . '",
                "supportType"               : "' . $data->media_type_id . '",
                "registrationFee"           : "' . $REG_FEE . '",
                "qrType"                    : "' . $data->product_id . '",
                "operationTypeId"           : "' . $OP_TYPE_ID . '",
                "operatorId"                : "' . $data->app_id . '",
                "name"                      : "' . $data->pax_name . '",
                "email"                     : "' . $data->pax_email . '",
                "mobile"                    : "' . $data->pax_mobile . '",
                "activationTime"            : "' . $data->insert_date . '",
                "operatorTransactionId"     : "' . $data->sale_or_no . '"
            },
            "payment": {
                "pass_price"                : "' . $data->sale_amt . '",
                "pgId"                      : "' . $PG_ID . '",
                "pgOrderId"                 : "' . $pgOrderId . '"
            }
        }',
            CURLOPT_HTTPHEADER => [
                "Authorization: $API_SECRET",
                'Content-Type: application/json',]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // SV TRIP
    function genSVTrip($data)
    {

        $BASE_URL_MMOPL = env('BASE_URL_MMOPL');
        $API_SECRET = env('API_SECRET');
        $OP_TYPE_ID = "1";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL_MMOPL/qrcode/issueTrip",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
             "data": {
                    "tokenType"             :   "' . $data->sale_amt . '",
                    "operationTypeId"       :   "' . $data->op_type_id . '",
                    "operatorId"            :   "' . $OP_TYPE_ID . '",
                    "name"                  :   "' . $data->pax_name . '",
                    "email"                 :   "' . $data->pax_email . '",
                    "mobile"                :   "' . $data->pax_mobile . '",
                    "activationTime"        :   "' . $data->insert_date . '",
                    "masterTxnId"           :   "' . $data->ms_qr_no . '",
                    "qrType"                :   "' . $data->product_id . '",
                    "tokenType"             :   "' . $data->pass_id . '"
                 }
            }',

            CURLOPT_HTTPHEADER => [
                "Authorization: $API_SECRET",
                'Content-Type: application/json',]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

    // STATUS API SLAVE
    function getSlaveStatus($slave)
    {

        $BASE_URL_MMOPL = env('BASE_URL_MMOPL');
        $API_SECRET = env('API_SECRET');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL_MMOPL/qrcode/status/$slave",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "Authorization: $API_SECRET",
                'Content-Type: application/json',]
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    // REFUND INFO
    function getRefundInfo($masterTxnID, $opTypeId)
    {

        $BASE_URL_MMOPL = env('BASE_URL_MMOPL');
        $API_SECRET = env('API_SECRET');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL_MMOPL/qrcode/refund/info?tokenType=81&masterTxnId=$BASE_URL_MMOPL&operatorId=$opTypeId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "Authorization: $API_SECRET",
                'Content-Type: application/json',]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

    // REFUND
    function refundTicket($data, $refund_or_no)
    {

        $BASE_URL_MMOPL = env('BASE_URL_MMOPL');
        $API_SECRET = env('API_SECRET');
        $OPERATION_ID = "6";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "$BASE_URL_MMOPL/qrcode/refund",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "data": {
                    "operatorId"                    : "' . $data -> operatorId . '",
                    "supportType"                   : "' . $data -> supportType . '",
                    "qrType"                        : "' . $data -> qrType . '",
                    "tokenType"                     : "' . $data -> tokenType . '",
                    "source"                        : "' . $data -> source . '",
                    "destination"                   : "' . $data -> destination . '",
                    "remainingBalance"              : "' . $data -> remainingBalance . '",
                    "details": {
                        "registration": {
                            "processingFee"         : "' . $data -> processingFee . '",
                            "refundType"            : "' . $data -> refundType . '",
                            "processingFeeAmount"   : "' . $data -> processingFeeAmount . '",
                            "refundAmount"          : "' . $data -> refundAmount . '",
                            "passPrice"             : "' . $data -> passPrice . '",
                        },
                        "pass": {
                            "processingFee"         : "' . $data -> processingFee . '",
                            "refundType"            : "' . $data -> refundType . '",
                            "processingFeeAmount"   : "' . $data -> processingFeeAmount . '",
                            "refundAmount"          : "' . $data -> refundAmount . '",
                            "passPrice"             : "' . $data -> passPrice . '",
                        }
                    },
                    "operatorTransactionId"         : "' . $refund_or_no . '",
                    "operationTypeId"               : "' . $OPERATION_ID . '",
                    "masterTxnId"                   : "' . $data -> masterTxnId . '"
                }
            }',
            CURLOPT_HTTPHEADER => [
                "Authorization: $API_SECRET",
                'Content-Type: application/json']
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }



}
