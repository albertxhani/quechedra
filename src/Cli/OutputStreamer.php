<?php

namespace Quechedra\Cli;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class OutputStreamer {

    private $output;

    function __construct($output)
    {
        $this->output = $output;
        $this->registerTags();
    }

    function debug($text)
    {
        $this->output->writeln($this->timestamp(). $text);
    }

    function info($text)
    {
        $this->output->writeln("{$this->timestamp()}".  $text);
    }


    function error($text)
    {
        $this->output->writeln("<error>{$text}</error>");
    }

    private function registerTags()
    {
        $outputStyle = new OutputFormatterStyle('cyan', '', ['bold', 'blink']);
        $this->output->getFormatter()->setStyle('quechedra', $outputStyle);
    }

    private function timestamp()
    {
        return "<info>[" . \gmdate("Y-m-d\TH:i:s\Z") . "]</info> ";
    }

}