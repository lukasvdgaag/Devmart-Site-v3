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
/*        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement()->unique();
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('discord_id')->nullable();
            $table->boolean('discord_verified')->default(false);
            $table->string('role', 10)->default('user');
            $table->string('theme', 10)->default('system');
            $table->timestamp('username_changed_at')->useCurrent();
            $table->string('spigot', 50)->nullable();
            $table->boolean('spigot_verified')->default(false);
            $table->string('discord_suggestion_notifications', 20)->default('ALL_MESSAGES');
            $table->rememberToken();
            $table->timestamps();
        });*/

        $builder = DB::table('gaagjescraft.users')
            ->leftJoin('gaagjescraft.user_paypal AS p', 'users.id', '=', 'p.user')
            ->select(
                'users.*',
                'p.email AS paypal.email',
                'p.first_name AS paypal.first_name',
                'p.last_name AS paypal.surname',
                'p.business AS paypal_business'
            );
        foreach ($builder->get()->getIterator() as $user) {

            DB::table('gcnt.users')->updateOrInsert(
                ['id' => $user->id],
                [
                    'username' => $user->username,
                    'password' => $user->password,
                    'email' => $user->email,
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
                ]
            );

            DB::table('gcnt.user_paypal')->updateOrInsert(
                ['user' => $user->id],
                [
                    'name' => $user->paypal_first_name ?? $user->first_name,
                    'surname' => $user->paypal_last_name ?? $user->last_name,
                    'email' => $user->paypal_email ?? $user->email,
                    'business' => $user->paypal_business ?? null,
                ]
            );
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
