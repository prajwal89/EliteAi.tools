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
            $table->string('chrome_extension_id')->after('ios_app_id')->nullable();
            $table->string('firefox_extension_id')->after('ios_app_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->removeColumn('chrome_extension_id');
            $table->removeColumn('firefox_extension_id');
        });
    }
};
