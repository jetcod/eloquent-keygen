<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDummyTable extends Migration
{
    public function up()
    {
        Schema::create('dummy_table', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dummy_table');
    }
}
