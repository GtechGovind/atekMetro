<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pax_info', function (Blueprint $table) {
            $table->id('pax_id');
            $table->string('pax_name');
            $table->bigInteger('pax_mobile');
            $table->string('pax_email')->unique();
            $table->boolean('is_verified')->nullable();
            $table->string('pax_lang')->nullable();
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
        Schema::dropIfExists('users');
    }
}
