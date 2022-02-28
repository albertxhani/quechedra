<?php

namespace Quechedra;

use Quechedra\Utils\JobUtil;

class Process
{
    public function __construct($streamer)
    {
        $client = Client::getInstance();

        $this->manager = $client->getManager();
        $this->logger = $client->getLogger($streamer);
    }

    /**
     * Run Process infinitely
     *
     * @return void
     */
    public function run()
    {
        while(true) {

            $payload = $this->getJobPayload();

            if(!$payload) {
                $this->sleep(5);
                continue;
            }

            try {
                $job = JobUtil::constructJob($payload);

                $this->beforeProcessing($job->getId());

                $this->proccessJob($job, $payload["args"]);

                $this->jobProccessed($job->getId());

                $this->sleep(2);
            } catch(\Exception $e) {
                $this->logger->log("Job Failed with message: {$e->getMessage()}", "error");
            }
        }
    }

    /**
     * Get a job from the queue
     *
     * @return array
     */
    private function getJobPayload()
    {
        return $this->manager->pop();
    }

    /**
     * Proccess job
     *
     * @param Job   $job
     * @param array $arguments
     *
     * @return void
     */
    private function proccessJob($job, $arguments)
    {
        $job->process(...$arguments);
    }

    private function sleep($seconds) {
        sleep($seconds);
    }

    /**
     * Called before job is proccessed
     *
     * @param string $id Job Id
     *
     * @return void
     */
    private function beforeProcessing($id)
    {
        $this->logger->log("Processing Job $id", "debug");
    }

    /**
     * Called after job is proccessed
     *
     * @param string $id Job Id
     *
     * @return void
     */
    private function jobProccessed($id)
    {
        $this->logger->log("Job Proccessed: $id", "debug");
    }
}