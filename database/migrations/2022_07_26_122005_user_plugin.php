<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_user', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement()->unique();
            $table->integer('user_id')->unsigned();
            $table->integer('plugin_id')->unsigned();
            $table->integer('payment_id')->unsigned()->nullable();
            $table->dateTime('date')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
        });

        $table = DB::table('gcnt.plugin_user');
        foreach (DB::table('gaagjescraft.user_plugins')->select()->get()->getIterator() as $p) {
            $table->insert([
                'id' => $p->id,
                'user_id' => $p->user_id,
                'plugin_id' => $p->plugin_id,
                'date' => $p->date
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plugin_user');
        //
    }
}
