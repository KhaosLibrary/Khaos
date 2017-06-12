<?php

namespace spec\Khaos\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Tool\Bench\Resource\Definition\BenchDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use PhpSpec\ObjectBehavior;

class BenchDefinitionFactorySpec extends ObjectBehavior
{
    private $sampleResource = [
        'metadata' => [
            'id'          => 'application',
            'title'       => 'Application Title',
            'description' => 'Application Description'
        ]
    ];

    function it_is_a_resource_factory()
    {
        $this->shouldHaveType(ResourceDefinitionFactory::class);
    }

    function it_provides_type_of_resource_factory()
    {
        $this->getType()->shouldBe(BenchDefinition::TYPE);
    }

    function it_provides_application_resource_when_given_data()
    {
        $this->create($this->sampleResource)->shouldBeAnInstanceOf(BenchDefinition::class);
    }
}
