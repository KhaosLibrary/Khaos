<?php

namespace spec\Khaos\Bench\Tool;

use Auryn\Injector;
use InvalidArgumentException;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\ToolFactory;
use PhpSpec\ObjectBehavior;

class ToolFactorySpec extends ObjectBehavior
{
    function let(Injector $injector)
    {
        $this->beConstructedWith($injector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ToolFactory::class);
    }

    function it_only_allows_bench_tools_to_be_added()
    {
        $this->shouldThrow(InvalidArgumentException::class)->duringAdd('docker', self::class);
    }

    function it_provides_an_instance_of_the_specified_tool()
    {
        $this->add('docker', DockerTool::class);
        $this->create('docker')->shouldBeAnInstanceOf(DockerTool::class);
    }

    function it_throws_an_exception_if_the_requested_tool_does_not_exist()
    {
        $this->shouldThrow(InvalidArgumentException::class)->duringCreate('none-existent-tool');
    }

    function it_shares_the_same_instance_of_a_tool_if_create_is_called_multiple_times()
    {
        $this->add('docker', DockerTool::class);

        $obj1 = $this->create('docker');

        $this->create('docker')->shouldBe($obj1);
    }
}
