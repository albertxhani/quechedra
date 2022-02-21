<?php

namespace Quechedra;
use Quechedra\Client;

class Manager
{

    public function __construct()
    {
        $this->connection = Client::getInstance()->connection;
    }

    /**
     * Push a job into the queue
     *
     * @param array $payload Job's payload
     *
     * @return void
     */
    public function push($payload)
    {
        $queue = $payload["queue"];
        
        $this->connection->sAdd("queues", $queue);
        $this->connection->lPush("queue:{$queue}", json_encode($payload));
    }

    /**
     * Pop a job from the available queues
     *
     * @param boolean $queues
     *
     * @return atring
     */
    public function pop($queues = false)
    {
        $queues = $queues ?? $this->getQueues();
        return $this->connection->brPop($queues);
    }

    /**
     * Get all queues saved on redis
     *
     * @return void
     */
    private function getQueues()
    {
        $queues = $this->connection->sMembers('queues');
        return \shuffle($queues);
    }
}