<?php

namespace spec\Khaos\Bench\Tool\Docker\Resource\DefinitionFactory;

use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Bench\Tool\Docker\Resource\Definition\DockerImageDefinition;
use Khaos\Bench\Tool\Docker\Resource\DefinitionFactory\DockerImageDefinitionFactory;
use PhpSpec\ObjectBehavior;

class DockerImageDefinitionFactorySpec extends ObjectBehavior
{
    private $sampleResource = [
        'resource' => 'docker/image',
        'metadata' => [
            'id'          => 'varnish',
            'title'       => 'Example Docker Image Resource Definition',
            'description' => 'Example Description'
        ]
    ];

    function it_is_a_resource_definition_factory()
    {
        $this->shouldHaveType(ResourceDefinitionFactory::class);
    }

    function it_provides_type_of_resource_factory()
    {
        $this->getType()->shouldBe(DockerImageDefinition::TYPE);
    }

    function it_provides_application_resource_when_given_data()
    {
        $this->create($this->sampleResource)->shouldBeAnInstanceOf(DockerImageDefinition::class);
    }
}
