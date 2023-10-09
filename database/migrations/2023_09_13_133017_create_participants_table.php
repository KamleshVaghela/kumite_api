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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id')->nullable(false);

            $table->string('external_unique_id');
            $table->string('external_coach_code');
            $table->string('full_name')->nullable(false);
            $table->string('gender')->nullable(false);

            $table->integer('age')->nullable(false);
            $table->integer('weight')->nullable(false);

            $table->integer('rank_id')->nullable(false);

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
        Schema::dropIfExists('participants');
    }
};
