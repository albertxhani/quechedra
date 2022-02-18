<?php

namespace Quechedra\Test;

use PHPUnit\Framework\TestCase;
use Quechedra\Job;
use Quechedra\Utils\JobUtil;

final class JobUtilTest extends TestCase
{

    public function test_buildPayload(): void
    {
        $job = new Job();
        $payload = JobUtil::buildPayload($job, [1, 2]);

        $this->assertArrayHasKey("id", $payload);
        $this->assertArrayHasKey("queue", $payload);
        $this->assertArrayHasKey("class", $payload);
        $this->assertArrayHasKey("args", $payload);
        $this->assertArrayHasKey("retry", $payload);

        $this->assertEquals($payload["class"], "Quechedra\Job", "class is instance of Job");
    }

    public function test_buildPayload_InvalidArgumentException(): void
    {
        $this->expectException("\InvalidArgumentException");

        $job = new Job();
        JobUtil::buildPayload($job, [1, $job]);
    }

}