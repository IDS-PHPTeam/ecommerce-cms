<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCustomerFieldsForNewStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add profile_image field
            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('email');
            }
        });

        // First, change the column to VARCHAR temporarily to allow any value
        DB::statement("ALTER TABLE users MODIFY COLUMN account_status VARCHAR(50) DEFAULT 'active_not_verified'");
        
        // Update existing values to match new enum values
        DB::table('users')
            ->where('account_status', 'active')
            ->update(['account_status' => 'active_not_verified']);

        // Now change back to ENUM with new values
        DB::statement("ALTER TABLE users MODIFY COLUMN account_status ENUM('active_not_verified', 'active_verified', 'deactivated', 'suspended') DEFAULT 'active_not_verified'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert account_status enum
        DB::statement("ALTER TABLE users MODIFY COLUMN account_status ENUM('active', 'suspended') DEFAULT 'active'");
        
        // Update values back
        DB::table('users')
            ->whereIn('account_status', ['active_not_verified', 'active_verified'])
            ->where('role', 'customer')
            ->update(['account_status' => 'active']);
            
        DB::table('users')
            ->where('account_status', 'deactivated')
            ->where('role', 'customer')
            ->update(['account_status' => 'suspended']);

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_image')) {
                $table->dropColumn('profile_image');
            }
        });
    }
}
