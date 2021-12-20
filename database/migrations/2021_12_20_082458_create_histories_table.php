<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('saldo_id');
            $table->enum('jenis',['Saldo Awal','Penambahan','Pengurangan']);
            $table->double('jumlah');
            $table->double('saldo');
            $table->timestamps();

            $table->foreign('saldo_id')->references('id')->on('saldos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
