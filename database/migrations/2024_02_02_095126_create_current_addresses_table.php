<?php
// database/migrations/2022_01_01_create_current_addresses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('current_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('house_no');
            $table->string('building_name')->nullable();
            $table->string('street_name');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('landmark')->nullable();
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('current_addresses');
    }
}
