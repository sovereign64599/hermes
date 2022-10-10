<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('item_name')->nullable();
            $table->string('item_category')->nullable();
            $table->string('item_sub_category')->nullable();
            $table->integer('item_quantity')->nullable();
            $table->string('item_barcode')->nullable();
            $table->string('item_description')->nullable();
            $table->float('item_cost')->nullable();
            $table->float('item_sell')->nullable();
            $table->string('item_notes')->nullable();
            $table->string('item_photo')->nullable();
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
        Schema::dropIfExists('items');
    }
}
