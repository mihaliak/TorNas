<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('episode')->nullable();
            $table->string('size')->default(0);
            $table->string('file')->nullable();
            $table->string('location')->nullable();
            $table->string('hash')->nullable()->unique();
            $table->string('year')->nullable();
            $table->string('rating')->nullable();
            $table->string('runtime')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_paused')->default(false);
            $table->uuid('category_id')->nullable();
            $table->uuid('user_id')->nullable();
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
        Schema::drop('files');
    }
}
