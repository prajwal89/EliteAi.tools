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
        Schema::table('tools', function (Blueprint $table) {
            $table->string('model_type')->nullable()->after('vectors');
        });

        Schema::table('semantic_scores', function (Blueprint $table) {
            $table->string('model_type')->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->removeColumn('model_type');
        });

        Schema::table('semantic_scores', function (Blueprint $table) {
            $table->removeColumn('model_type');
        });
    }
};
