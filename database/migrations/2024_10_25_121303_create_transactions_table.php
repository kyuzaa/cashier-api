<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_meja');
            $table->json('items');
            $table->string('total_amount', 10);
            $table->enum('status', ['0', '1', '2']);
            $table->date('tanggal')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
