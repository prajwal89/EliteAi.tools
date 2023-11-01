<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tag_tool', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->unsignedBigInteger('tag_id'); // Foreign key for the tags table
            $table->unsignedBigInteger('tool_id'); // Foreign key for the tools table
            $table->timestamps(); // Created at and Updated at timestamps

            $table->unique(['tag_id', 'tool_id']); // Ensure a unique combination of tag_id and tool_id

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('tool_id')->references('id')->on('tools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tag_tool');
    }
};
