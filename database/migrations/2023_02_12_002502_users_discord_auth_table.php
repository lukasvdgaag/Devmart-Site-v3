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
        Schema::create('users_discord_auth', function (Blueprint $table) {
            $table->id();
            $table->string('token', 255);
            $table->string('discord_id', 25)->nullable();
            $table->string('discord_username', 50)->nullable();
            $table->string('discord_discriminator', 6)->nullable();
            $table->string('discord_email', 255)->nullable();
            $table->tinyInteger('discord_verified')->nullable();
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
        Schema::dropIfExists('users_discord_auth');
    }
};
