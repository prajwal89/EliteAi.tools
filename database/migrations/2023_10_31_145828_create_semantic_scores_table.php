<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('semantic_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tool1_id');
            $table->unsignedBigInteger('tool2_id');
            $table->decimal('score', 9, 8);
            $table->timestamps();

            $table->foreign('tool1_id')->references('id')->on('tools')->onDelete('cascade');
            $table->foreign('tool2_id')->references('id')->on('tools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('semantic_scores');
    }
};
