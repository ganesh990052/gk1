<?php

// database/migrations/2022_01_01_create_chits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChitsTable extends Migration
{
    public function up()
    {
        Schema::create('chits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Add more columns as needed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chits');
    }
}
