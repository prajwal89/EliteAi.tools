<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing check constraint
        // DB::statement('ALTER TABLE tools DROP CHECK tools_chk_3');

        // Rename the column
        Schema::table('tools', function (Blueprint $table) {
            $table->renameColumn('vectors', '_vectors');
        });

        // Recreate the constraint (change 'NEW_CHECK_CONSTRAINT' to the actual constraint)
        // DB::statement('ALTER TABLE tools ADD CHECK json_valid(`_vectors`)');
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->renameColumn('_vectors', 'vectors');
        });
    }
};
