<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('subreddit_id')->nullable()->after('youtube_channel_id');
            $table->string('telegram_channel_id')->nullable()->after('youtube_channel_id');
            $table->string('discord_channel_invite_id')->nullable()->after('youtube_channel_id');
        });
    }

    public function down(): void
    {
        //
    }
};
