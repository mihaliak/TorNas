<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('model_id');
            $table->string('model_type');
            $table->index(['model_id', 'model_type']);
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->text('manipulations');
            $table->text('custom_properties');
            $table->unsignedInteger('order_column')->nullable();
            $table->nullableTimestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('media');
    }
}
