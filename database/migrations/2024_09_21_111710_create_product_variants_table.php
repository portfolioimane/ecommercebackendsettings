<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('color')->nullable(); // Color of the variant
            $table->string('size')->nullable(); // Size of the variant
            $table->decimal('price_adjustment', 10, 2)->default(0); // Price adjustment for this variant
            $table->string('image_url')->nullable(); // URL for the variant's image
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
