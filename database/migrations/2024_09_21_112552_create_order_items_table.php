<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade'); // Link to product variant if available
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade'); // Link to product if no variant is selected
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Store final price (product or variant)
            $table->string('image')->nullable(); // Store product or variant image
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
