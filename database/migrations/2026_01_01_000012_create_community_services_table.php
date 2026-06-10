<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garden_id')->nullable()->constrained('gardens')->onDelete('set null');
            $table->string('activity_name');
            $table->string('team_name');
            $table->decimal('total_budget', 15, 2);
            $table->decimal('remaining_budget', 15, 2)->default(0);
            $table->year('year');
            $table->string('status')->default('planned'); // planned, ongoing, completed
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('year');
            $table->index('status');
            $table->index('garden_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_services');
    }
};
