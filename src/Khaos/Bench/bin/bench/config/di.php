<?php

use Auryn\Injector;
use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\BenchDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\CommandDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\NamespaceDefinitionFactory;
use Khaos\Bench\Resource\DefinitionFactory\CompositeDefinitionFactory;
use Khaos\Bench\Resource\DefinitionFactory\ImportDefinitionFactory;
use Khaos\Bench\Resource\DefinitionFieldParser\DefinitionFieldParser;
use Khaos\Bench\Resource\DefinitionLoader\ArrayDefinitionLoader;
use Khaos\Bench\Resource\DefinitionLoader\CompositeDefinitionLoader;
use Khaos\Bench\Resource\DefinitionLoader\FileDefinitionLoader;
use Khaos\Bench\Resource\DefinitionLoader\YamlDefinitionLoader;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionRepository;
use Khaos\Bench\Resource\ResourceDefinitionFactory;
use Khaos\Bench\Resource\ResourceDefinitionFieldParser;
use Khaos\Bench\Resource\ResourceDefinitionLoader;
use Khaos\Bench\Resource\ResourceDefinitionRepository;
use Khaos\Bench\Tool\Bench\BenchTool;
use Khaos\Bench\Tool\Docker\DockerTool;
use Khaos\Bench\Tool\ToolFactory;
use Khaos\Console\Usage\Parser\OptionDefinitionParser;
use Symfony\Component\EventDispatcher\EventDispatcher;

$injector = new Injector;

/*
 * Map Interface -> Concrete Class
 * Provide default concrete classes for interfaces.
 */

$injector->alias(ResourceDefinitionRepository::class, DefinitionRepository::class);
$injector->alias(ResourceDefinitionLoader::class,     CompositeDefinitionLoader::class);
$injector->alias(ResourceDefinitionFactory::class,    CompositeDefinitionFactory::class);
$injector->alias(ResourceDefinitionFieldParser::class,DefinitionFieldParser::class);

/*
 * Shared Classes
 * Define which classes should use a shared instance.
 */

$injector->share($injector);
$injector->share(Bench::class);
$injector->share(EventDispatcher::class);
$injector->share(ResourceDefinitionRepository::class);
$injector->share(OptionDefinitionParser::class);
$injector->share(ResourceDefinitionFieldParser::class);

/*
 * CompositeDefinitionFactory
 *  - bench
 *  - bench/import
 *  - bench/command
 */

$injector->prepare
(
    CompositeDefinitionFactory::class,
    function(CompositeDefinitionFactory $definitionFactory, Injector $injector)
    {
        $definitionFactory->add($injector->make(BenchDefinitionFactory::class));
        $definitionFactory->add($injector->make(ImportDefinitionFactory::class));
        $definitionFactory->add($injector->make(CommandDefinitionFactory::class));
        $definitionFactory->add($injector->make(NamespaceDefinitionFactory::class));
    }
);

/*
 * CompositeDefinitionLoader
 *  - File
 */

$injector->prepare
(
    CompositeDefinitionLoader::class,
    function(CompositeDefinitionLoader $definitionLoader, Injector $injector)
    {
        $definitionLoader->add($injector->make(FileDefinitionLoader::class));
    }
);

/*
 * FileDefinitionLoader
 *  - YAML
 */

$injector->prepare
(
    FileDefinitionLoader::class,
    function(FileDefinitionLoader $definitionLoader, Injector $injector)
    {
        $definitionLoader->add($injector->make(YamlDefinitionLoader::class), ['yaml', 'yml']);
    }
);

return $injector;