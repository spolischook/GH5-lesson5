<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class GetGitHubUserStatsCommand extends Command
{
    /** @var array  */
    private $parameters;

    /** @var  \MongoDB */
    private $db;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        $this->initDb();

        parent::__construct('get-github-user-stats');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('get GitHub stats');

        $githubClient = new \Github\Client();

        foreach ($this->getParameters()["students_github_usernames"] as $username) {
            /** @var \Github\Api\User $ghUser */
            $ghUser = $githubClient->api('users');
            $repositories = $ghUser->repositories($username, 'all');

            var_dump(count($repositories));exit;
        }
    }

    /**
     * @return array
     * @throws
     */
    protected function getParameters()
    {
        if (!$this->parameters) {
            if (!$parametersYmlFile = realpath(__DIR__."/../../../../parameters.yml")) {
                throw \InvalidArgumentException('Can\'t find parameters.yml file');
            }

            $this->parameters = Yaml::parse($parametersYmlFile);
        }

        return $this->parameters;
    }

    /**
     * @return \MongoDB
     */
    protected function initDb()
    {
        $m = new \MongoClient();
        $dbName = $this->getParameters()['database']['name'];

        $this->db = $m->$dbName;

        return $this;
    }
}

