<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelRecipientTable extends Migration
{
    public function up()
    {
        Schema::create('channel_recipient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id')->index();
            $table->unsignedBigInteger('recipient_id')->index();
            $table->string('data', 255);

            $table->unique(['channel_id', 'recipient_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_recipient');
    }
}
