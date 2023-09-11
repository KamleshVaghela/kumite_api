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
        Schema::create('competition_level_details', function (Blueprint $table) {
            $table->id();

            $table->integer('comp_id')->nullable(false);
            $table->integer('level_id')->nullable(false);

            $table->string('state_geo_id')->nullable(false)->default(0);
            $table->string('district_geo_id')->nullable(false)->default(0);
            $table->string('coach_id')->nullable(false)->default(0);;

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
        Schema::dropIfExists('competition_level_details');
    }
};
