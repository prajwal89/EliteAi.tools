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
        // First, change the data type to integer
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('telegram_promotional_message_id');
        });

        // Next, add a new nullable JSON column
        Schema::table('tools', function (Blueprint $table) {
            $table->json('telegram_promotional_message_data')->nullable()->after('contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
