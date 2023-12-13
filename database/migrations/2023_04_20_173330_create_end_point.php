<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndPoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('end_point', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serveIp');
            $table->string('domain');
            $table->Text('publicKey');
            $table->Text('privateKey');
            $table->integer('tracker')->nullable();
            $table->integer('status')->nullable();
            $table->integer('expire')->nullable();
            $table->string('idServices')->nullable();
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
        Schema::dropIfExists('end_point');
    }
}
