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
        Schema::create('top_search_tool_semantic_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('top_search_id');
            $table->unsignedBigInteger('tool_id');
            $table->decimal('score', 9, 8);
            $table->string('model_type')->nullable();
            $table->timestamps();

            $table->unique(['top_search_id', 'tool_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_search_tool_semantic_scores');
    }
};
