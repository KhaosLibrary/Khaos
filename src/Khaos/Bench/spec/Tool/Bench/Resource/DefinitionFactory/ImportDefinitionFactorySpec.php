<?php

namespace spec\Khaos\Bench\Tool\Bench\Resource\DefinitionFactory;

use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use PhpSpec\ObjectBehavior;

class ImportDefinitionFactorySpec extends ObjectBehavior
{
    private $sampleResource = [
        'metadata' => [
            'id'          => 'import',
            'title'       => 'Import Resource Title',
            'description' => 'Import Resource Description'
        ]
    ];

    function it_is_a_resource_factory()
    {
        $this->shouldHaveType(ResourceDefinitionFactory::class);
    }

    function it_provides_type_of_resource_factory()
    {
        $this->getType()->shouldBe(ImportDefinition::TYPE);
    }

    function it_provides_import_resource_when_given_data()
    {
        $this->create($this->sampleResource)->shouldBeAnInstanceOf(ImportDefinition::class);
    }
}
