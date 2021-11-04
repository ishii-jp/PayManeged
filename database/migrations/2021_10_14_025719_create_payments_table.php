<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('category_id')->comment('カテゴリーID');
            $table->integer('user_id')->comment('入力者のユーザーID');
            $table->integer('year')->comment('年');
            $table->integer('month')->comment('月');
            $table->integer('price')->comment('金額');
            $table->timestamps();

            $table->unique(['category_id', 'user_id', 'year', 'month']);
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
