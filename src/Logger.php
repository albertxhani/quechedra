<?php

namespace Quechedra;

class Logger
{

    private $strean;

    private $level;

    public function __construct($stream, $level)
    {
        $this->stream = $stream;
        $this->level = $level;
    }

    public function log($content)
    {
        $this->streamer->log($content);
    }

}