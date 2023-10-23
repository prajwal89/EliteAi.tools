<?php

use App\Enums\PricingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('pricing_type')->after('use_cases')->default(PricingType::OTHER->value);
        });
    }

    public function down()
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('pricing_type');
        });
    }
};
