<?php

namespace Quechedra\Test;

use PHPUnit\Framework\TestCase;
use Quechedra\Job;


final class JobTest extends TestCase
{

    public function testRetry(): void
    {
        $job = new Job();

        $job->retry(5);
        $this->assertEquals(5, $job->getRetries(), "set number of retries to 5");

        $job->retry(true);
        $this->assertEquals(20, $job->getRetries(), "retry count falls to 20");

        $job->retry(false);
        $this->assertEquals(false, $job->getRetries(), "retry is disabled");
    }

    public function testQueue(): void
    {
        $job = new Job();

        $job->queueTo("test");
        $this->assertEquals("test", $job->getQueue(), "set queue to test");
    }
}