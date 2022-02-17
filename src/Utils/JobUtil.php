<?php

namespace Quechedra;

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
            "args"  => json_encode($args, true),
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

}