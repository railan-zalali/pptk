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
        Schema::create('afdelings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('total_area_ha', 10, 2);
            $table->decimal('tm_area_ha', 10, 2)->comment('Luas Tanaman Menghasilkan');
            $table->string('manager_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afdelings');
    }
};
