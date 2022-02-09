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

}