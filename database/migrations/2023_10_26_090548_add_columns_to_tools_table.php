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
            $table->string('youtube_channel_id')->nullable()->after('linkedin_company_id');
            $table->string('facebook_id')->nullable()->after('linkedin_company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->removeColumn('youtube_channel_id');
            $table->removeColumn('facebook_id');
        });
    }
};
