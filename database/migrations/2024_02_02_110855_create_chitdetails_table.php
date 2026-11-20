<?php
// database/migrations/2022_01_01_create_chitdetails_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChitdetailsTable extends Migration
{
    public function up()
    {
        Schema::create('chitdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chit_id');
            $table->decimal('chit_value', 10, 2);
            $table->integer('duration');
            $table->decimal('subscription_amount', 10, 2);
            $table->date('first_auction_date');
            $table->string('chit_type');
            $table->timestamps();
            $table->foreign('chit_id')->references('id')->on('chits')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chitdetails');
    }
}
