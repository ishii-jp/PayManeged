<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_sums', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('ユーザーID');
            $table->integer('year')->comment('年');
            $table->integer('month')->comment('月');
            $table->integer('total_price')->comment('合計金額');
            $table->timestamps();

            $table->unique(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_sums');
    }
}
