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
        Schema::create('bout_participant_details', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id')->nullable(false);
            $table->integer('bout_id')->nullable(false);

            $table->integer('participant_id')->nullable(false);
            $table->integer('participant_sequence')->nullable(false);


            $table->integer('user_id')->nullable(false);
            $table->timestamp('last_modified');
            $table->integer('last_modified_user_id')->nullable(false);
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
        Schema::dropIfExists('bout_participant_details');
    }
};
