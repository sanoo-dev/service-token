<?php

namespace Common\App\Jobs;


use Exception;

/**
 * Class used as an intermediary to send the queue from API to CONSOLE
 * Use this if you want to remove dependency on CONSOLE job_classes from API (in case on API server there is no CONSOLE)
 *
 * Ex: dispatch(new \Common\App\Jobs\DispatchNewJob('Console\Modules\Example\Jobs\ExampleJobs', ['example_attr' => 'example_data']))
 *      ->onQueue('queue_name_of_you_choice_or_queue_name_of_job_run');
 *
 * If CONSOLE is on server API you can use basic way to send queue
 *
 * Ex: dispatch(new \Console\Modules\Example\Jobs\ExampleJobs(['example_attr' => 'example_data']));
 */
class DispatchNewJob extends CoreJob
{
    public function __construct(
        public string $job_class,
        public array  $data
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        try {
            if (class_exists($this->job_class)) {
                dispatch(new $this->job_class($this->data))->onConnection('sync');
            } else {
                $this->writeError("$this->job_class is not exist");
                throw new Exception("$this->job_class is not exist");
            }
        } catch (Exception $e) {
            $this->writeException($e);
            throw new $e;
        }
    }
}
