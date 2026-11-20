<?php
// database/migrations/2022_01_01_create_bank_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->foreign('user_id')->references('id')->on('users'); // Foreign key constraint
            $table->string('account_number');
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('ifsc_code');
            $table->string('type_of_account');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_details');
    }
}
