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

            $payload = $this->getPayload();

            if(!$payload) {
                $this->sleep(5);
                continue;
            }

            $job = $this->getJob($payload);
            if(!$job) continue;

            try {

                // Log message before processing the job
                $this->beforeProcessing($job->getId());

                // Proccess job
                $this->proccess($job, $payload["args"]);

                // Log message when job has finnished processing
                $this->jobProccessed($job->getId());

                $this->sleep(2);
            } catch(\Exception $e) {
                $this->report($e);
            }
        }
    }

    /**
     * Get a job from the queue
     *
     * @return array
     */
    private function getPayload()
    {
        $payload = $this->manager->pop();

        if(!$payload) return false;

        return json_decode($payload, true);
    }

    /**
     * Get Job object based on payload
     *
     * @param array $payload job Payload from redis
     *
     * @return Job
     */
    public function getJob($payload)
    {
        try {
            return JobUtil::constructJob($payload);
        } catch(\Exception $e) {
            $this->report($e);
        }
    }

    /**
     * Proccess job
     *
     * @param Job   $job
     * @param array $arguments
     *
     * @return void
     */
    private function proccess($job, $arguments)
    {
        \ob_start();
        $job->process(...$arguments);
        \ob_end_clean();
    }

    /**
     * Sleep for a given number of seconds
     *
     * @param integer $seconds
     *
     * @return void
     */
    private function sleep($seconds) {
        \sleep($seconds);
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

    /**
     * Log exception
     *
     * @param \Exception $exception
     *
     * @return void
     */
    private function report($exception)
    {
        $this->logger->log("Job failed with message: " . $exception->getMessage(), 'error');
    }

}