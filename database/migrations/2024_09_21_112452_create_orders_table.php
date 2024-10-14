<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0); // Discount field with default value of 0
            $table->string('payment_method'); // Payment method field
            $table->string('status')->default('pending'); // Default status

            // Adding foreign key for shipping_area_id
            $table->foreignId('shipping_area_id')->nullable()->constrained('shipping_areas')->onDelete('set null'); // Nullable foreign key for shipping areas

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
