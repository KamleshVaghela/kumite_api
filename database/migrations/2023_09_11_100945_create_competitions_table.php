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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('short_description')->nullable(false);

            $table->string('additional_details')->nullable(false);
            
            $table->integer('fees')->nullable(false)->default(0);
            $table->integer('kata_fees')->nullable(false)->default(0);
            $table->integer('kumite_fees')->nullable(false)->default(0);
            $table->integer('team_kata_fees')->nullable(false)->default(0);
            $table->integer('team_kumite_fees')->nullable(false)->default(0);
            $table->integer('coach_fees')->nullable(false)->default(0);

            $table->string('level_id');

            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable(false);

            $table->date('student_reg_end_date')->nullable(false);
            $table->date('coach_end_date')->nullable(false);

            $table->string('type_id');
            
            
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
        Schema::dropIfExists('competitions');
    }
};
