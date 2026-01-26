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
        Schema::rename('transactions', 'wallet_transactions');
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('source')->nullable()->after('status'); // flutterwave, epins
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('source');
        });
        Schema::rename('wallet_transactions', 'transactions');
    }
};
