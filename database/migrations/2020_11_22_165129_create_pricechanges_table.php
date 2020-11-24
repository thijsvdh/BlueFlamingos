<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricechangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->string('currency');
            $table->timestamps();
        });

        Schema::create('pricechanges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('offer_id');
            $table->integer('price_id')->unsigned();
            $table->bigInteger('date');
            $table->timestamps();
            $table->foreign('offer_id')
                ->references('id')->on('offers')
                ->onDelete('cascade');
            $table->foreign('price_id')
                ->references('id')->on('prices')
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
        Schema::dropIfExists('pricechanges');
    }
}
