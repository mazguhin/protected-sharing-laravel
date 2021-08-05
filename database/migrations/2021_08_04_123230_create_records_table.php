<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->text('data');
            $table->string('identifier', 255)->unique();
            $table->string('password', 255);
            $table->dateTime('deadline_at')->nullable();
            $table->string('author_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('records');
    }
}
