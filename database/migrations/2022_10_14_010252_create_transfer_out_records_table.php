<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferOutRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_out_records', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('item_name')->nullable();
            $table->string('item_category');
            $table->string('item_sub_category');
            $table->integer('item_quantity')->nullable();
            $table->integer('item_quantity_deduct')->nullable();
            $table->string('item_barcode');
            $table->string('item_description')->nullable();
            $table->float('item_cost');
            $table->float('item_sell');
            $table->string('item_photo')->nullable();
            $table->string('form_number')->nullable();
            $table->string('custom_date')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('transfer_out_records');
    }
}
