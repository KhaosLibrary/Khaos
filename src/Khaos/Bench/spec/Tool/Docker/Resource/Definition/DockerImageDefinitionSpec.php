<?php

namespace spec\Khaos\Bench\Tool\Docker\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Tool\Docker\Resource\Definition\DockerImageDefinition;
use PhpSpec\ObjectBehavior;

class DockerImageDefinitionSpec extends ObjectBehavior
{
    private $sampleResource = [
        'resource' => 'docker/image',
        'metadata' => [
            'id'          => 'varnish',
            'title'       => 'Example Docker Image Resource Definition',
            'description' => 'Example Description'
        ]
    ];

    function let()
    {
        $this->beConstructedWith($this->sampleResource);
    }

    function it_is_a_resource_definition()
    {
        $this->shouldHaveType(ResourceDefinition::class);
    }

    function it_provides_metadata_title()
    {
        $this->getTitle()->shouldBe('Example Docker Image Resource Definition');
    }

    function it_provides_metadata_description()
    {
        $this->getDescription()->shouldBe('Example Description');
    }

    function it_provides_metadata_id()
    {
        $this->getId()->shouldBe('varnish');
    }

    function it_provides_text_representation_of_resource_type()
    {
        $this->getType()->shouldBe(DockerImageDefinition::TYPE);
    }

    function it_throws_invalid_argument_exception_if_no_id_specified()
    {
        $this->beConstructedWith([
            'resource' => 'docker/image',
            'metadata' => [
                'title'       => 'Example Docker Image Resource Definition',
                'description' => 'Example Description'
            ]
        ]);

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
