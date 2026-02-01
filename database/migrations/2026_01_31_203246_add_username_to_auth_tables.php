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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
        });

        Schema::table('login_histories', function (Blueprint $table) {
            $table->string('username')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('username');
        });

        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
