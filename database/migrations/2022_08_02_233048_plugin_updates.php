<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PluginUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugin_updates', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement()->unique();
            $table->integer('plugin')->unsigned();
            $table->string('version');
            $table->smallInteger('beta_number')->unsigned()->default(0);
            $table->string('file_extension', 10)->default('jar');
            $table->string('title');
            $table->text('changelog');
            $table->dateTime('created_at')->useCurrent();
            $table->integer('downloads')->default(0);

            $table->foreign('plugin')->references('id')->on('plugins')->onUpdate('cascade')->onDelete('cascade');
        });

        $table = DB::table('gcnt.plugin_updates');
        foreach (DB::table('gaagjescraft.updates')->select()->get()->getIterator() as $p) {
            $table->insert([
                'id' => $p->id,
                'plugin' => $p->plugin,
                'version' => $p->version,
                'beta_number' => $p->beta_number,
                'title' => $p->title,
                'changelog' => $p->description,
                'created_at' => $p->date,
                'downloads' => $p->downloads,
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
        Schema::dropIfExists('plugin_updates');
    }
}
