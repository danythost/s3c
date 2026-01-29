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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_order_id_foreign');
            $table->dropColumn('order_id');
            $table->decimal('amount', 12, 2)->change();
            $table->enum('type', ['debit', 'credit'])->change();
            $table->enum('status', ['pending', 'success', 'failed'])->change();
            $table->string('source')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->decimal('amount', 15, 2)->change();
            $table->string('type')->change();
            $table->string('status')->change();
            $table->string('source')->nullable()->change();
        });
    }
};
