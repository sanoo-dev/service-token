<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('name_role');
            $table->dropColumn('id_role');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('id_permission');
            $table->dropColumn('name_permission');
            $table->dropColumn('status');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('route');
            $table->dropColumn('status');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('secretKey');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
