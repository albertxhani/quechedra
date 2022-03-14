<?php

namespace Quechedra;

use Quechedra\Utils\JobUtil;

class Resolver
{
    private $_job = null;

    private $_payload = [];

    /**
     * Condtructs the job object given the payload fetched
     * from redis
     *
     * @param array $payload Payload
     */
    public function __construct($payload)
    {
        $this->_payload = $payload;
        $this->_job = JobUtil::constructJob($this->_payload);
    }

    /**
     * Get constructed job
     *
     * @return Job
     */
    public function getJob()
    {
        return $this->_job;
    }

    /**
     * Get arguments to be passed to process function
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->_payload['args'];
    }

    /**
     * Calls process function of binded job
     *
     * @return void
     */
    public function processJob()
    {
        \ob_start();
        $this->_job->process(...$this->getArguments());
        \ob_end_clean();
    }

    /**
     * Check if job can be re queued after failure
     *
     * @return boolean
     */
    public function canRequeue()
    {
        $retries = $this->_job->getRetries();
        $retry_count = $this->_payload["retry_count"] ?? 1;

        return ($retries && $retry_count >= $retries);
    }

    /**
     * Construct payload for the failed job
     *
     * @return void
     */
    public function failed()
    {
        $this->_payload["failed_at"] = \gmdate("Y-m-d\TH:i:s\Z");

        if ($this->canRequeue()) {
            $retry_count = $payload["retry_count"] ?? 1;
            $this->_payload["retry_count"] += $retry_count + 1;
        }

        return $this->_payload;
    }

}