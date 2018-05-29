<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Historys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('historys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recieved_by');
            $table->string('description');
            $table->string('debit');
            $table->string('credit');
            $table->string('approved');
            $table->string('type');
            $table->string('staff_id')->default('none');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historys');
    }
}
