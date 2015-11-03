<?php

namespace App\Tests\Console\Command;

use App\Console\Command\GetGitHubUserStatsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GetGitHubUserStatsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $collectionStub = $this->getMockBuilder('MongoCollection')->disableOriginalConstructor()->getMock();
        $dbStub = $this->getMockBuilder('MongoDB')->disableOriginalConstructor()->getMock();
        $dbStub->method('__get')->willReturn($collectionStub);

        $map = [
            ['users', $this->getMockBuilder('Github\Api\User')->disableOriginalConstructor()->getMock()],
        ];
        $githubClientStub = $this->getMockBuilder('Github\Client')->getMock();
        $githubClientStub->method('api')
            ->will($this->returnValueMap($map));

        $application = new Application();
        $application->add(new GetGitHubUserStatsCommand($dbStub, $githubClientStub));

        $command = $application->find('get-github-user-stats');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'username' => 'torvalds']);

        $this->assertRegExp('/get GitHub stats/', $commandTester->getDisplay());
        $this->assertRegExp('/Success/', $commandTester->getDisplay());
    }
}

