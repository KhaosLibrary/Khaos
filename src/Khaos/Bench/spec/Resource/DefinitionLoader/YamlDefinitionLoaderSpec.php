<?php

namespace spec\Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\DefinitionFactory\CompositeDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Yaml\Parser;

class YamlDefinitionLoaderSpec extends ObjectBehavior
{
    private $sample = __DIR__.'/../_sample/bench.yml';

    private $sampleMultipleDocuments = __DIR__.'/../_sample/multiple-documents.yml';

    function let(Parser $parser, CompositeDefinitionFactory $definitionFactory)
    {
        $this->beConstructedWith($parser, $definitionFactory);
    }

    function it_is_a_resource_loader()
    {
        $this->shouldHaveType(ResourceDefinitionLoader::class);
    }

    function it_provides_resource_definition_data_based_on_the_given_yaml(Parser $parser, CompositeDefinitionFactory $definitionFactory)
    {
        $yamlDefinition  = file_get_contents($this->sample);
        $arrayDefinition = [
            'resource' => 'bench',
            'metadata' => [
                'title' => 'Sample Bench Title',
                'description' => 'Sample Bench Description'
            ]
        ];

        $parser->parse($yamlDefinition)->willReturn($arrayDefinition);

        $this->load($yamlDefinition)->shouldReturn([$arrayDefinition]);
    }

    function it_supports_yaml_with_multiple_documents(Parser $parser)
    {
        $yamlDefinition = file_get_contents($this->sampleMultipleDocuments);

        $parser->parse(Argument::type('string'))->willReturn(['resource' => 'bench']);

        $this->load($yamlDefinition)->shouldReturn([['resource' => 'bench'],['resource' => 'bench']]);
    }
}
