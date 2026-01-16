<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content_html')->nullable();
            $table->json('meta')->nullable(); // Untuk menyimpan data terstruktur (anggaran, list kegiatan, dll)
            $table->json('files')->nullable(); // Untuk menyimpan path dokumen/laporan
            $table->string('hero_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
