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
        Schema::table('visits', function (Blueprint $table) {
            $table->string('title')->nullable()->after('garden_id');
            $table->integer('duration')->nullable()->after('visit_date');
            $table->integer('participants_count')->nullable()->after('duration');
            $table->text('participants_list')->nullable()->after('participants_count');
            $table->text('description')->nullable()->after('participants_list');
            $table->text('objectives')->nullable()->after('description');
            $table->text('findings')->nullable()->after('objectives');
            $table->text('recommendations')->nullable()->after('findings');
            $table->integer('rating')->nullable()->after('recommendations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'duration',
                'participants_count',
                'participants_list',
                'description',
                'objectives',
                'findings',
                'recommendations',
                'rating',
            ]);
        });
    }
};
