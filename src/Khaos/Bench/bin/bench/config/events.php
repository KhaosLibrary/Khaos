<?php

use Auryn\Injector;
use Khaos\Bench\Command\Event\CommandFoundEvent;
use Khaos\Bench\Command\ContextualHelp;
use Khaos\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionImporter;
use Khaos\Bench\Resource\DefinitionRepository\Event\ResourceDefinitionImported;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @var Injector $injector
 * @var EventDispatcher $dispatcher
 */

$dispatcher = $injector->make(EventDispatcher::class);
$dispatcher->addListener(ResourceDefinitionImported::NAME.'::'.ImportDefinition::TYPE, $injector->make(DefinitionImporter::class));

return $dispatcher;