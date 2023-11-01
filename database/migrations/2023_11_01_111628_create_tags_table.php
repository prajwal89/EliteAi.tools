<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->string('name')->unique(); // Name of the tag
            $table->string('slug')->unique(); // Unique slug for the tag
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('tags');
    }
};
