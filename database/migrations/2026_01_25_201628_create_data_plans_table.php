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
            Schema::create('data_plans', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('epins');
            $table->string('network');               // MTN, AIRTEL, GLO, 9MOBILE
            $table->string('code');                  // provider plan code
            $table->string('name');                  // e.g MTN 1GB SME
            $table->string('volume')->nullable();
            $table->string('type')->nullable();
            $table->decimal('provider_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->string('validity')->nullable(); // e.g., '30 days'
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['provider', 'code']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_plans');
    }
};
