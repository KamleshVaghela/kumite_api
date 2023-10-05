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
        Schema::create('default_category_master', function (Blueprint $table) {
            $table->id();
            $table->string('category_group')->nullable(false);
            $table->string('gender')->nullable(false);
            $table->string('category')->nullable(false);

            $table->string('from_age');
            $table->string('to_age');

            $table->string('from_weight');
            $table->string('to_weight');
            
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
        Schema::dropIfExists('default_category_master');
    }
};
