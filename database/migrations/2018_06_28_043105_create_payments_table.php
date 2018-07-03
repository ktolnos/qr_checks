<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payer_id');
            $table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('check_id')->nullable();
            $table->foreign('check_id')->references('id')->on('checks');
            $table->unsignedInteger('payee_id')->nullable();
            $table->foreign('payee_id')->references('id')->on('users');
            $table->decimal('amount');
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
        Schema::dropIfExists('payments');
    }
}
