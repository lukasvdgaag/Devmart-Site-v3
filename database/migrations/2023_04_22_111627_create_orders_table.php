<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->foreignId('user_id')->type('integer')->unsigned()->constrained('users');
            $table->foreignId('plugin_id')->type('integer')->unsigned()->constrained('plugins');
            $table->string('order_id', 255)->nullable();
            $table->decimal('payment_amount');
            $table->string('status', 255)->nullable();
            $table->text('breakdown');

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
};
