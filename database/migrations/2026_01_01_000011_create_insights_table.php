<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('insight_type')->nullable(); // productivity, quality, strategic
            $table->text('message')->nullable();
            $table->string('alert_level')->default('low'); // low, medium, high
            $table->json('recommendations')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['garden_id', 'alert_level']);
            $table->index('insight_type');
            $table->index('generated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insights');
    }
};
