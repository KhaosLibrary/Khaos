<?php

use Auryn\Injector;
use Khaos\Bench\Command\Event\CommandFoundEvent;
use Khaos\Bench\Command\ContextualHelp;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Resource\DefinitionRepository\DefinitionImporter;
use Khaos\Bench\Resource\DefinitionRepository\Event\ResourceDefinitionImportedEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @var Injector $injector
 * @var EventDispatcher $dispatcher
 */

$dispatcher = $injector->make(EventDispatcher::class);
$dispatcher->addListener(ResourceDefinitionImportedEvent::NAME.'::'.ImportDefinition::TYPE, $injector->make(DefinitionImporter::class));

return $dispatcher;