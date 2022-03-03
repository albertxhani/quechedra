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
        $this->log($this->timestamp("fail") . $text);
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
        $styles = [
            "fail" => [
                "red", "", ["bold"]
            ],
            "quechedra" => [
                "cyan", "", ["bold", "blink"]
            ]
        ];

        foreach($styles as $tag => $values) {
            $outputStyle = new OutputFormatterStyle(...$values);
            $this->output->getFormatter()->setStyle($tag, $outputStyle);
        }
    }

    /**
     * get current UTC date and time
     *
     * @return void
     */
    private function timestamp($tag = "info")
    {
        return "<{$tag}>[" . \gmdate("Y-m-d\TH:i:s\Z") . "]</{$tag}> ";
    }

}