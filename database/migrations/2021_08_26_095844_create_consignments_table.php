<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id');
            $table->string('status')->default('Created');
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('consignment_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consignment_id');
            $table->unsignedInteger('product_id');
            $table->string('VSSLPR');
            $table->string('name');
            $table->integer('amount');
            $table->float('price');
            $table->timestamps();
            $table->foreign('consignment_id')->references('id')->on('consignments')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consignments');
    }
}
