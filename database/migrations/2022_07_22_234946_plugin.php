<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Plugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement()->unique();
            $table->string('name', 50)->unique()->nullable(false);
            $table->string('description', 150);
            $table->string('title', 100);
            $table->text('features');
            $table->boolean('custom')->default(false);
            $table->integer('spigot_link')->nullable();
            $table->string('github_link', 100)->nullable();
            $table->string('minecraft_versions')->default('');
            $table->string('dependencies')->default('');
            $table->string('categories')->default('');
            $table->dateTime('last_updated')->nullable(false);
            $table->integer('author')->unsigned()->nullable(false);
            $table->decimal('price', 4)->default(0);
            $table->string('logo_url')->default('https://www.gcnt.net/inc/img/default-plugin-image.png');
            $table->string("banner_url")->default('https://www.gcnt.net/inc/img/default-plugin-banner.jpg');
            $table->string('donation_url')->default('https://www.gcnt.net/donate');

            $table->foreign('author')->references('id')->on('users')->onDelete('no action')->onUpdate('cascade');
            $table->timestamps();
        });

        $table = DB::table('gcnt.plugins');
        foreach (DB::table('gaagjescraft.plugins')->select()->get()->getIterator() as $p) {
            $table->insert([
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'title' => $p->title,
                'features' => '',
                'custom' => $p->custom,
                'spigot_link' => $p->spigot_link,
                'minecraft_versions' => $p->minecraft_versions ?? '',
                'last_updated' => $p->last_updated,
                'author' => $p->author,
                'price' => $p->price,
                'logo_url' => $p->logo_url,
                'dependencies' => ''
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
        Schema::dropIfExists('plugins');
    }
}
