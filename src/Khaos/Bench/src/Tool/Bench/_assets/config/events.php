<?php

use Auryn\Injector;
use Khaos\Bench\Command\Event\InvalidUsageEvent;
use Khaos\Bench\Tool\Bench\Command\ShowHelpOnInvalidUsageEvent;
use Khaos\Bench\Tool\Bench\Resource\Definition\ImportDefinition;
use Khaos\Bench\Tool\Bench\Resource\DefinitionRepository\DefinitionImporter;
use Khaos\Bench\Resource\DefinitionRepository\Event\ResourceDefinitionImportedEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @var Injector        $injector
 * @var EventDispatcher $dispatcher
 */

$dispatcher = $injector->make(EventDispatcher::class);
$dispatcher->addListener(ResourceDefinitionImportedEvent::NAME.'::'.ImportDefinition::TYPE, $injector->make(DefinitionImporter::class));
$dispatcher->addListener(InvalidUsageEvent::NAME,                                           $injector->make(ShowHelpOnInvalidUsageEvent::class));

return $dispatcher;