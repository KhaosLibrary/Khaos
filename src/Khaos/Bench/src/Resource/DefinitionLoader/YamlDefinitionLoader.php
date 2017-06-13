<?php

namespace Khaos\Bench\Resource\DefinitionLoader;

use Khaos\Bench\Resource\ResourceDefinition;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Resource\DefinitionFactory\CompositeDefinitionFactory;
use PhpSpec\Exception\Exception;
use Symfony\Component\Yaml\Parser;

class YamlDefinitionLoader implements ResourceDefinitionLoader
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * YamlResourceLoader constructor.
     *
     * @param Parser              $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Load Resource Definitions
     *
     * @param string $source YAML Documents
     *
     * @return array  An array of resource definitions in an array format
     */
    public function load($source)
    {
        $definitions = [];

        foreach ($this->getYamlDocuments($source) as $document) {
            $definitions[] = $this->parser->parse($document);
        }

        return $definitions;
    }

    /**
     * Get YAML Documents
     *
     * Hack: Symfony's YAML Parser does not support multiple documents, this is just
     * a quick way around it.
     *
     * @param string $content
     *
     * @return array An array of Yaml Documents
     */
    private function getYamlDocuments($content)
    {
        return preg_split('/\R---\R/', $content);
    }
}