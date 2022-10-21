<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->float('sales_amount', 12,2);
            $table->integer('transaction_number');
            $table->string('custom_date');
            $table->string('proccessed_by');
            $table->timestamps();
        });
    }

    /** 01010570 01040855 01030569 01030566 01030558 01030556 01030548 01030544
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
