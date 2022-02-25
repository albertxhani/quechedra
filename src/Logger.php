<?php

namespace Quechedra;

class Logger
{

    private $stream;

    private $level;

    /**
     * Available log levels
     *
     * @var array
     */
    private $levels = [
        "fatal",
        "error",
        "warn",
        "info",
        "debug"
    ];

    /**
     * Initialize Logger
     *
     * @param mixed  $stream Output Stream
     * @param string $level  Log Level
     */
    public function __construct($stream, $level)
    {
        $this->stream = $stream;
        $this->level = $level;
    }

    /**
     * Log message by level. If the log level is lower in
     * the hierarchy than the default logging level
     * the message will not be logged
     *
     * @param string $content Message to be logged
     * @param string $level   on of the available levels
     *
     * @return void
     */
    public function log($content, $level = "debug")
    {
        if(!in_array($level, $this->levels)) {
            return;
        }

        if($this->evaluate($level)) {
            $this->stream->$level($content);
        }
    }

    /**
     * Evaluate if the message should be logged or not
     * based on applications configuration. Check
     * quechedra.yml for more
     *
     * @param string $level One of the available log levels
     *
     * @return void
     */
    private function evaluate($level)
    {
        return (
            array_search($level, $this->levels)
             >= array_search($this->level, $this->levels)
        );
    }
}