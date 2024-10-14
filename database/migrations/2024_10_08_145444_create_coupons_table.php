<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Unique coupon code
            $table->decimal('discount', 8, 2); // Discount amount
            $table->enum('discount_type', ['fixed', 'percentage']); // Type of discount
            $table->dateTime('expiration_date'); // Expiration date and time of the coupon
            $table->timestamps(); // Created and updated timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
