<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semantic_scores', function (Blueprint $table) {
            $table->unique(['tool1_id', 'tool2_id']);
        });
    }

    public function down(): void
    {
        Schema::table('semantic_scores', function (Blueprint $table) {
            //
        });
    }
};
