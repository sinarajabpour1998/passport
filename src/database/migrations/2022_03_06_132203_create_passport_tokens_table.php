<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassportTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passport_tokens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->comment("table: users, column: id");
            $table->string("username");
            $table->string("password");
            $table->text('token');
            $table->text('refresh_token');
            $table->enum("status", ["enable", "disable"]);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('passport_tokens');
    }
}
