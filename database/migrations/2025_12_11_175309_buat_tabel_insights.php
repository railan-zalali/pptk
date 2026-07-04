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
        Schema::create('insights', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('garden_id')->constrained()->cascadeOnDelete();
            $table->string('insight_type');
            $table->text('message');
            $table->enum('alert_level', ['low', 'medium', 'high']);
            $table->json('recommendations')->nullable();
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['garden_id', 'insight_type']);
            $table->index('alert_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insights');
    }
};
