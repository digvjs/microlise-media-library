<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('media_id')->unsigned();
            $table->foreign('media_id')->references('id')->on('media');
            $table->string('size', 10);
            $table->string('mime_type', 20);
            $table->string('extention', 5);
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
        Schema::dropIfExists('media_meta');
    }
}
