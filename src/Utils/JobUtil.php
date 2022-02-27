<?php

namespace Quechedra\Utils;

use Exception;
use InvalidArgumentException;

use Quechedra\Job;

class JobUtil
{

    /**
     * Build payload for a given Job
     *
     * @param Job $job    Job object
     * @param array $args Job arguments
     *
     * @return array
     */
    public static function buildPayload($job, $args)
    {

        if(!$job instanceof Job)
            throw new Exception("Class {get_class($job)} is not insance of Quechedra\Job");

        foreach($args as $arg) {
            if(is_object($arg))
                throw new InvalidArgumentException("Arguments passed to job should not be objects");
        }

        return [
            "id"    => self::uniqueId(),
            "queue" => $job->getQueue() ?? "default",
            "class" => get_class($job),
            "args"  => $args,
            "retry" => $job->getRetries()
        ];
    }

    /**
     * Generate a unique Id for the job
     *
     * @return string
     */
    public static function uniqueId()
    {
        $bytes = random_bytes(12);
        return bin2hex($bytes);
    }

    /**
     * Construct Job object and arguments to be passed
     * to the process function
     *
     * @param string $payload Job information
     *
     * @return Job
     */
    public static function constructJob($payload)
    {
        $payload = json_decode($payload, true);
        $class = $payload["class"];

        if(!class_exists($class)) {
            throw new \Exception("Class $class does not exist");
        }

        $job = new $class();
        $job->setId($payload["id"]);

        if (!is_callable(array($job, 'proccess'))) {
            throw new \Exception("Class $class does not have a callable proccess function");
        }

        return $job;
    }

}