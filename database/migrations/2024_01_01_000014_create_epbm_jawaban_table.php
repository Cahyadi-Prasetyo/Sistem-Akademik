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
        Schema::create('epbm_jawaban', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('epbm_pertanyaan_id');
            $table->unsignedBigInteger('rencana_studi_id');
            $table->string('nidn', 20); // Specific dosen being evaluated
            $table->integer('nilai_rating')->nullable(); // 1-5 scale
            $table->text('jawaban_text')->nullable(); // For text questions
            $table->timestamps();

            $table->foreign('epbm_pertanyaan_id')->references('id')->on('epbm_pertanyaan')->onDelete('cascade');
            $table->foreign('rencana_studi_id')->references('id')->on('rencana_studi')->onDelete('cascade');
            $table->foreign('nidn')->references('nidn')->on('dosen')->onDelete('cascade');
            
            // Unique constraint per question per student per dosen
            $table->unique(['epbm_pertanyaan_id', 'rencana_studi_id', 'nidn'], 'epbm_jawaban_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epbm_jawaban');
    }
};
