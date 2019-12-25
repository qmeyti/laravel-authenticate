<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('user_id');

            $table->enum('mode', ['mobile', 'email']);
            $table->string('code', 191);

            $table->unsignedInteger('send_count');
            $table->unsignedBigInteger('send_time');

            $table->unsignedInteger('try_count');
            $table->unsignedBigInteger('try_time');

            $table->boolean('verify')->default(false);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verifications');
    }
}
