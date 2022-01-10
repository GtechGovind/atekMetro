<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SvMsBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_ms_booking', function (Blueprint $table) {
            $table->id();
            $table->dateTime('txn_date');
            $table->integer('mm_ms_acc_id');
            $table->string('sale_or_no');
            $table->string('ms_qr_no');
            $table->string('ms_qr_exp');
            $table->integer('op_type_id');
            $table->double('pass_price');
            $table->integer('media_type_id');
            $table->integer('product_id');
            $table->integer('pass_id');
            $table->timestamp('insert_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
