<?php

namespace Quechedra\Cli\Commands;

use Quechedra\Utils\Cli;
use Quechedra\Process;
use Quechedra\Cli\OutputStreamer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('run')
            ->setDescription('Run a single proccess that listens to available queues')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(Cli::footprint());
        $output->writeln("Running in PHP " . phpversion());

        $redis_extension = extension_loaded('redis');
        if(!$redis_extension) {
            $output->writeln("<error>Redis extension is not loaded<error>");
            return 0;
        }

        $output->writeln("Starting Process ...");

        $streamer = new OutputStreamer($output);

        $process = new Process($streamer);
        $process->run();

        return 0;
    }
}