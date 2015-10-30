<?php

namespace App\Tests\Console\Command;

use App\Console\Command\GetGitHubUserStatsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GetGitHubUserStatsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new GetGitHubUserStatsCommand());

        $command = $application->find('get-github-user-stats');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/get GitHub stats/', $commandTester->getDisplay());
        $this->assertRegExp('/Success/', $commandTester->getDisplay());
    }
}

