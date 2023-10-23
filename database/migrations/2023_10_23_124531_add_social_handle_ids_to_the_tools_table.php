<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('linkedin_company_id')->after('uploaded_favicon')->nullable();
            $table->string('linkedin_id')->after('uploaded_favicon')->nullable();
            $table->string('twitter_id')->after('uploaded_favicon')->nullable();
            $table->string('tiktok_id')->after('uploaded_favicon')->nullable();
            $table->string('instagram_id')->after('uploaded_favicon')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('instagram_id');
            $table->dropColumn('tiktok_id');
            $table->dropColumn('twitter_id');
            $table->dropColumn('linkedin_id');
            $table->dropColumn('linkedin_company_id');
        });
    }
};
