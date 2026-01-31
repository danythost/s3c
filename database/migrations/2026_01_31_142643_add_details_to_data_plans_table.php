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
        Schema::table('data_plans', function (Blueprint $table) {
            $table->string('volume')->nullable()->after('name');
            $table->string('type')->nullable()->after('volume'); // SME, CG, GIFTING, etc.
            
            // Drop validty first to change type cleanly (safe since we are re-seeding)
            $table->dropColumn('validity');
        });

        Schema::table('data_plans', function (Blueprint $table) {
             $table->string('validity')->nullable()->after('selling_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_plans', function (Blueprint $table) {
            //
        });
    }
};
