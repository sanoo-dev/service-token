<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFieldName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('appId', 'app_id');
            $table->renameColumn('appName', 'name');
            $table->renameColumn('serveIp', 'server_ip');
            $table->renameColumn('serveIpTransfer', 'endpoint_server_ip');
            $table->renameColumn('domainTransfer', 'endpoint_domain');
            $table->renameColumn('typeToken', 'token_type');
            $table->renameColumn('partnerCode', 'partner_code');
        });

        Schema::table('endpoints', function (Blueprint $table) {
            $table->renameColumn('serveIp', 'server_ip');
            $table->renameColumn('publicKey', 'public_key');
            $table->renameColumn('privateKey', 'private_key');
            $table->renameColumn('idServices', 'service_id');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->renameColumn('action', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('app_id', 'appId');
            $table->renameColumn('name', 'appName');
            $table->renameColumn('server_ip', 'serveIp');
            $table->renameColumn('endpoint_server_ip', 'serveIpTransfer');
            $table->renameColumn('endpoint_domain', 'domainTransfer');
            $table->renameColumn('token_type', 'typeToken');
            $table->renameColumn('partner_code', 'partnerCode');
            $table->renameColumn('secret_key', 'secretKey');
        });

        Schema::table('endpoints', function (Blueprint $table) {
            $table->renameColumn('server_ip', 'serveIp');
            $table->renameColumn('public_key', 'publicKey');
            $table->renameColumn('private_key', 'privateKey');
            $table->renameColumn('service_id', 'idServices');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->renameColumn('name', 'action');
        });
    }
}
