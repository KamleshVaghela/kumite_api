<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('external_competition_id')->nullable(false);
            $table->string('full_name')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->string('team')->nullable(false);
            $table->string('coach_name')->nullable(false);
            $table->string('rank')->nullable(false);
            $table->integer('age')->nullable(false);
            $table->integer('weight')->nullable(false);
            $table->integer('user_id')->nullable(false);
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
        Schema::table('external_participants', function (Blueprint $table) {
            //
        });
    }
};