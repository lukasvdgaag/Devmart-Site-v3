<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Pastes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pastes', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->integer('creator')->nullable()->unsigned()->default(null);
            $table->string('title', 50)->default('Unknown Paste');
            $table->string('style', 20)->nullable();
            $table->enum('visibility', [
               'PUBLIC', 'HIDDEN', 'PRIVATE'
            ])->default('PUBLIC');
            $table->dateTime('expire_at')->nullable();

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('creator')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
        });

        $table = DB::table('gcnt.pastes');
        foreach (DB::table('gaagjescraft.pastes')->select()->get()->getIterator() as $p) {
            $table->insert([
                'id' => $p->name,
                'creator' => $p->creator === 0 ? null : $p->creator,
                'title' => $p->title,
                'style' => $p->language,
                'visibility' => 'PUBLIC',
                'created_at' => $p->created_at,
                'updated_at' => $p->modified_at
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
        Schema::dropIfExists('pastes');
    }
}
