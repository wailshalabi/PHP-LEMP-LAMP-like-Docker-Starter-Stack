<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:status',
    description: 'Show PHP CLI and container status'
)]
class StatusCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Symfony Console is working</info>');
        $output->writeln('PHP Version: ' . PHP_VERSION);
        $output->writeln('Memory Limit: ' . ini_get('memory_limit'));

        return Command::SUCCESS;
    }
}
