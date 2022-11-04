<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('users', function (Blueprint $table) {
//            $table->integer('id')->unsigned()->autoIncrement()->unique();
//            $table->string('username', 50)->unique();
//            $table->string('name', 50);
//            $table->string('surname', 50);
//            $table->string('email')->unique();
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password');
//            $table->string('discord')->nullable();
//            $table->string('discord_id')->nullable();
//            $table->boolean('discord_verified')->default(false);
//            $table->string('role', 10)->default('user');
//            $table->string('theme', 10)->default('system');
//            $table->string('verify_code')->default('');
//            $table->timestamp('username_changed_at')->useCurrent();
//            $table->string('spigot', 50)->nullable();
//            $table->boolean('spigot_verified')->default(false);
//            $table->string('discord_suggestion_notifications', 20)->default('ALL_MESSAGES');
//            $table->rememberToken();
//            $table->timestamps();
//        });

        $table = DB::table('gcnt.users');

        foreach (DB::table('gaagjescraft.users')->select()->get()->getIterator() as $user) {
            $table->insert([
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name' => $user->first_name,
                'surname' => $user->last_name,
                'email' => $user->email,
                'discord' => $user->discord,
                'discord_id' => $user->discord_id,
                'discord_verified' => $user->discord_verified,
                'created_at' => $user->created_at,
                'role' => $user->role,
                'theme' => $user->theme,
                'verify_code' => $user->verify_code,
                'username_changed_at' => $user->username_changedate,
                'email_verified_at' => null,
                'spigot' => $user->spigot,
                'spigot_verified' => $user->spigot_verified,
                'discord_suggestion_notifications' => $user->discord_suggestion_notifications
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
//        Schema::dropIfExists('users');
    }
}
