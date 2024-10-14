<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_homepage_headers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageHeadersTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_headers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->string('buttonText');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('homepage_headers');
    }
}
