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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->date('visit_date');
            $table->integer('duration')->nullable();
            $table->integer('participants_count')->nullable();
            $table->text('participants_list')->nullable();
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->integer('rating')->nullable();
            $table->string('visitor_name');
            $table->text('purpose');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            
            $table->index('visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
