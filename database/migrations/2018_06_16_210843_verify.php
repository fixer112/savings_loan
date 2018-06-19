<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Verify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifys', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('due_date')->nullable();
            $table->string('status')->default('pending');
            $table->string('loan');
            $table->string('form1');
            $table->string('form2');
            $table->string('form3');
            $table->string('reason')->nullable();
            $table->string('staff_id');
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
         Schema::dropIfExists('verifys');
    }
}
