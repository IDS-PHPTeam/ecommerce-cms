<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTableForNewStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add product_type
            $table->enum('product_type', ['simple', 'variable'])->default('simple')->after('description');
            
            // For simple products: keep price and sale_price, but make nullable
            // For variable products: these will be in variants
            
            // Stock management fields
            $table->boolean('track_stock')->default(true)->after('sale_price');
            $table->integer('stock_quantity')->nullable()->after('track_stock');
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'on_backorder'])->nullable()->after('stock_quantity');
            
            // Remove old category field (will use many-to-many relationship)
            // Keep old fields for backward compatibility, but we'll migrate data first
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'track_stock', 'stock_quantity', 'stock_status']);
        });
    }
}
