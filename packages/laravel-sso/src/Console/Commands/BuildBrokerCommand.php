<?php

namespace TuoiTre\SSO\Console\Commands;

use Illuminate\Console\Command;
use TuoiTre\SSO\Jobs\BuildBrokerJob;
use TuoiTre\SSO\Models\Broker;
use TuoiTre\SSO\Repositories\Interfaces\BrokerRepository;

class BuildBrokerCommand extends Command
{
    protected $signature = 'laravel-sso:build-broker {from=0 : From} {to=10 : To}';

    protected $description = 'Command create broker';

    public function handle(BrokerRepository $brokerRepository)
    {
        $from = $this->argument('from');
        $to = $this->argument('to');
        $brokers = Broker::query()->offset($from)->limit($to - $from)->get();
        foreach ($brokers as $broker) {
            dispatch(new BuildBrokerJob($broker->name))->onConnection('sync');
            echo "\nBuild broker success with name: $broker->name";
        }
        echo "\nEnd build broker \n";
    }
}
