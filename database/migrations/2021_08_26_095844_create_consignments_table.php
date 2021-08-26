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
            $table->integer('department_name');
            $table->string('status')->default('created');
            $table->timestamps();
        });

        Schema::create('consignment_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('consignment_id');
            $table->string('VSSLPR');
            $table->string('name');
            $table->integer('amount');
            $table->timestamps();
            $table->foreign('consignment_id')->references('id')->on('consignments')->onDelete('cascade');
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
