<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('slack_app_id')->nullable()->after('pinterest_id');
            $table->string('slack_channel_id')->nullable()->after('pinterest_id');
            $table->string('figma_plugin_id')->nullable()->after('pinterest_id');
            $table->string('vimeo_introduction_video_id')->nullable()->after('pinterest_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('slack_app_id');
            $table->dropColumn('slack_channel_id');
            $table->dropColumn('figma_plugin_id');
            $table->dropColumn('vimeo_introduction_video_id');
        });
    }
};
