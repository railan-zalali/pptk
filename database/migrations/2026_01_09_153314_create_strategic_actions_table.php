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
        Schema::create('strategic_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained()->onDelete('cascade');
            $table->date('period')->comment('Date or Month');
            $table->enum('action_type', ['fertilizer_root', 'fertilizer_leaf', 'cultivator', 'weed_control']);
            $table->decimal('target_volume', 10, 2);
            $table->decimal('realization_volume', 10, 2);
            $table->decimal('nitrogen_content', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategic_actions');
    }
};
