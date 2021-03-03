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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('date_of_birth');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE `users`
            CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            CHANGE `updated_at` `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
