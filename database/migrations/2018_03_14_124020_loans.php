<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Loans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('due_date');
            $table->dateTime('week_due_date');
            $table->string('interest_status')->default('not paid');
            $table->string('due')->default('not paid');
            $table->Integer('skip_due')->default('0');
            $table->string('veri_remark');
            $table->string('loan_category');
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
        Schema::dropIfExists('loans');
    }
}
