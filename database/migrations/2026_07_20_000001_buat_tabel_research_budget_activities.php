<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_budget_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed dengan data yang sudah ada (hardcoded sebelumnya)
        $activities = [
            'Kina', 'KUT', 'Malabar/Sedep', 'Drone', 'GWP',
            'IPM Helopeltis', 'Bio Kompos', 'Daur Petik',
            'Inkubasi Riset', 'Inkubasi Booster',
        ];

        foreach ($activities as $i => $name) {
            DB::table('research_budget_activities')->insert([
                'name'       => $name,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('research_budget_activities');
    }
};
