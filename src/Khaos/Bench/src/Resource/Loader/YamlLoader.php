<?php

namespace Khaos\Bench\Resource\Loader;

use Khaos\Bench\Resource\Loader\Yaml\Parser;

class YamlLoader implements Loader
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function load($source)
    {
        $data = [];

        foreach ($this->getYamlDocuments($source) as $yamlDocument)
            $data[] = $this->parser->parse($yamlDocument);

        return $data;
    }

    /**
     * Get Yaml Documents
     *
     * Hack: Symfony's Yaml Parser does not support multiple documents, this is just
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