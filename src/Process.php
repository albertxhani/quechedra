<?php

namespace Quechedra;

use Quechedra\Utils\JobUtil;

class Process
{
    public function __construct()
    {
        $this->manager = Client::getInstance()->getManager();
    }

    /**
     * Run Process infinitely
     *
     * @return void
     */
    public function run()
    {
        while(true) {

            $payload = $this->manager->pop();

            if(!$payload) {
                sleep(10);
                continue;
            }

            [$job, $arguments] = JobUtil::constructJob($payload);

            try {
                $job->process(...$arguments);
                sleep(2);
            } catch(\Exception $e) {
                // to be handled
            }
        }
    }

}