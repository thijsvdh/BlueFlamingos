<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('ean')->after('id');
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('feedId');
            $table->text('productUrl');
            $table->bigInteger('modified');
            $table->integer('sourceProductId')->unsigned()->index();
            $table->text('programLogo');
            $table->string('programName');
            $table->boolean('availability');
            $table->text('deliveryTime');
            $table->string('shippingCost');
            $table->timestamps();
            $table->foreign('sourceProductId')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
