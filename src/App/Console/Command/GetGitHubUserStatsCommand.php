<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetGitHubUserStatsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('get-github-user-stats')
            ->setDescription('Get user stats from GitHub.com and store it to data storage')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('get GitHub stats');
    }
}

