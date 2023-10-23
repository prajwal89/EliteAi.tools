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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tag_name')->nullable();
            $table->longText('summary');
            $table->string('domain_name')->unique();
            $table->string('home_page_url');
            $table->boolean('has_api')->default(false);
            $table->json('top_features')->nullable();
            $table->json('use_cases')->nullable();
            $table->string('uploaded_screenshot')->nullable();
            $table->string('uploaded_favicon')->nullable();
            $table->bigInteger('owner_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
