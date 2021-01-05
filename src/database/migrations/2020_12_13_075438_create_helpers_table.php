<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('events');
            $table->String('object');
            $table->String('task_name')->nullable();
            $table->String('group_name')->nullable();
            $table->String('user')->nullable();
            $table->String('attribute')->nullable();
            $table->json('conditionRule')->nullable();
            $table->String('action')->nullable();
            $table->boolean('statuse')->nullable();
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
        Schema::dropIfExists('helpers');
    }
}
