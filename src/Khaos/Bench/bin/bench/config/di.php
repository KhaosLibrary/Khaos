<?php

use Auryn\Injector;
use Khaos\Bench\Bench;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\BenchDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\CommandDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\NamespaceDefinitionFactory;
use Khaos\Bench\Resource\DefinitionFactory\CompositeDefinitionFactory;
use Khaos\Bench\Tool\Bench\Resource\DefinitionFactory\ImportDefinitionFactory;
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
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
$injector->alias(ConsoleOutputInterface::class,       ConsoleOutput::class);
$injector->alias(OutputFormatterInterface::class,     OutputFormatter::class);

/*
 * Shared Classes
 * Define which classes should use a shared instance.
 */

$injector->share($injector);
$injector->share(Bench::class);
$injector->share(EventDispatcher::class);
$injector->share(ResourceDefinitionRepository::class);
$injector->share(ResourceDefinitionFactory::class);
$injector->share(ResourceDefinitionFieldParser::class);
$injector->share(OptionDefinitionParser::class);
$injector->share(ConsoleOutputInterface::class);

/*
 * OutputFormatter
 */

$injector->prepare(OutputFormatterInterface::class, function(OutputFormatterInterface $outputFormatter, Injector $injector)
{
    $outputFormatter->setStyle('heading', new OutputFormatterStyle('yellow', 'default'));
    $outputFormatter->setStyle('green', new OutputFormatterStyle('green', 'default'));
    $outputFormatter->setStyle('yellow', new OutputFormatterStyle('yellow', 'default'));
});

/*
 * CompositeDefinitionLoader
 *  - File
 */

$injector->prepare(CompositeDefinitionLoader::class, function(CompositeDefinitionLoader $definitionLoader, Injector $injector)
{
    $definitionLoader->add($injector->make(FileDefinitionLoader::class));
});

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