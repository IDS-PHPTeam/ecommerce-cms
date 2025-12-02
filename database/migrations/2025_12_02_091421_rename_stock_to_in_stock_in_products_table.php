<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStockToInStockInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Convert enum values to integers: 'in_stock' -> 1, 'out_of_stock' -> 0
        \DB::statement("UPDATE products SET stock = CASE WHEN stock = 'in_stock' THEN '1' ELSE '0' END");
        
        // Change column to integer temporarily
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock VARCHAR(20)");
        \DB::statement("UPDATE products SET stock = CASE WHEN stock = '1' THEN '1' ELSE '0' END");
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock INT DEFAULT 1 NOT NULL");
        
        // Rename column from stock to in_stock
        \DB::statement("ALTER TABLE products CHANGE stock in_stock INT DEFAULT 1 NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rename column back from in_stock to stock
        \DB::statement("ALTER TABLE products CHANGE in_stock stock INT DEFAULT 1 NOT NULL");
        
        // Convert back to enum
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock VARCHAR(20)");
        \DB::statement("UPDATE products SET stock = CASE WHEN stock = '1' THEN 'in_stock' ELSE 'out_of_stock' END");
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock ENUM('in_stock', 'out_of_stock') DEFAULT 'in_stock' NOT NULL");
    }
}
