<?php

namespace Quechedra;

use Symfony\Component\Yaml\Yaml;
use Quechedra\RedisConnector;
use Quechedra\ClientOptions;
use Quechedra\Logger;

class Client
{

    private static $instance = null;

    /**
     * Redis Connection
     *
     * @var RedisConnector
     */
    public $connection = null;


    /**
     * Options
     *
     * @var ClientOptions
     */
    private $options = null;

    /**
     * Managet Instance
     *
     * @var Manager
     */
    private $manager = null;


    /**
     * Logger Instance
     *
     * @var Logger
     */
    private $logger = null;

    /**
     * Initialize class
     */
    private function __construct()
    {
        $this->options = new ClientOptions();
        $this->defaultConfig();
        $this->setConnection($this->options->redis);
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
    public function configureFromFile($path)
    {
        $config = Yaml::parseFile($path);
        foreach($config as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * If quechedra.yaml file exists load configurations
     * from there
     *
     * @return void
     */
    private function defaultConfig()
    {
        $path = __DIR__ . "/../quechedra.yaml";
        if(\file_exists($path)) {
            $this->configureFromFile($path);
        }
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
     * Set Manager
     *
     * @return Manager
     */
    public function getManager()
    {
        return ($this->manager) ?
            $this->manager : new Manager($this->connection);
    }

    /**
     * Get Logger
     *
     * @param mixed $streamer Output streamer
     *
     * @return Logger
     */
    public function getLogger($streamer)
    {
        return ($this->logger) ?
            $this->logger : new Logger($streamer, $this->options->log_level);
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