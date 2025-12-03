<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('driver_status', ['active', 'inactive'])->nullable()->after('role');
            $table->integer('load_capacity')->nullable()->comment('Maximum load capacity in kg')->after('driver_status');
            $table->decimal('average_rating', 3, 2)->nullable()->default(0)->after('load_capacity');
            $table->integer('total_orders')->default(0)->after('average_rating');
            $table->integer('completed_orders')->default(0)->after('total_orders');
            $table->integer('failed_orders')->default(0)->after('completed_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['driver_status', 'load_capacity', 'average_rating', 'total_orders', 'completed_orders', 'failed_orders']);
        });
    }
}
