<?php

namespace spec\Khaos\Bench\Resource\Definition;

use InvalidArgumentException;
use Khaos\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class ImportDefinitionSpec extends ObjectBehavior
{
    private $sampleResource = [
        'metadata' => [
            'id'          => 'import',
            'title'       => 'Import Resource Title',
            'description' => 'Import Resource Description'
        ],
        'import' => []
    ];

    function let()
    {
        $this->beConstructedWith($this->sampleResource);
    }

    function it_is_a_resource()
    {
        $this->beConstructedWith($this->sampleResource);
        $this->shouldHaveType(ResourceDefinition::class);
    }

    function it_provides_metadata_title()
    {
        $this->getTitle()->shouldBe('Import Resource Title');
    }

    function it_provides_metadata_description()
    {
        $this->getDescription()->shouldBe('Import Resource Description');
    }

    function it_provides_metadata_id()
    {
        $this->getId()->shouldBe('import');
    }

    function it_provides_array_of_import_patterns()
    {
        $this->beConstructedWith([
            'metadata' => [
                'id'          => 'import',
                'title'       => 'Import Resource Title',
                'description' => 'Import Resource Description'
            ],
            'import' => [
                'file/pattern/1',
                'file/pattern/2'
            ]
        ]);

        $this->getImportPatterns()->shouldBe([
            'file/pattern/1',
            'file/pattern/2'
        ]);
    }

    function it_provides_text_representation_of_resource_type()
    {
        $this->getType()->shouldBe(ImportDefinition::TYPE);
    }

    function it_assigns_an_unique_id_when_none_specified()
    {
        $this->beConstructedWith([
            'metadata' => [
                'title'       => 'Import Resource Title',
                'description' => 'Import Resource Description'
            ]
        ]);

        $this->getId()->shouldBe('_internal/bench/import/0');

        $this::getUniqueId()->shouldBe('_internal/bench/import/1');
        $this::getUniqueId()->shouldBe('_internal/bench/import/2');
    }
}
