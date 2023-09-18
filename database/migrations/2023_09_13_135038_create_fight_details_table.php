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
        Schema::create('fight_details', function (Blueprint $table) {
            $table->id();

            $table->integer('bout_id')->nullable(false);

            $table->integer('fight_number')->nullable(false); 

            $table->integer('aka');
            $table->integer('ao');

            $table->integer('winner');
            $table->integer('bye');

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
        Schema::dropIfExists('fight_details');
    }
};
