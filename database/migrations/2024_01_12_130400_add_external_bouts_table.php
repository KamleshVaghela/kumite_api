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
        Schema::create('external_bouts', function (Blueprint $table) {
            $table->id();

            $table->integer('external_competition_id')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->string('category')->nullable(false);
            
            $table->string('age_category')->nullable(false);
            $table->string('weight_category')->nullable(false);
            $table->string('rank_category')->nullable(false);

            $table->string('tatami')->nullable(false);
            $table->string('session')->nullable(false);

            $table->string('bout_number')->nullable(false);

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
        Schema::table('external_bouts', function (Blueprint $table) {
            //
        });
    }
};