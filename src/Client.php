<?php

namespace Quechedra;

use Symfony\Component\Yaml\Yaml;
use Quechedra\RedisConnector;
use Quechedra\ClientOptions;

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
     * Options
     */
    private $options = null;

    /**
     * Initialize class
     */
    private function __construct()
    {
        $this->configYaml();
    }

    /**
     * Get Client Instance
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

    /**
     * Set values to the options object
     *
     * @param string $name
     * @param mixed  $value
     */
    function __set($name, $value)
    {
        $this->options->$name = $value;
    }

    /**
     * Get value from options
     *
     * @param string $name
     *
     * @return mixed
     */
    function __get($name)
    {
        return $this->options->$name;
    }

}