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
        Schema::table('login_histories', function (Blueprint $table) {
            // Add admin_id column if it doesn't exist
            if (!Schema::hasColumn('login_histories', 'admin_id')) {
                $table->foreignId('admin_id')->nullable()->after('user_id')->constrained('admins')->onDelete('cascade');
            } else {
                // Column exists, just add the foreign key constraint if not already there
                try {
                    $table->foreign('admin_id', 'fk_login_admin')->references('id')->on('admins')->onDelete('cascade');
                } catch (\Exception $e) {
                    // Foreign key might already exist, ignore
                }
            }
            
            // Add username column if it doesn't exist
            if (!Schema::hasColumn('login_histories', 'username')) {
                $table->string('username')->nullable()->after('email');
            }
            
            // Rename user_id foreign key to fk_login_user if needed
            try {
                $table->dropForeign(['user_id']);
                $table->foreign('user_id', 'fk_login_user')->references('id')->on('users')->onDelete('cascade');
            } catch (\Exception $e) {
                // Foreign key might not exist or already named correctly
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropForeign('fk_login_admin');
            $table->dropColumn('admin_id');
            $table->dropColumn('username');
            
            $table->dropForeign('fk_login_user');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
