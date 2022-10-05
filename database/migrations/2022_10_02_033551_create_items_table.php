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
            $table->string('item_name');
            $table->string('item_category');
            $table->string('item_sub_category');
            $table->string('item_quantity');
            $table->string('item_barcode');
            $table->string('item_description');
            $table->string('item_cost');
            $table->string('item_sell');
            $table->string('item_notes')->nullable();
            $table->string('item_photo')->nullable();
            $table->rememberToken();
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
