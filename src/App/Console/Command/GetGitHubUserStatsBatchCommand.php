<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GetGitHubUserStatsBatchCommand extends Command
{
    /** @var  array */
    private $usernames;

    /**
     * @param array $usernames
     */
    public function __construct(array $usernames)
    {
        $this->usernames = $usernames;

        parent::__construct('get-github-user-stats-batch');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->usernames as $username) {
            $command = 'php bin/console get-github-user-stats '.$username;
            $process = new Process($command);
            $process->start();
        }

    }
}

