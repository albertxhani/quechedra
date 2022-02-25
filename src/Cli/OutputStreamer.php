<?php

namespace Quechedra\Cli;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class OutputStreamer {

    private $output;

    /**
     * Initialize OutputStreamer
     *
     * @param mixed $output Comand line output wrapper
     */
    function __construct($output)
    {
        $this->output = $output;
        $this->registerTags();
    }

    /**
     * print message to output
     *
     * @param strig $text text to log
     *
     * @return void
     */
    private function log($text)
    {
        $this->output->writeln($text);
    }

    /**
     * Log message with debug level
     *
     * @param strig $text text to log
     *
     * @return void
     */
    function debug($text)
    {
        $this->log($this->timestamp() . $text);
    }

    /**
     * Log message with info level
     *
     * @param strig $text text to log
     *
     * @return void
     */
    function info($text)
    {
        $this->log($this->timestamp() . $text);
    }

    /**
     * Log message with error level
     *
     * @param strig $text text to log
     *
     * @return void
     */
    function error($text)
    {
        $this->log("<error>{$text}</error>");
    }

    /**
     * Log message with fatal level
     *
     * @param strig $text text to log
     *
     * @return void
     */
    function fatal($text)
    {
        $this->log("<error>{$text}</error>");
    }

    /**
     * Register new styles for symfony console
     *
     * @return void
     */
    private function registerTags()
    {
        $outputStyle = new OutputFormatterStyle('cyan', '', ['bold', 'blink']);
        $this->output->getFormatter()->setStyle('quechedra', $outputStyle);
    }

    /**
     * get current UTC date and time
     *
     * @return void
     */
    private function timestamp()
    {
        return "<info>[" . \gmdate("Y-m-d\TH:i:s\Z") . "]</info> ";
    }

}