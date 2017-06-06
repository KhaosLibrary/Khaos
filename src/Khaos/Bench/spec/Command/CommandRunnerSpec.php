<?php

namespace spec\Khaos\Bench\Command;

use Khaos\Bench\Command\CommandRunner;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Console\Usage\Parser\UsageParserBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class CommandRunnerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, ResourceDefinitionRepository $resourceDefinitionRepository, UsageParserBuilder $usageParserBuilder, ResourceDefinitionFieldParser $fieldParser)
    {
        $this->beConstructedWith($eventDispatcher, $resourceDefinitionRepository, $usageParserBuilder, $fieldParser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommandRunner::class);
    }
}
