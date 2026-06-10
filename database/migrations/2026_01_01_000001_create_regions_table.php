<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('regional_code')->unique();
            $table->string('regional_name');
            $table->string('province');
            $table->string('coordinates')->nullable();
            $table->string('photo_path')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('regional_name');
            $table->index('province');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
