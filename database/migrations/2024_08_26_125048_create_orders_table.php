<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->unsigned();
            $table->string('order_date');
            $table->tinyInteger('order_status')
                ->comment('0 - Pending / 1 - Complete');
            $table->integer('total_products')->default(0);
            $table->integer('sub_total')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('total');
            $table->string('invoice_no');
            $table->string('employee')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('payment_type')->nullable();
            $table->integer('pay')->nullable();
            $table->integer('due')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
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
        Schema::dropIfExists('orders');
    }
}
