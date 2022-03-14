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
        while (true) {

            $payload = $this->getPayload();

            if (!$payload) {
                $this->sleep(5);
                continue;
            }

            $resolver = $this->getJobResolver($payload);

            try {

                $job = $resolver->getJob();

                // Log message before processing the job
                $this->beforeProcessing($job->getId());

                // Proccess job
                $resolver->processJob();

                // Log message when job has finnished processing
                $this->jobProccessed($job->getId());

                $this->sleep(2);
            } catch(\Exception $e) {
                $this->handleFailedJob($resolver);
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

        if (!$payload) return false;

        return json_decode($payload, true);
    }

    /**
     * Get Job object based on payload
     *
     * @param array $payload job Payload from redis
     *
     * @return Resolver
     */
    public function getJobResolver($payload)
    {
        try {
            return new Resolver($payload);
        } catch(\Exception $e) {
            $this->report($e);
        }
    }

    /**
     * Sleep for a given number of seconds
     *
     * @param integer $seconds Number of seconds to sleep
     *
     * @return void
     */
    private function sleep($seconds)
    {
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
     * @param \Exception $exception Exception that occured
     *
     * @return void
     */
    private function report($exception)
    {
        $this->logger->log("Job failed with message: " . $exception->getMessage(), 'error');
    }

    /**
     * Decides what will happen if a job fails
     *
     * @param Resolver $resolver Job Resover
     *
     * @return void
     */
    private function handleFailedJob($resolver)
    {
        $payload = $resolver->failed();

        if ($resolver->canRequeue()) {
            $this->manager->requeue($payload, strtotime("now + 5minutes"));
        } else {
            $this->manager->graveyard($payload);
        }
    }

}