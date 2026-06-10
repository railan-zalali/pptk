<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->constrained('gardens')->onDelete('restrict');
            $table->string('title');
            $table->date('visit_date');
            $table->unsignedTinyInteger('duration')->default(1)->comment('Durasi dalam jam');
            $table->unsignedSmallInteger('participants_count')->default(1);
            $table->text('participants_list')->nullable();
            $table->string('visitor_name')->nullable();
            $table->text('description');
            $table->text('objectives')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('purpose')->nullable();
            $table->unsignedTinyInteger('rating')->nullable()->comment('Rating 1-5');
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->softDeletes();
            $table->timestamps();

            $table->index(['garden_id', 'visit_date']);
            $table->index('visit_date');
            $table->index('status');
        });

        Schema::create('visit_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->index('visit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_photos');
        Schema::dropIfExists('visits');
    }
};
