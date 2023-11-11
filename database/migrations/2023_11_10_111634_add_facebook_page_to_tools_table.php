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
            $table->string('facebook_page_id')->nullable()->after('facebook_id');
            $table->string('dribbble_id')->nullable()->after('facebook_id');
            $table->string('behance_id')->nullable()->after('facebook_id');

            $table->string('mac_store_id')->nullable()->after('ios_app_id');
            $table->string('window_store_id')->nullable()->after('ios_app_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            //
        });
    }
};
