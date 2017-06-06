<?php

namespace spec\Khaos\Bench\Command;

use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CommandRunnerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitionRepository, UsageParserBuilder $usageParserBuilder)
    {
        $this->beConstructedWith($eventDispatcher, $resourceDefinitionRepository, $usageParserBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandRunner::class);
    }
}
