<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->boolean('is_telegram_promotional_message_sent')->default(false)->after('contact_email');
            $table->boolean('is_twitter_promotional_message_sent')->default(false)->after('contact_email');
        });
    }

    public function down()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('is_telegram_promotional_message_sent');
            $table->dropColumn('is_twitter_promotional_message_sent');
        });
    }
};
