<?php
// database/migrations/2022_01_01_create_nominee_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomineeDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('nominee_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->string('nominee_name');
            $table->string('relation');
            $table->string('contact_number');
            // Add more columns as needed
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nominee_details');
    }
}
