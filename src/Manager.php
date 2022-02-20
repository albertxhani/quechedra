<?php

namespace Quechedra;
use Quechedra\Client;

class Manager
{

    public function __construct()
    {
        $this->connection = Client::getInstance()->connection;
    }

    public function push($payload)
    {
        $queue = $payload["queue"];
        
        $this->connection->sAdd("queues", $queue);
        $this->connection->lPush("queue:{$queue}", json_encode($payload));
    }
}