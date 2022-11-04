<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Payments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('payments', function (Blueprint $table) {
//            $table->integer('id')->unsigned()->autoIncrement()->unique();
//            $table->integer('user_id')->unsigned()->nullable();
//            $table->integer('plugin_id')->unsigned()->nullable();
//            $table->string('transaction_id', 20);
//            $table->decimal('payment_amount');
//            $table->decimal('payment_fee')->default(0);
//            $table->string('payment_status', 25);
//            $table->string('item_id', 25);
//            $table->string('email', 255)->nullable()->default(null);
//            $table->enum('platform', ['MY_GCNT', 'SPIGOT_MC', 'MC_MARKET', 'CUSTOM'])->default('MY_GCNT');
//            $table->dateTime('created_at')->useCurrent();
//            $table->boolean('verified')->default(false);
//
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
//            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade')->onUpdate('cascade');
//        });

        $table = DB::table('gcnt.payments');
        foreach (DB::table('gaagjescraft.mygcnt_payments')->select()->get()->getIterator() as $p) {
            $table->insert([
                'id' => $p->id,
                'user_id' => $p->user_id,
                'plugin_id' => $p->plugin_id,
                'transaction_id' => $p->txnid,
                'payment_amount' => $p->payment_amount,
                'payment_status' => $p->payment_status,
                'item_id' => $p->itemid,
                'email' => $p->email,
                'platform' => $p->platform,
                'created_at' => $p->createdtime,
                'verified' => $p->verified
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
        Schema::dropIfExists('payments');
    }
}
