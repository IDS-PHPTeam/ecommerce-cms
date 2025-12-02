<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStockToEnumInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, change column to varchar temporarily to allow string values
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock VARCHAR(20) DEFAULT 'in_stock'");
        
        // Convert existing integer stock values to string values
        \DB::statement("UPDATE products SET stock = CASE WHEN CAST(stock AS UNSIGNED) > 0 THEN 'in_stock' ELSE 'out_of_stock' END");
        
        // Now change to enum
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock ENUM('in_stock', 'out_of_stock') DEFAULT 'in_stock' NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Convert enum values back to integer (first change to varchar)
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock VARCHAR(20)");
        \DB::statement("UPDATE products SET stock = CASE WHEN stock = 'in_stock' THEN '1' ELSE '0' END");
        
        // Change column type from varchar to integer
        \DB::statement("ALTER TABLE products MODIFY COLUMN stock INT DEFAULT 0 NOT NULL");
    }
}
