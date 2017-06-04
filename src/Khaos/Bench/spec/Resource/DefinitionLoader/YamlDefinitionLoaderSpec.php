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

    function it_provides_a_resource_definition_based_on_the_given_yaml(Parser $parser, ResourceDefinition $resourceDefinition, CompositeDefinitionFactory $definitionFactory)
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
        $definitionFactory->create($arrayDefinition)->willReturn($resourceDefinition);

        $this->load($yamlDefinition)->shouldReturn([$resourceDefinition]);
    }

    function it_supports_yaml_with_multiple_documents(
        Parser $parser,
        ResourceDefinition $resourceDefinition1,
        ResourceDefinition $resourceDefinition2,
        CompositeDefinitionFactory $definitionFactory)
    {
        $yamlDefinition = file_get_contents($this->sampleMultipleDocuments);

        $parser->parse(Argument::type('string'))->willReturn([]);
        $definitionFactory->create(Argument::type('array'))->willReturn($resourceDefinition1, $resourceDefinition2);

        $this->load($yamlDefinition)->shouldReturn([$resourceDefinition1, $resourceDefinition2]);
    }
}
