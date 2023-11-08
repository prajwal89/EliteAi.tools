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
        Schema::create('top_searches', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->string('slug');
            $table->unsignedBigInteger('extracted_from_tool_id')->nullable();
            $table->unsignedBigInteger('search_id')->nullable();
            $table->json('_vectors')->nullable();
            $table->string('model_type')->nullable();
            $table->timestamps();

            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_searches');
    }
};
