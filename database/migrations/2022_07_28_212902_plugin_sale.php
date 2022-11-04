<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PluginSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_sale', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement()->unique();
            $table->integer('plugin')->unsigned();
            $table->decimal('percentage', 5)->default(0);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->foreign('plugin')->references('id')->on('plugins')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('plugin_sale');
    }
}
