<?php

namespace Quechedra;

use Symfony\Component\Yaml\Yaml;
use Quechedra\RedisConnector;

class Client
{

    private static $instance = null;

    /**
     * Redis Connection
     *
     * @var RedisConnector
     */
    private $connection = null;

    /**
     * Initialize class
     */
    private function __construct()
    {
        $this->configYaml();
    }

    /**
     * FGet Client Instance
     *
     * @return Client
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set up configuration using the yaml file
     *
     * @return void
     */
    private function configYaml()
    {

        $path = __DIR__ . "/../quechedra.yaml";
        try {
            $config = Yaml::parseFile($path);
        } catch(\Exception $e) {
            throw new \Exception("Yaml file could not be found");
        }

        $this->setConnection($config["redis"]);
    }

    /**
     * establish a new redis connection
     *
     * @param array $redis_config redis connection config
     *
     * @return void
     */
    public function setConnection($redis_config)
    {
        $this->connection = (new RedisConnector())
            ->create($redis_config);
    }

}