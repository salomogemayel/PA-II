<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengunjung_undangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengunjung_id');
            $table->unsignedBigInteger('undangan_id');
            $table->timestamps();
        
            $table->foreign('pengunjung_id')->references('id')->on('pengunjung')->onDelete('cascade');
            $table->foreign('undangan_id')->references('id')->on('undangan_pengunjung')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengunjung_undangan');
    }
};
