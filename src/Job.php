<?php

namespace Quechedra;

use Quechedra\Utils\JobUtil;
class Job
{

    /**
     * Queue where the job will be proccessed
     *
     * @var string
     */
    protected $queue = "default";

    /**
     * Retry job if it fails. If it is set to false the job will be
     * proccessed only once. If an intiger is given instead
     * the job will be tried that many times if it
     * fails each time
     *
     * @var mixed
     */
    protected $retry = true;


    function __construct() { }

    /**
     * Change queue for a single job. This will not change the queue for incoming jobs
     * you have to set the instance variable $queue = "name" in the child class
     * inheriting Quechedra\Job
     *
     * @param string $queue Queue name
     *
     * @return self
     */
    public function queueTo($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * Set number of reties for the job until it fails. If set to 'false' the job
     * will be executed only once. If set to true it will execute until it
     * reaches the max number of executions set by the global config
     *
     * @param integer|bool $value Retry value
     * @return void
     */
    public function retry($value)
    {
        $this->retry = (\is_numeric($value) || \is_bool($value)) ? $value : false;
        return $this;
    }

    /**
     * Get number of how many times a job should be retried. If
     * retry is set to true, set a default nummber of retries
     *
     * @return mixed
     */
    public function getRetries()
    {
        if(is_numeric($this->retry)) {
            return $this->retry;
        }

        if($this->retry) {
            return 20;
        }

        return false;
    }

    /**
     * get queue the job should be added to
     *
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Push Job to queue. Arguments passed to this function
     * showld be one of: string, bool, number, array
     *
     * @return void
     */
    public function processAsync()
    {
        $args = func_get_args();

        $payload = JobUtil::buildPayload($this, $args);
        (new Manager())->push($payload);
    }

}
