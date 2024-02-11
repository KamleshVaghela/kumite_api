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
        Schema::create('external_competition', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('short_description')->nullable(false);

            $table->string('additional_details')->nullable(false);

            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);
            
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
        Schema::table('external_competition', function (Blueprint $table) {
            //
        });
    }
};