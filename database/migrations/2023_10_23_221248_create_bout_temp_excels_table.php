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
        Schema::create('bout_temp_excels', function (Blueprint $table) {
            $table->id();
            
            $table->integer('unique_id')->nullable(false);
            $table->string('bout_number')->nullable(false);
            $table->string('category')->nullable(false);

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
        Schema::dropIfExists('bout_temp_excels');
    }
};
