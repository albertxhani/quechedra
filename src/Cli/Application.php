<?php

namespace Quechedra\Cli;

use Symfony\Component\Console\Application as RootApplication;
use Quechedra\Utils\Cli;

class Application extends RootApplication
{

    public function getHelp()
    {
        return Cli::footprint();
    }

    protected function getDefaultCommands(): array
    {
        $commands = array_merge(parent::getDefaultCommands(), array(
            new Commands\RunCommand()
        ));

        return $commands;
    }

}