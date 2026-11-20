<?php
// database/migrations/2022_01_01_create_occupation_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupationDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('occupation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->string('occupation');
            $table->string('current_employer')->nullable();
            $table->integer('years_at_current_employer')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->string('living_status'); // Assuming it could be 'rented' or 'own'
            $table->integer('years_at_current_residence')->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('occupation_details');
    }
}
