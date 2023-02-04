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
            $table->string('id')->unsigned()->primary()->autoIncrement();
            $table->string('name', 20)->index()->unique();
            $table->integer('creator')->nullable()->unsigned()->default(null);
            $table->string('title', 50)->default('Unknown Paste');
            $table->string('style', 20)->nullable();
            $table->enum('visibility', [
               'PUBLIC', 'UNLISTED', 'PRIVATE'
            ])->default('PUBLIC');
            $table->string('content', '16777215');
            $table->string('lifetime', 10)->nullable();
            $table->dateTime('expire_at')->nullable();
            $table->timestamps();

            $table->foreign('creator')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
        });

        $table = DB::table('gcnt.pastes');
        foreach (DB::table('gaagjescraft.pastes')->select()->get()->getIterator() as $p) {
            $table->insert([
                'name' => $p->name,
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
