<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('epbm_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('epbm_periode_id');
            $table->integer('urutan'); // Question order
            $table->text('pertanyaan');
            $table->enum('jenis', ['rating', 'text'])->default('rating');
            $table->timestamps();

            $table->foreign('epbm_periode_id')->references('id')->on('epbm_periode')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epbm_pertanyaan');
    }
};
