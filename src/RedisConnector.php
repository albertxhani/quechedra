<?php

namespace Quechedra;

use Quechedra\Contracts\ConnectorInterface;

class RedisConnector implements ConnectorInterface
{

    /**
     * Redis instance
     *
     * @var Redis
     */
    private $instance = null;


    function __construct()
    {
        if(!class_exists("Redis")) {
            throw new \Exception("Redis extenstion is not activated");
        }
    }

    /**
     * Create a new redis connection
     *
     * @param array $config Connection details
     *
     * @return Redis
     */
    public function create($config)
    {

        if ($this->instance) return $this->instance;

        $redis = new \Redis();
        $redis->connect(...array_values($config));

        return $this->instance = $redis;
    }

}