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
     * @return array
     */
    public function pop($queues = false)
    {
        $queues = $this->getQueues();
        return $this->connection->brPop($queues, 1);
    }

    /**
     * Get all queues saved on redis
     *
     * @return void
     */
    public function getQueues()
    {
        $queues = $this->connection->sMembers('queues');

        foreach($queues as $key => $queue) {
            $queues[$key] = "queue:{$queue}";
        }

        \shuffle($queues);
        return $queues;
    }
}