<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->id();
            $table->string('appId');
            $table->string('appName');
            $table->integer('status');
            $table->string('serveIp');
            $table->string('domain');
            $table->string('serveIpTransfer');
            $table->string('domainTransfer');
            $table->string('typeToken')->nullable();
            $table->string('partnerCode');
            $table->string('secretKey');
            $table->string('meta')->nullable();
            $table->string('content')->nullable();
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
        Schema::dropIfExists('service');
    }
}
