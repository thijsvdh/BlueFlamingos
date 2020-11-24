<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('productImage')->nullable();
            $table->string('language')->nullable();
            $table->text('shortDescription')->nullable();
            $table->string('model');
            $table->string('groupingId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
