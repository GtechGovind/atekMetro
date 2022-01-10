<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SaleOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order', function (Blueprint $table) {
            $table->id('sl_id');
            $table->string('sale_or_no');
            $table->dateTime('txn_date');
            $table->bigInteger('pax_mobile');
            $table->text('pax_name');
            $table->text('pax_email');
            $table->integer('src_stn_id')->nullable();
            $table->integer('des_stn_id')->nullable();
            $table->integer('unit');
            $table->double('sale_amt');
            $table->integer('media_type_id');
            $table->integer('product_id');
            $table->integer('pass_id');
            $table->integer('app_id');
            $table->string('app_or_no')->nullable();
            $table->string('app_txn_no')->nullable();
            $table->integer('sale_or_status')->default(1);
            $table->timestamp('insert_date')->default(DB::raw('CURRENT_TIMESTAMP'));;
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
