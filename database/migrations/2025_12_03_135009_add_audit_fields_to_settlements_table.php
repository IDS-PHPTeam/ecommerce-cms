<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditFieldsToSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('settlements') && !Schema::hasColumn('settlements', 'created_by')) {
            Schema::table('settlements', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('updated_at')->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('settlements')) {
            Schema::table('settlements', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
}
