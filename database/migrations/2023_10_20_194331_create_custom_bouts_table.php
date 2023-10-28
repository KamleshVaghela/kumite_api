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
        Schema::create('custom_bouts', function (Blueprint $table) {
            $table->id();

            $table->integer('competition_id')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->string('category')->nullable(false);
            $table->string('bout_number')->nullable(false);
            
            $table->integer('first');
            $table->integer('second');
            $table->integer('third_1');
            $table->integer('third_2');
            
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
        Schema::dropIfExists('custom_bouts');
    }
};
