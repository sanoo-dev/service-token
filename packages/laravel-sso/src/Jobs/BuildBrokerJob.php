<?php

namespace TuoiTre\SSO\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TuoiTre\SSO\Models\Broker;
use TuoiTre\SSO\Repositories\Interfaces\BrokerRepository;

class BuildBrokerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $brokerName
    ) {
    }

    public function handle(BrokerRepository $brokerRepository)
    {
        $data = Broker::query()->where(['name' => $this->brokerName])->first()->toArray();
        if (!empty($data)) {
            unset($data['created_at'], $data['updated_at']);
            $brokerRepository->updateOrInsert(['name' => $this->brokerName], $data);
            echo "\nBuild broker success with data: " . @json_encode($data);
        }
    }
}
