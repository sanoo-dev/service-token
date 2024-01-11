<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('service', 'services');
        Schema::rename('end_point', 'endpoints');
        Schema::rename('account', 'accounts');
        Schema::rename('role', 'roles');
        Schema::rename('permission', 'permissions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('services', 'service');
        Schema::rename('endpoints', 'end_point');
        Schema::rename('accounts', 'account');
        Schema::rename('roles', 'role');
        Schema::rename('permissions', 'permission');
    }
}
