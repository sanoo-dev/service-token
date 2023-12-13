<?php

namespace TuoiTre\SSO\Console\Commands;

use Illuminate\Console\Command;
use TuoiTre\SSO\Jobs\BuildBrokerJob;
use TuoiTre\SSO\Models\Broker;

class CreateBrokerCommand extends Command
{
    protected $signature = 'laravel-sso:create-broker {name : Name} {domain : Domain}';

    protected $description = 'Command create broker';

    public function handle()
    {
        $brokerName = $this->argument('name');
        $brokerDomain = $this->argument('domain');
        Broker::query()->updateOrCreate(
            [
                'name' => $brokerName
            ],
            [
                'name' => $brokerName,
                'domain' => $brokerDomain
            ]
        );

        if (config('laravel-sso.notUseDatabaseForApi', false)) {
            dispatch(new BuildBrokerJob($brokerName))->onConnection('sync');
        }

        echo "\nCreate broker success with name: $brokerName \n";
    }
}
