<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('role');
            $table->string('mentor')->nullable();
            $table->string('referal')->nullable();
            $table->string('password');
            $table->string('suspend')->default('no');
            //$table->string('email')->unique();
            $table->string('resi_add')->nullable();
            $table->string('busi_add')->nullable();
            $table->string('nature_add')->nullable();
            $table->string('number')->nullable();
            $table->bigInteger('savings_balance')->default('0');
            $table->bigInteger('loan_balance')->default('0');
            $table->string('passport')->nullable();
            $table->string('idcard')->nullable();
            $table->string('kin_name')->nullable();
            $table->string('kin_add')->nullable();
            $table->string('kin_relation')->nullable();
            $table->string('kin_number')->nullable();
            $table->string('kin_verify')->nullable();
            $table->string('kin_passport')->nullable();
            $table->string('gara1_name')->nullable();
            $table->string('gara1_add')->nullable();
            $table->string('gara1_occupation')->nullable();
            $table->string('gara1_number')->nullable();
            $table->string('gara1_verify')->nullable();
            $table->string('gara1_passport')->nullable();
            $table->string('gara2_name')->nullable();
            $table->string('gara2_add')->nullable();
            $table->string('gara2_occupation')->nullable();
            $table->string('gara2_number')->nullable();
            $table->string('gara2_verify')->nullable();
            $table->string('gara2_passport')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
