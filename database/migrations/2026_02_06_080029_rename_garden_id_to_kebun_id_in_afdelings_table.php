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
        Schema::table('afdelings', function (Blueprint $table) {
            $table->dropForeign(['garden_id']);
            $table->renameColumn('garden_id', 'kebun_id');
            $table->foreign('kebun_id')->references('id')->on('gardens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('afdelings', function (Blueprint $table) {
            $table->dropForeign(['kebun_id']);
            $table->renameColumn('kebun_id', 'garden_id');
            $table->foreign('garden_id')->references('id')->on('gardens')->onDelete('cascade');
        });
    }
};
