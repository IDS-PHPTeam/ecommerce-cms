<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditFieldsToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add created_by and updated_by to products table
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'created_by')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }

        // Add created_by and updated_by to categories table
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'created_by')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }

        // Add created_by and updated_by to orders table
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'created_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }

        // Add created_by and updated_by to order_items table
        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'created_by')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }

        // Add created_by and updated_by to roles table
        if (Schema::hasTable('roles') && !Schema::hasColumn('roles', 'created_by')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }

        // Add created_by and updated_by to permissions table
        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'created_by')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }

        // Add created_by and updated_by to settings table
        if (Schema::hasTable('settings') && !Schema::hasColumn('settings', 'created_by')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }


        // Add created_by and updated_by to users table (to track who created/updated the user account)
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'created_by')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->after('updated_at');
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            });
            
            // Add foreign key constraints separately to avoid issues during migration
            // Note: We use DB::statement to add the foreign key after the column exists
            if (Schema::hasColumn('users', 'created_by')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                });
            }
            if (Schema::hasColumn('users', 'updated_by')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove created_by and updated_by from products table
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }

        // Remove created_by and updated_by from categories table
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }

        // Remove created_by and updated_by from orders table
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }

        // Remove created_by and updated_by from order_items table
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }

        // Remove created_by and updated_by from roles table
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }

        // Remove created_by and updated_by from permissions table
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }

        // Remove created_by and updated_by from settings table
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }


        // Remove created_by and updated_by from users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'created_by')) {
                    $table->dropForeign(['created_by']);
                }
                if (Schema::hasColumn('users', 'updated_by')) {
                    $table->dropForeign(['updated_by']);
                }
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
}
