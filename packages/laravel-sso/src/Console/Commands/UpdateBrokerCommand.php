<?php

namespace TuoiTre\SSO\Console\Commands;

use Illuminate\Console\Command;
use TuoiTre\SSO\Jobs\BuildBrokerJob;
use TuoiTre\SSO\Models\Broker;

class UpdateBrokerCommand extends Command
{
    protected $signature = 'laravel-sso:update-broker {name : Name} {domain? : Domain} {status? : Status}';

    protected $description = 'Command create broker';

    public function handle()
    {
        $brokerName = $this->argument('name');
        $brokerDomain = $this->argument('domain');
        $brokerStatus = $this->argument('status');
        $broker = Broker::query()->where(['name' => $brokerName])->first();
        if (!empty($broker)) {
            $attributes = [];
            if (!empty($brokerDomain)) {
                $attributes['domain'] = $brokerDomain;
            }
            if (!empty($brokerStatus)) {
                $attributes['status'] = $brokerStatus;
            }
            $broker->update($attributes);

            if (config('laravel-sso.notUseDatabaseForApi', false)) {
                dispatch(new BuildBrokerJob($brokerName))->onConnection('sync');
            }
        }

        echo "\nUpdate broker success with name: $brokerName \n";
    }
}
