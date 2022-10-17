<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('item_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_category');
            $table->string('item_sub_category');
            $table->integer('item_quantity_deduct')->nullable();
            $table->string('item_barcode');
            $table->string('item_description')->nullable();
            $table->float('item_price');
            $table->float('total_amount');
            $table->string('form_number')->nullable();
            $table->string('custom_date')->nullable();
            $table->string('delivery_status')->nullable();
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
        Schema::dropIfExists('deliveries');
    }
}
