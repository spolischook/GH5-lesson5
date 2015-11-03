<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetGitHubUserStatsCommand extends Command
{
    /** @var  \MongoDB */
    private $db;

    /** @var \Github\Client  */
    private $githubClient;

    /**
     * @param \MongoDB $db
     * @param \Github\Client $githubClient
     */
    public function __construct(\MongoDB $db, \Github\Client $githubClient)
    {
        $this->db = $db;
        $this->githubClient = $githubClient;

        parent::__construct('get-github-user-stats');
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument(
            'username',
            InputArgument::REQUIRED,
            'GitHub username for grab public statistic'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $output->writeln('get GitHub stats for '.$username);

        $user            = ['username' => $username];
        $user['info']    = $this->githubClient->api('users')->show($username);
        $user['repos']   = $this->githubClient->api('users')->repositories($username, 'all');
        $user['commits'] = [];
        foreach ($user['repos'] as $repo) {
            try {
                $repoCommits = $this->githubClient->api('repositories')->commits()->all($repo['owner']['login'], $repo['name'], [
                    'author' => $username,
                    'since'  => '2015-10-01T00:00:00Z'
                ]);
            } catch (\Exception $e) {
                $repoCommits = [];
            }
            $user['commits'] = array_merge($user['commits'], $repoCommits);
        }


        $this->db->users->update(['username' => $username], $user, ["upsert" => true]);

        $output->writeln('Success import');
    }
}

