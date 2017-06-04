<?php

namespace spec\Khaos\Bench\Tool\Docker;

use Auryn\Injector;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\Tool;
use PhpSpec\ObjectBehavior;

class DockerToolSpec extends ObjectBehavior
{
    function let(Injector $injector)
    {

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DockerTool::class);
    }

    function it_is_a_bench_tool()
    {
        $this->shouldHaveType(Tool::class);
    }

    function it_provides_instance_of_tool(Injector $injector)
    {
        $this::create($injector)->shouldBeAnInstanceOf(DockerTool::class);
    }
}
