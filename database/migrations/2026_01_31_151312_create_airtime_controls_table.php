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
        Schema::create('airtime_controls', function (Blueprint $table) {
            $table->id();
            $table->string('network')->unique(); // MTN, GLO, AIRTEL, 9MOBILE
            $table->decimal('min_amount', 10, 2)->default(50);
            $table->decimal('max_amount', 10, 2)->default(50000);
            $table->decimal('commission_percentage', 5, 2)->default(2.00); // e.g. 2%
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_controls');
    }
};
