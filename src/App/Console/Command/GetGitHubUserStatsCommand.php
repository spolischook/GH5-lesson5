<?php

namespace App\Console\Command;

use Github\Client;
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

        $githubClient = new Client();
        $githubClient->authenticate(file_get_contents(realpath(__DIR__."/../../../../.github-token")), Client::AUTH_HTTP_TOKEN);

        foreach ($this->getParameters()["students_github_usernames"] as $username) {
            $dbUser = $this->db->users->findOne(['username' => $username]) ?: ['username' => $username];

            $dbUser['users/'.rawurlencode($username)] = $githubClient->api('users')->show($username);
            $dbUser['users/'.rawurlencode($username).'/repos'] = $githubClient->api('users')->repositories($username, 'all');

            $status = $this->db->users->insert($dbUser);
        }

        $output->writeln('Success import');
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

