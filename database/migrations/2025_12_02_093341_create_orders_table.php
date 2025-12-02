<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('location');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->enum('status', ['pending', 'assigned', 'failed', 'completed'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_price', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->integer('concurrency')->default(1);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamp('order_date')->useCurrent();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
